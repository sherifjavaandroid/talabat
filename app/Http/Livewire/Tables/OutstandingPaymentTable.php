<?php

namespace App\Http\Livewire\Tables;

use App\Models\OutstandingBalance;
use App\Models\PaymentMethod;
use App\Models\WalletTransaction;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;


class OutstandingPaymentTable extends BaseDataTableComponent
{

    public $model = OutstandingBalance::class;
    public function query()
    {
        $query = OutstandingBalance::with('user', 'order');
        return $query->when($this->getFilter('status'), fn ($query, $status) => $query->where('completed', $status))
            ->when($this->getFilter('start_date'), fn ($query, $sDate) => $query->whereDate('created_at', ">=", $sDate))
            ->when($this->getFilter('end_date'), fn ($query, $eDate) => $query->whereDate('created_at', "<=", $eDate))
            ->orderBy('created_at', 'DESC');
    }

    public function filters(): array
    {
        return [
            'status' => Filter::make(__("Payment Status"))
                ->select([
                    '' => __('Any'),
                    '1' => __('Paid'),
                    '0' => __('Unpaid'),
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

    public function columns(): array
    {
        return [
            Column::make(__('ID'), "id"),
            Column::make(__('Order Code'), 'order.code')->format(function ($value, $column, $row) {
                return view('components.table.order', $data = [
                    "model" => $row->order,
                    "value" => $value
                ]);
            })->searchable(),
            Column::make(__('User'), 'user.name')->searchable(),
            Column::make(__('Amount'), 'amount')->format(function ($value, $column, $row) {
                return view('components.table.price', $data = [
                    "model" => $row,
                    "value" => $value,
                ]);
            })->searchable()->sortable(),
            Column::make(__('Paid'), 'paid')->format(function ($value, $column, $row) {
                return view('components.table.price', $data = [
                    "model" => $row,
                    "value" => $value,
                ]);
            })->searchable()->sortable(),
            Column::make(__('Balance'), 'balance')->format(function ($value, $column, $row) {
                return view('components.table.price', $data = [
                    "model" => $row,
                    "value" => $value,
                ]);
            })->searchable()->sortable(),
            //
            Column::make(__('Status'), 'completed')->format(function ($value, $column, $row) {
                return view('components.table.custom', $data = [
                    "value" => $value ? __("Paid") : __("Unpaid")
                ]);
            }),
            Column::make(__('Created At'), 'formatted_date'),
        ];
    }


    public function activateModel()
    {

        try {
            \DB::beginTransaction();
            request()->session()->put('wallet_transaction_code', $this->selectedModel->ref ??  $this->selectedModel->session_id);
            $this->selectedModel->status = "successful";
            $this->selectedModel->save();
            //update wallet balance
            $this->selectedModel->wallet->balance += $this->selectedModel->amount;
            $this->selectedModel->wallet->save();
            \DB::commit();
            $this->showSuccessAlert("Activated");
        } catch (Exception $error) {
            \DB::rollback();
            $this->showErrorAlert("Failed");
        }
    }


    public function deactivateModel()
    {

        try {
            \DB::beginTransaction();
            $this->selectedModel->status = "failed";
            $this->selectedModel->save();
            \DB::commit();
            $this->showSuccessAlert("Deactivated");
        } catch (Exception $error) {
            $this->showErrorAlert("Failed");
        }
    }
}
