@if ($paginationEnabled && $showPerPage)
    <div class="w-full ml-0 md:w-auto md:ltr:ml-2 md:rtl:mr-2">
        <select
            wire:model="perPage"
            id="perPage"
            class="block w-full py-2 pl-3 pr-10 text-base leading-6 border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-indigo-300 focus:shadow-outline-indigo sm:text-sm sm:leading-5"
        >
            @foreach ($perPageAccepted as $item)
                <option value="{{ $item }}">{{ $item === -1 ? __('All') : $item }}</option>
            @endforeach
        </select>
    </div>
@endif
