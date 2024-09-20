<?php

namespace App\Http\Livewire\Tables;

use App\Models\Coupon;
use App\Models\User;
use Rappasoft\LaravelLivewireTables\Views\Column;

class CouponTable extends BaseDataTableComponent
{

    public $model = Coupon::class;

    public function query()
    {
        $user = User::find(\Auth::id());
        if ($user->hasRole('admin')) {
            return Coupon::with('products', 'vendors');
        } else {
            return Coupon::with('products', 'vendors')->where('creator_id', $user->id);
        }
    }

    public function columns():array
    {
        return [
            Column::make(__('ID'),"id"),
            Column::make(__('Code'),'code')->searchable()->sortable(),
            Column::make(__('Discount'))->format(function ($value, $column, $row) {
                return view('components.table.coupon_discount_price', $data = [
                    "model" => $row
                ]);
            })->searchable()->sortable(),
            Column::make(__('Description'))->format(function ($value, $column, $row) {
                return view('components.table.short_description', $data = [
                    "model" => $row
                ]);
            }),
            Column::make(__('Expires On'),'expires_on')->sortable(),
            $this->activeColumn(),
            $this->actionsColumn('components.buttons.coupon_actions'),
        ];
    }
}
