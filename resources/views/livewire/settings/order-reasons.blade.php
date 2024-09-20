<div>

    <x-baseview title="{{ __('Order cancellation reasons') }}" :showNew="true">
        <livewire:tables.cancellation-reason-table />
    </x-baseview>


    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Save') }}" action="save">
            <p class="text-xl font-semibold">{{ __('Create Order cancellation reason') }}</p>
            <x-select name="type" title="{{ __('Type') }}" placeholder="{{ __('Type') }}" :options="$types" />
            <x-textarea name="reason" title="{{ __('Reason') }}" placeholder="{{ __('Reason') }}" />
        </x-modal>
    </div>


    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Save') }}" action="update">
            <p class="text-xl font-semibold">{{ __('Edit Order cancellation reason') }}</p>
            <x-select name="type" title="{{ __('Type') }}" placeholder="{{ __('Type') }}" :options="$types" />
            <x-textarea name="reason" title="{{ __('Reason') }}" placeholder="{{ __('Reason') }}" />
        </x-modal>
    </div>


</div>
