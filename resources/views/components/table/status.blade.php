@php
$color = config('backend.colors')[$value] ?? "#f0f0f0";
@endphp

<div class="w-24 flex items-center justify-center px-2 py-1 m-1 font-medium rounded-full text-white" style="color: {{ $color }};border: 1px solid {{ $color }};">
    <div class="flex-initial max-w-full text-xs font-normal leading-none">{{ ucfirst($value) ?? '' }}</div>
</div>
