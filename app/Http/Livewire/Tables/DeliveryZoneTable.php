<?php

namespace App\Http\Livewire\Tables;

use App\Models\DeliveryZone;
use App\Traits\DBTrait;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class DeliveryZoneTable extends BaseDataTableComponent
{

    use DBTrait;

    public $model = DeliveryZone::class;
    public string $defaultSortColumn = 'is_active';
    public string $defaultSortDirection = 'desc';

    public function filters(): array
    {
        return [
            'start_date' => Filter::make(__('Start Date'))
                ->date([
                    'min' => now()->subYear()->format('Y-m-d'), // Optional
                    'max' => now()->format('Y-m-d') // Optional
                ]),
            'end_date' => Filter::make(__('End Date'))
                ->date([
                    'min' => now()->subYear()->format('Y-m-d'), // Optional
                    'max' => now()->format('Y-m-d') // Optional
                ])
        ];
    }

    public function query()
    {
        return $this->model::withCount('vendors')
            ->when($this->getFilter('start_date'), fn ($query, $sDate) => $query->whereDate('created_at', ">=", $sDate))
            ->when($this->getFilter('end_date'), fn ($query, $eDate) => $query->whereDate('created_at', "<=", $eDate));
    }

    public function columns(): array
    {
        $columns = [
            $this->indexColumn(),
            Column::make(__('Name'), 'name')->searchable()->sortable(),
            Column::make(__('Delivery Fee'), 'delivery_fee')->format(function ($value, $column, $row) {
                if ($value != null) {
                    $text = currencyFormat($value ??  '');
                } else {
                    $text = "--";
                }
                return view('components.table.plain', $data = [
                    "text" => $text
                ]);
            })->searchable()->sortable(),

            Column::make(__('Vendors'), 'vendors_count'),
            Column::make(__('Active'))->format(function ($value, $column, $row) {
                return view('components.table.active', $data = [
                    "model" => $row
                ]);
            }),
            Column::make(__('Created At'), 'formatted_date'),
        ];

        if (app()->environment('production')) {
            $columns[] = Column::make(__('Actions'))->format(function ($value, $column, $row) {
                return view('components.buttons.actions', $data = [
                    "model" => $row
                ]);
            });
        }

        return $columns;
    }

    public function deleteModel()
    {

        try {
            $this->isDemo();
            \DB::beginTransaction();
            $this->clearTableRecordsBy("delivery_zone_id", $this->selectedModel->id);
            $this->selectedModel->delete();
            \DB::commit();
            $this->showSuccessAlert(__("Deleted"));
        } catch (Exception $error) {
            \DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? "Failed");
        }
    }
}