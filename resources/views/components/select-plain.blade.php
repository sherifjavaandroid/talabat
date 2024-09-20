<select {{-- name --}} name="{{ $name ?? '' }}" {{-- id --}} id="{{ $id ?? ($name ?? '') }}"
    {{-- on change --}} wire:change="{{ $onchange ?? '' }}" {{-- defer --}}
    @if ($defer ?? true) wire:model.defer='{{ $name ?? '' }}' @else wire:model='{{ $name ?? '' }}' @endif
    {{-- width --}} @if ($width ?? false) style="width: {{ $width }}%" @endif
    class="h-full rounded-md border-0 bg-transparent py-0 pl-2 pr-7 text-gray-500 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm">
    @if ($noPreSelect ?? false)
        <option> {{ $hint ?? '-- Select --' }}</option>
    @endif
    @foreach ($options as $option)
        @php
            $optionId = $option->id ?? ($option['id'] ?? $option);
        @endphp
        <option value="{{ $optionId }}" {{ $selected ?? '' == $optionId ? 'selected' : '' }}>
            {{ Str::ucfirst(__($option->name ?? ($option['name'] ?? $option))) }}
        </option>
    @endforeach
</select>
