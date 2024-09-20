@section('title',  __('Services Report') )
<div>

    <x-baseview title="">
         {{-- Info cards --}}
         <div class="grid gap-6 mt-10 md:grid-cols-2 lg:grid-cols-3">

            {{-- servicesChart --}}
            <x-dashboard-chart>
                <livewire:livewire-pie-chart :pie-chart-model="$servicesChart" />
            </x-dashboard-chart>

            {{-- topCategoriesChart --}}
            <x-dashboard-chart>
                <livewire:livewire-pie-chart :pie-chart-model="$topCategoriesChart" />
            </x-dashboard-chart>


        </div>
    </x-baseview>
    <div class="mt-12"></div>

    <x-baseview title="{{ __('Services Report') }}">
        <livewire:tables.reports.service-report-table />
    </x-baseview>

</div>
