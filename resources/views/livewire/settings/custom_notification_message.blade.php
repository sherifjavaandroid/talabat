<div>

    <p class="text-sm font-light text-red-300">{{ __('These are messages sent to customer base on order status') }}</p>

    <x-form action="saveCustomNotificationSettings" :noClass="true">


        <div class='grid grid-cols-1 gap-4 mb-10 md:grid-cols-2 '>

            <x-input title="{{ __('Pending') }}" name="pending" />
            <x-input title="{{ __('Preparing') }}" name="preparing" />
            <x-input title="{{ __('Ready') }}" name="ready" />
            <x-input title="{{ __('Enroute') }}" name="enroute" />
            <x-input title="{{ __('Completed') }}" name="completed" />
            <x-input title="{{ __('Cancelled') }}" name="cancelled" />
            <x-input title="{{ __('Failed') }}" name="failed" />
        </div>
        <p class="pt-4 mt-10 text-2xl border-t">{{ __('Manager/Vendor Notification') }}</p>
        <div class='grid grid-cols-1 gap-4 mb-10 md:grid-cols-2 '>
            <x-input title="{{ __('Preparing') }}" name="managerPreparingMssage" />
            <x-input title="{{ __('Enroute') }}" name="managerEnrouteMsg" />
        </div>
        <x-buttons.primary title="{{ __('Save Changes') }}" />


    </x-form>


</div>
