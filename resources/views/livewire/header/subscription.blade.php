<div>
    @hasanyrole('manager')
        @if($subExpired)
            <div class="p-2 py-4 text-center text-white bg-red-400">
                Your subscription has expired. <a href="{{ route('my.subscribe') }}"
                    class="p-2 text-black bg-white rounded hover:underline hover:shadow">Subscribe</a>
            </div>
        @endif
    @endhasanyrole

</div>
