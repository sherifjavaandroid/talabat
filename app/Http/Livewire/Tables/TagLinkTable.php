<?php

namespace App\Http\Livewire\Tables;

use App\Models\Tag;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TagLinkTable extends BaseDataTableComponent
{


    public $header_view = 'components.buttons.new';
    public $model;

    public function mount($model){
        $this->model = $model;
        $this->selected = $this->model->products()->pluck("id")->toArray();
    }

    public function bulkActions(): array
    {
        $tagActions = [
            "setTag" => __("Save")
        ];
        return $tagActions;
    }




    public function query()
    {
        $user = User::find(Auth::id());
        if ($user->hasRole('admin')) {
            return Product::query();
        } elseif ($user->hasRole('city-admin')) {
            return Product::with('vendor')->whereHas("vendor", function ($query) {
                return $query->where('creator_id', Auth::id());
            });
        } else {
            return Product::where("vendor_id", Auth::user()->vendor_id);
        }
    }

    public function columns(): array
    {
        $columns = [
            Column::make(__('ID'), "id")->searchable()->sortable(),
            Column::make(__('Name'), 'name')->searchable()->sortable(),
            Column::make($this->model->name)->format(function ($value, $column, $row) {
                return view('components.table.bool', $data = [
                    "isTrue" => $row->hasTag($this->model->id)
                ]);
            })
        ];
        return $columns;
    }


    public function setTag()
    {
        try {
            $tag = Tag::find($this->model->id);
            $tag->products()->sync($this->selectedKeys());
            $this->showSuccessAlert(__("successful"));
        } catch (\Exception $ex) {
            logger("keys", [$ex]);
            $this->showErrorAlert(__("Failed"));
        }
    }
}
