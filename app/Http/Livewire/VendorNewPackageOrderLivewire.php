<?php

namespace App\Http\Livewire;

use App\Http\Controllers\API\PackageOrderController;
use App\Models\DeliveryAddress;
use App\Models\Order;
use Exception;
use App\Models\PackageType;
use App\Models\PackageTypePricing;
use App\Models\Vendor;
use App\Models\PaymentMethod;
use App\Models\Coupon;
use App\Services\ParcelOrderService;
use App\Traits\PackageOrderTrait;
use Illuminate\Support\Facades\Auth;

class VendorNewPackageOrderLivewire extends BaseLivewireComponent
{
    use PackageOrderTrait;

    //
    public $model = Order::class;
    public $currentStep = 1;
    public $packageTypes = [];
    public $orderStops = [];
    public $deliveryAddresses = [];
    public $scheduleDates = [];
    public $scheduleTimes = [];
    public $orderSummary = [];
    public $paymentMethods = [];


    //selections
    public $user_id = null;
    public $selectedPackageTypeId = null;
    public $packageType = null;
    public $packageTypePricing = null;
    public $vendor_id = null;
    public $schedule_enable;
    public $schedule_date;
    public $schedule_time;
    public $package_weight;
    public $package_length;
    public $package_width;
    public $package_height;
    public $orderStopsPreview = [];
    public $payer = "sender";
    public $coupon_code = null;
    public $coupon = null;
    public $payment_method_id = null;
    // amounts
    public $distance = 0;
    public $sub_total = 0;
    public $delivery_fee = 0;
    public $package_parameter_fee = 0;
    public $discount = 0;
    public $total = 0;
    public $tax;
    public $tax_rate;
    public $fees = [];
    public $orderToken = null;


    public $listeners = [
        'user_idUpdated' => 'updatedUserId',
        'schedule_dateUpdated' => 'updatedScheduleDate',
        'schedule_timeUpdated' => 'updatedScheduleTime',
        'autocompleteAddressSelected' => 'autocompleteAddressSelected',
    ];

    public function render()
    {
        return view('livewire.vendor-new-order-package');
    }


    public function fetchPackageTypes()
    {
        $this->packageTypes = PackageType::active()
            ->whereHas("package_type_pricings", function ($query) {
                $query->where('vendor_id', Auth::user()->vendor_id);
            })
            ->get();
    }

    public function updatedUserId($value)
    {
        $this->user_id = $value['value'];
    }

    public function onPackageTypeSelected($id)
    {
        $this->selectedPackageTypeId = $id;
        $this->packageType = PackageType::find($id);
    }

    public function validateStep1()
    {
        if ($this->user_id == null) {
            $this->addError('user_id', __("Please select a user"));
            return;
        }

        if ($this->selectedPackageTypeId == null) {
            $this->showErrorAlert(__("Please select a package type"));
            return;
        }
        //
        $this->prepareOrderStops();
    }

    //step 2, delivery location selection
    public function prepareOrderStops()
    {
        //if order stops is empty, add the first one
        if ($this->orderStops == null || count($this->orderStops) == 0) {
            $this->addOrderStop(
                $label = __("From")
            );

            //also check if multiple stops are allowed
            $multipleStops = (bool) setting('enableParcelMultipleStops');
            if (!$multipleStops) {
                $this->addOrderStop(
                    $label = __("To")
                );
            } else {
                $this->addOrderStop();
            }
        }

        //fetch delivery addresses
        $this->deliveryAddresses = DeliveryAddress::where('user_id', $this->user_id)->get();
        //map to array
        $this->deliveryAddresses = $this->deliveryAddresses->map(function ($item) {
            return [
                "id" => $item->id,
                "name" => $item->name . " - " . $item->address,
            ];
        })->toArray();

        //
        $this->nextStep();
    }


    public function addOrderStop($label = null)
    {
        $this->orderStops[] = [
            "code" => uniqid(),
            "label" => $label ?? __("Stop"),
            "id" => "",
            //toggle to show map picker or address picker
            "showMapPicker" => false,
            "name" => "",
            "address" => "",
            "latitude" => "",
            "longitude" => "",
            "contact" => [
                "name" => "",
                "phone" => "",
                "note" => "",
            ]
        ];
    }

