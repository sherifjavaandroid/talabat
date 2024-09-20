@extends('view.emails.plain')
@section('body')
<img src="https://sketchvalley.com/wp-content/uploads/2021/02/supportteam-details.jpg" />
<div class="my-8">
    <p class="mb-8 text-3xl font-semibold tracking-wider text-center">{{ __('Account Updated') }}</p>
    <p class="mt-1 text-lg font-light">{{ __('Hi') }} <span class="font-bold">{{ $vendor->name }}</span></p>
    @if ($vendor->is_active)
        <p class="my-4 text-lg font-light">
            {{ __("Your account has been activated. You can now manage your account via your manager account.") }}
        </p>
        <div class="my-2">
            <p>
                {{ __("Here's where you can login to") }} {{ env('APP_NAME','') }} {{ __("(be sure to bookmark this page).") }}
            </p>
            <p>
                <a href="{{ route('login') }}" class="text-blue-900 underline">{{ route('login') }}</a>
            </p>
        </div>
    @else
        <p class="my-4 text-lg font-light">
            {{ __("Your account has just been deactivated. You will not be able to accept order from customers.") }}
            @if ($vendor->use_subscription)
            {{ __("This can be caused by an expired subscription. You would need to login and subscribe to a Subscription plan for your account be be activated again.") }}
            @endif
        </p>
        <div class="my-2">
            <p>
                {{ __("Here's where you can contact support for more info") }} 
            </p>
            <p>
                <a href="{{ route('contact') }}" class="text-blue-900 underline">{{ route('contact') }}</a>
            </p>
        </div>
    @endif



</div>
@endsection
