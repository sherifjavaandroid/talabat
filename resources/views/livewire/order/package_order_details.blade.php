<div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
    <x-details.item title="{{ __('Code') }}" text="#{{ $selectedModel->code ?? '' }}" />
    <x-details.item title="{{ __('Status') }}" text="{{ $selectedModel->status ?? '' }}" />
    <x-details.item title="{{ __('Payment Status') }}" text="{{ $selectedModel->payment_status ?? '' }}" />
    <x-details.item title="{{ __('Payment Method') }}" text="{{ $selectedModel->payment_method->name ?? '' }}" />
    <x-details.item title="{{ __('Collect Payment From') }}"
        text="{{ $selectedModel->payer ?? 1 ? __('Sender') : 'Receiver' }}" />
</div>
<div class="grid grid-cols-1 gap-4 mt-5 border-t md:grid-cols-2 lg:grid-cols-3">
    <x-details.item title="{{ $selectedModel != null && $selectedModel->is_package ? __('Sender') : __('User') }}"
        text="{{ $selectedModel->user->name ?? '' }}" />
    <x-details.item
        title="{{ $selectedModel != null && $selectedModel->is_package ? __('Sender Phone') : __('User Phone') }}"
        text="{{ $selectedModel->user->phone ?? '' }}" />

</div>
{{-- recipient address/info --}}

@foreach ($selectedModel->stops as $key => $stop)
    <div class="grid grid-cols-1 gap-4 px-2 my-1 border border-gray-300 rounded-md md:grid-cols-2">
        <x-details.item title="{{ __('Stop') }} {{ $key + 1 }}">
            <p class="text-sm font-semibold"><span class="text-sm font-bold">{{ __('Name') }}:</span>
                {{ $stop->delivery_address->name ?? '' }}</p>
            <p class="text-sm font-medium"><span class="text-sm font-bold">{{ __('Address') }}:</span>
                {{ $stop->delivery_address->address ?? '' }}</p>
            <p class="text-sm font-light"><span class="text-sm font-bold">{{ __('Description') }}:</span>
                {{ $stop->delivery_address->description ?? '' }}</p>
        </x-details.item>

        <x-details.item title="{{ __('Recipient') }}">
            {{ $stop->name ?? '' }}
            @empty($stop->phone)
            @else
                ({{ $stop->phone ?? '' }})
            @endempty

        </x-details.item>

        <x-details.item title="{{ __('Note') }}">
            <p class="break-words overflow-clip"> {{ $stop->note ?? '' }}</p>
        </x-details.item>
        {{-- order stop photo proof --}}
        @if ($stop != null && $stop->proof && !strpos($stop->proof, 'default.png'))
            <x-details.item title="{{ __('Order Stop Proof') }}">
                <a href="{{ $stop->proof }}" target="_blank"> <img src="{{ $stop->proof }}"
                        class="w-24 h-24 rounded-sm" /></a>
            </x-details.item>
            <div></div>
        @endif
    </div>
@endforeach

<div class="grid grid-cols-1 gap-4 mt-5 border-t md:grid-cols-2 lg:grid-cols-3">

    <x-details.item title="{{ __('Vendor') }}" text="{{ $selectedModel->vendor->name ?? '' }}" />
    <x-details.item title="{{ __('Vendor Address') }}" text="{{ $selectedModel->vendor->address ?? '' }}" />
    <x-details.item title="{{ __('Vendor Phone') }}" text="{{ $selectedModel->vendor->phone ?? '' }}" />


    <x-details.item title="{{ __('Date of order') }}"
        text="{{ $selectedModel != null ? $selectedModel->created_at->format('M d, Y \\a\\t H:i a') : '' }}" />
    <x-details.item title="{{ __('Updated At') }}"
        text="{{ $selectedModel != null ? $selectedModel->updated_at->format('M d, Y \\a\\t H:i a') : '' }}" />
    @if ($selectedModel != null && $selectedModel->status == 'scheduled')
        @php
            $scheduleDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $selectedModel->pickup_date . ' ' . $selectedModel->pickup_time);
        @endphp
        <x-details.item title="{{ __('Scheduled For') }}" text="{{ $scheduleDate->format('M d, Y \\a\\t h:i a') }}" />
    @endif
