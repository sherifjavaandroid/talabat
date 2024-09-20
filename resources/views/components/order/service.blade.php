<table class="w-full border rounded">
    <thead>
        <tr class="font-medium bg-gray-200 ">
            <th class="p-2">{{ __('Service') }}</th>
            <th class="p-2">{{ __('Options') }}</th>
            <th class="p-2">{{ __('Price') }}</th>
            <th class="p-2">{{ __($order->order_service->service->duration ?? 'Fixed') }}</th>
        </tr>
    </thead>
    <tbody>

        @if (!empty($order))
            <tr class="font-light border-b ">
                <td class="p-2">
                    <p>{{ $order->order_service->service->name ?? '' }}</p>
                    <p class="text-xs font-light text-gray-500 space-x-2">
                        <span>{{ $order->order_service->service->category->name ?? '' }}</span>
                        @if ($order->order_service->service->subcategory != null)
                            <span>/</span>
                            <span>{{ $order->order_service->service->subcategory->name ?? '' }}</span>
                        @endif
                    </p>
                </td>
                <td class="p-2">{{ $order->order_service->options ?? '' }}</td>
                <td class="p-2">{{ currencyFormat($order->order_service->price ?? '') }}</td>
                <td class="p-2">{{ $order->order_service->hours ?? '' }}</td>
            </tr>
        @endif

    </tbody>
</table>
