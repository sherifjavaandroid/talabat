<div wire:init="setupData">
    <x-form action="savePrivacyPolicy" :noClass="true">
        <p>
            <span class="font-bold">Link:</span>
            <br />
            <a href="{{ url(route('privacy')) }}" target="_blank" class="underline">{{ url(route('privacy')) }}</a>
        </p>
        <div class="w-full">
            <x-input.summernote name="privacyPolicy" title="{!! __('Privacy & Policy') !!}" id="privacyPolicyEdit" />
            <x-buttons.primary title="{{ __('Save Changes') }}" />

        </div>
    </x-form>

    <x-loading />
</div>
