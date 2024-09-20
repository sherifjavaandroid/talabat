<label class="block mt-4 text-sm {{ isRTL() ? 'text-right':'text-left' }}">
    <p class="mb-1 text-gray-700" dir="rtl">{{ $title ?? '' }}</p>
    {{ $slot ?? '' }}
  </label>
