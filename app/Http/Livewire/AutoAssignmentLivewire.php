<?php

namespace App\Http\Livewire;

use App\Models\AutoAssignment;

class AutoAssignmentLivewire extends BaseLivewireComponent
{


    public function getListeners()
    {
        return $this->listeners + [
            'clearAutoAssignment' => 'clearAutoAssignment',
        ];
    }


    public function render()
    {
        return view('livewire.auto-assignment');
    }


    public function initiateClearAutoAssignment()
    {
        $this->confirm(__('Clear'), [
            'icon' => 'question',
            'toast' => false,
            'text' =>  __('Are you sure you want to clear/delete all auto-assigments?'),
            'position' => 'center',
            'showConfirmButton' => true,
            'cancelButtonText' => __("Cancel"),
            'confirmButtonText' => __("Yes"),
            'onConfirmed' => 'clearAutoAssignment',
            'onCancelled' => 'cancelled'
        ]);
    }

    public function clearAutoAssignment()
    {
        try {
            AutoAssignment::query()->truncate();
            $this->showSuccessAlert(__("Autoassignment clearing successfully!"));
            $this->emit('refreshTable');
        } catch (\Exception $ex) {
            logger("Clear Autoassignment failed", [$ex]);
            $this->showErrorAlert($ex->getMessage() ?? __("Autoassignment clearing failed!"));
        }
    }
}
