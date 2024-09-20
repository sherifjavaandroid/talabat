@if ($model->is_active ?? ($active ?? false))
    {{-- <span class="px-2 py-1 text-sm font-medium text-center text-white bg-green-500 rounded-xl">{{ __('Active') }}</span> --}}
    <span title="{{ __('Active') }}">
        <x-table.check />
    </span>
@else
    {{-- <span class="px-2 py-1 text-sm font-medium text-center text-white bg-red-500 rounded-xl">{{ __('Inactive') }}</span> --}}
    <span title="{{ __('Inactive') }}">
        <x-table.close />
    </span>
@endif
