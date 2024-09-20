<x-form noClass="true" action="saveOrderSettings">

    <div class='grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3'>
        <div>
            <x-input title="Ordering Rate Limit" name="orderRetryAfter" type="number" />
            <p class="text-xs text-gray-400 mt-1">{{ __('The number of seconds to wait before retrying an order.') }}</p>
        </div>

        <x-checkbox title="{{ __('Allow Unencrypted Old App Version Ordering') }}" name="allowOldUnEncryptedOrder">
            <p class="text-xs font-light text-gray-600">
                {{ __('When enabled, older app versions without the order data encryption can still place order.') }}
            </p>
        </x-checkbox>

    </div>

    {{-- save button --}}
    <div class="flex justify-end mt-4">
        <x-buttons.primary class="ml-4">
            {{ __('Save') }}
        </x-buttons.primary>
    </div>
</x-form>
