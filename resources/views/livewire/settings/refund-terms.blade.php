<div wire:init="setupData">


    <x-form action="saveTermsSettings" :noClass="true">
        <p>
            <span class="font-bold">Link:</span>
            <br />
            <a href="{{ url(route('refund.terms')) }}" target="_blank"
                class="underline">{{ url(route('refund.terms')) }}</a>
        </p>
        <div class="w-full">
            <x-input.summernote name="terms" title="{!! __('Refund Policy') !!}" id="refundTermsEdit" />
            <x-buttons.primary title="{{ __('Save Changes') }}" />
        </div>
    </x-form>

    <x-loading />
</div>
