<?php

namespace App\Http\Livewire\Tables;

use App\Models\Currency;
use Kdion4891\LaravelLivewireTables\Column;

class CurrencyTable extends BaseTableComponent
{

    public $model = Currency::class;
    public $header_view = 'components.buttons.new';

    public function query()
    {
        return Currency::query();
    }

    public function columns()
    {
        return [
            Column::make(__('ID'),"id")->searchable()->sortable(),
            Column::make(__('Name'),'name')->searchable(),
            Column::make(__('Code'),'code')->searchable(),
            Column::make(__('Country Code'),'country_code')->searchable(),
            Column::make(__('Symbol'),'symbol')->searchable(),
            Column::make(__('Actions'))->view('components.buttons.currency_actions'),
        ];
    }

    public function activateModel()
    {

        try {

            $this->isDemo();

            // update the site name
            setting([
                'currencyCode' =>  $this->selectedModel->code,
                'currency' =>  $this->selectedModel->symbol,
                'currencyCountryCode' =>  $this->selectedModel->country_code ?? 'GH',
            ])->save();
            $this->showSuccessAlert(__("Activated"));
        } catch (\Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Failed"));
        }
    }
}
