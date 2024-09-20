@section('title',  __('Driver Remittance'))
<div>

    <x-baseview title="{{ __('Driver Remittance') }}">
        <livewire:tables.remittance-table />
    </x-baseview>

    {{-- collection --}}
    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Driver Remittance') }}" action="collect">
            <p class="text-xl font-semibold">{{ __('Remittance By') }} {{ $selectedModel->user->name ?? "" }}</p>
            <x-select title="{{ __('Staus') }}" :options="['Collected','Cancelled']" name="status" :defer="true" />
        </x-modal>
    </div>


    {{-- details moal --}}
    <div x-data="{ open: @entangle('showDetails') }">

        @if($showDetails)
        <x-modal-lg>

            <p class="text-xl font-semibold">{{ __('Order Details') }}</p>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                <x-details.item title="{{ __('Code') }}"
                    text="#{{ $selectedModel->code ?? '' }}" />
                <x-details.item title="{{ __('Status') }}"
                    text="{{ $selectedModel->status ?? '' }}" />
                <x-details.item title="{{ __('Payment Status') }}"
                    text="{{ $selectedModel->payment_status ?? '' }}" />
                <x-details.item title="{{ __('Payment Method') }}"
                    text="{{ $selectedModel->payment_method->name ?? '' }}" />
            </div>
            <div class="grid grid-cols-1 gap-4 mt-5 border-t md:grid-cols-2 lg:grid-cols-3">
                <x-details.item
                    title="{{ $selectedModel != null && $selectedModel->is_package ? __('Sender')  : __('User') }}"
                    text="{{ $selectedModel->user->name ?? '' }}" />
                <x-details.item
                    title="{{ $selectedModel != null && $selectedModel->is_package ? __('Sender Phone')  : __('User Phone') }}"
                    text="{{ $selectedModel->user->phone ?? '' }}" />

                {{-- Pickup address --}}
                @if($selectedModel != null && $selectedModel->vendor != null && $selectedModel->vendor->vendor_type != null && $selectedModel->vendor->vendor_type->is_parcel ?? false )
                    @if(!empty($selectedModel->delivery_address))
                        <x-details.item title="{{ __('Delivery Address') }}"
                            text="{{ $selectedModel->delivery_address->address ?? '' }}" />
                    @else
                        <x-details.item title="{{ __('Delivery Address') }}"
                            text="{{ __('Customer Self Pickup') }}" />
                    @endif

                @endif

            </div>
            {{-- recipient address/info --}}
            @if($selectedModel != null && $selectedModel->vendor != null && $selectedModel->vendor->vendor_type != null && $selectedModel->vendor->vendor_type->is_parcel ?? false )
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                    @foreach($selectedModel->stops as $key => $stop)
                        <x-details.item title="{{ __('Stop') }} {{ $key + 1 }}"
                            text="{{ $stop->delivery_address->address ?? '' }}" />
                            <x-details.item title="{{ __('Recipient') }}">
                                {{ $stop->name ?? '' }}
                                @empty($stop->phone)
                                    
                                @else 
                                    ({{ $stop->phone ?? '' }})
                                @endempty
                                 
                            </x-details.item>
                    @endforeach

                </div>
            @endif
            @if($selectedModel != null && $selectedModel->vendor != null && $selectedModel->vendor->vendor_type != null && $selectedModel->vendor->vendor_type->is_parcel ?? false )
                <div class="mt-5 border-t">
                    <x-details.item title="{{ __('Note') }}"
                    text="{{ $selectedModel->note ?? '--' }}" />
                </div>
            @endif
            <div class="grid grid-cols-1 gap-4 mt-5 border-t md:grid-cols-2 lg:grid-cols-3">

                <x-details.item title="{{ __('Vendor') }}"
                    text="{{ $selectedModel->vendor->name ?? '' }}" />
                <x-details.item title="{{ __('Vendor Address') }}"
                    text="{{ $selectedModel->vendor->address ?? '' }}" />


                <x-details.item title="{{ __('Date of order') }}"
                    text="{{ $selectedModel->formatted_date ?? '' }}" />
                <x-details.item title="{{ __('Updated At') }}"
                    text="{{ $selectedModel->updated_at ?? '--' }}" />
            </div>

            {{-- driver info --}}
            <div class="grid grid-cols-1 gap-4 pt-4 mt-4 border-t md:grid-cols-2 lg:grid-cols-3">
                <x-details.item title="{{ __('Driver') }}"
                    text="{{ $selectedModel->driver->name ?? '--' }}" />
                <x-details.item title="{{ __('Driver Phone') }}"
                    text="{{ $selectedModel->driver->phone ?? '--' }}" />
            </div>

            {{-- foods --}}
            @if($selectedModel != null && $selectedModel->vendor != null && $selectedModel->vendor->vendor_type->is_parcel ?? false)
                <div class="pt-4 mt-4 border-t ">
                    <x-order.package :order="$selectedModel ?? ''" />
                </div>
            @else
                <div class="pt-4 mt-4 border-t ">
                    <x-order.products :products="$selectedModel->products ?? ''" />
                </div>
            @endif
            
            {{-- order photo --}}
            @if($selectedModel != null && $selectedModel->photo && !strpos($selectedModel->photo, 'default.png') )
            <p class="mt-8 font-bold text-md">Order Photo</p>
                <a href="{{ $selectedModel->photo }}" target="_blank"> <img src="{{ $selectedModel->photo }}" class="w-56 h-56 rounded-sm" /></a>
            @endif

            {{-- money --}}
            <div class="pt-4 border-t justify-items-end">

                <div class="flex items-center justify-start p-4 space-x-20 border-2">
                    <p class="my-auto">
                        {{-- <x-label title="Driver Tip" /> --}}
                        {{ __('Driver Tip') }}
                    </p>
                    <x-details.p
                        text="{{ currencyFormat($selectedModel->tip ?? '0.00') }}" />
                </div>
                <div class="flex items-center justify-end space-x-20 border-b">
                    <x-label title="{{ __('Subtotal') }}" />
                    <div class="w-6/12 md:w-4/12 lg:w-2/12">
                        <x-details.p
                            text="{{ currencyFormat($selectedModel->sub_total ?? '') }}" />
                    </div>
                </div>
                <div class="flex items-center justify-end space-x-20 border-b">
                    <x-label title="{{ __('Discount Amount') }}" />
                    <div class="w-6/12 md:w-4/12 lg:w-2/12">
                        <x-details.p
                            text="-{{ currencyFormat($selectedModel->discount ?? '') }}" />
                    </div>
                </div>
                <div class="flex items-center justify-end space-x-20 border-b">
                    <x-label title="{{ __('Delivery Fee') }}" />
                    <div class="w-6/12 md:w-4/12 lg:w-2/12">
                        <x-details.p
                            text="+{{ currencyFormat($selectedModel->delivery_fee ?? '') }}" />
                    </div>
                </div>
                <div class="flex items-center justify-end space-x-20 border-b">
                    <x-label title="{{ __('Tax') }}" />
                    <div class="w-6/12 md:w-4/12 lg:w-2/12">
                        <x-details.p
                            text="+{{ currencyFormat($selectedModel->tax ?? '') }}" />
                    </div>
                </div>
                <div class="flex items-center justify-end space-x-20 border-b">
                    <x-label title="{{ __('Total') }}" />
                    <div class="w-6/12 md:w-4/12 lg:w-2/12">
                        <x-details.p
                            text="{{ currencyFormat($selectedModel->total ?? '') }}" />
                    </div>
                </div>
            </div>

        </x-modal-lg>
        @endif
    </div>


</div>


