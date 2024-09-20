@section('title',  __('Tags') )
<div>

    <x-baseview title="{{ __('Tags') }}" :showNew="true">
        <livewire:tables.tag-table />
    </x-baseview>

    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Save') }}" action="save">
            <p class="text-xl font-semibold">{{ __('Create Tag') }}</p>
            <x-input title="{{ __('Name') }}" name="name" placeholder="" />
            <x-select title="{{ __('Type') }}" :options='$types' name="vendor_type_id" :defer="false" noPreSelect="true" />
        </x-modal>
    </div>

    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Update') }}" action="update">

            <p class="text-xl font-semibold">{{ __('Edit Tag') }}</p>
            <x-input title="{{ __('Name') }}" name="name" placeholder="" />
            <x-select title="{{ __('Type') }}" :options='$types' name="vendor_type_id" :defer="false" noPreSelect="true" />
        </x-modal>
    </div>
</div>


