<?php

namespace App\Http\Livewire\Tables;


use App\Models\PaymentMethod;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PaymentMethodTable extends BaseDataTableComponent
{

    public $model = PaymentMethod::class;
    public string $defaultSortColumn = 'is_active';
    public string $defaultSortDirection = 'desc';

    public function query()
    {
        return PaymentMethod::query();
    }

    public function columns(): array
    {
        return [
            Column::make(__('ID'), "id")->searchable()->sortable()->addClass('w-12'),
            $this->smImageColumn($position = "object-center")->addClass('w-24'),
            Column::make(__('Name'), 'name')->searchable()->sortable(),
            Column::make(__('Slug'), 'slug')->addClass('w-24'),
            // $this->activeColumn()->sortable(),
            Column::make(__('Created At'), 'formatted_date')->sortable(function ($query, $direction) {
                return $query->orderBy('created_at', $direction);
            }),
            Column::make(__('Actions'))->format(function ($value, $column, $row) {
                return view('components.buttons.no_delete_actions', $data = [
                    "model" => $row
                ]);
            }),
        ];
    }

    public function setTableRowClass($row): ?string
    {
        return $row->is_active ? null : 'inactive-item';
    }


    public function activateModel()
    {

        try {
            $this->isDemo();
            $this->selectedModel->is_active = true;
            $this->selectedModel->save();
            $this->showSuccessAlert(__("Activated"));
        } catch (\Exception $error) {
            $this->showErrorAlert("Failed");
        }
    }


    public function deactivateModel()
    {

        try {
            $this->isDemo();
            $this->selectedModel->is_active = false;
            $this->selectedModel->save();
            $this->showSuccessAlert(__("Deactivated"));
        } catch (\Exception $error) {
            $this->showErrorAlert("Failed");
        }
    }
}