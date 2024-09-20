@section('title', __('Payment Webhooks Settings'))
<div>

    <x-baseview title="{{ __('Payment Webhooks Settings') }}">
        <p class="pb-4 text-sm italic">
            <span class="font-bold text-red-500">{{ __("Note") }}:</span> {{ __("Not all payment gateway support webhook") }}
        </p>
        <livewire:tables.webhook-table />
    </x-baseview>

</div>
