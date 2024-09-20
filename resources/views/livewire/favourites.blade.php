@section('title',  __('Favourites') )
    <div>

        <x-baseview title="{{ __('Favourites') }}">
            <livewire:tables.favourite-table />
        </x-baseview>

    </div>
