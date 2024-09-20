@section('title',  __('Auto-assignments') )
<div>
<div class="w-32 mb-4">
    <x-buttons.primary title="{{ __('Clear') }}" wireClick="initiateClearAutoAssignment" />
</div>
    <x-baseview title="{{ __('Auto-assignments') }} ">
        <div wire:poll.20000ms="refreshDataTable">
            <livewire:tables.auto-assignment-table />
        </div>
    </x-baseview>


</div>
