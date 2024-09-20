@section('title', __('User Permissions'))
<div>
    <div class="rounded-lg px-12 pt-8 pb-12 bg-white shadow border border-gray-100">
        <div>
            <p class="text-xl font-bold underline">{{ $selectedModel->name }} {{ __('Permissions') }}</p>
        </div>
        <form wire:submit.prevent="save">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4">
                @foreach ($permissions as $permission)
                    <x-checkbox :title="$permission->name" name="selectedPermissions" :value="$permission->name" />
                @endforeach
            </div>

            <div class="flex justify-end mt-4">
                <x-buttons.primary type="submit" :title="__('Save')" />
            </div>
        </form>
    </div>
</div>
