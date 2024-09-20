<div>
    <div class="flex items-center p-4 text-lg bg-white border rounded cursor-pointer hover:shadow-md"
        wire:click='{{ $wireClick ?? '' }}'>
        <div class="{{ isRTL() ? 'ml-4' : 'mr-4' }}"> {{ $slot ?? '' }} </div>
        {{ $title }}
    </div>

    {{-- new slot for info --}}
    {{ $info ?? '' }}
</div>
