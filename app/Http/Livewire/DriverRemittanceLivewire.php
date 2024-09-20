<?php

namespace App\Http\Livewire;

use App\Models\Remittance;
use Exception;
use Illuminate\Support\Facades\DB;

class DriverRemittanceLivewire extends BaseLivewireComponent
{

    //
    protected $listeners = [
        "initiateRemittanceCollection" => "initiateRemittanceCollection",
        'dismissModal' => 'dismissModal',
        'showDetailsModal' => 'showDetailsModal',
    ];

    public $model = Remittance::class;
    public $status;

    public function render()
    {
        return view('livewire.remittance');
    }


    public function showDetailsModal($id)
    {
        $this->selectedModel = $this->model::find($id)->order;
        $this->showDetails = true;
    }

    public function initiateRemittanceCollection($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->showCreateModal();
    }


    public function collect()
    {

        try {

            DB::beginTransaction();
            //update status
            $this->selectedModel->status = $this->status ?? "collected";
            $this->selectedModel->Save();

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Remittance") . " " . __('update successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            logger($error);
            $this->showErrorAlert($error->getMessage() ?? __("Remittance") . " " . __('updated failed!'));
        }
    }

}
