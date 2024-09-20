@section('title', __('Server Settings'))
<div>

    <x-baseview title="{{ __('Server Settings') }}">



        <div class='grid grid-cols-1 gap-10 md:grid-cols-2'>
            <div>
                <x-form action="saveMailSettings">
                    <p class="text-2xl font-body">{{ __('Mail Setting') }}</p>
                    <div class="p-2 my-2 bg-gray-100 border border-gray-300 rounded">
                        <p>{{ __('Popular smtp servers') }}</p>
                        <span class="mx-1"><a href="https://www.mailgun.com/" target="_blank"
                                class="text-xs underline">mailgun.com</a></span>
                        <span class="mx-1"><a href="https://www.sendgrid.com/" target="_blank"
                                class="text-xs underline">sendgrid.com</a></span>
                        <span class="mx-1"><a href="https://www.sendinblue.com/" target="_blank"
                                class="text-xs underline">sendinblue.com</a></span>
                        <span class="mx-1"><a href="https://www.mailjet.com/" target="_blank"
                                class="text-xs underline">mailjet.com</a></span>
                        <span class="mx-1"><a href="https://www.sparkpost.com/" target="_blank"
                                class="text-xs underline">sparkpost.com</a></span>
                    </div>

                    {{--  --}}
                    @production
                        <x-input title="Host" name="mailHost" />
                        <x-input title="Port" name="mailPort" />
                        <x-input title="Username" name="mailUsername" />
                        <x-input title="Password" name="mailPassword" type="password" />
                        <x-input title="From Email" name="mailFromAddress" />
                        <x-input title="From Name" name="mailFromName" />
                        <x-input title="Encryption" name="mailEncryption" placeholder="tls" />
                        <div class="h-1 my-4 bg-gray-500 border-3"></div>
                        <x-buttons.primary title="{{ __('Save Changes') }}" />
                    @else
                        <div class="p-2 my-2 bg-gray-100 border border-gray-300 rounded">
                            <p>{{ __('Mail settings are disabled in development mode') }}</p>
                        </div>
                    @endproduction
                </x-form>
            </div>
            {{-- Test email seetings is working --}}
            <div>
                <x-form action="saveSystemMailSettings">
                    <p class="text-2xl font-body">{{ __('System Contact Mail') }}</p>
                    <x-input title="{{ __('Email') }}" name="systemEmail" type="email" />
                    <p class="text-xs italic">
                        <span class="text-sm font-bold text-red-500">{{ __('Note') }}:</span>
                        {{ __('All system related action will be sent to this email. Action like new user,vendor completed order etc.') }}
                    </p>
                    <x-buttons.primary title="{{ __('Save') }}" />
                </x-form>

                <x-form action="testMailSettings">
                    <p class="text-2xl font-body">{{ __('Test Mail Setting') }}</p>
                    <x-input title="{{ __('Email') }}" name="testEmail" type="email" />
                    <x-input title="{{ __('Subject') }}" name="testSubject" />
                    <x-textarea title="{{ __('Body') }}" name="testBody" />
                    <x-buttons.primary title="{{ __('Send') }}" />
                </x-form>
            </div>
        </div>

    </x-baseview>

</div>
