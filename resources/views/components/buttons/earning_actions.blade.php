<div class="flex items-center gap-x-2">

    @if ($model->amount < 0)
        <x-buttons.plain wireClick="$emit('initiateEarningWalletClearance', {{ $model->id }})">
            {{ __('Zeroing out') }}
        </x-buttons.plain>
    @endif

    <x-buttons.payout :model="$model" />
    @if (($type ?? '') == 'drivers')
        @hasanyrole('admin|city-admin')
            @if ($model->amount > 0)
                <x-buttons.plain wireClick="$emit('initiateEarningWalletTransfer', {{ $model->id }})">
                    {{ __('Transfer') }}
                </x-buttons.plain>
            @endif
        @endhasanyrole
    @endif



</div>
