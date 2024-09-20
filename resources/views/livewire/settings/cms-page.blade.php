@section('title', __('CMS Pages '))
<div>

    <x-baseview title="{{ __('CMS Pages') }}" :showNew="true">
        <livewire:tables.cms-table />
    </x-baseview>

    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal-lg confirmText="{{ __('Save') }}" action="save" :clickAway="false">
            <p class="text-xl font-semibold">{{ __('New Page') }}</p>
            <div class="space-y-2">
                <x-input name="name" title="{{ __('Name') }}" />
                <x-input name="slug" title="{{ __('Slug') }}" />
                <x-input.summernote name="content" title="{{ __('Content') }}" id="newContent" />
                <x-input-error message="{{ $errors->first('content') }}" />
                <x-checkbox name="active" title="{{ __('Active') }}" />
            </div>
        </x-modal-lg>
    </div>
    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal-lg confirmText="{{ __('Update') }}" action="update" :clickAway="false">
            <p class="text-xl font-semibold">{{ __('Edit Page') }}</p>
            <div class="space-y-2">
                <x-input name="name" title="{{ __('Name') }}" />
                <x-input name="slug" title="{{ __('Slug') }}" />
                <x-input.summernote name="content" title="{{ __('Content') }}" id="editContent" />
                <x-input-error message="{{ $errors->first('content') }}" />
                <x-checkbox name="active" title="{{ __('Active') }}" />
            </div>
        </x-modal-lg>
    </div>
</div>
