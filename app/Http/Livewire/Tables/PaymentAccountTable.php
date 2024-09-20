<?php

namespace App\Http\Livewire\Tables;

use App\Models\PaymentAccount;
use App\Models\Vendor;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PaymentAccountTable extends BaseDataTableComponent
{

    public $model = PaymentAccount::class;

    public function query()
    {
        $authUser = \Auth::user();
        //
        if ($authUser->hasRole('manager')) {
            return $this->model::whereHasMorph('accountable', [Vendor::class], function ($query) use ($authUser) {
                return $query->where("id", $authUser->vendor_id);
            });
        } else {
            return $this->model::query();
        }
    }
    public function columns(): array
    {
        $columns = [];
        $authUser = \Auth::user();


        $columns[] = Column::make(__('ID'), 'id')->sortable();
        if ($authUser->hasRole('admin')) {
            $columns[] = Column::make(__('Type'), 'type');
            $columns[] = Column::make(__('Name'), 'accountable.name')->searchable();
        }
        $columns[] = Column::make(__('Account Nme'), 'name')->searchable()->sortable();
        $columns[] =  Column::make(__('Account Number'), 'number')->searchable()->sortable();
        $columns[] = Column::make(__('Active'))->format(function ($value, $column, $row) {
            return view('components.table.active', $data = [
                "model" => $row
            ]);
        });

        //
        if ($authUser->hasRole('admin')) {
            $columns[] = Column::make(__('Actions'))->format(function ($value, $column, $row) {
                return view('components.buttons.show', $data = [
                    "model" => $row
                ]);
            });
        } else {
            $columns[] = Column::make(__('Actions'))->format(function ($value, $column, $row) {
                return view('components.buttons.simple_actions', $data = [
                    "model" => $row
                ]);
            });
        }


        //
        return $columns;
    }
}
