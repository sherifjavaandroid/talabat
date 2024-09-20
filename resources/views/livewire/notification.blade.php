@section('title', __('Notification'))
<div>

    <x-baseview title="{{ __('Send Notification') }}" showNew="true">
        <livewire:tables.push-notification-table />
    </x-baseview>


    {{-- new form --}}
    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Send') }}" action="sendNotification">
            <p class="text-xl font-semibold">{{ __('Create Option') }}</p>

            <x-input title="{{ __('Title') }}" name="headings" />
            <x-label title="{{ __('Message') }}" />
            <textarea wire:model.defer="message" class="w-full h-40 p-2 mt-1 border rounded"></textarea>
            @error('message')
                <span class="mt-1 text-xs text-red-700">{{ $message }}</span>
            @enderror
            {{-- receiver --}}
            <div class="grid grid-cols-2 gap-2">
                <x-checkbox title="{{ __('All') }}" name="allReceiver" description="Send to all users"
                    :defer="false" />
                <x-checkbox title="{{ __('Custom') }}" name="customReceiver" description="Send Directly to roles"
                    :defer="false" />
            </div>
            <div class="flex flex-wrap mt-2 mb-6 space-x-5" x-data="{ open: @entangle('customReceiver') }" x-show="open">
                @foreach ($roles as $key => $role)
                    <x-checkbox title="{{ $role->name }}" name="customReceiverRoles.{{ $key }}"
                        value="{{ $role->name }}" :defer="false" />
                @endforeach
            </div>
            <x-media-upload title="{{ __('Image ( Ratio 3:1 )') }}" name="photo" :photo="$photo" :photoInfo="$photoInfo"
                types="PNG or JPEG" rules="image/*" />

            <hr class="my-2" />
            {{-- product/vendor attachment --}}
            <div class="grid grid-cols-2 gap-2">
                <x-checkbox title="{{ __('Product') }}" name="useProduct"
                    description="{{ __('Attach product to notification') }}" :defer="false" />
                <x-checkbox title="{{ __('Vendor') }}" name="useVendor"
                    description="{{ __('Attach vendor to notification') }}" :defer="false" />
                <x-checkbox title="{{ __('Service') }}" name="useService"
                    description="{{ __('Attach service to notification') }}" :defer="false" />
            </div>
            {{-- products --}}
            <div class="{{ $useProduct ? 'block' : 'hidden' }}">
                <livewire:component.autocomplete-input title="{{ __('Product') }}" column="name" model="Product"
                    errorMessage="{{ $errors->first('product_id') }}" emitFunction="autocompleteProductSelected" />
            </div>
            {{-- vendors --}}
            <div class="{{ $useVendor ? 'block' : 'hidden' }}">
                <livewire:component.autocomplete-input title="{{ __('Vendor') }}" column="name" model="Vendor"
                    errorMessage="{{ $errors->first('vendor_id') }}" emitFunction="autocompleteVendorSelected" />
            </div>
            {{-- service --}}
            <div class="{{ $useService ? 'block' : 'hidden' }}">
                <livewire:component.autocomplete-input title="{{ __('Service') }}" column="name" model="Service"
                    errorMessage="{{ $errors->first('service_id') }}" emitFunction="autocompleteServiceSelected" />
            </div>

        </x-modal>
    </div>


</div>
