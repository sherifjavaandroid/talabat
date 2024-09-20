<?php

namespace App\Http\Livewire\Tables;

use App\Models\ContentPage;
use Rappasoft\LaravelLivewireTables\Views\Column;

class CmsTable extends BaseDataTableComponent
{
    public $model = ContentPage::class;

    public function query()
    {
        return ContentPage::query();
    }

    public function columns(): array
    {
        return [
            Column::make("ID", "id")->sortable()->searchable(),
            Column::make(__("Slug"), "slug")->sortable()->searchable(),
            Column::make(__("Name"), "name")->sortable()->searchable(),
            Column::make(__('Link'))->format(function ($value, $column, $row) {
                $link = route("cms.page", ["slug" => $row->slug]);
                $link = "<a href='$link' target='_blank' class='hover:underline'>$link</a>";
                return view('components.table.plain', $data = [
                    "text" => $link
                ]);
            }),
            $this->activeColumn(),
            $this->actionsColumn(),
        ];
    }
}
