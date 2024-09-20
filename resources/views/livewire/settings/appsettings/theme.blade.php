<x-form noClass="true" action="saveThemeSettings">
    <p class="mt-4 text-lg border-b">Main Colors</p>
    <div class='grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3'>
        <x-input title="Accent Color" name="accentColor" type="color" class="h-10" />
        <x-input title="Primary Color" name="primaryColor" type="color" class="h-10" />
        <x-input title="Primary Dark Color" name="primaryColorDark" type="color" class="h-10" />
    </div>
    {{-- other --}}
    <p class="mt-4 text-lg border-b">Onboarding Colors</p>
    <div class='grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3'>
        <x-input title="Onboarding Page 1 Background Color" name="onboarding1Color" type="color" class="h-10" />
        <x-input title="Onboarding Page 2 Background Color" name="onboarding2Color" type="color" class="h-10" />
        <x-input title="Onboarding Page 3 Background Color" name="onboarding3Color" type="color" class="h-10" />
        {{-- next --}}
        <x-input title="Onboarding Indicator Dot Color" name="onboardingIndicatorDotColor" type="color"
            class="h-10" />
        <x-input title="Onboarding Indicator Active Dot Color" name="onboardingIndicatorActiveDotColor" type="color"
            class="h-10" />
    </div>
    <p class="mt-4 text-lg border-b">Order Status Colors</p>
    <div class='grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3'>
        <x-input title="Open Color" name="openColor" type="color" class="h-10" />
        <x-input title="Close Color" name="closeColor" type="color" class="h-10" />
        <x-input title="Delivery Color" name="deliveryColor" type="color" class="h-10" />
        <x-input title="Pickup Color" name="pickupColor" type="color" class="h-10" />
        <x-input title="Rating Color" name="ratingColor" type="color" class="h-10" />
    </div>
    <p class="mt-4 text-lg border-b">Order Status Colors</p>
    <div class='grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3'>
        {{-- other status colors --}}
        <x-input title="Pending Color" name="pendingColor" type="color" class="h-10" />
        <x-input title="Preparing Color" name="preparingColor" type="color" class="h-10" />
        <x-input title="Enroute Color" name="enrouteColor" type="color" class="h-10" />
        <x-input title="Failed Color" name="failedColor" type="color" class="h-10" />
        <x-input title="Cancelled Color" name="cancelledColor" type="color" class="h-10" />
        <x-input title="Delivered Color" name="deliveredColor" type="color" class="h-10" />
        <x-input title="Successful Color" name="successfulColor" type="color" class="h-10" />
    </div>
    {{-- save button --}}
    <div class="flex justify-end mt-4">
        <x-buttons.primary class="ml-4">
            {{ __('Save') }}
        </x-buttons.primary>
    </div>
</x-form>
