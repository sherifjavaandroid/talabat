@section('title',  __('Products Report') )
<div>

    <x-baseview title="">
         {{-- Info cards --}}
         <div class="grid gap-6 mt-10 md:grid-cols-2 lg:grid-cols-3">

            {{-- topProductsChart --}}
            <x-dashboard-chart>
                <livewire:livewire-pie-chart :pie-chart-model="$topProductsChart" />
            </x-dashboard-chart>

            {{-- topCategoriesChart --}}
            <x-dashboard-chart>
                <livewire:livewire-pie-chart :pie-chart-model="$topCategoriesChart" />
            </x-dashboard-chart>


        </div>
    </x-baseview>
    <div class="mt-12"></div>

    <x-baseview title="{{ __('Products Report') }}">
        <livewire:tables.reports.product-report-table />
    </x-baseview>

</div>
