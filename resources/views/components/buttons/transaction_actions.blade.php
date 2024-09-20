<div class="flex items-center gap-x-2">

    @if (in_array($model->status, ['review']))
        <x-buttons.deactivate :model="$model" />
        <x-buttons.activate :model="$model" />
    @endif
    <x-buttons.show :model="$model" />

</div>
