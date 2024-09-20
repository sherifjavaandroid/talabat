@section('title', __('Product Requests'))
<div>

    <x-baseview title="{{ __('Product Requests') }}">
        <livewire:tables.product-request-table />
    </x-baseview>


    {{-- details modal --}}
    <div x-data="{ open: @entangle('showDetails') }">
        <x-modal-lg>

            <p class="text-xl font-semibold">{{ $selectedModel->name ?? '' }} {{ __('Details') }}</p>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-details.item title="{{ __('Name') }}" text="{{ $selectedModel->name ?? '' }}" />
                <x-details.item title="{{ __('SKU') }}" text="{{ $selectedModel->sku ?? '' }}" />
            </div>
            <x-details.item title="{{ __('Description') }}" text="">
                {!! $selectedModel->description ?? '' !!}
            </x-details.item>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-details.item title="{{ __('Price') }}" text="{{ currencyFormat($selectedModel->price ?? '') }}" />
                <x-details.item title="{{ __('Discount Price') }}"
                    text="{{ currencyFormat($selectedModel->discount_price ?? '') }}" />


                {{-- <x-details.item title="" text="" /> --}}
                <x-details.item title="{{ __('Capacity') }}" text="{{ $selectedModel->capacity ?? '' }}" />
                <x-details.item title="{{ __('Unit') }}" text="{{ $selectedModel->unit ?? '' }}" />


                <x-details.item title="{{ __('Package Count') }}" text="{{ $selectedModel->package_count ?? '0' }}" />
                <x-details.item title="{{ __('Available Qty') }}" text="{{ $selectedModel->available_qty ?? '' }}" />


                <x-details.item title="{{ __('Vendor') }}" text="{{ $selectedModel->vendor->name ?? '' }}" />
                <x-details.item title="{{ __('Menus') }}" text="">
                    @if ($selectedModel != null)
                        {{ implode(', ', $selectedModel->menus()->pluck('name')->toArray()) }}
                    @endif
                </x-details.item>



            </div>
            <x-details.item title="{{ __('Photos') }}" text="">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach ($selectedModel->photos ?? [] as $photo)
                        <a href="{{ $photo }}" target="_blank"><img src="{{ $photo }}"
                                class="w-24 h-24 mx-2 rounded-sm" /></a>
                    @endforeach
                </div>
            </x-details.item>
            {{-- list option groups and options --}}
            @if ($selectedModel->has_options ?? false)
                <div>
                    <hr class="my-4" />
                    {{-- <p class="font-medium my-2">{{ __('Option Groups') }}</p> --}}
                    <table class="w-full table-auto border-collapse border border-slate-500">
                        <thead class="bg-slate-400">
                            <tr class="bg-slate-400">
                                <th class="p-2 border border-slate-400 bg-gray-100">{{ __('Option Group') }}</th>
                                <th class="p-2 border border-slate-400 bg-gray-100 w-20">{{ __('Active') }}</th>
                                <th class="p-2 border border-slate-400 bg-gray-100">{{ __('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($selectedModel->option_groups ?? [] as $optionGroup)
                                <tr>
                                    <td class="p-2 border border-slate-400">{{ $optionGroup->name }}</td>
                                    <td class="p-2 border border-slate-400">
                                        @if ($optionGroup->is_active)
                                            <x-table.check />
                                        @else
                                            <x-table.close />
                                        @endif
                                    </td>
                                    <td class="p-2 border border-slate-400 wrap space-x-2">
                                        @foreach ($optionGroup->options ?? [] as $option)
                                            <span class="rounded-full bg-gray-200 px-2 text-sm">
                                                {{ $option->name }}
                                            </span>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if ($selectedModel->digital ?? false)
                <hr class="my-4" />
                <x-details.item title="{{ __('File') }}" text="">
                    <div class="space-y-3">
                        @foreach ($selectedModel->digital_files ?? [] as $file)
                            <div class="flex items-center p-2 border rounded">
                                <div class="w-full text-wrap">
                                    <p>{{ $file->name }}</p>
                                    <p>
                                        <span class="text-xs font-thin text-primary-400">
                                            {{ $file->size }} bytes
                                        </span>
                                    </p>
                                </div>

                                <a href="{{ $file->link }}" target="_blank"
                                    class="font-medium hover:underline text-primary-500 hover:text-primary-800 hover:font-bold">
                                    {{ __('Download') }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </x-details.item>
            @endif
            <div class="grid grid-cols-1 gap-4 pt-4 mt-4 border-t md:grid-cols-2 lg:grid-cols-3">

                <div>
                    <x-label title="{{ __('Status') }}" />
                    <x-table.active :model="$selectedModel" />
                </div>

                <div>
                    <x-label title="{{ __('Plus Option') }}" />
                    <x-table.bool isTrue="{{ $selectedModel->plus_option ?? false }}" />
                </div>

                <div>
                    <x-label title="{{ __('Available for Delivery') }}" />
                    <x-table.bool isTrue="{{ $selectedModel->deliverable ?? false }}" />
                </div>

            </div>

            <div class="grid grid-cols-1 gap-4 pt-4 mt-4 border-t md:grid-cols-2 lg:grid-cols-3">





            </div>

        </x-modal-lg>
    </div>
</div>
