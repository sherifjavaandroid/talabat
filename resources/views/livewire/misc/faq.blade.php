@section('title', __('FAQs') )
<div>

    <x-baseview title="{{ __('FAQs') }}" :showNew="true">
        <livewire:tables.misc.faq-table />
    </x-baseview>

    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Save') }}" action="save">
            <p class="text-xl font-semibold">{{ __('Create Faq') }}</p>
            <x-select title="{{ __('Type') }}" :options='$types ?? []' name="type" :defer="false" />
            {{-- title --}}
            <x-input title="{{ __('Title') }}" name="title" placeholder="" />
            <div wire:ignore class="mt-4">
                <span class="mb-1 text-gray-700">{{ __('Body') }}</span>
                <div id="newDescription" class="description"></div>
                <div class="hidden">
                    <input type="text" id="description" wire:model.defer="body" />
                </div>
            </div>
            <x-checkbox title="{{ __('Active') }}" name="is_active" :defer="false" />

        </x-modal>
    </div>

    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Update') }}" action="update">

            <p class="text-xl font-semibold">{{ __('Edit Faq') }}</p>
            <x-select title="{{ __('Type') }}" :options='$types ?? []' name="type" :defer="false" />
            {{-- title --}}
            <x-input title="{{ __('Title') }}" name="title" placeholder="" />
            <div wire:ignore class="mt-4">
                <span class="mb-1 text-gray-700">{{ __('Body') }}</span>
                <div id="editDescription" class="description"></div>
                <div class="hidden">
                    <input type="text" id="description" wire:model.defer="body" />
                </div>
            </div>
            <x-checkbox title="{{ __('Active') }}" name="is_active" :defer="false" />

        </x-modal>
    </div>


    {{-- details modal --}}
    <div x-data="{ open: @entangle('showDetails') }">
        <x-modal-lg>
            <p class="text-xl font-semibold">{{ __('Faq Details') }}</p>
            <x-details.item title="{{ __('Title') }}" text="{{ $selectedModel->title ?? '' }}" />
            <x-details.item title="{{ __('Body') }}" text="">
                {!! $selectedModel->body ?? '' !!}
            </x-details.item>

            <div class="grid grid-cols-1 gap-4 pt-4 mt-4 border-t md:grid-cols-2 lg:grid-cols-3">
                <div>
                    <x-label title="{{ __('Type') }}" />
                    <x-table.plain text="{{ __($selectedModel->type ?? 'All') }}" />
                </div>

                <div>
                    <x-label title="{{ __('Status') }}" />
                    <x-table.active :model="$selectedModel" />
                </div>
            </div>
        </x-modal-lg>
    </div>
</div>

@include('layouts.partials.wysisyg')