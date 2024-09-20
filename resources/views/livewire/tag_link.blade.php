@section('title',  __('Link Tags') )
<div>

    <x-baseview title="{{ __('Link Tags') }}" >
        <livewire:tables.tag-link-table  :model="$selectedModel" />
    </x-baseview>

</div>


