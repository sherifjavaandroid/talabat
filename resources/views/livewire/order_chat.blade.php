@section('title', __('Order Chat') )
<div>

    <div class="shadow-lg shadow-zinc-400 block h-[85vh] bg-gray-200 border overflow-clip border-gray-200 rounded-lg md:flex">
        <div class="w-full border-r h-90vh md:w-3/12 ">
            @foreach ($chatTypes ?? [] as $chatType)
            @php
            $chatTypeCode = ($chatType['code'] ?? '');
            $selected = $selectedChatType == $chatTypeCode;
            $selectedClasses = $selected ? 'bg-primary-600 text-white font-semibold':'';
            @endphp
            <div class="p-2 bg-gray-100 border-b border-gray-200 hover:bg-primary-600 hover:text-white hover:font-semibold hover:cursor-pointer {{ $selectedClasses }}" wire:click="changeChatType('{{ $chatTypeCode }}')">
                {{ __($chatType['name'] ?? $chatType ?? '') }}
            </div>
            @endforeach
        </div>
        <div class="w-full py-2 bg-white">
            <div class="px-4 py-2 text-lg font-semibold border-b">
                {{ __('Chats') }}
            </div>
            <div id="chatListDiv">
                <div id="chatList" class="w-full flex flex-col p-4 space-y-4 overflow-scroll h-[78vh]">
                </div>
            </div>
            @empty($chats)
            <div class="flex items-center justify-center min-h-full" id="emptyChat">
                <div>
                    <img src="{{ asset('images/empty_chat.png') }}" class="w-32 h-32 mx-auto my-4 centered" />
                    @empty($selectedChatType)
                    <p class="text-lg font-medium">
                        {{ __('No chat listed here, please select a chat type on the side of this box') }}
                    </p>
                    @else
                    <p class="text-lg font-medium text-center">
                        {{ __('No chat from the selected chat type.') }}
                        <br />
                        {{ __('When new chat are send, you can refresh this to see them list here') }}
                    </p>
                    <x-buttons.primary title="Refresh" wireClick='fetchChats()' />
                    @endempty

                </div>
            </div>
            @endempty
        </div>
    </div>
    <x-loading />
</div>

@push('scripts')
<script src="{{ asset('js/order-chat.js') }}"></script>
@endpush
