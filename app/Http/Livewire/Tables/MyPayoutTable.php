<?php

namespace App\Http\Livewire\Tables;

use App\Models\Payout;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Support\Facades\Auth;

class MyPayoutTable extends BaseDataTableComponent
{

    public $model = Payout::class;
    public $type;

    public function query()
    {

        $vendorId = \Auth::user()->vendor_id;
        return Payout::with('user', 'earning.user', 'earning.vendor')->whereHas('earning', function ($query) use ($vendorId) {
            return $query->whereNotNull('vendor_id')->where('vendor_id', $vendorId);
        })->orderBy('updated_at', 'desc');
    }


    public function columns(): array
    {
        $columns = [
            Column::make(__('ID'), 'id')->sortable(),
            Column::make(__('Amount'),'amount')->format(function ($value, $column, $row) {
                return view('components.table.price', $data = [
                    "model" => $row,
                    "value" => $value,
                ]);
            }),

        ];

        $columns[] =  Column::make(__('Vendor'), 'earning.vendor.name')->searchable();
        $columns[] = Column::make(__('Paid By'), 'user.name');
        $columns[] = Column::make(__('Date'), 'formatted_date');
        $columns[] = Column::make(__('Status'), 'status')->format(function ($value, $column, $row) {
            return view('components.table.status', $data = [
                "value" => $value,
            ]);
        });

        //actions
        $columns[] = Column::make(__('Actions'))->format(function ($value, $column, $row) {
            return view('components.buttons.payout_actions', $data = [
                "model" => $row,
            ]);
        });
        return $columns;
    }

    public function initiateActivate($id)
    {
        $this->emit('initiateEdit', $id);
    }

    public function initiateDeactivate($id)
    {
        $this->selectedModel = $this->model::find($id);

        $this->confirm(__("Reject"), [
            'icon' => 'question',
            'toast' => false,
            'text' =>  __('Are you sure you want to Reject the selected data?'),
            'position' => 'center',
            'showConfirmButton' => true,
            'cancelButtonText' => __("Reject"),
            'onConfirmed' => 'deactivateModel',
            'onCancelled' => 'cancelled'
        ]);
    }


    public function deactivateModel()
    {

        try {
            $this->selectedModel->status = "failed";
            $this->selectedModel->save();
            $this->showSuccessAlert(__("Rejected"));
        } catch (Exception $error) {
            $this->showErrorAlert("Failed");
        }
    }
}
