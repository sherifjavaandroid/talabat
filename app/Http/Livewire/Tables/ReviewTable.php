<?php

namespace App\Http\Livewire\Tables;

use App\Models\Review;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class ReviewTable extends BaseDataTableComponent
{

    public $model = Review::class;

    public function filters(): array
    {
        return [
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

    public function query()
    {
        return Review::with('user', 'driver', 'vendor')
            ->when($this->getFilter('start_date'), fn ($query, $sDate) => $query->whereDate('created_at', ">=", $sDate))
            ->when($this->getFilter('end_date'), fn ($query, $eDate) => $query->whereDate('created_at', "<=", $eDate))
            ->orderBy('created_at', 'DESC');
    }

    public function columns(): array
    {
        return [
            Column::make(__('ID'), "id")->searchable()->sortable(),
            Column::make(__('User'), 'user.name')->searchable()->sortable(),
            Column::make(__('Vendor'), 'vendor.name')->searchable()->sortable(),
            Column::make(__('Driver'), 'driver.name')->searchable()->sortable(),
            Column::make(__('Rating'))->sortable(),
            Column::make(__('Review'))->format(function ($value, $column, $row) {
                return view('components.table.custom', $data = [
                    "value" => "" . $row->review . " ",
                ]);
            }),
            Column::make(__('Created At'), 'formatted_date'),
            Column::make(__('Actions'))->format(function ($value, $column, $row) {
                return view('components.buttons.delete', $data = [
                    "model" => $row,
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
            //delete the rating record
            $rateableType = 'App\Models\Vendor';
            if (empty($this->selectedModel->vendor_id)) {
                $rateableType = 'App\Models\User';
            }
            //
            \DB::table('ratings')->where('rateable_type', $rateableType)
            ->where('rateable_id',  $this->selectedModel->vendor_id)
            ->where('user_id',  $this->selectedModel->user_id)
            ->where('rating',  $this->selectedModel->rating)
            ->delete();
            $this->selectedModel->delete();
            \DB::commit();
            $this->showSuccessAlert("Deleted");
        } catch (Exception $error) {
            \DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? "Failed");
        }
    }
}
