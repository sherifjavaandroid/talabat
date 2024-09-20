@section('title', __('Customers Report') )
<div>

    <x-baseview title="">
        {{-- Info cards --}}
        <div class="grid gap-6 mt-10 md:grid-cols-2 lg:grid-cols-4 md:grid">

            {{-- topSuccessfulOrdersChart --}}
            <x-dashboard-chart>
                <livewire:livewire-pie-chart :pie-chart-model="$topSuccessfulOrdersChart" />
            </x-dashboard-chart>


            {{-- topCustomersChart --}}
            <x-dashboard-chart>
                <livewire:livewire-pie-chart :pie-chart-model="$topCustomersChart" />
            </x-dashboard-chart>



            {{-- mostCancelsChart --}}
            <x-dashboard-chart>
                <livewire:livewire-pie-chart :pie-chart-model="$mostCancelsChart" />
            </x-dashboard-chart>

            {{-- leastCustomersChart --}}
            <x-dashboard-chart>
                <livewire:livewire-pie-chart :pie-chart-model="$leastCustomersChart" />
            </x-dashboard-chart>


        </div>
    </x-baseview>
    <div class="mt-12"></div>

    <x-baseview title="{{ __('Customers Report') }}">
        <livewire:tables.reports.customer-report-table />
    </x-baseview>

</div>
