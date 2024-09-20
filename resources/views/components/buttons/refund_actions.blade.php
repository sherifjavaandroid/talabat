<div class="flex items-center gap-x-2">
    <x-buttons.show :model="$model" />
    @if ($model->status == "pending")
    <x-buttons.reject :model="$model" />
    <x-buttons.approve :model="$model" />
    @endif
</div>
