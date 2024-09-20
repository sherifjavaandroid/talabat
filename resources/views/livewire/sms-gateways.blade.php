@section('title', __('SMS Gateways'))
<div>

    <x-baseview title="{{ __('SMS Gateways') }}">
        <livewire:tables.sms-gateway-table />
    </x-baseview>

    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Update') }}" action="update">

            <p class="text-xl font-semibold">{{ __('Edit SMS Gateway') }}</p>
            <x-input title="{{ __('Name') }}" name="name" disable />

            @if (($selectedModel->slug ?? '') == 'twilio')
                <x-input title="{{ __('Account Id') }}" name="accountId" />
                <x-input title="{{ __('AUTH TOken') }}" name="token" />
                <x-input title="{{ __('From Number') }}" name="fromNumber" />
            @elseif (($selectedModel->slug ?? '') == 'msg91')
                <x-input title="{{ __('DLT Template Id') }}" name="template_id" />
                <x-input title="{{ __('Route Number') }}" name="route" />
                <x-input title="{{ __('Authentication Key') }}" name="authkey" />
                <x-input title="{{ __('Sender') }}" name="sender" />
                <x-textarea title="{{ __('Template') }}" name="template" />
            @elseif (($selectedModel->slug ?? '') == 'gatewayapi')
                <x-input title="{{ __('API Secret') }}" name="authSecret" />
                <x-input title="{{ __('API Key') }}" name="authkey" />
                <x-input title="{{ __('Token') }}" name="token" />
                <x-input title="{{ __('Sender') }}" name="sender" />
            @elseif (($selectedModel->slug ?? '') == 'termii')
                <x-input title="{{ __('API Key') }}" name="authkey" />
                <x-input title="{{ __('Sender') }}" name="sender" />
            @elseif (($selectedModel->slug ?? '') == 'africastalking')
                <x-input title="{{ __('Username') }}" name="token" />
                <x-input title="{{ __('API Key') }}" name="authkey" />
                <x-input title="{{ __('Sender / From') }}" name="sender" />
            @elseif (($selectedModel->slug ?? '') == 'hubtel')
                <x-input title="{{ __('Username') }}" name="authkey" />
                <x-input title="{{ __('Password') }}" name="token" type="password" />
                <x-input title="{{ __('Sender / From') }}" name="sender" />
            @endif
            {{--  custom code  --}}
            <x-checkbox title="{{ __('Active') }}" name="isActive" :defer="false" />


        </x-modal>
    </div>


    <div x-data="{ open: @entangle('showDetails') }">
        <x-modal confirmText="{{ __('Send') }}" action="testSMS">
            <p class="text-xl font-semibold">{{ __('Test SMS Gateway') }}</p>
            <x-input title="{{ __('Phone Number') }}" name="phoneNumber" placeholder="+2335574..." />
            <x-input title="{{ __('Message') }}" name="testMessage" placeholder="Hello World" />
        </x-modal>
    </div>
</div>
