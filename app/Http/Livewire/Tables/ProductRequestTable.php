<?php

namespace App\Http\Livewire\Tables;

use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ProductRequestTable extends OrderingBaseDataTableComponent
{

    public $model = Product::class;
    public array $bulkActions = [];

    public function mount()
    {
        $this->bulkActions = [
            'massApprovalSelected' => __('Mass Approval'),
            'massDeletionSelected' => __('Mass Deletion'),
        ];
    }


    public function query()
    {

        $user = User::find(Auth::id());
        if ($user->hasRole('admin')) {
            $mQuery = Product::with('vendor');
        } elseif ($user->hasRole('city-admin')) {
            $mQuery = Product::with('vendor')->whereHas("vendor", function ($query) {
                return $query->where('creator_id', Auth::id());
            });
        } else {
            $mQuery = Product::where("vendor_id", Auth::user()->vendor_id);
        }

        return $mQuery->where('approved', 0)->withCount('options');
    }


    public function columns(): array
    {
        return [
            Column::make(__('ID'), 'id')->searchable()->sortable(),
            $this->xsImageColumn(),
            Column::make(__('Vendor'), 'vendor.name')->sortable(function ($query, $direction) {
                //order by vendor name using join
                return $query->join('vendors', 'vendors.id', '=', 'products.vendor_id')
                    ->orderBy('vendors.name', $direction);
            }),
            Column::make(__('Name'), 'name')->addClass('w-3/12 line-clamp-1 text-ellipsis truncate')->searchable()->sortable(),
            Column::make(__('Price'), 'price')->format(function ($value, $column, $row) {
                if ($row->discount_price) {
                    $text = "<span class='font-medium'>" . currencyFormat($row->discount_price ??  '') . "</span>";
                    $text .= " <span class='text-xs line-through'>" . currencyFormat($row->price) . "</span>";
                } else {
                    $text = currencyFormat($value ??  '');
                }
                return view('components.table.plain', $data = [
                    "text" => $text
                ]);
            })->searchable()->sortable(),
            Column::make(__('Available Qty'), "available_qty")->sortable(),

            Column::make(__('Actions'))->addClass('flex items-center')->format(function ($value, $column, $row) {
                return view('components.buttons.product_request_actions', $data = [
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


    public function initiateActivate($id)
    {
        $this->selectedModel = $this->model::find($id);

        $this->confirm(__('Approve'), [
            'icon' => 'question',
            'toast' => false,
            'text' =>  __('Are you sure you want to approve the selected data?'),
            'position' => 'center',
            'showConfirmButton' => true,
            'cancelButtonText' => __("Cancel"),
            'confirmButtonText' => __("Yes"),
            'onConfirmed' => 'activateModel',
            'onCancelled' => 'cancelled'
        ]);
    }


    public function activateModel()
    {

        try {
            if ($this->checkDemo) {
                $this->isDemo();
            }
            $this->selectedModel->approved = true;
            $this->selectedModel->save();
            $this->showSuccessAlert(__("Approved"));
        } catch (Exception $error) {
            $this->showErrorAlert("Failed");
        }
    }


    //Bulk Actions
    public function massApprovalSelected()
    {
        if ($this->selectedRowsQuery->count() > 0) {
            try {
                $totalItems = $this->selectedRowsQuery->count();
                DB::beginTransaction();
                //loop through the selected rows
                foreach ($this->selectedRowsQuery->get() as $model) {
                    $model->approved = true;
                    $model->save();
                }
                DB::commit();
                $this->showSuccessAlert($totalItems . " " . __("Products") . " " . __("Approved"));
                $this->resetBulk();
            } catch (Exception $error) {
                DB::rollback();
                $this->showErrorAlert($error->getMessage());
            }
        } else {
            $this->showErrorAlert(__("No data selected"));
        }
    }

    public function massDeletionSelected()
    {
        if ($this->selectedRowsQuery->count() > 0) {
            try {
                $totalItems = $this->selectedRowsQuery->count();
                DB::beginTransaction();
                //loop through the selected rows
                foreach ($this->selectedRowsQuery->get() as $model) {
                    $model->delete();
                }
                DB::commit();
                $this->showSuccessAlert($totalItems . " " . __("Products") . " " . __("Deleted"));
                $this->resetBulk();
            } catch (Exception $error) {
                DB::rollback();
                $this->showErrorAlert($error->getMessage());
            }
        } else {
            $this->showErrorAlert(__("No data selected"));
        }
    }
}
