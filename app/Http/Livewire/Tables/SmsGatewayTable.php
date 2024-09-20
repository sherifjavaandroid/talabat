<?php

namespace App\Http\Livewire\Tables;


use App\Models\SmsGateway;
use Rappasoft\LaravelLivewireTables\Views\Column;



class SmsGatewayTable extends BaseDataTableComponent
{

    public $model = SmsGateway::class;

    public function query()
    {
        return SmsGateway::query();
    }

    public function columns(): array
    {
        $columns = [
            Column::make(__('ID'), "id")->searchable()->sortable(),
            Column::make(__('Name'), 'name')->searchable()->sortable(),
        ];
        //hide actions column in demo
        if (!$this->inDemo()) {
            $columns[] =
                Column::make(__('Actions'))->format(function ($value, $column, $row) {
                    return view('components.buttons.simple_actions', $data = [
                        "model" => $row
                    ]);
                });
        }
        $columns[] = Column::make(__('Test'))->format(function ($value, $column, $row) {
            return view('components.buttons.show', $data = [
                "model" => $row
            ]);
        });

        return $columns;
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
