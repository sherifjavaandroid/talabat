<?php

namespace App\Http\Livewire\Tables;

use App\Models\Refund;
use App\Models\User;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

use Illuminate\Support\Facades\Auth;

class RefundTable extends BaseDataTableComponent
{

    public $model = Refund::class;

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

        $user = User::find(Auth::id());
        if ($user->hasRole('admin')) {
            $query = Refund::query();
        } else if ($user->hasRole('city-admin')) {
            $query = Refund::with('order.vendor')->whereHas("order.vendor", function ($query) {
                return $query->where('creator_id', Auth::id());
            });
        } else {
            $query = Refund::with('order.vendor')->whereHas("order.vendor", function ($query) {
                return $query->where('order.vendor.id', Auth::user()->vendor_id);
            });
        }

        return $query->when($this->getFilter('start_date'), fn ($query, $sDate) => $query->whereDate('created_at', ">=", $sDate))
            ->when($this->getFilter('end_date'), fn ($query, $eDate) => $query->whereDate('created_at', "<=", $eDate));
    }

    public function columns(): array
    {

        return [
            Column::make(__('ID'), 'id')->searchable()->sortable(),
            Column::make(__('Code'), 'order.code')->searchable(),
            Column::make(__('User'), 'order.user.name')->searchable(),
            Column::make(__('Status'), 'status')
                ->format(function ($value, $column, $row) {
                    return view('components.table.custom', $data = [
                        "value" => __(\Str::ucfirst($value))
                    ]);
                }),
            Column::make(__('Total'), 'order.total')->format(function ($value, $column, $row) {
                return view('components.table.price', $data = [
                    "value" => $value ?? 0,
                ]);
            })->searchable(),
            Column::make(__('Method'), 'order.payment_method.name')->searchable(),
            Column::make(__('Created At'), 'created_at')->sortable(),
            Column::make(__('Actions'))->format(function ($value, $column, $row) {
                return view('components.buttons.refund_actions', $data = [
                    "model" => $row
                ]);
            })
        ];
    }

    public function setTableRowClass($row): ?string
    {
        $orderIsTrashed = $row->order->trashed();
        return !$orderIsTrashed ? null : 'inactive-item';
    }


    public function approveModel()
    {

        try {
            if ($this->checkDemo) {
                $this->isDemo();
            }
            \DB::beginTransaction();
            $this->selectedModel->order->refundUser(true);
            $this->selectedModel->status = "successful";
            $this->selectedModel->save();
            \DB::commit();
            $this->showSuccessAlert(__("Approved"));
        } catch (Exception $error) {
            \DB::rollback();
            $this->showErrorAlert("Failed");
        }
    }
}