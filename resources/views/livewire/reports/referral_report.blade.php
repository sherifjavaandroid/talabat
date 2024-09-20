@section('title', __('Referral Report') )
<div>

    <x-baseview title="">
        {{-- Info cards --}}
        <div class="grid gap-6 mt-10 md:grid-cols-2 md:grid">

            {{-- topReferringUserChart --}}
            <x-dashboard-chart>
                <livewire:livewire-pie-chart :pie-chart-model="$topReferringUserChart" />
            </x-dashboard-chart>

            {{-- completedReferralChart --}}
            <x-dashboard-chart>
                <livewire:livewire-pie-chart :pie-chart-model="$completedReferralChart" />
            </x-dashboard-chart>


        </div>
    </x-baseview>
    <div class="mt-12"></div>

    <x-baseview title="{{ __('Referral Report') }}">
        <livewire:tables.reports.referral-report-table />
    </x-baseview>

</div>
