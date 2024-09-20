<button
    class="flex items-center p-2 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 border border-transparent rounded-lg bg-cyan-600 active:bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:shadow-outline-blue"
    wire:click="$emitUp('initiateLoginAs', {{ $model->id }} ) "
    title="{{__('Login As Manager')}}">
    <x-heroicon-o-login class="w-5 h-5"/>
</button>
