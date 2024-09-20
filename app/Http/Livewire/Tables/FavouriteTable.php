<?php

namespace App\Http\Livewire\Tables;

use App\Models\Favourite;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Kdion4891\LaravelLivewireTables\Column;

class FavouriteTable extends BaseTableComponent
{

    public $model = Favourite::class;

    public function query()
    {
        return Favourite::with('user','product');
    }

    public function columns()
    {
        return [
            Column::make(__('ID'),"id")->searchable()->sortable(),
            Column::make(__('Product'),'product.name')->searchable()->sortable(),
            Column::make(__('User'), 'user.name')->searchable()->sortable(),
        ];
    }


}
