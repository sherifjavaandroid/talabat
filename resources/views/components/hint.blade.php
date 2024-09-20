<p class="mt-1 text-sm font-light text-gray-500">
    <span class="font-medium text-red-500">{{ $prefix ?? __('Note:') }}</span>
    {{ $text ?? '' }}{{ $slot ?? '' }}
</p>
