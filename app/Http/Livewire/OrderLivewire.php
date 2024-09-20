<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class OrderLivewire extends BaseLivewireComponent
{
    //
    public $model = Order::class;
    //
    public $orderId;
    public $deliveryBoyId;
    public $status;
    public $paymentStatus;
    public $note;

    //
    public $orderStatus;
    public $orderPaymentStatus;
    public $isPickup;



    public function getListeners()
    {
        return $this->listeners + [
            'autocompleteDeliveryAddressSelected' => 'autocompleteDeliveryAddressSelected',
            'deliveryBoyIdUpdated' => 'autocompleteDriverSelected',
        ];
    }

    public function render()
    {
        return view('livewire.orders');
    }


    public function loadCustomData()
    {
        if (empty($this->orderStatus)) {
            $this->orderStatus = $this->orderStatus();
        }
        if (empty($this->orderPaymentStatus)) {
            $this->orderPaymentStatus = $this->orderPaymentStatus();
        }
    }

    public function autocompleteDriverSelected($value)
    {
        try {
            //clear old products
            $this->deliveryBoyId = $value['value'];
        } catch (\Exception $ex) {
            logger("Error", [$ex]);
        }
    }

    public function showDetailsModal($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->orderId = $id;
        $this->showDetails = true;
        $this->stopRefresh = true;
    }

    // Updating model
    public function initiateEdit($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->deliveryBoyId = $this->selectedModel->driver_id;
        $this->status = $this->selectedModel->status;
        $this->paymentStatus = $this->selectedModel->payment_status;
        $this->note = $this->selectedModel->note;
        $this->loadCustomData();
        //
        if ($this->deliveryBoyId != null) {
            $this->emit('deliveryBoyId_Loaded', $this->deliveryBoyId);
        }
        $this->showEditModal();
    }


    public function update()
    {

        try {

            DB::beginTransaction();
            $this->selectedModel->driver_id = $this->deliveryBoyId ?? null;
            $this->selectedModel->payment_status = $this->paymentStatus;
            $this->selectedModel->note = $this->note;
            $this->selectedModel->setStatus($this->status);
            $this->selectedModel->save();
            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Order") . " " . __('updated successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Order") . " " . __('updated failed!'));
        }
    }



    //reivew payment
    public function reviewPayment($id)
    {
        //
        $this->selectedModel = $this->model::find($id);
        $this->emit('showAssignModal');
    }

    public function approvePayment()
    {
        //
        try {

            DB::beginTransaction();
            $this->selectedModel->payment_status = "successful";
            $this->selectedModel->save();
            //TODO - Issue fetch payment when prescription is been edited
            $this->selectedModel->payment->status = "successful";
            $this->selectedModel->payment->save();
            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Order") . " " . __('updated successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Order") . " " . __('updated failed!'));
        }
    }

    //
    public function showEditOrderProducts()
    {
        $this->closeModal();
        //only allow cod payment edit orders
        if ($this->selectedModel->payment_method != null && !$this->selectedModel->payment_method->is_cash) {
            $this->showErrorAlert(__("Only Order with Cash Payment can be edited. Thank you"));
        } else {
            $link = route('order.edit.products', [
                "code" => $this->selectedModel->code,
            ]);
            $this->emit('newTab', $link);
        }
    }


    public function showCreateModal()
    {
        $link = route('order.create');
        $this->emit('newTab', $link);
    }
}
