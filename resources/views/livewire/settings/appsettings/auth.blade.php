{{-- Auth Layout --}}
<x-form noClass="true" action="saveAuthSettings">
    <div class='grid grid-cols-1 gap-4 mb-10 md:grid-cols-3'>
        {{-- enableOTPLogin --}}
        <div class="block mt-4 text-sm">
            <p>{{ __('Email Login') }}</p>
            <x-checkbox title="{{ __('Enable') }}" name="enableEmailLogin" :defer="true" />
        </div>
        <div class="block mt-4 text-sm">
            <p>{{ __('OTP Login') }}</p>
            <x-checkbox title="{{ __('Enable') }}" name="enableOTPLogin" :defer="true" />
        </div>
        {{-- Working --}}
        <x-select :options="$smsGateways" :title="__('Phone OTP for verification')" name="otpGateway" />
        <div></div>
    </div>

    <div class='grid grid-cols-1 gap-4 mb-10 md:grid-cols-3'>
        <div class="block mt-4 text-sm">
            <p>{{ __('Google Login') }}</p>
            <x-checkbox title="{{ __('Enable') }}" name="googleLogin" :defer="true" />
        </div>
        <div class="block mt-4 text-sm">
            <p>{{ __('Apple Login') }}</p>
            <x-checkbox title="{{ __('Enable') }}" name="appleLogin" :defer="true" />
        </div>
        <div class="block mt-4 text-sm">
            <p>{{ __('Facebook Login') }}</p>
            <x-checkbox title="{{ __('Enable') }}" name="facebbokLogin" :defer="true" />
        </div>
        <div class="block mt-4 text-sm">
            <p>{{ __('Auto Create Account with social login') }}</p>
            <x-checkbox title="{{ __('Enable') }}" name="auto_create_social_account" :defer="true" />
        </div>

        <div class="block mt-4 text-sm">
            <p>{{ __('QR Code Login') }}</p>
            <x-checkbox title="{{ __('Enable') }}" name="qrcodeLogin" :defer="true" />
        </div>
    </div>
    {{-- save button --}}
    <div class="flex justify-end mt-4">
        <x-buttons.primary class="ml-4">
            {{ __('Save') }}
        </x-buttons.primary>
    </div>
</x-form>
