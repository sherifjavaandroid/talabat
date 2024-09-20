@section('title', __('Product Reviews'))
    <div>

        <x-baseview title="{{ __('Product Reviews') }}">
            <livewire:tables.product-review-table />
        </x-baseview>

    </div>
