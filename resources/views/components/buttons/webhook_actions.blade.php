<div class="flex items-center gap-x-2">


    <x-buttons.plain wireClick="$emit('generateHash', {{ $model->id }} )" title="">
        <x-heroicon-o-refresh class="w-5 h-5 mr-2" />
        <span class="text-xs">{{__('Re-Generate')}}</span>
    </x-buttons.plain>

</div>
