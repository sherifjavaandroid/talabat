@section('title', 'Become A Partner')
<div>
    @if (setting('partnersCanRegister', true))
        @php
            $img = getValidValue(setting('loginImage'), asset('images/login.jpeg'));
            if (!empty($img)) {
                $bg = "bg-[url('" . $img . "')]";
            } else {
                $bg = 'bg-gray-50';
            }
        @endphp
        <div class="flex items-center min-h-screen {{ $bg }} md:flex ">
            <div class="py-4 mx-auto my-10 overflow-y-auto">
                <div
                    class="w-11/12 h-full max-w-xl mx-auto my-12 overflow-hidden bg-white rounded-lg shadow-lg shadow-slate-400 md:my-auto md:max-w-2xl ">
                    <div class="flex flex-col overflow-y-auto md:flex-row">
                        <div class="flex items-center justify-center w-full p-6 sm:p-12 ">
                            <div class="w-full">
                                <div class="flex items-center justify-between">
                                    <h1 class="w-full mb-4 text-3xl font-bold text-gray-700 uppercase">
                                        {{ __('Become a partner') }}
                                    </h1>
                                    <livewire:select.language-selector />
                                </div>
                                {{-- tabs  --}}
                                <div id="tab_wrapper">
                                    @include('livewire.auth.register.partials.driver')
                                    <p class="my-4 text-center">
                                        {{ __('Already have an account?') }} <a href="{{ route('login') }}"
                                            class="ml-2 font-bold text-primary-500 text-md">{{ __('Login') }}</a>
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Registration disabled  --}}
        <div class="w-full p-12 mx-auto my-12 rounded-sm shadow md:w-5/12 lg:w-4/12 ">
            <p class="mb-2 text-2xl font-semibold">{{ __('Registration Page Not available') }}</p>
            <p class="text-sm">
                {{ __('Partner account registration is currently unavailable. Please stay tune/contact support regarding further instruction about registering for a partners account. Thank you') }}
            </p>
            <p class='mt-4 text-center'><a href="{{ route('contact') }}"
                    class="underline text-primary-600">{{ __('Contact Us') }}</a></p>
        </div>
    @endif
    {{-- loading --}}
    <x-loading />
    @include('layouts.partials.phoneselector')
</div>