    public function removeOrderStop($index)
    {
        //prevent removing if the array is 2 or less
        if (count($this->orderStops) <= 2) {
            return;
        }
        unset($this->orderStops[$index]);
        $this->orderStops = array_values($this->orderStops);
    }

    public function openMapPicker($index)
    {
        $this->orderStops[$index]['showMapPicker'] = true;
    }

    public function openAddressPicker($index)
    {
        $this->orderStops[$index]['showMapPicker'] = false;
    }

    public function autocompleteAddressSelected($fullAddress, $key)
    {
        $this->orderStops[$key]['address'] = $fullAddress['address'];
        $this->orderStops[$key]['latitude'] = $fullAddress['latitude'];
        $this->orderStops[$key]['longitude'] = $fullAddress['longitude'];
        $this->orderStops[$key]['name'] = $fullAddress['name'] ?? $fullAddress['address'];
        //
        $this->orderStops[$key]['city'] = $fullAddress['city'];
        $this->orderStops[$key]['state'] = $fullAddress['state'];
        $this->orderStops[$key]['country'] = $fullAddress['country'];
    }


    public function updatedorderStops($value, $key)
    {
        $exploded = explode(".", $key);
        $index = $exploded[0];
        $field = $exploded[1];
        //if th filed is not id or address, return
        if ($field != "id" && $field != "address") {
            return;
        }

        //if value is null or empty, clear the address
        if ($value == null || empty($value)) {
            $this->orderStops[$index]['address'] = "";
            $this->orderStops[$index]['latitude'] = "";
            $this->orderStops[$index]['longitude'] = "";
            $this->orderStops[$index]['name'] = "";
            $this->orderStops[$index]['city'] = "";
            $this->orderStops[$index]['state'] = "";
            $this->orderStops[$index]['country'] = "";
            $this->orderStops[$index][$field] = $value;
        } else {

            //
            $fullAddress = DeliveryAddress::find($value);
            $this->orderStops[$index]['address'] = $fullAddress['address'];
            $this->orderStops[$index]['latitude'] = $fullAddress['latitude'];
            $this->orderStops[$index]['longitude'] = $fullAddress['longitude'];
            $this->orderStops[$index]['name'] = $fullAddress['name'] ?? $fullAddress['address'];
            //
            $this->orderStops[$index]['city'] = $fullAddress['city'];
            $this->orderStops[$index]['state'] = $fullAddress['state'];
            $this->orderStops[$index]['country'] = $fullAddress['country'];
            $this->orderStops[$index][$field] = $value;
        }
    }


    public function validateStep2()
    {

        //clear error bag
        $this->resetErrorBag();
        $this->vendor_id = Auth::user()->vendor_id;
        //manual validation
        foreach ($this->orderStops as $key => $stop) {
            //
            if ($stop['id'] != null || $stop['address'] != null || !empty($stop['address'])) {
                //check if vendor service is available in the selected location
                $isLocatioServices = $this->isStopServiceByVendor($this->vendor_id, $stop);
                if (!$isLocatioServices) {
                    $this->addError('orderStops.' . $key . '.address', __("Vendor does not service this location"));
                }
            } else {
                $this->addError('orderStops.' . $key . '.address', __("Please select an address"));
            }
        }

        //has errors
        if ($this->getErrorBag()) {
            $errors = $this->getErrorBag();
            //check if empty
            if (count($errors) > 0) {
                return;
            }
        }

        $this->nextStep();
        $this->prepareVendorPricing();
    }


    public function prepareVendorPricing()
    {

        $id = Auth::user()->vendor_id;
        $this->vendor_id = $id;
        $this->selectedModel = Vendor::find($id);
        $this->packageTypePricing = PackageTypePricing::where('vendor_id', $id)->where('package_type_id', $this->selectedPackageTypeId)->first();

        //emit event to update the vendor_id
        $this->emit('vendor_idUpdated', [
            'value' => $id,
            "name" => "vendor_id",
        ]);
    }

    //on schedule_enable updated
    public function updatedScheduleEnable($value)
    {
        //TODO: check if the vendor has schedule enabled
        $this->schedule_enable = $value;
        $this->emit('vendor_idUpdated', [
            'value' => $this->vendor_id,
            "name" => "vendor_id",
        ]);
    }



