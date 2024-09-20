<?php

namespace App\Http\Livewire\Tables;

use App\Models\DeliveryAddress;
use App\Models\Order;
use App\Models\ProductReview;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;
use Spatie\Permission\Models\Role;

class DeletedUserTable extends BaseDataTableComponent
{

    public $model = User::class;
    public $role;


    public function filters(): array
    {
        $roles = Role::all()->pluck('name')->toArray();
        $roleFilters = [
            "" => __('All')
        ];
        foreach ($roles as $role) {
            $roleFilters[$role] = $role;
        }
        return [
            'role' => Filter::make(__("Role"))
                ->select($roleFilters),
        ];
    }


    public function query()
    {
        $user = User::find(Auth::id());
        if ($user->hasRole('admin')) {
            return User::onlyTrashed()->with('roles', 'creator')->whereHas('roles', function ($query) {
                $query->when($this->getFilter('role'), fn ($query, $role) => $query->where('name', $role));
            });
        } else {

            return User::onlyTrashed()->with('roles', 'creator')->whereHas('roles', function ($query) {
                $query->when($this->getFilter('role'), fn ($query, $role) => $query->where('name', $role));
            })->where('creator_id', Auth::id());
        }
    }


    public function columns(): array
    {
        $columns = [
            Column::make(__('ID'), "id")->searchable()->sortable(),
            Column::make(__('Name'), 'name')
                ->format(function ($value, $column, $row) {
                    return view('components.table.user', $data = [
                        "value" => $value,
                        "model" => $row,
                    ]);
                })->searchable()->sortable(),
        ];



        $mColumns = [
            $this->customColumn(__('Wallet'), 'components.table.wallet'),
            Column::make(__('Role'), 'role_name'),
            Column::make(__('Created At'), 'formatted_date'),
            $this->actionsColumn('components.buttons.delete'),
        ];

        $columns = array_merge($columns, $mColumns);

        return $columns;
    }



    //
    public function deleteModel()
    {

        try {

            $this->isDemo();
            \DB::beginTransaction();
            //
            $walletIds = Wallet::where('user_id', $this->selectedModel->id)->get()->pluck('id');
            DeliveryAddress::whereIn('user_id', [$this->selectedModel->id])->delete();
            Order::whereIn('user_id', [$this->selectedModel->id])->delete();
            WalletTransaction::whereIn('wallet_id', $walletIds)->delete();
            Wallet::whereIn('id', $walletIds)->delete();
            ProductReview::whereIn('user_id', [$this->selectedModel->id])->delete();
            $this->selectedModel = $this->selectedModel->fresh();
            $this->selectedModel->forceDelete();
            \DB::commit();
            $this->showSuccessAlert("Deleted");
        } catch (Exception $error) {
            \DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? "Failed");
        }
    }
}
