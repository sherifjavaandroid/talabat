<div class="flex items-center justify-center w-32 gap-x-2">
    
    <x-buttons.show :model="$model" />
    <x-buttons.primary wireClick="$emit('initiateRemittanceCollection', {{ $model->id }})" :noMargin="true">
        <x-heroicon-o-check class="w-5 h-5 mx-2"/> {{ __("Collect") }}
    </x-buttons.primary>

</div>
