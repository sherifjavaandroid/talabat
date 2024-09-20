<div class="flex items-center gap-x-2">


    @hasanyrole('admin|city-admin')
        <x-buttons.assign :model="$model" />
        <x-buttons.loginas :model="$model" />
    @endhasanyrole
    <x-buttons.time :model="$model" />
    <x-buttons.show :model="$model" />
    <x-buttons.edit :model="$model" />

    @hasanyrole('city-admin|admin')
        @if ($model->is_active)
            <x-buttons.deactivate :model="$model" />
        @else
            <x-buttons.activate :model="$model" />
        @endif

        <x-buttons.delete :model="$model" />
    @endhasanyrole

    @role('manager')
        @if ($model->is_active)
            <x-buttons.deactivate :model="$model" />
        @endif
    @endrole


    @php
        $bgColor = 'bg-red-500';
        $title = __('Go Offline');
        $action = 'goOffline';
        if (!$model->is_open) {
            $bgColor = 'bg-green-500';
            $title = __('Go Online');
            $action = 'goOnline';
        }
    @endphp

    <x-buttons.plain :bgColor="$bgColor" :title="$title" wireClick="{{ $action }}('{{ $model->id }}')">
        @if ($model->is_open)
            <x-heroicon-o-status-offline class="ml-1 w-5 h-5" />
        @else
            <x-heroicon-o-status-online class="ml-1 w-5 h-5" />
        @endif
    </x-buttons.plain>



</div>
