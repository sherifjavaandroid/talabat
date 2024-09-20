<div x-data="{ showMore: false }">
    <div class="flex flex-wrap items-center space-x-2 gap-y-2">
        <x-buttons.show :model="$model" />
        {{-- timing --}}
        <button
            class="flex items-center p-2 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-600 hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray"
            wire:click="$emit('changeProductTiming', {{ $model->id }} ) " title="{{ __('Edit Open/close time') }}">
            <x-heroicon-o-clock class="w-5 h-5" />
            {{ $slot ?? '' }}
        </button>

        <x-buttons.edit :model="$model" />

        {{-- <button type="button"
            class="flex items-center p-2 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-600 hover:bg-gray-600 focus:outline-none focus:shadow-outline-gray"
            x-on:click="showMore = !showMore" title="{{ __('More') }}">
            <x-heroicon-o-dots-vertical class="w-5 h-5 text-white" />
        </button> --}}
        {{-- </div> --}}
        {{-- <div class="mt-2 flex flex-wrap items-center space-x-2 gap-y-2" x-show="showMore"> --}}

        @if ($model->is_active)
            <x-buttons.deactivate :model="$model" />
        @else
            <x-buttons.activate :model="$model" />
        @endif
        <x-buttons.delete :model="$model" />
        @if ($model->available_qty != null && $model->available_qty > 0)
            <x-buttons.plain bgColor="bg-red-500" wireClick="$emit('setOutOfStock', {{ $model->id }} )"
                title="{{ __('Set product to out of stock') }}">
                <x-lineawesome-ban-solid class="w-5 h-5" />
            </x-buttons.plain>
        @endif

        {{-- report --}}
        <a href="{{ route('product.details', ['id' => $model->id]) }}" target="_blank">
            <x-buttons.plain bgColor="bg-primary-500" title="{{ __('View product report') }}">
                <x-lineawesome-chart-pie-solid class="w-5 h-5" />
            </x-buttons.plain>
        </a>


    </div>
</div>
