<div>
    <div class="visible md:invisible">
        <div class="flex items-center">
            <button id="closePageBtn"
                class="inline-flex items-center px-4 py-2 mx-auto space-x-1 text-sm font-thin text-gray-800 bg-gray-300 rounded hover:bg-gray-400">
                <x-heroicon-o-x class="w-4 h-4" />
                <span>{{ __('Close') }}</span>
            </button>
        </div>
    </div>


    @push('scripts')
        <script src="{{ asset('js/mobile-communicator.js') }}"></script>
    @endpush
</div>
