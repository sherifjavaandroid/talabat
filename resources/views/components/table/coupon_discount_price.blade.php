@php
    if( !empty($data) ){
        $model = $data["model"];
    }
@endphp

@if ( $model->percentage )
    {{ $model->discount_price ?? $model->discount ?? '' }}%
@else
    {{ currencyFormat($model->discount_price ?? $model->discount ?? '') }}
@endif

