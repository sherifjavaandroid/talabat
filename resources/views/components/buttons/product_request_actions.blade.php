<div class="flex flex-wrap items-center space-x-2 gap-y-2">
    <x-buttons.show :model="$model" />
    <x-buttons.activate :model="$model" hint="{{ __('Approve') }}" />
    <x-buttons.delete :model="$model" />
</div>
