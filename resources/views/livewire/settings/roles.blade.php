@section('title', __('Roles'))
<div>

    <x-baseview title="{{ __('Roles') }}" :showNew="true">
        <livewire:tables.role-table />
    </x-baseview>

    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal-lg confirmText="{{ __('Update') }}" action="update">
            <p class="text-xl font-semibold">{{ __('Edit') }} {{ $selectedModel->name ?? '' }} {{ __('Permissions') }}</p>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4">
                @foreach ($permissions as $permission)
                <x-checkbox title="{{ $permission->name }}" name="selectedPermissions.{{ $permission->id }}" value="{{ $permission->name }}" />
                @endforeach
            </div>
        </x-modal-lg>
    </div>

    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{__('Save')}}" action="save">
            <p class="text-xl font-semibold">{{__('New Role')}}</p>
            <x-input title="{{__('Name')}}" name="name" />
        </x-modal>
    </div>


</div>
