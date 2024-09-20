@section('title', __('Fees') )
<div>

    <x-baseview title="{{ __('Fees') }}" :showNew="true">
        <livewire:tables.fee-table />
    </x-baseview>

    {{-- new form --}}
    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Save') }}" action="save" :clickAway="false">
            <p class="text-xl font-semibold">{{ __('Create Fee') }}</p>
            <x-input title="{{ __('Name') }}" name="name" />
            <x-input title="{{ __('Value') }}" name="value" />
            <div class="grid gap-4 md:grid-cols-2">
                <x-checkbox title="{{ __('For Admin') }}" name="for_admin" :defer="false" />
                <x-checkbox title="{{ __('Is Percentage?') }}" name="percentage" :defer="false" />
                <x-checkbox title="{{ __('Active') }}" name="is_active" :defer="false" />
            </div>
        </x-modal>
    </div>

    {{-- update form --}}
    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Update') }}" action="update" :clickAway="false">
            <p class="text-xl font-semibold">{{ __('Update Fee') }}</p>
            <x-input title="{{ __('Name') }}" name="name" />
            <x-input title="{{ __('Value') }}" name="value" />
            <div class="grid gap-4 md:grid-cols-2">
                <x-checkbox title="{{ __('For Admin') }}" name="for_admin" :defer="false" />
                <x-checkbox title="{{ __('Is Percentage?') }}" name="percentage" :defer="false" />
                <x-checkbox title="{{ __('Active') }}" name="is_active" :defer="false" />
            </div>
        </x-modal>
    </div>


</div>
