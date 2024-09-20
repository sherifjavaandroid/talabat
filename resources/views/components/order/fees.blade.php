@if (!empty($order->fees))
    @php
        
        $fees = json_decode($order->fees, true);
        if (!is_array($fees)) {
            $fees = json_decode($fees, true);
        }
    @endphp
    @foreach ($fees ?? [] as $fee)
        <div class="flex items-center justify-end space-x-20 border-b">
            <x-label title="{{ __($fee['name']) }}" />
            <div class="w-6/12 md:w-4/12 lg:w-2/12">
                <x-details.p text="+{{ currencyFormat($fee['amount'] ?? '') }}" />
            </div>
        </div>
    @endforeach
@endif
