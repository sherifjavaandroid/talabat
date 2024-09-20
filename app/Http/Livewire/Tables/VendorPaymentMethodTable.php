<?php

namespace App\Http\Livewire\Tables;


use App\Models\VendorPaymentMethod;
use Exception;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Column;

class VendorPaymentMethodTable extends BaseDataTableComponent
{

    public $model = VendorPaymentMethod::class;
    public string $defaultSortColumn = 'payment_method_id';
    public string $defaultSortDirection = 'desc';
    public $header_view = 'components.buttons.new';
    public $sort_attribute = 'payment_method.id';

    public function query()
    {
        return VendorPaymentMethod::with('payment_method')
            ->where('vendor_id', Auth::user()->vendor_id);
    }

    public function columns(): array
    {
        return [
            Column::make(__('ID'), 'payment_method.id')->addClass('w-24'),
            Column::make(__('Name'), 'payment_method.name')->searchable(),
            Column::make(__('Actions'))->format(function ($value, $column, $row) {
                return view('components.buttons.delete', $data = [
                    "model" => $row
                ]);
            })->addClass('w-24'),
        ];
    }

    public function initiateDelete($id)
    {
        $this->selectedModel = $id;

        $this->confirm('Delete', [
            'icon' => 'question',
            'toast' => false,
            'text' =>  __('Are you sure you want to delete the selected data?'),
            'position' => 'center',
            'showConfirmButton' => true,
            'cancelButtonText' => __("Cancel"),
            'onConfirmed' => 'deleteModel',
            'onCancelled' => 'cancelled'
        ]);
    }

    public function deleteModel()
    {

        try {
            $this->isDemo();
            Auth::user()->vendor->payment_methods()->detach($this->selectedModel);
            $this->showSuccessAlert(__("Deleted"));
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? "Failed");
        }
    }
}