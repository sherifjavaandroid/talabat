<?php

namespace App\Http\Livewire\Tables\Taxi;

use App\Http\Livewire\Tables\BaseTableComponent;
use App\Models\PaymentMethod;
use Kdion4891\LaravelLivewireTables\Column;

class PaymentMethodsTable extends BaseTableComponent
{

    public $model = PaymentMethod::class;
    public $header_view = null;

    public function query()
    {
        return PaymentMethod::query();
    }

    public function columns()
    {
        return [
            Column::make(__('ID'),"id")->searchable()->sortable(),
            Column::make(__('Image'))->view('components.table.image_sm'),
            Column::make(__('Name'),'name')->searchable(),
            Column::make(__('Use In Taxi'),'use_taxi')->view('components.table.bool'),
            Column::make(__('Actions'),'use_taxi')->view('components.buttons.status_actions'),
        ];
    }

    public function activateModel()
    {

        try {
            $this->isDemo();
            $this->selectedModel->use_taxi = true;
            $this->selectedModel->save();
            $this->showSuccessAlert(__("Activated"));
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? "Failed");
        }
    }


    public function deactivateModel()
    {

        try {
            $this->isDemo();
            $this->selectedModel->use_taxi = false;
            $this->selectedModel->save();
            $this->showSuccessAlert(__("Deactivated"));
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? "Failed");
        }
    }
}