</div>

@if ($selectedModel != null && $selectedModel->status == 'cancelled')
    <x-details.item title="{{ __('Cancel Reason') }}" text="{!! $selectedModel->reason ?? '--' !!}" />
@endif

{{-- driver info --}}
<div class="grid grid-cols-1 gap-4 pt-4 mt-4 border-t md:grid-cols-2 lg:grid-cols-3">
    <x-details.item title="{{ __('Driver') }}" text="{{ $selectedModel->driver->name ?? '--' }}" />
    <x-details.item title="{{ __('Driver Phone') }}" text="{{ $selectedModel->driver->phone ?? '--' }}" />
</div>

{{-- parcel details --}}
<div class="pt-4 mt-4 border-t ">
    <x-order.package :order="$selectedModel ?? ''" />
</div>

{{-- order photo --}}
@if ($selectedModel != null && $selectedModel->photo && !strpos($selectedModel->photo, 'default.png'))
    <p class="mt-8 font-bold text-md">{{ __('Order Photo') }}</p>
    <a href="{{ $selectedModel->photo }}" target="_blank"> <img src="{{ $selectedModel->photo }}"
            class="w-56 h-56 rounded-sm" /></a>
@endif

{{-- order signature --}}
@if ($selectedModel != null && $selectedModel->signature && !strpos($selectedModel->signature, 'default.png'))
    <p class="mt-8 font-bold text-md">{{ __('Signature') }}</p>
    <a href="{{ $selectedModel->signature }}" target="_blank"> <img src="{{ $selectedModel->signature }}"
            class="w-56 h-56 rounded-sm" /></a>
@endif

{{-- order delivery photo --}}
@if ($selectedModel != null && $selectedModel->delivery_photo && !strpos($selectedModel->delivery_photo, 'default.png'))
    <p class="mt-8 font-bold text-md">{{ __('Delivery photo') }}</p>
    <a href="{{ $selectedModel->delivery_photo }}" target="_blank"> <img src="{{ $selectedModel->delivery_photo }}"
            class="w-56 h-56 rounded-sm" /></a>
@endif

{{-- money --}}
<div class="pt-4 border-t justify-items-end">

    <div class="flex items-center justify-start p-4 space-x-20 border-2">
        <p class="my-auto">
            {{-- <x-label title="Driver Tip" /> --}}
            {{ __('Driver Tip') }}
        </p>
        <x-details.p text="{{ currencyFormat($selectedModel->tip ?? '0.00') }}" />
    </div>
    <div class="flex items-center justify-end space-x-20 border-b">
        <x-label title="{{ __('Subtotal') }}" />
        <div class="w-6/12 md:w-4/12 lg:w-2/12">
            <x-details.p text="{{ currencyFormat($selectedModel->sub_total ?? '') }}" />
        </div>
    </div>
    <div class="flex items-center justify-end space-x-20 border-b">
        <x-label title="{{ __('Discount Amount') }}" />
        <div class="w-6/12 md:w-4/12 lg:w-2/12">
            <x-details.p text="-{{ currencyFormat($selectedModel->discount ?? '') }}" />
        </div>
    </div>
    <div class="flex items-center justify-end space-x-20 border-b">
        <x-label title="{{ __('Delivery Fee') }}" />
        <div class="w-6/12 md:w-4/12 lg:w-2/12">
            <x-details.p text="+{{ currencyFormat($selectedModel->delivery_fee ?? '') }}" />
        </div>
    </div>
    <div class="flex items-center justify-end space-x-20 border-b">
        <x-label title="{{ __('Tax') }}" />
        <div class="w-6/12 md:w-4/12 lg:w-2/12">
            <x-details.p text="+{{ currencyFormat($selectedModel->tax ?? '') }}" />
        </div>
    </div>
    {{--  order fees  --}}
    <x-order.fees :order="$selectedModel" />
    <div class="flex items-center justify-end space-x-20 border-b">
        <x-label title="{{ __('Total') }}" />
        <div class="w-6/12 md:w-4/12 lg:w-2/12">
            <x-details.p text="{{ currencyFormat($selectedModel->total ?? '') }}" />
        </div>
    </div>
</div>
