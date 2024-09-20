<?php

namespace App\Http\Livewire\Tables;

use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ServiceTable extends OrderingBaseDataTableComponent
{

    public $model = Service::class;
    public bool $columnSelect = false;

    public function query()
    {

        $user = User::find(Auth::id());
        if ($user->hasRole('admin')) {
            return $this->model::query();
        } elseif ($user->hasRole('city-admin')) {
            return $this->model::with('vendor')->whereHas("vendor", function ($query) {
                return $query->where('creator_id', Auth::id());
            });
        } else {
            return $this->model::where("vendor_id", Auth::user()->vendor_id);
        }
    }

    public function columns(): array
    {
        return [
            Column::make(__('ID'), 'id')->searchable()->sortable(),
            Column::make(__('Name'), 'name')->addClass('break-all p-2 w-64 md:w-5/12')->searchable()->sortable(),
            Column::make(__('Duration Type'), 'duration')->addClass('')->searchable()->sortable(),
            Column::make(__('Price'), 'price')->format(function ($value, $column, $row) {
                return view('components.table.price', $data = [
                    "model" => $row,
                ]);
            })->searchable()->sortable(),
            Column::make(__('Discount Price'), 'discount_price')->format(function ($value, $column, $row) {
                return view('components.table.discount_price', $data = [
                    "model" => $row
                ]);
            })->searchable()->sortable(),
            Column::make(__('Active'))->format(function ($value, $column, $row) {
                return view('components.table.active', $data = [
                    "model" => $row
                ]);
            }),
            Column::make(__('Actions'))->format(function ($value, $column, $row) {
                return view('components.buttons.crud_actions', $data = [
                    "model" => $row
                ]);
            }),
        ];
    }

    //
    public function deleteModel()
    {

        try {
            $this->isDemo();
            \DB::beginTransaction();
            $this->selectedModel->delete();
            \DB::commit();
            $this->showSuccessAlert("Deleted");
        } catch (Exception $error) {
            \DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? "Failed");
        }
    }
}