    public function updatedScheduleDate($value)
    {
        $this->schedule_date = $value['value'];
    }

    public function updatedScheduleTime($value)
    {
        $this->schedule_time = $value['value'];
    }



    //step 3, contact selection
    public function validateStep3()
    {
        $this->resetErrorBag();
        //
        $this->validate([
            'orderStops.*.contact.name' => 'required',
            'orderStops.*.contact.phone' => 'required|numeric',
        ]);
        //
        $this->nextStep();
    }


    //step 4, package parameters
    public function validateStep4()
    {
        $this->resetErrorBag();

        //check if extra items are enabled
        if ($this->packageTypePricing != null && $this->packageTypePricing->field_required) {
            $this->validate([
                'package_weight' => 'required|numeric',
                'package_length' => 'required|numeric',
                'package_width' => 'required|numeric',
                'package_height' => 'required|numeric',
            ]);
        }
        //
        $this->nextStep();
        $this->prepareSummary();
    }



    //step 5, summary
    public function prepareSummary()
    {
        //stops
        $this->orderStopsPreview = [];

        foreach ($this->orderStops as $key => $stop) {

            //check if stop is new or existing
            if ($stop['id'] == null || empty($stop['id'])) {
                //create new delivery address and add to orderStopsPreview
                $mDeliveryAddress = new DeliveryAddress();
                $mDeliveryAddress->user_id = $this->user_id;
                $mDeliveryAddress->name = $stop['address'];
                $mDeliveryAddress->address = $stop['address'];
                $mDeliveryAddress->latitude = $stop['latitude'];
                $mDeliveryAddress->longitude = $stop['longitude'];
                $mDeliveryAddress->city = $stop['city'];
                $mDeliveryAddress->state = $stop['state'];
                $mDeliveryAddress->country = $stop['country'];
                $mDeliveryAddress->save();
                //
                $this->orderStops[$key]['id'] = $mDeliveryAddress->id;
                $stop['id'] = $mDeliveryAddress->id;
            }


            //get address from db
            $mDeliveryAddress = DeliveryAddress::find($stop['id']);
            $this->orderStopsPreview[] = [
                "label" => $stop['label'],
                "name" => $stop['contact']['name'],
                "phone" => $stop['contact']['phone'],
                "note" => $stop['contact']['note'],
                "address" => $mDeliveryAddress->address,
                "latitude" => $mDeliveryAddress->lat,
                "longitude" => $mDeliveryAddress->long,
            ];
        }
    }




