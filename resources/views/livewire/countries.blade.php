@section('title', __('Countries'))
<div>

    <x-baseview title="{{ __('Countries') }}" showNew="true">
        <livewire:tables.country-table />
    </x-baseview>

    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Save') }}" action="save">
            <p class="text-xl font-semibold">{{ __('Create Country') }}</p>
            <x-input title="{{ __('Name') }}" name="name" placeholder="" />
        </x-modal>
    </div>

    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Update') }}" action="update">

            <p class="text-xl font-semibold">{{ __('Edit Country') }}</p>
            <x-input title="{{ __('Name') }}" name="name" placeholder="" />


        </x-modal>
    </div>


</div>
