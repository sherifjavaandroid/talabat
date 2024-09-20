<?php

namespace App\Http\Livewire\Tables;

use App\Models\PaymentMethod;
use App\Models\WalletTransaction;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;


class WalletTransactionTable extends BaseDataTableComponent
{

    public $model = WalletTransaction::class;
    public function query()
    {
        $query = WalletTransaction::with('wallet.user', 'payment_method');
        return $query->when($this->getFilter('status'), fn ($query, $status) => $query->where('status', $status))
            ->when($this->getFilter('payment_method_id'), fn ($query, $paymentMethodId) => $query->where('payment_method_id', $paymentMethodId))
            ->when($this->getFilter('start_date'), fn ($query, $sDate) => $query->whereDate('created_at', ">=", $sDate))
            ->when($this->getFilter('end_date'), fn ($query, $eDate) => $query->whereDate('created_at', "<=", $eDate))->orderBy('created_at', 'DESC');
    }

    public function filters(): array
    {
        $filterPaymentMethods = [
            '' => __('Any'),
        ];
        $paymentMethods = PaymentMethod::get();
        foreach ($paymentMethods as $paymentMethod) {
            $filterPaymentMethods[$paymentMethod->id] = $paymentMethod->name;
        }
        return [
            'payment_method_id' => Filter::make(__("Payment Method"))
                ->select($filterPaymentMethods),
            'status' => Filter::make(__("Payment Status"))
                ->select([
                    '' => __('Any'),
                    'pending' => __('Pending'),
                    'review' => __('Review'),
                    'successful' => __('Successful'),
                    'failed' => __('Failed'),
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
            Column::make(__('Ref No.'), 'ref')->format(function ($value, $column, $row) {
                return view('components.table.custom', $data = [
                    "value" => "" . $row->ref . " ",
                ]);
            })->searchable()->sortable(),
            Column::make(__('Amount'), 'amount')->format(function ($value, $column, $row) {
                return view('components.table.price', $data = [
                    "model" => $row,
                    "value" => $value,
                ]);
            })->searchable()->sortable(),
            Column::make(__('User'), 'wallet.user.name')->searchable(),
            Column::make(__('Status'))->searchable()->sortable(),
            Column::make(__('Method'))->format(function ($value, $column, $row) {
                $localTransfer = substr($row->ref, 0, strlen("lt_")) === "lt_";
                return view('components.table.payment_method', $data = [
                    "model" => $row,
                    "value" => $localTransfer ? __("Transfer") : ($row->payment_method != null ? $row->payment_method->name : ''),
                ]);
            }),
            Column::make(__('Created At'), 'formatted_date_time'),
            Column::make(__('Actions'))->format(function ($value, $column, $row) {
                return view('components.buttons.transaction_actions', $data = [
                    "model" => $row,
                ]);
            }),
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
