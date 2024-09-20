@section('title', __('Onboarding'))
<div>

    <x-baseview title="{{ __('Onboarding') }}" :showNew="inProduction()">
        <livewire:tables.misc.onboarding-table />
    </x-baseview>

    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Save') }}" action="save">
            <p class="text-xl font-semibold">{{ __('Create Onboarding') }}</p>
            <x-media-upload title="{{ __('Photo') }}" name="photo" :photo="$photo" :photoInfo="$photoInfo"
                types="PNG or JPEG" rules="image/*" />

            <x-select title="{{ __('Type') }}" :options='$types ?? []' name="type" :defer="false" :noPreSelect="true" />
            {{-- title --}}
            <x-input title="{{ __('Title') }}" name="title" placeholder="" />
            <x-textarea title="{{ __('Description') }}" name="description" placeholder="" disable="true" />
            <x-checkbox title="{{ __('Active') }}" name="is_active" :defer="false" />

        </x-modal>
    </div>

    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Update') }}" action="update">

            <p class="text-xl font-semibold">{{ __('Edit Onboarding') }}</p>
            <x-media-upload title="{{ __('Photo') }}" name="photo" preview="{{ $selectedModel->photo ?? '' }}"
                :photo="$photo" :photoInfo="$photoInfo" types="PNG or JPEG" rules="image/*" />


            <x-select title="{{ __('Type') }}" :options='$types ?? []' name="type" :defer="false"
                :noPreSelect="true" />
            {{-- title --}}
            <x-input title="{{ __('Title') }}" name="title" placeholder="" />
            <x-textarea title="{{ __('Description') }}" name="description" placeholder="" disable="true" />
            <x-checkbox title="{{ __('Active') }}" name="is_active" :defer="false" />

        </x-modal>
    </div>
</div>
