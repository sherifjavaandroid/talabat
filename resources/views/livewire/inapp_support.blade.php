@section('title', __('In-App Support') )
<div>

    <x-baseview title="{{ __('In-App Support') }}">

        <x-form action="save">
            <p>
                <span class="font-bold">Link:</span>
                <br />
                <a href="{{ url(route('support.chat')) }}" target="_blank" class="underline">{{ url(route('support.chat')) }}</a>
            </p>
            <div class="w-full md:w-4/5 lg:w-5/12">

                <div class="mb-4">
                    <x-label title="{{ __('Link/Widget Code/Iframe') }}"/>
                </div>
                <textarea id="inappSupportCode" wire:model.defer="inappSupportCode" class="w-full h-64 p-2 border border-black rounded-sm"></textarea>
                <x-buttons.primary title="{{ __('Save Changes') }}" />

            <div>
        </x-form>

    </x-baseview>



</div>




