@if (!empty($model))
<a href="{{ route('orders') }}?filters[search]={{ $model->code }}" class="hover:underline text-primary-600"> {{ $value ?? $model->code ?? $model[$column->attribute] ??  '' }}</a>
@endif