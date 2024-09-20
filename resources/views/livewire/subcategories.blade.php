@section('title', __('SubCategories'))
<div>

    <x-baseview title="{{ __('SubCategories') }}" :showNew="true">
        <livewire:tables.sub-category-table />
    </x-baseview>

    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Save') }}" action="save">
            <p class="text-xl font-semibold">{{ __('Create SubCategory') }}</p>
            <x-input title="{{ __('Name') }}" name="name" placeholder="Vegetables" />
            <x-select title="{{ __('Category') }}" :options="$categories ?? []" name="category_id" :noPreSelect="true" />
            <x-media-upload title="{{ __('Photo') }}" name="photo" {{--  preview="{{ $selectedModel->photo ?? '' }}"  --}} :photo="$photo"
                :photoInfo="$photoInfo" types="PNG or JPEG" rules="image/*" />
            <x-checkbox title="Active" name="isActive" :defer="false" />

        </x-modal>
    </div>

    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="Update" action="update">

            <p class="text-xl font-semibold">{{ __('Edit SubCategory') }}</p>
            <x-input title="{{ __('Name') }}" name="name" placeholder="Vegetables" />
            <x-select title="{{ __('Category') }}" :options="$categories ?? []" name="category_id" :noPreSelect="true" />
            <x-media-upload title="{{ __('Photo') }}" name="photo" preview="{{ $selectedModel->photo ?? '' }}"
                :photo="$photo" :photoInfo="$photoInfo" types="PNG or JPEG" rules="image/*" />
            <x-checkbox title="{{ __('Active') }}" name="isActive" :defer="false" />


        </x-modal>
    </div>
</div>
