<table class="w-full border rounded">
    <thead>
        <tr class="font-medium bg-gray-200 ">
            <th class="p-2">{{ __('Product') }}</th>
            <th class="p-2">{{ __('Options') }}</th>
            <th class="p-2">{{ __('Price') }}</th>
            <th class="p-2">{{ __('Quantity') }}</th>
        </tr>
    </thead>
    <tbody>

        @if (!empty($products))
            @foreach ($products as $product)
                <tr class="font-light border-b ">
                    <td class="p-2">{{ $product->product->name }}</td>
                    <td class="p-2">{{ $product->options }}</td>
                    <td class="p-2">{{ currencyFormat($product->price) }}</td>
                    <td class="p-2">{{ $product->quantity }}</td>
                </tr>
            @endforeach
        @endif

    </tbody>
</table>
