@section('title', __('Wallet Transactions'))
<div>

    <x-baseview title="{{ __('Wallet Transactions') }}" :showNew="true"
        actionTitle="{{ __('Add/Minus Wallet Balance') }}">
        <livewire:tables.wallet-transaction-table />
    </x-baseview>

    {{-- add or minus from user wallet balance  --}}
    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Save') }}" action="save">
            <p class="text-xl font-semibold">{{ __('Update User Wallet Balance') }}</p>
            {{-- Customer --}}
            <x-label for="customer_id" title="{{ __('Customer') }}">
                <livewire:select.user-select name="user_id" placeholder="{{ __('Select Customer') }}" :searchable="true" />
                <x-input-error message="{{ $errors->first('user_id') }}" />
            </x-label>
            {{-- current balance  --}}
            <x-details.item title="{{ __('Current Balance') }}"
                text="{{ currencyFormat($wallet->balance ?? '0.00') }}" />
            <x-select title="{{ __('Action') }}" :options='$actions' name="selectedAction" />
            <x-input title="{{ __('Amount') }}" name="amount" placeholder="" type="number" />
            <x-textarea title="{{ __('Note') }}" name="note" />
        </x-modal>
    </div>

    {{-- details modal --}}
    <div x-data="{ open: @entangle('showDetails') }">
        <x-modal>

            <p class="text-xl font-semibold">
                {{ __('Wallet Transactions Details') }}
            </p>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-details.item title="{{ __('Ref') }}" text="{{ $selectedModel->ref ?? '' }}" />
                <x-details.item title="{{ __('Transaction Code') }}" text="">
                    <p class="break-words">{{ $selectedModel->session_id ?? '' }}</p>
                </x-details.item>
                <x-details.item title="{{ __('User') }}" text="{{ $selectedModel->wallet->user->name ?? '' }}" />
                <x-details.item title="{{ __('Amount') }}"
                    text="{{ currencyFormat($selectedModel->amount ?? 0.0) }}" />
                <x-details.item title="{{ __('Payment Method') }}"
                    text="{{ $selectedModel->payment_method->name ?? '' }}" />
                <x-details.item title="{{ __('Status') }}" text="{{ $selectedModel->status ?? '' }}" />
            </div>
            <hr class="mt-4" />
            <x-details.item title="{{ __('Reason') }}" text="{{ $selectedModel->reason ?? '' }}" />
            @if (
                $selectedModel != null &&
                    $selectedModel->payment_method != null &&
                    $selectedModel->payment_method->slug == 'offline')
                <img src="{{ $selectedModel->photo ?? '' }}" class="mt-6 border rounded-sm" />
            @endif
        </x-modal>
    </div>




</div>
