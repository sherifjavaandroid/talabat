<?php

namespace App\Http\Livewire\Tables\Reports;

use App\Exports\ProductReportExport;
use App\Models\Service;
use Maatwebsite\Excel\Facades\Excel;

use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class ServiceReportTable extends BaseReportTable
{

    public $model = Service::class;

    public function query()
    {
        return $this->model::mine()->withCount(['sales' => function ($query) {
            $query->when($this->getFilter('start_date'), fn ($query, $sDate) => $query->whereDate('created_at', ">=", $sDate))
                ->when($this->getFilter('end_date'), fn ($query, $eDate) => $query->whereDate('created_at', "<=", $eDate))->groupBy('service_id');
        }]);
    }

    public function columns(): array
    {
        return [
            Column::make(__('ID'), 'id'),
            Column::make(__('Image'))->format(function ($value, $column, $row) {
                return view('components.table.image_sm', $data = [
                    "model" => $row
                ]);
            }),
            Column::make(__('Name'), 'name')->searchable()->sortable(),
            Column::make(__('Vendor'), "vendor.name")->searchable()->sortable(),
            Column::make(__('Order Count'), 'sales_count')->format(function ($value, $column, $row) {
                return view('components.table.count', $data = [
                    "model" => $row,
                    "value" => $value
                ]);
            })->sortable(),
        ];
    }




    public function exportSelected()
    {

        $this->isDemo(true);
        if ($this->selectedRowsQuery->count() > 0) {
            $fileName = "service_report(";
            //
            if ($this->getFilter('start_date')) {
                $fileName .= $this->getFilter('start_date') . " - ";
            }
            //
            else if ($this->getFilter('end_date')) {
                $fileName .= $this->getFilter('end_date');
            }

            $fileName .= ").xlsx";
            return Excel::download(new ProductReportExport($this->selectedRowsQuery), $fileName);
        } else {
            //
        }
    }
}
