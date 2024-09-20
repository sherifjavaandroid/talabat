<div class="flex items-center gap-x-2">

    @if ($view ?? false)
        <x-buttons.show :model="$model" target="$target ?? ''" />
    @endif
    @if ($edit ?? false)
        <x-buttons.edit :model="$model" target="$target ?? ''" />
    @endif
    @if ($toggleActive ?? false)
        @if ($model->is_active)
            <x-buttons.deactivate :model="$model" target="$target ?? ''" />
        @else
            <x-buttons.activate :model="$model" target="$target ?? ''" />
        @endif
    @endif
    @if ($delete ?? false)
        <x-buttons.delete :model="$model" target="$target ?? ''" />
    @endif

</div>
