@extends('view.emails.raw_plain')

@section('body')
    <div class="bg-gray-100">
        <div class="w-full p-12 md:w-10/12 lg:w-6/12 mx-auto">
            {{-- logo --}}
            <img src="{{ url(setting('websiteLogo', asset('images/logo.png'))) }}" alt="logo"
                class="w-24 h-24 mx-auto mt-10 mb-8" />
            {{-- intro --}}
            <div>
                <p class="font-bold text-2xl mb-2"> {{ __('Welcome') }}</p>
                <p>
                    {{ __('Hi') }}
                    <span class="font-bold">{{ $user->name }},</span>
                    {{ __('we’re glad you’re here! You have just created an account with') }}
                    <b>{{ env('APP_NAME') }}. </b>
                </p>
            </div>

            {{-- account details --}}
            <div class="bg-white p-8 text-center my-6 border border-gray-200">

                <p class="mb-3"> {{ __('Your account information:') }}</p>
                <p><span class="font-bold">{{ __('Name:') }}</span> {{ $user->name }}</p>
                <p><span class="font-bold">{{ __('Email:') }}</span> {{ $user->email }}</p>
                @if ($password)
                    <div class="my-1">
                        <p class="text-sm italic">
                            {{ __('Your generated account password below, feel free to change it from the system') }}</p>
                        <p><span class="font-bold">{{ __('Password:') }}</span> {{ $password ?? '' }}</p>
                    </div>
                @endif
            </div>

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
