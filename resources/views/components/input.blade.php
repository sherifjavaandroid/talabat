<label class="block {{ empty($title ?? '') ?'': 'mt-4' }} text-sm">
    @if (!empty($title ?? ''))
    <span class="text-gray-700">{{ $title ?? '' }}</span>
    @endif
    <input {{ $attributes->merge(['class' => 'block w-full p-2 mt-1 text-sm border border-gray-300 rounded focus:border-primary-400 focus:outline-none focus:shadow-outline-primary']) }} placeholder="{{ $placeholder ?? '' }}" type="{{ $type ?? 'text' }}" id='{{ $name ?? '' }}' @if ( $defer ?? true ) wire:model.defer='{{ $name ?? '' }}' @else wire:model='{{ $name ?? '' }}' @endif @if ( $disable ?? false ) disabled @endif />
    @if (!empty($hint))
    <span class="text-xs italic text-gray-700">{{ $hint ?? '' }}</span>
    @endif
    @error($name ?? '')
    <span class="mt-1 text-xs text-red-700">{{ $message }}</span>
    @enderror
</label>
