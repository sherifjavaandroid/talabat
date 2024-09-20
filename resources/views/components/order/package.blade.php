<table class="w-full border rounded">
    <thead>
        <tr class="font-medium bg-gray-200 ">
            <th class="p-2">{{__('Package Type')}}</th>
            <th class="p-2">{{__('Weight')}}</th>
            <th class="p-2">{{__('Width')}}</th>
            <th class="p-2">{{__('Length')}}</th>
            <th class="p-2">{{__('Height')}}</th>
        </tr>
    </thead>
    <tbody>

        @if (!empty($order))
            <tr class="font-light border-b ">
                <td class="p-2">{{ $order->package_type->name ?? '' }}</td>
                <td class="p-2">{{ $order->weight }}kg</td>
                <td class="p-2">{{ $order->width }}cm</td>
                <td class="p-2">{{ $order->length }}cm</td>
                <td class="p-2">{{ $order->height }}cm</td>
            </tr>
        @endif

    </tbody>
</table>
