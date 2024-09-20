<?php

namespace App\Traits;

use Rappasoft\LaravelLivewireTables\Views\Column;

trait DataTableTrait
{

    public function activeColumn($column = 'is_active')
    {

        return Column::make(__('Active'), $column)->format(function ($value, $column, $row) {
            return view('components.table.active', $data = [
                "model" => $row,
                'active' => $value
            ]);
        });
    }

    public function xsImageColumn()
    {

        return Column::make(__('Image'))->format(function ($value, $column, $row) {
            return view('components.table.image_xs', $data = [
                "model" => $row
            ]);
        });
    }

    public function smImageColumn($position = "object-center")
    {

        return Column::make(__('Image'))->format(function ($value, $column, $row) use ($position) {
            return view('components.table.image_sm', $data = [
                "model" => $row,
                "position" => $position
            ]);
        });
    }

    public function imageColumn()
    {

        return Column::make(__('Image'))->format(function ($value, $column, $row) {
            return view('components.table.image_md', $data = [
                "model" => $row
            ]);
        });
    }

    public function logoColumn()
    {

        return Column::make(__('Logo'))->format(function ($value, $column, $row) {
            return view('components.table.logo', $data = [
                "model" => $row
            ]);
        });
    }

    public function actionsColumn($actionView = null)
    {

        return Column::make(__('Actions'))->format(function ($value, $column, $row) use ($actionView) {
            return view($actionView ?? 'components.buttons.actions', $data = [
                "model" => $row
            ]);
        });
    }

    public function customActionsColumn(
        $showView = true,
        $showEdit = true,
        $showDelete = true,
        $showToggleActive = true
    ) {

        return Column::make(__('Actions'))->format(function ($value, $column, $row) use (
            $showView,
            $showEdit,
            $showDelete,
            $showToggleActive
        ) {
            return view("components.buttons.custom_actions", $data = [
                "model" => $row,
                "view" => $showView,
                "edit" => $showEdit,
                "delete" => $showDelete,
                "toggleActive" => $showToggleActive,
            ]);
        });
    }

    public function customColumn($title = '', $actionView)
    {

        return Column::make($title)->format(function ($value, $column, $row) use ($actionView) {
            return view($actionView, $data = [
                "model" => $row
            ]);
        });
    }


    public function priceColumn()
    {

        return Column::make(__('Price'), 'price')->format(function ($value, $column, $row) {
            return view('components.table.price', $data = [
                "model" => $row
            ]);
        });
    }

    public function colorColumn($column = 'color')
    {
        return Column::make(__('Color'), $column)->format(function ($value, $column, $row) {
            $text = "<div class='h-8 rounded-sm flex items-center justify-center' style='background-color: $value'>$value</div>";
            return view('components.table.plain', $data = [
                "text" => $text
            ]);
        });
    }


    public function indexColumn($label = "S\N")
    {
        $this->index = $this->page > 1 ? ($this->page - 1) * $this->perPage : 0;
        return Column::make($label)->format(fn () => ++$this->index)->addClass('w-16');
    }
}
