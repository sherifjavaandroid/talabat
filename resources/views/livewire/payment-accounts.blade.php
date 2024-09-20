@section('title', __('Payment Accounts') )
<div>
    <x-baseview title="{{ __('Payment Accounts') }}" :showNew="$showNew">
        <livewire:tables.payment-account-table />
    </x-baseview>

    {{-- show detils --}}
    <div x-data="{ open: @entangle('showDetails') }">
        <x-modal>
            <p class="text-xl font-semibold">{{ __('Payment Account Details') }}</p>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-details.item title="{{ __('Type') }}" text="{{ $selectedModel->type ?? '' }}" />
                <x-details.item title="{{ __('Name') }}" text="{{ $selectedModel->accountable->name ?? '' }}" />
            </div>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-details.item title="{{ __('Account Name') }}" text="{{ $selectedModel->name ?? '' }}" />
                <x-details.item title="{{ __('Account Number') }}" text="{{ $selectedModel->number ?? '' }}" />
            </div>
            <x-details.item title="{{ __('Instructions') }}" text="{{ $selectedModel->instructions ?? '' }}" />
        </x-modal>
    </div>

     {{-- show create --}}
     <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Save') }}" action="save">
            <p class="text-xl font-semibold">{{ __('New Payment Account') }}</p>
            <x-input title="{{ __('Account Name') }}" name="name" />
            <x-input title="{{ __('Account Number') }}" name="number" />
            <x-textarea title="{{ __('Instructions') }}" name="instructions" />
            <x-checkbox title="{{ __('Active') }}" name="is_active" :defer="false" />
        </x-modal>
    </div>
     {{-- show update --}}
     <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Update') }}" action="update">
            <p class="text-xl font-semibold">{{ __('Edit Payment Account') }}</p>
            <x-input title="{{ __('Account Name') }}" name="name" />
            <x-input title="{{ __('Account Number') }}" name="number" />
            <x-textarea title="{{ __('Instructions') }}" name="instructions" />
            <x-checkbox title="{{ __('Active') }}" name="is_active" :defer="false" />
        </x-modal>
    </div>

</div>
