<div class="flex items-center gap-x-2">

    @if ( $isTrue ?? $model[$column->attribute] ?? false )
        <x-buttons.deactivate :model="$model" />
    @else
        <x-buttons.activate :model="$model" />
    @endif

</div>
