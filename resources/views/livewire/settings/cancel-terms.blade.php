<div wire:init="setupData">


    <x-form action="saveTermsSettings" :noClass="true">
        <p>
            <span class="font-bold">Link:</span>
            <br />
            <a href="{{ url(route('cancel.terms')) }}" target="_blank"
                class="underline">{{ url(route('cancel.terms')) }}</a>
        </p>
        <div class="w-full">
            <x-input.summernote name="terms" title="{!! __('Cancellation Policy') !!}" id="cancelTermsEdit" />
            <x-buttons.primary title="{{ __('Save Changes') }}" />
        </div>
    </x-form>

    <x-loading />
</div>
