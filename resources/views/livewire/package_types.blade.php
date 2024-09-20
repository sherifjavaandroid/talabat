@section('title',  __('Package Types') )
    <div>

        <x-baseview title="{{ __('Package Types') }}" :showNew="true">
            <livewire:tables.package-type-table />
        </x-baseview>

        <div x-data="{ open: @entangle('showCreate') }">
            <x-modal confirmText="{{ __('Save') }}" action="save">
                <p class="text-xl font-semibold">{{ __('Create Package Type') }}</p>
                <x-input title="{{ __('Name') }}" name="name" placeholder="" />
                <x-input title="{{ __('Description') }}" name="description" placeholder="" />
                <x-checkbox title="{{ __('Driver must verify all stops') }}" name="driver_verify_stops" :defer="true" />
                <x-media-upload title="{{ __('Photo') }}" name="photo" {{-- preview="{{ $selectedModel->photo ?? '' }}" --}} :photo="$photo" :photoInfo="$photoInfo"
                    types="PNG or JPEG" rules="image/*" />
                <x-checkbox title="{{ __('Active') }}" name="is_active" :defer="true" />

            </x-modal>
        </div>

        <div x-data="{ open: @entangle('showEdit') }">
            <x-modal confirmText="{{ __('Update') }}" action="update">

                <p class="text-xl font-semibold">{{ __('Edit Package Type') }}</p>
                <x-input title="{{ __('Name') }}" name="name" placeholder="" />
                <x-input title="{{ __('Description') }}" name="description" placeholder="" />
                <x-checkbox title="{{ __('Driver must verify all stops') }}" name="driver_verify_stops" :defer="true" />
                <x-media-upload title="{{ __('Photo') }}" name="photo" preview="{{ $selectedModel->photo ?? '' }}" :photo="$photo" :photoInfo="$photoInfo"
                    types="PNG or JPEG" rules="image/*" />
                <x-checkbox title="{{ __('Active') }}" name="is_active" :defer="true" />


            </x-modal>
        </div>
    </div>
