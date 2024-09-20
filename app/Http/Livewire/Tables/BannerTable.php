<?php

namespace App\Http\Livewire\Tables;

use App\Models\Banner;
use Rappasoft\LaravelLivewireTables\Views\Column;

class BannerTable extends OrderingBaseDataTableComponent
{

    public $model = Banner::class;
    public $header_view = 'components.buttons.new';


    public function query()
    {
        return Banner::with('category', 'vendor');
    }

    public function columns(): array
    {
        $columns = [
            Column::make(__('ID'), "id")->searchable()->sortable(),
            Column::make(__('Type'))->format(function ($value, $column, $row) {
                $text = $row->category_id ? "category" : ($row->vendor_id ? "vendor" : ($row->product_id ? "product" : "link"));
                //first letter uppercase
                $text = ucfirst($text);
                return $text;
            }),

            Column::make(__('Section/Page Visible'))->format(function ($value, $column, $row) {
                $text = $row->vendor_type->name ?? __("Home Page");
                return $text;
            }),
            Column::make(__('Info'))->format(function ($value, $column, $row) {
                $text = $row->category->name ?? $row->vendor->name ?? $row->product->name ?? $row->link;
                return $text;
            })->addClass('w-4/12'),
            Column::make(__('Image'))->format(function ($value, $column, $row) {
                return view('components.table.image_md', $data = [
                    "model" => $row
                ]);
            }),
            Column::make(__('Active'), 'is_active')->format(function ($value, $column, $row) {
                return view('components.table.active', $data = [
                    "model" => $row
                ]);
            })->sortable(),
            Column::make(__('Created At'), 'formatted_date'),


        ];

        //
        if (app()->environment('production')) {
            $columns[] = Column::make(__('Actions'))->format(function ($value, $column, $row) {
                return view('components.buttons.actions', $data = [
                    "model" => $row
                ]);
            });
        }

        return $columns;
    }
}