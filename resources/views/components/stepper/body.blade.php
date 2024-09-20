<div x-show="currentStep == {{ $step ?? 1 }}">
    {{ $slot ?? '' }}

    <div class="flex justify-end gap-2 mt-4">
        @if ($showPrev ?? true)
            <x-buttons.plain :full="false" title="{{ $prevText ?? __('Previous') }}"
                wireClick="{{ $prevClick ?? 'prevStep' }}" bgColor="bg-gray-400">
                <x-heroicon-o-arrow-sm-left class="w-5 h-5" />
                <span class="mx-1">{{ $prevText ?? __('Previous') }}</span>
            </x-buttons.plain>
        @endif

        @if ($showNext ?? true)
            <x-buttons.plain :full="false" title="{{ $nextText ?? __('Next') }}" wireClick="{{ $nextClick ?? '' }}"
                bgColor="bg-primary-600">
                <span class="mx-1">{{ $nextText ?? __('Next') }}</span>
                <x-heroicon-o-arrow-narrow-right class="w-5 h-5" />
            </x-buttons.plain>
        @endif
    </div>
</div>
