<div class=" w-full md:w-10/12 lg:w-6/12">
    <x-form noClass="true" action="saveCurrencySettings">
        <div class='grid grid-cols-1 gap-4 md:grid-cols-2'>

            <x-select title="{{ __('Location') }}" name="currencyLocation" :options="['Left', 'Right']" />
            <x-input title="{{ __('Thousand separator') }}" name="currencyFormat" />
            <x-input title="{{ __('Decimal separator') }}" name="currencyDecimalFormat" />
            <x-input title="{{ __('Decimals') }}" name="currencyDecimals" type="number" />
        </div>
        <x-buttons.primary title="{{ __('Save') }}" />
    </x-form>
</div>
