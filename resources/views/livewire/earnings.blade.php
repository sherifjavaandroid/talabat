@section('title', __('Earning') . ' ' . Str::ucfirst($type))
<div>

    <x-baseview title="{{ __('Earning') }} {{ Str::ucfirst($type) }}">
        {{-- <livewire:tables.earning-table /> --}}
        @livewire('tables.earning-table', [
            'type' => $type,
        ])
    </x-baseview>

    {{-- payout --}}
    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Payout') }}" action="payout">
            <p class="text-xl font-semibold">{{ __('Pay') }} {{ Str::ucfirst(Str::singular($type ?? '')) }}</p>
            <x-input title="{{ __('Amount') }}" name="amount" placeholder="10" />
            <x-select title="{{ __('Payment Method') }}" :options="$paymentMethods ?? []" name="payment_method_id" />
            <x-select title="{{ __('Payment Account') }}" :options="$paymentAccounts ?? []" name="payment_account_id" />
            <x-input title="{{ __('Note') }}" name="note" placeholder="" />
        </x-modal>
    </div>

    {{-- transfer --}}
    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Transfer') }}" action="transfer">
            <p class="text-xl font-semibold">{{ __('Transfer To Wallet') }} ({{ $selectedModel->user->name ?? '' }})
            </p>
            <x-input title="{{ __('Amount') }}" name="transferAmount" placeholder="10" />
            <x-input title="{{ __('Current Account Password') }}" name="password" type="password" />
        </x-modal>
    </div>


</div>