    //step 6, payment
    public function initiatePaymentPage()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->fetchPaymentMethods();
        //
        $this->nextStep();
        $this->calculateOrderAmounts();
    }

    public function fetchPaymentMethods()
    {
        //fetch vendor payment methods
        $this->paymentMethods = $this->selectedModel->payment_methods;
        if ($this->paymentMethods == null || count($this->paymentMethods) == 0) {
            $this->paymentMethods = PaymentMethod::active()->get();
        }
    }

    //when receiver is selected, allow only cash payment method
    public function updatedPayer($value)
    {
        $this->payment_method_id = null;
        if ($value == "receiver") {
            $this->paymentMethods = PaymentMethod::active()->where('slug', 'cash')->get();
        } else {
            $this->fetchPaymentMethods();
        }
    }

    public function onPaymentMethodSelected($id)
    {
        $this->payment_method_id = $id;
    }

    public function applyCoupon()
    {
        $this->resetErrorBag();
        $this->validate([
            'coupon_code' => 'required|exists:coupons,code',
        ]);

        //
        $this->coupon = Coupon::where('code', $this->coupon_code)->first();

        //check if the coupon vendor type is same as the selected vendor
        if ($this->coupon->vendor_type_id != null && $this->coupon->vendor_type_id != $this->selectedModel->vendor_type_id) {
            $this->showErrorAlert(__("Coupon code is invalid for this vendor type"));
            $this->coupon = null;
            $this->calculateOrderAmounts();
            return;
        }
        //
        if ($this->coupon == null) {
            $this->showErrorAlert(__("Coupon code is invalid"));
        }

        //check if vendor id is in the list of allowed vendors for this coupon
        $vendorIds = $this->coupon->vendors->pluck('id')->toArray();
        if (count($vendorIds) > 0 && !in_array($this->selectedModel->id, $vendorIds)) {
            $this->showErrorAlert(__("Coupon code is invalid for this vendor"));
            $this->coupon = null;
            $this->calculateOrderAmounts();
            return;
        }

        //
        $this->calculateOrderAmounts();
    }

    public function removeCoupon()
    {
        $this->coupon = null;
        $this->coupon_code = null;
        $this->calculateOrderAmounts();
    }

    public function calculateOrderAmounts()
    {
        //get the order distance
        $packageOrderController =  new PackageOrderController();
        $locations = [];
        foreach ($this->orderStops as $stop) {
            // $mDeliveryAddress = DeliveryAddress::find($stop['id']);
            $locations[] = [
                "id" => $stop['id'],
            ];
        }
        $payload = [
            'vendor_id' => $this->selectedModel->id,
            'package_type_id' => $this->selectedPackageTypeId,
            'stops' => $locations,
            'coupon_code' => $this->coupon_code,
            "weight" => $this->package_weight,
        ];
        $request = new \Illuminate\Http\Request();
        $request->merge($payload);
        $response = $packageOrderController->summary($request);
        if ($response->getStatusCode() != 200) {
            $this->showErrorAlert($response->getData(true)['message'] ??   __("Failed to fetch summary"));
            return;
        }

        //
        $summary = $response->getData(true);
        $this->distance = $summary['distance'];
        $this->delivery_fee = $summary['delivery_fee'];
        $this->sub_total = $summary['sub_total'];
        $this->package_parameter_fee = $summary['package_type_fee'];
        $this->tax = $summary['tax'];
        $this->fees = $summary['vendor_fees'];
        $this->total = $summary['total'];
        $this->discount = $summary['discount'];
        $this->orderToken = $summary['token'];
    }



    public function placeNewOrder()
    {
        //
        if ($this->payer == null) {
            $this->showErrorAlert(__("Please select a payer"));
            return;
        } else if ($this->payment_method_id == null) {
            $this->showErrorAlert(__("Please select a payment method"));
            return;
        }

        //create order
        try {
            //get the order distance
            $locations = [];
            foreach ($this->orderStops as $stop) {
                $locations[] = [
                    "id" => $stop['id'],
                    "name" => $stop['contact']['name'],
                    "phone" => $stop['contact']['phone'],
                    "note" => $stop['contact']['note'],
                ];
            }
            $payload = [
                "type" => "package",
                "note" => "",
                "coupon_code" => $this->coupon_code,
                "package_type_id" => $this->selectedPackageTypeId,
                "vendor_id" => $this->selectedModel->id,
                "pickup_date" => $this->schedule_date,
                "pickup_time" =>  $this->schedule_time,
                "stops" => $locations,
                "weight" => $this->package_weight,
                "width" => $this->package_width,
                "length" => $this->package_length,
                "height" => $this->package_height,
                "payment_method_id" => $this->payment_method_id,
                "sub_total" => $this->sub_total,
                "discount" => $this->discount,
                "delivery_fee" => $this->delivery_fee,
                "tax" => $this->tax,
                "tax_rate" => $this->tax_rate,
                "token" => $this->orderToken,
                "payer" => $this->payer == "sender" ? true : false,
                "fees" => $this->fees,
                "total" => $this->total,
            ];
            $request = new \Illuminate\Http\Request();
            $request->merge($payload);
            $response = (new ParcelOrderService())->placeOrder($request, $this->user_id);
            if ($response->getStatusCode() != 200) {
                $this->showErrorAlert(__("Failed to place order"));
                return;
            }

            //message and link from response
            $message = $response->getData(true)['message'];
            $link = $response->getData(true)['link'];
            //
            $this->showSuccessAlert($message);
            //if link is not null, redirect to the link
            if ($link != null && $link != "") {
                $this->emit('newTab', $link);
            }

            //reload the page
            return redirect()->route('vendor.package.order.new');
        } catch (Exception $e) {
            $this->showErrorAlert($e->getMessage() ?? __("Failed to place order"));
            return;
        }
    }



    public function prevStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function nextStep()
    {
        $this->currentStep++;
    }
}
