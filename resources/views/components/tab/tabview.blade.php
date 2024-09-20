<div x-data="{ openTab: {{ $initialTab ?? 1 }} }" {{ $attributes->merge(['class' => 'bg-white rounded px-4 py-2']) }}>
    {{-- section for tab header --}}
    <div class="mt-4 space-x-4 flex items-center justify-start border-b border-gray-300 dark:border-gray-700">
        {{ $header ?? '' }}
    </div>
    {{-- section for ab body --}}
    <div>
        {{ $body ?? '' }}
    </div>
</div>
