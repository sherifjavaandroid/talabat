<?php

namespace App\Http\Livewire\Tables;

use App\Models\PushNotification;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PushNotificationTable extends BaseDataTableComponent
{

    public $model = PushNotification::class;
    public function query()
    {
        return PushNotification::with('user');
    }

    public function columns(): array
    {

        $columns = [
            Column::make(__('ID'), 'id'),
            Column::make(__('Target'), 'role')->searchable()->sortable(),
            Column::make(__('Title'), 'title')->searchable()->sortable(),
            Column::make(__('Body'), 'body')->searchable()->sortable(),
            $this->smImageColumn(),
            Column::make(__('Sender'), 'user.name'),
            Column::make(__('Created At'), 'formatted_date'),
            $this->actionsColumn('components.buttons.delete'),
        ];


        return $columns;
    }
}
