<div class="flex items-center gap-x-2">
    {{-- details --}}
    <x-buttons.plain title="{{ __('Details') }}" bgColor="bg-primary-500"
        onClick="window.open('{{ route('users.details', ['id' => $model->id]) }}')">
        <x-heroicon-o-document-text class="w-5 h-5" />
    </x-buttons.plain>

    {{-- TODO: View driver location on map --}}

    @if ($model->id != \Auth::id())

        <x-buttons.show :model="$model" />
        @hasanyrole('admin')
            @if ($model->hasAnyRole('city-admin'))
                <x-buttons.assign :model="$model" />
            @endif
        @endhasanyrole
        @can('edit-driver')
            <x-buttons.edit :model="$model" />

            @if ($model->is_active)
                <x-buttons.deactivate :model="$model" />
            @else
                <x-buttons.activate :model="$model" />
            @endif
        @endcan
        @can('delete-driver')
            <x-buttons.delete :model="$model" />
        @endcan
    @else
        <span class="text-xs italic font-thin text-gray-400">{{ __('Current Account') }}</span>
    @endif

</div>
