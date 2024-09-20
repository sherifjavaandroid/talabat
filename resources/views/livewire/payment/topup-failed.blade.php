@section('title', __('Wallet Topup'))
<div class="">
    <div class="w-11/12 p-12 mx-auto mt-20 border rounded shadow md:w-6/12 lg:w-4/12">
        <x-heroicon-o-exclamation class="w-12 h-12 mx-auto text-yellow-400 md:h-24 md:w-24" />
        <p class="text-xl font-medium text-center">{{__('Failed')}}</p>
        <p class="text-sm text-center">{{ $msg ?? '' }}</p>
    </div>

    {{-- close --}}
    <p class="w-full p-4 text-sm text-center text-gray-500">{{__('You may close this window')}}</p>
</div>
