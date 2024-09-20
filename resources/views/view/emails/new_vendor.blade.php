@extends('view.emails.plain')
@section('body')
<img src="https://sketchvalley.com/wp-content/uploads/2021/07/cs-details.jpg" />
<div class="my-8">
    <p class="mb-8 text-3xl font-semibold tracking-wider text-center">{{ __('You just created a new account. Congrats!') }}</p>
    <p class="mt-1 text-lg font-light">{{ __('Welcome') }} <span class="font-bold">{{ $vendor->name }}</span></p>
    <p class="my-4 text-lg font-light">
        {{ __('Starting a business is no easy task, but there is no better place to start than') }} {{ env('APP_NAME','') }}. {{ __("We'll handle everything from marketing and payments to secure checkout and shipping so you can focus on what you love.") }}
    </p>
    <div class="my-2">
        <p>
            {{ __("Here's where you can login to") }} {{ env('APP_NAME','') }} {{ __("(be sure to bookmark this page).") }}
        </p>
        <p>
            <a href="{{ route('login') }}" class="text-blue-900 underline">{{ route('login') }}</a>
        </p>
    </div>

</div>
@endsection
