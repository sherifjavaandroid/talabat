@section('title', __('Options Group'))
<div>

    <x-baseview title="{{ __('Options Group') }}" :showNew="true" :newInfo="true">
        <livewire:tables.options-group-table />
    </x-baseview>

    {{-- new form --}}
    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Save') }}" action="save">
            <p class="text-xl font-semibold">{{ __('Create Option Group') }}</p>

            <x-input title="{{ __('Name') }}" name="name" />
            <x-checkbox title="{{ __('Multiple') }}"
                description="{{ __('Allow customers to select multiple options under this option group') }}"
                name="multiple" :defer="false" />
            @if ($multiple)
                <x-input title="{{ __('Max Selectable Option') }}" name="max_options" />
            @endif
            <x-checkbox title="{{ __('Required') }}"
                description="{{ __('Customer is required to select at least one option under this option group') }}"
                name="required" :defer="false" />

            <x-checkbox title="{{ __('Active') }}" name="isActive" :defer="false" />

        </x-modal>
    </div>

    {{-- update form --}}
    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Update') }}" action="update">
            <p class="text-xl font-semibold">{{ __('Update Option Group') }}</p>

            <x-input title="{{ __('Name') }}" name="name" />
            <x-checkbox title="{{ __('Multiple') }}"
                description="{{ __('Allow customers to select multiple options under this option group') }}"
                name="multiple" :defer="false" />
            @if ($multiple)
                <x-input title="{{ __('Max Selectable Option') }}" name="max_options" />
            @endif
            <x-checkbox title="Required"
                description="{{ __('Customer is required to select at least one option under this option group') }}"
                name="required" :defer="false" />
            <x-checkbox title="{{ __('Active') }}" name="isActive" :defer="false" />


        </x-modal>
    </div>

</div>
