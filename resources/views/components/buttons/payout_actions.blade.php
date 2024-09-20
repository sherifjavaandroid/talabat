<div class="flex items-center gap-x-2">
    <x-buttons.show :model="$model" />

    @if( $model->status == "review" )
    @can('manage-payout')
    <x-buttons.deactivate :model="$model" />
    <x-buttons.activate :model="$model" />
    @endcan
    @endif
</div>
