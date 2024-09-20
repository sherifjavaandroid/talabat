<?php

namespace App\Http\Livewire\Tables;

use App\Models\User;
use App\Traits\DBTrait;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Exception;
use Illuminate\Support\Facades\DB;

class SystemDriverTable extends BaseDataTableComponent
{
    use DBTrait;
    public $model = User::class;
    public $role;
    public string $defaultSortColumn = 'is_active';
    public string $defaultSortDirection = 'desc';




    public function query()
    {
        //fetach users with driver role
        return User::role('driver')->withCount('currently_assigned_orders')->with('driver_type');
    }


    public function columns(): array
    {
        return [
            Column::make(__('ID'), "id")->searchable()->sortable(),
            Column::make(__('Name'), 'name')->format(function ($value, $column, $row) {

                $text =  "<p class='font-semibold'>" . $value . "</p>";
                if (!$this->inDemo()) {
                    $text .=  "<p class='text-xs hover:underline'><a href='tel:" . $row->phone . "' target='_blank'>" . $row->phone . "</a></p>";
                }


                if (!$this->inDemo()) {
                    $columns[] = Column::make(__('Phone'), 'phone')->searchable()->sortable();
                }

                return view('components.table.plain', [
                    "text" => $text,
                ]);
            })->searchable()->sortable(),

            $this->customColumn(__('Wallet'), 'components.table.wallet'),
            Column::make(__('Commission') . "(%)", 'commission'),
            // if is online or not
            Column::make(__('Status'), 'is_online')->format(function ($value, $column, $row) {

                if ($value) {
                    $text =  __("Online");
                    $textColor = "text-white";
                    $bgColor = "border-0 shadow-sm bg-green-500";
                } else {
                    $text =  __("Offline");
                    $textColor = "text-white";
                    $bgColor = "border-0 shadow-sm bg-red-500";
                }

                return view('components.table.plain_chip', [
                    "text" => $text,
                    "textColor" => $textColor,
                    "bgColor" => $bgColor,
                ]);
            })->sortable(),
            //if is free or not
            Column::make(__('Free'), 'assigned_orders')->format(function ($value, $column, $row) {

                if ($row->assigned_orders > 0) {
                    $text =  __("Assigned Order");
                    $textColor = "text-white";
                    $bgColor = "border-0 shadow-sm bg-green-500";
                } else {
                    $text =  __("Free");
                    $textColor = "text-white";
                    $bgColor = "border-0 shadow-sm bg-red-500";
                }

                return view('components.table.plain_chip', [
                    "text" => $text,
                    "textColor" => $textColor,
                    "bgColor" => $bgColor,
                ]);
            })->sortable(function ($query, $direction) {
                return $query->orderBy('currently_assigned_orders_count', $direction);
            }),

            //view on map
            $this->actionsColumn('components.buttons.driver_actions'),
        ];
    }

    //
    public function deleteModel()
    {

        try {

            $this->isDemo();
            DB::beginTransaction();
            //
            $driverId = $this->selectedModel->id;
            //remove driver_id from all tables
            $tablesWithDriverId = $this->getTablesWithColumn('driver_id');
            $this->removeRecordsFromDB($tablesWithDriverId, 'driver_id', $driverId);
            //remove user_id from all tables, matching the driver_id
            $tablesWithUserId = $this->getTablesWithColumn('user_id');
            $this->removeRecordsFromDB($tablesWithUserId, 'user_id', $driverId);
            $this->selectedModel = $this->selectedModel->fresh();
            $this->selectedModel->forceDelete();
            DB::commit();
            $this->showSuccessAlert("Deleted");
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? "Failed");
        }
    }
}
