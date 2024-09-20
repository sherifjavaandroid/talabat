@section('title', __('Outstanding Payments') )
<div>

    <x-baseview title="{{ __('Outstanding Payments') }}">
        <livewire:tables.outstanding-payment-table />
    </x-baseview>

</div>
