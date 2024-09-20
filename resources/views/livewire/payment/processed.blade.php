@section('title', __('Payment Update'))
<div class="">
    <div class="w-11/12 p-12 mx-auto mt-20 border rounded shadow md:w-6/12 lg:w-4/12">

        {{ svg($icon ?? 'heroicon-o-exclamation')->class('w-12 h-12 mx-auto text-primary-400 md:h-24 md:w-24') }}
        <p class="text-xl font-medium text-center">{{ !empty($title) ? $title : __('Already processed') }}</p>
        <p class="text-sm text-center">
            {{ !empty($message) ? $message : __('Payment has already been processed. Please check your app to see more details about the payment') }}
        </p>
    </div>

    {{-- close --}}
    <p class="w-full p-4 text-sm text-center text-gray-500">{{ __('You may close this window') }}</p>
    <x-buttons.close />
</div>
