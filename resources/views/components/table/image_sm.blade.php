<div>
    <a href="{{ $model->photo ?? $column->attribute }}" target="_blank"> <img
            src="{{ $model->photo ?? $column->attribute }}"
            class="{{ $position ?? 'object-cover' }} w-16 h-12 overflow-hidden bg-white rounded" />
    </a>
</div>
