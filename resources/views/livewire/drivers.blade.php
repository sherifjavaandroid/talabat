@section('title',  __('Users') )
<div>

    <x-baseview title="{{ __('Delivery Boys') }}" :showNew="true">
        <livewire:tables.driver-table />
    </x-baseview>

    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Save') }}" action="save">
            <p class="text-xl font-semibold">{{ __('Create User Account') }}</p>
            <x-input title="{{ __('Name') }}" name="name" placeholder="John" />
            <x-input title="{{ __('Email') }}" name="email" placeholder="info@mail.com" />
            <x-input title="{{ __('Phone') }}" name="phone" placeholder="" />
            <x-input title="{{ __('Login Password') }}" name="password" type="password" placeholder="**********************" />
            <x-input title="{{ __('Commission') }}" name="commission" placeholder="" />
        </x-modal>
    </div>

    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Update') }}" action="update">

            <p class="text-xl font-semibold">{{ __('Edit User Account') }}</p>
            <x-input title="{{ __('Name') }}" name="name" placeholder="John" />
            <x-input title="{{ __('Email') }}" name="email" placeholder="info@mail.com" />
            <x-input title="{{ __('Phone') }}" name="phone" placeholder="" />
            <x-input title="{{ __('Login Password') }}" name="password" type="password" placeholder="**********************" />
            <x-input title="{{ __('Commission') }}" name="commission" placeholder="" />

        </x-modal>
    </div>
</div>


