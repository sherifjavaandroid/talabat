@section('title',  __('Subscriptions') )
<div>

    <x-baseview title="{{ __('Subscriptions') }}">
        <livewire:tables.subscription-table />
    </x-baseview>

    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Save') }}" action="save">

            <p class="text-xl font-semibold">{{ __('New Subscription') }}</p>
            <x-input title="{{ __('Name') }}" name="name" />
            <x-input title="{{ __('Days') }}" name="days" type="number" />
            <x-input title="{{ __('Qty') }}" name="qty" type="number" />
            <p class="mt-1 text-xs text-gray-400">{{ __("Note: Number of products/services/package types allowed per vendor") }}</p>
            <x-input title="{{ __('Amount') }}" name="amount" />
            <x-checkbox
                    title="{{ __('Active') }}"
                    name="isActive" :defer="false" />


        </x-modal>
    </div>

    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Update') }}" action="update">

            <p class="text-xl font-semibold">{{ __('Edit Subscription') }}</p>
            <x-input title="{{ __('Name') }}" name="name" />
            <x-input title="{{ __('Days') }}" name="days" type="number" />
            <x-input title="{{ __('Qty') }}" name="qty" type="number" />
            <p class="mt-1 text-xs text-gray-400">{{ __("Note: Number of products/services/package types allowed per vendor") }}</p>
            <x-input title="{{ __('Amount') }}" name="amount" />
            <x-checkbox
                    title="{{ __('Active') }}"
                    name="isActive" :defer="false" />


        </x-modal>
    </div>

</div>


