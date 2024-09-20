@section('title',  __('Vendors Report') )
<div>

    <x-baseview title="">
         {{-- Info cards --}}
         <div class="grid gap-6 mt-10 md:grid-cols-2 lg:grid-cols-3">

            {{-- topVendorsChart --}}
            <x-dashboard-chart>
                <livewire:livewire-pie-chart :pie-chart-model="$topVendorsChart" />
            </x-dashboard-chart>

            {{-- topEarningVendorsChart --}}
            <x-dashboard-chart>
                <livewire:livewire-pie-chart :pie-chart-model="$topEarningVendorsChart" />
            </x-dashboard-chart>

            {{-- leastVendorsChart --}}
            <x-dashboard-chart>
                <livewire:livewire-pie-chart :pie-chart-model="$leastVendorsChart" />
            </x-dashboard-chart>


        </div>
    </x-baseview>
    <div class="mt-12"></div>

    <x-baseview title="{{ __('Vendors Report') }}">
        <livewire:tables.reports.vendor-report-table />
    </x-baseview>

</div>
