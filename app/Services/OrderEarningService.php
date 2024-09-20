<?php

namespace App\Services;

use App\Models\Commission;
use App\Models\Earned;
use App\Models\Earning;
use App\Models\Fee;
use App\Models\Remittance;
use App\Models\EarningReport;
use App\Models\User;
use App\Models\Wallet;

class OrderEarningService
{
    public function __constuct()
    {
        //
    }


    public function updateEarning($order)
    {

        //'pending','preparing','ready','enroute','delivered','failed','cancelled'
        if (in_array($order->status, ["delivered", "successful"])) {

            try {

                \DB::beginTransaction();

                $isCashOrder = $order->payment_method->slug == "cash";

                //only if online or driver wallet
                $enableDriverWallet = (bool) setting('enableDriverWallet', "0");
                $vendorEarningEnabled = (bool) setting('vendorEarningEnabled', "0");
                $generalVendorCommission = setting('vendorsCommission', "0");
                $generalDriverCommission = setting('driversCommission', "0");
                $systemAdminTotalCommission = 0;
                $updateAdminCommission = false;
                $driverSelfPay = (bool) setting('finance.driverSelfPay', false);

                //update vendor earning
                if ($order->vendor != null) {
                    //
                    $earned = Earned::where(
                        [
                            'vendor_id' => $order->vendor_id,
                            'order_id' => $order->id,
                        ]
                    )->first();

                    if (empty($earned)) {
                        //
                        $updateAdminCommission = true;
                        //vendor fees for the order
                        $orderVendorFee = 0;
                        if (\Schema::hasColumn("orders", 'fees')) {
                            $orderFees = json_decode($order->fees ?? [], true);
                            foreach ($orderFees as $orderFee) {
                                $fee = Fee::find($orderFee['id']);
                                if (!empty($fee) && !$fee->for_admin) {
                                    $orderVendorFee += (float) $orderFee["amount"];
                                }
                            }
                        }
                        //get/create vendor earning record
                        $earning = Earning::firstOrCreate(
                            ['vendor_id' => $order->vendor_id],
                            ['amount' => 0]
                        );
                        //get vendor commission
                        $vendorCommission = $order->vendor->commission;
                        if (is_null($vendorCommission)) {
                            $vendorCommission = $generalVendorCommission;
                        }
                        //get system commission in amount from the order subtotal
                        $systemCommission = ($vendorCommission / 100) * $order->sub_total;
                        //add the system commission from subtotal to admin total order earned
                        $systemAdminTotalCommission = $systemAdminTotalCommission + $systemCommission;
                        // logger("vendor admin com ", [$vendorCommission, $order->sub_total, $systemAdminTotalCommission]);

                        //for booking/service order, COD
                        if ($isCashOrder && ($order->is_service || $order->is_booking)) {
                            //minus our commission from their earning
                            $earning->amount -= $systemCommission;
                            $earning->save();
                        }
                        //if order is pickup and its cash, then remove our earning from vendor account
                        else if ($isCashOrder && empty($order->delivery_address_id)) {
                            //minus our commission from their earning
                            $earning->amount -= $systemCommission;
                            $earning->save();
                        } else if (($vendorEarningEnabled || !$isCashOrder || !$enableDriverWallet)) {
                            //get the earned amount for vendor from the subTotal order
                            $earnedAmount = ($order->sub_total - $systemCommission) - ($order->discount ?? 0);
                            //add their earned amount to their earning balance
                            $earning->amount += $earnedAmount + $orderVendorFee;
                            $earning->save();
                        }


                        //Saving vendor commission and admin commission
                        $earnedAmount = ($order->sub_total - $systemCommission) - ($order->discount ?? 0);
                        $earnedAmount += $orderVendorFee ?? 0.0;
                        //save admin vendor commission data
                        Commission::updateOrCreate(
                            ['order_id' => $order->id],
                            ['vendor_commission' => $earnedAmount]
                        );
                        //save earned
                        $earned = Earned::updateOrCreate(
                            ['order_id' => $order->id],
                            ['vendor_id' => $order->vendor_id]
                        );
                        //save vendor earning report
                        if (\Schema::hasTable("earning_reports")) {
                            //
                            $earningReport = EarningReport::create(
                                [
                                    'earning_id' => $earning->id,
                                    'earning' => $earnedAmount + $systemCommission,
                                    'commission' => $systemCommission,
                                    'balance' => $earnedAmount,
                                    'rate' => $vendorCommission,
                                    'order_id' => $order->id,
                                ],
                            );
                        }
                    }
                }



                //update driver
                try {
                    if (!empty($order->driver_id)) {

                        //
                        $earned = Earned::where(
                            [
                                'driver_id' => $order->driver_id,
                                'order_id' => $order->id,
                            ]
                        )->first();

                        if (empty($earned)) {

                            //if the pay driver delivery fee is enabled, then set the delivery fee to 0
                            $collectDeliveryCash = (bool) setting('finance.delivery.collectDeliveryCash', 0);
                            if ($collectDeliveryCash) {
                                $order->delivery_fee = 0;
                            }
                            //
                            $updateAdminCommission = true;
                            //
                            $driverEarning = Earning::firstOrCreate(
                                ['user_id' => $order->driver_id],
                                ['amount' => 0]
                            );

                            $driver = User::find($order->driver_id);
                            //
                            if (is_null($driver->commission)) {
                                $driver->commission = $generalDriverCommission;
                            }
                            //driver commission from delivery fee + tip from customer
                            if (!empty($order->taxi_order)) {
                                $earnedAmount = ($driver->commission / 100) * $order->total;
                                //add the system commission from delivery fee to admin total order earned
                                // $systemAdminTotalCommission += ((100 - $driver->commission) / 100) * $order->total;
                                // logger("driver admin com taxi", [$driver->commission, $order->total, $systemAdminTotalCommission]);
                            } else {
                                $orderTip = !$isCashOrder ? $order->tip : 0;
                                $earnedAmount = (($driver->commission / 100) * $order->delivery_fee) + $orderTip;
                                //add the system commission from delivery fee to admin total order earned
                                // $systemAdminTotalCommission += ((100 - $driver->commission) / 100) * $order->delivery_fee;
                                // logger("driver admin com", [$driver->commission, $order->delivery_fee, $systemAdminTotalCommission]);
                            }


                            //if system is using driver wallet
                            //if its online order payment
                            if (!$isCashOrder) {
                                $driverEarning->amount = $driverEarning->amount + $earnedAmount;
                            } else  if ($enableDriverWallet) {

                                //
                                $driverWallet = $order->driver->wallet;
                                if (empty($driverWallet)) {
                                    $driverWallet = $order->driver->updateWallet(0);
                                }

                                //record amount to deduct from driver wallet
                                $totalToDeduct = 0;
                                //only remove commission fro driver wallet is driver self pay for order
                                if ($driverSelfPay && ($order->vendor_id != null && !empty($order->vendor_id))) {
                                    $orderTip = !$isCashOrder ? $order->tip : 0;
                                    $totalToDeduct  = $order->delivery_fee - ($earnedAmount - $orderTip);
                                    $driverWallet->balance = $driverWallet->balance - $totalToDeduct;
                                } else {
                                    $totalToDeduct  = $order->total - $earnedAmount;
                                    $driverWallet->balance = $driverWallet->balance - $totalToDeduct;
                                }

                                //save wallet balance
                                $driverWallet->save();
                                //save driver wallet transaction
                                $driverWallet->saveWalletTransaction(
                                    $totalToDeduct,
                                    __("System charges for Order :code", ['code' => "#" . $order->code . ""]),
                                    $isCredit = false,
                                    $ref = null,
                                    $status = "successful"
                                );
                                //end of driver wallet
                            } else {
                                $driverEarning->amount = $driverEarning->amount + $earnedAmount;
                                //save the record of the order that needs to be collected fromm driver
                                //log the order for driver remittance
                                $remittance = new Remittance();
                                $remittance->user_id = $order->driver_id;
                                $remittance->order_id = $order->id;
                                if (\Schema::hasColumn('remittances', 'earned')) {
                                    $remittance->earned = $earnedAmount;
                                }
                                $remittance->save();
                            }
                            $driverEarning->save();


                            //save earned
                            $earned = Earned::updateOrCreate(
                                ['order_id' => $order->id],
                                ['driver_id' => $order->driver_id]
                            );

                            //save admin commission data
                            $systemDriverCommission = (empty($order->taxi_order) ? $order->delivery_fee : $order->total) - $earnedAmount;
                            $systemAdminTotalCommission = $systemAdminTotalCommission + $systemDriverCommission;
                            $commission = Commission::updateOrCreate(
                                ['order_id' => $order->id],
                                [
                                    'driver_commission' => $earnedAmount,
                                ]
                            );
                            //save driver earning report
                            if (\Schema::hasTable("earning_reports")) {
                                $earningReport = EarningReport::create(
                                    [
                                        'earning_id' => $driverEarning->id,
                                        'earning' => (empty($order->taxi_order) ? $order->delivery_fee : $order->total),
                                        'commission' => $systemDriverCommission,
                                        'balance' => $earnedAmount,
                                        'rate' => $driver->commission,
                                        'order_id' => $order->id,
                                    ],
                                );
                            }

                            //revert delivery fee
                            if ($collectDeliveryCash) {
                                $order->delivery_fee = $order->getOriginal('delivery_fee');
                            }
                        }
                    }
                } catch (\Exception $ex) {
                    \DB::rollback();
                    logger("error", [$ex]);
                    logger("Driver earnig error", [$ex->getMessage()]);
                    logger("Order Code", [$order->code]);
                    logger("----------");
                }



                //update admin commission
                if ($updateAdminCommission) {
                    $commission = Commission::updateOrCreate(
                        ['order_id' => $order->id],
                        [
                            'admin_commission' => $systemAdminTotalCommission,
                        ]
                    );
                }

                // logger("save admin commission", [$commission]);


                \DB::commit();
            } catch (\Exception $ex) {
                \DB::rollback();
                logger("earnig error", [$ex]);
            }
        }
    }
}
