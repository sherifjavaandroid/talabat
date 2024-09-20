@section('title', __('Profile'))
<div>

    <x-baseview title="{{ __('Update Profile') }}">

        <div class="block md:space-x-10 md:flex">
            <div class="w-full lg:w-8/12">
                <x-form action="updateProfile">
                    <p class="font-semibold">{{ __('Update Profile information') }}</p>
                    <x-media-upload title="{{ __('Profile') }}" name="photo" preview="{{ Auth::user()->photo }}"
                        :photo="$photo" :photoInfo="$photoInfo" types="PNG or JPEG" rules="image/*" />
                    <x-input title="{{ __('Name') }}" type="text" name="name" />
                    <x-input title="{{ __('Email') }}" type="email" name="email" />
                    {{-- <x-input title="{{ __('Phone') }}" type="tel" name="phone" /> --}}
                    <x-phoneselector />
                    <x-buttons.primary title="{{ __('Update') }}" />

                </x-form>
            </div>

            {{-- Password Change --}}
            <div class="w-full lg:w-8/12">
                <x-form action="changePassword">
                    <p class="font-semibold">{{ __('Change Password') }}</p>
                    <x-input title="{{ __('Current Password') }}" type="password" name="current_password" />
                    <x-input title="{{ __('New Password') }}" type="password" name="new_password" />
                    <x-input title="{{ __('Confirm New Password') }}" type="password"
                        name="new_password_confirmation" />
                    <x-buttons.primary title="{{ __('Update') }}" />

                </x-form>
            </div>

            <div>
    </x-baseview>


</div>
@include('layouts.partials.phoneselector')
