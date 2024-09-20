<div class="flex items-center gap-x-2">

    <x-buttons.plain bgColor="bg-primary-500" wireClick="$emit('newTab', '{{ route('tags.link',$model->id) }}' ) ">
        {{ __("Link/Unlink") }}
    </x-buttons.plain>
    <x-buttons.edit :model="$model" />
    <x-buttons.delete :model="$model" />

</div>