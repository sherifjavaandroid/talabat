@section('title', __('My Payout'))
<div>

    <x-baseview title="{{ __('My Payout') }}" :showNew="true">
        <livewire:tables.my-payout-table />
    </x-baseview>

    {{-- show detils --}}
    <div x-data="{ open: @entangle('showDetails') }">
        <x-modal>
            <p class="text-xl font-semibold">{{ __('Payout Details') }}</p>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-details.item title="{{ __('Amount') }}" text="{{ currencyFormat($selectedModel->amount ?? '') }}" />
                <x-details.item title="{{ __('Name') }}" text="{{ $selectedModel->earning->vendor->name ?? $selectedModel->earning->user->name ?? '' }}" />
            </div>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-details.item title="{{ __('Paid By') }}" text="{{ $selectedModel->user->name ?? '' }}" />
                <x-details.item title="{{ __('Date') }}" text="{{ $selectedModel->formatted_date ?? '' }}" />
            </div>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-details.item title="{{ __('Payment Method') }}" text="{{ $selectedModel->payment_method->name ?? '' }}" />
            </div>
            <x-details.item title="{{ __('Note') }}" text="{{ $selectedModel->note ?? '' }}" />
            @if($selectedModel->payment_account ?? null)
            <div class="px-2 my-4 bg-gray-100 border border-gray-300">
                <p class="mt-2 text-sm font-semibold">{{ __('Payment Account Details') }}</p>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <x-details.item title="{{ __('Account Name') }}" text="{{ $selectedModel->payment_account->name ?? '' }}" />
                    <x-details.item title="{{ __('Account Number') }}" text="{{ $selectedModel->payment_account->number ?? '' }}" />
                </div>
                <x-details.item title="{{ __('Instructions') }}" text="{{ $selectedModel->payment_account->instructions ?? '' }}" />
            </div>
            @endif

            <x-details.item title="{{ __('Status') }}">
                <x-table.status value="{{ $selectedModel->status ?? '' }}" />
            </x-details.item>
        </x-modal>
    </div>

    {{-- payout --}}
    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Request Payout') }}" action="requestPayout">
            <p class="text-xl font-semibold">{{ __('Request Payout') }}</p>
            <x-select title="{{ __('Payment Account') }}" :options="$paymentAccounts" name="payment_account_id" />
            <x-input title="{{ __('Amount') }}" name="amount" placeholder="10" />
            <x-input title="{{ __('Note') }}" name="note" placeholder="" />
        </x-modal>
    </div>

</div>
