<div wire:init="setupData">


    <x-form action="saveTermsSettings" :noClass="true">
        <p>
            <span class="font-bold">Link:</span>
            <br />
            <a href="{{ url(route('payment.terms')) }}" target="_blank"
                class="underline">{{ url(route('payment.terms')) }}</a>
        </p>
        <div class="w-full">
            <x-input.summernote name="terms" title="{!! __('Payment Terms & Condition') !!}" id="paymentTermsEdit" />
            <x-buttons.primary title="{{ __('Save Changes') }}" />
        </div>
    </x-form>

    <x-loading />
</div>
