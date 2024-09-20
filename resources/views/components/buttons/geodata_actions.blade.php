<div class="flex items-center gap-x-2">
    <x-buttons.edit :model="$model" target="$target ?? ''" />
    <x-buttons.delete :model="$model" target="$target ?? ''" />
    {{-- add sync boudaries button --}}
    @production
        {{-- @empty($model->boundaries) --}}
        <x-buttons.plain wireClick="syncModelBoundaries({{ $model->id ?? ($id ?? '') }})" title="{{ __('Sync Boudaries') }}">
            <x-heroicon-o-refresh class="w-5 h-5" />
            <p class="text-sm">{{ __('Sync Boudaries') }}</p>
        </x-buttons.plain>
        {{-- @endempty --}}
    @endproduction
</div>
