@if (!empty($model))
    <a href="{{ route('users.details', $model->id) }}" class="hover:underline text-primary-600 text-sm">
        <p>{{ $value ?? ($model->name ?? ($model[$column->attribute] ?? '')) }}</p>
        @production
            <p class="text-xs text-gray-500 mt-1">{{ $model->phone }}</p>
        @endproduction
    </a>
@endif
