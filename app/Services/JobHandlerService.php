<?php

namespace App\Services;

use App\Jobs\DriverDetailsJob;
use App\Jobs\DriverVehicleTypeJob;
use App\Jobs\OrderStatusNotificationJob;
use App\Jobs\OrderPaymentRequestNotificationJob;
use App\Jobs\OrderPaymentStatusChangeNotificationJob;
use App\Jobs\TaxiOrderMatchingJob;
use App\Jobs\ClearDriverFirebaseJob;
use App\Jobs\ClearFirebaseJob;
use App\Jobs\PushToFirebaseJob;
use App\Jobs\NewVendorJob;
use App\Jobs\VendorUpdateJob;
use App\Jobs\VPSTaxiOrderMatchingJob;
use App\Traits\FirebaseAuthTrait;
use App\Traits\FirebaseMessagingTrait;

class JobHandlerService
{
    use FirebaseAuthTrait, FirebaseMessagingTrait;

    public function __constuct()
    {
        //
    }

    public function driverDetailsJob($driver)
    {

        //update driver free record on firebase
        if (delayFCMJob()) {
            DriverDetailsJob::dispatch($driver)
                ->delay(
                    now()->addSeconds(
                        jobDelaySeconds()
                    )
                );
        } else {
            (new DriverDetailsJob($driver))->handle();
        }
    }

    public function driverVehicleTypeJob($driver)
    {

        if (delayFCMJob()) {
            DriverVehicleTypeJob::dispatch($driver)
                ->delay(
                    now()->addSeconds(
                        jobDelaySeconds()
                    )
                );
        } else {
            (new DriverVehicleTypeJob($driver))->handle();
        }
    }

    public function clearDriverFCMJob($expiredDriverNewOrder)
    {

        //clear firebase data
        if (delayFCMJob()) {
            ClearDriverFirebaseJob::dispatch($expiredDriverNewOrder)
                ->delay(
                    now()->addSeconds(
                        jobDelaySeconds()
                    )
                );
        } else {
            (new ClearDriverFirebaseJob($expiredDriverNewOrder))->handle();
        }
    }

    public function pushOrderToFCMJob($order)
    {

        //clear firebase data
        if (delayFCMJob()) {
            PushToFirebaseJob::dispatch($order, \Auth::user())
                ->delay(
                    now()->addSeconds(
                        jobDelaySeconds()
                    )
                );
        } else {
            (new PushToFirebaseJob($order, \Auth::user()))->handle();
        }
    }

    public function clearFCMJob($order)
    {

        //clear firebase data
        ClearFirebaseJob::dispatch($order, \Auth::user())
            ->delay(
                now()->addSeconds(
                    (jobDelaySeconds() + 40) ?? 60
                )
            );
    }


    //
    //Type
    /**
     * 1 - Regulater Status change
     * 2 - Taxi status change
     * 3 - Driver notification
     */
    public function orderFCMNotificationJob($order, $type = 1, $status = null)
    {

        //clear firebase data
        if (delayFCMJob()) {
            OrderStatusNotificationJob::dispatch($order, $type, $status)
                ->delay(now()->addSeconds(jobDelaySeconds()));
        } else {
            (new OrderStatusNotificationJob($order, $type, $status))->handle();
        }
    }

    public function orderPaymentRequestNotificationJob($order)
    {

        //clear firebase data
        if (delayFCMJob()) {
            OrderPaymentRequestNotificationJob::dispatch($order)
                ->delay(
                    now()->addSeconds(
                        jobDelaySeconds()
                    )
                );
        } else {
            (new OrderPaymentRequestNotificationJob($order))->handle();
        }
    }

    public function orderPaymentStatusChangeNotificationJob($order)
    {

        //clear firebase data
        if (delayFCMJob()) {
            OrderPaymentStatusChangeNotificationJob::dispatch($order)
                ->delay(
                    now()->addSeconds(
                        jobDelaySeconds()
                    )
                );
        } else {
            (new OrderPaymentStatusChangeNotificationJob($order))->handle();
        }
    }

    //for taxi order push to firestore
    public function uploadTaxiOrderJob($order)
    {

        // $taxiUseFirebaseServer = (bool) setting('taxiUseFirebaseServer', false);
        //force all taxi matchng to use VPS server
        //TODO: To be able to disable pushing data to firebase
        $delayFor = setting('taxiDelayTaxiMatching', 2); //now()->addSeconds(setting('taxiDelayTaxiMatching', 2));
        $assignmentType = setting('autoassignmentsystem', 0);
        if ($assignmentType == 0) {
            VPSTaxiOrderMatchingJob::dispatch($order)->delay($delayFor);
        } else {
            TaxiOrderMatchingJob::dispatch($order)->delay($delayFor);
        }
    }




    //Vendor mails
    public function sendWelcomeToVendor($vendor)
    {

        NewVendorJob::dispatch($vendor)
            ->delay(
                now()->addSeconds(
                    jobDelaySeconds()
                )
            );
    }
    public function sendUpdateToVendor($vendor)
    {

        VendorUpdateJob::dispatch($vendor)
            ->delay(
                now()->addSeconds(
                    jobDelaySeconds()
                )
            );
    }
}
