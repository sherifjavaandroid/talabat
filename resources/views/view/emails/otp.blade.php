@extends('view.emails.raw_plain')

@section('body')
    <div class="bg-gray-50 py-8">
        <div class="w-full p-12 md:w-10/12 lg:w-6/12 mx-auto bg-white rounded-sm">
            {{-- logo --}}
            <img src="{{ url(setting('websiteLogo', asset('images/logo.png'))) }}" alt="logo"
                class="max-h-20 mr-auto mt-4 mb-10" />
            {{-- intro --}}
            <div>

                <p class="text-sm">
                    {{ __('Hello') }} {{ $user->name }},
                </p>
                <p class="font-bold text-2xl my-4"> {{ __('Login/Reset Password') }}</p>
                <p>
                    {{ __("It looks like you're trying to log in to your account or reseting account password. To complete the process, please use the one-time password (OTP) below:") }}
                </p>
            </div>

            {{-- account details --}}
            <div class="bg-primary-500 p-8 text-center my-6 text-2xl border border-gray-200">
                <p> {{ $otp }}</p>
            </div>

            <p class="font-thin text-sm"> {{ __('This code expires in 5 minutes.') }}</p>
            {{-- call to action --}}
            <div class="flex justify-between items-center">
                @if (!empty(setting('androidDownloadLink', '')) || !empty(setting('iosDownloadLink', '')))
                    <p>{{ __('Download the app and enjoy purchases') }}</p>
                @endif
                <div class="flex items-end space-x-2">
                    {{-- android --}}
                    @if (!empty(setting('androidDownloadLink', '')))
                        <a target="_blank" href="{{ setting('androidDownloadLink', '') }}"
                            style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;color:{{ setting('websiteColor', '#21a179') }};font-size:14px"><img
                                class="adapt-img"
                                src="https://icfcn.stripocdn.email/content/guids/CABINET_e48ed8a1cdc6a86a71047ec89b3eabf6/images/82871534250557673.png"
                                alt="Google Play"
                                style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"
                                title="Google Play" width="133"></a>
                    @endif
                    {{-- ios --}}
                    @if (!empty(setting('iosDownloadLink', '')))
                        <a target="_blank" href="{{ setting('iosDownloadLink', '') }}"
                            style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;color:{{ setting('websiteColor', '#21a179') }};font-size:14px"><img
                                class="adapt-img"
                                src="https://icfcn.stripocdn.email/content/guids/CABINET_e48ed8a1cdc6a86a71047ec89b3eabf6/images/92051534250512328.png"
                                alt="Apple App Store"
                                style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"
                                title="Apple App Store" width="133"></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
