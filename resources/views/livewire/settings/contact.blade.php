<div wire:init="setupData">

    <x-form action="saveContactInfo" :noClass="true">
        <p>
            <span class="font-bold">Link:</span>
            <br />
            <a href="{{ url(route('contact')) }}" target="_blank" class="underline">{{ url(route('contact')) }}</a>
        </p>
        <div class="w-full">
            <x-input.summernote name="contactInfo" title="{{ __('Contact Info') }}" id="contactInfoEdit" />
            <x-buttons.primary title="{{ __('Save Changes') }}" />

        </div>
    </x-form>
    <x-loading />
</div>
