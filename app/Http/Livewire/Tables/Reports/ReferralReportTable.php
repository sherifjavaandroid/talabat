<?php

namespace App\Http\Livewire\Tables\Reports;

use App\Exports\ReferralReportExport;
use App\Models\Referral;
use Maatwebsite\Excel\Facades\Excel;

use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class ReferralReportTable extends BaseReportTable
{

    public $model = Referral::class;

    public function filters(): array
    {
        return [
            'status' => Filter::make(__("Status"))
                ->select([
                    '' => __('Any'),
                    '1' => __('Completed'),
                    '0' => __('Not Completed'),
                ]),
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
        //
        return $this->model::with(
            'referringUser',
            'referredUser'
        )->mine()
            ->when($this->getFilter('status'), function ($query, $status) {
                return $query->where('confirmed', $status);
            })->when($this->getFilter('start_date'), function ($query, $sDate) {
                return $query->whereDate('created_at', ">=", $sDate);
            })->when($this->getFilter('end_date'), function ($query, $eDate) {
                return $query->whereDate('created_at', "<=", $eDate);
            });
    }

    public function columns(): array
    {
        return [
            Column::make(__('ID'), 'id'),
            // Column::make(__('Referring User'), 'referringUser.name')->searchable(),
            Column::make(__('Referring User'), 'referringUser.name')
                ->format(function ($value, $column, $row) {
                    return view('components.table.user', $data = [
                        "value" => $value,
                        "model" => $row->referringUser,
                    ]);
                })->searchable(),
            // Column::make(__('Referred User'), 'referredUser.name')->searchable(),
            Column::make(__('Referred User'), 'referredUser.name')
                ->format(function ($value, $column, $row) {
                    return view('components.table.user', $data = [
                        "value" => $value,
                        "model" => $row->referredUser,
                    ]);
                })->searchable(),
            Column::make(__('Amount'), 'amount')->format(function ($value, $column, $row) {
                return view('components.table.price', $data = [
                    "model" => $row,
                    "column" => $column,
                    "value" => number_format($value, 2),
                ]);
            })->sortable(),
            Column::make(__('Confirmed'), 'confirmed')->addClass('w-32')->format(function ($value, $column, $row) {
                return view('components.table.chip', $data = [
                    "text" => $value ? __("Yes") : __("No"),
                    'bgColor' => $value ? 'bg-green-500' : 'bg-red-500',
                    'textColor' =>  'text-white',
                ]);
            })->sortable(),
            Column::make(__('Created At'), 'formatted_date'),
        ];
    }




    public function exportSelected()
    {
        $this->isDemo(true);
        //
        if ($this->selectedRowsQuery->count() > 0) {
            $fileName = "referral_report(";
            //
            if ($this->getFilter('start_date')) {
                $fileName .= $this->getFilter('start_date') . " - ";
            }
            //
            else if ($this->getFilter('end_date')) {
                $fileName .= $this->getFilter('end_date');
            }

            $fileName .= ").xlsx";

            //
            $dataSet = $this->selectedRowsQuery->get()->toArray();
            //
            return Excel::download(new ReferralReportExport($dataSet), $fileName);
        } else {
            //
        }
    }
}
