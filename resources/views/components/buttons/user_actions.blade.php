<div class="flex items-center gap-x-2">
    {{-- details --}}
    <x-buttons.plain title="{{ __('Details') }}" bgColor="bg-primary-500"
        onClick="window.open('{{ route('users.details', ['id' => $model->id]) }}')">
        <x-heroicon-o-document-text class="w-5 h-5" />
    </x-buttons.plain>

    @if ($model->id != \Auth::id())
        @can('assign-permissions')
            @php
                $link = route('users.assign-permissions', ['id' => $model->id]);
            @endphp
            <x-buttons.plain title="{{ __('Assign permission') }}" onClick="window.open('{{ $link }}', '_blank')">
                <x-heroicon-o-lock-closed class="w-5 h-5" />
            </x-buttons.plain>
        @endcan
        <x-buttons.show :model="$model" />
        @hasanyrole('admin')
            @if ($model->hasAnyRole('city-admin'))
                <x-buttons.assign :model="$model" />
            @endif
        @endhasanyrole
        <x-buttons.edit :model="$model" />
        @if ($model->is_active)
            <x-buttons.deactivate :model="$model" />
        @else
            <x-buttons.activate :model="$model" />
        @endif

        <x-buttons.delete :model="$model" />
    @else
        <span class="text-xs italic font-thin text-gray-400">{{ __('Current Account') }}</span>
    @endif

</div>
