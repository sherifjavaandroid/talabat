@section('title', __('Subscription Report'))
<div>

    <x-baseview title="">
        {{-- Info cards --}}
        <div class="grid gap-6 mt-10 md:grid-cols-2 lg:grid-cols-3">

            {{-- topVendorsChart --}}
            <x-dashboard-chart>
                <livewire:livewire-pie-chart :pie-chart-model="$topVendorsChart" />
            </x-dashboard-chart>

            {{-- topSubscriptionVendorsChart --}}
            <x-dashboard-chart>
                <livewire:livewire-pie-chart :pie-chart-model="$topSubscriptionVendorsChart" />
            </x-dashboard-chart>

            {{-- leastSubscriptionVendorsChart --}}
            <x-dashboard-chart>
                <livewire:livewire-pie-chart :pie-chart-model="$leastSubscriptionVendorsChart" />
            </x-dashboard-chart>


        </div>
    </x-baseview>
    <div class="mt-12"></div>

    <x-baseview title="{{ __('Subscription Report') }}">
        <livewire:tables.reports.subscription-report-table />
    </x-baseview>

</div>
