<form wire:submit.prevent="signUp">
    @csrf
    <x-input title="{{ __('Name') }}" name="name" placeholder="John" />
    <div class="grid grid-cols-1 gap-0 md:gap-4 md:grid-cols-2">
        <x-input title="{{ __('Email') }}" name="email" placeholder="info@mail.com" />
        <x-phoneselector />
    </div>
    <x-input title="{{ __('Login Password') }}" name="password" type="password" placeholder="**********************" />
    <x-input title="{{ __('Referral Code') }}" name="referalCode" placeholder="" />
    <hr class="my-4" />
    <x-select :options="$driverTypes ?? []" name="driverType" title="{{ __('Driver Type') }}" :defer="false" />
    @include('livewire.auth.register.partials.taxi_driver')
    <hr class="my-4" />
    <p class="font-light">{{ __('Documents') }}</p>
    <div>
        {!! setting('page.settings.driverDocumentInstructions', '') !!}
    </div>
    <livewire:component.multiple-media-upload types="PNG or JPEG" fileTypes="image/*"
        emitFunction="driverDocumentsUploaded" max="{{ setting('page.settings.driverDocumentCount', 3) }}" />
    <x-input-error message="{{ $errors->first('driverDocuments') }}" />
    <hr class="my-4" />
    <div class="flex items-center my-3">
        <x-checkbox name="agreedDriver" :defer="false" :noMargin="true"> <span>{{ __('I agree with') }} <a
                    href="{{ route('terms') }}" target="_blank"
                    class="font-bold text-primary-500 hover:underline">{{ __('terms and conditions') }}</a></span>
        </x-checkbox>
    </div>
    <x-form-errors />
    <x-buttons.primary title="{{ __('Sign Up') }}" />
</form>
@push('scripts')
    <script>
        $(document).ready(function() {
            //WHEN THE IFRAME HAS LOADED
            $('iframe').ready(function() {
                //timeout to allow iframe to load
                setTimeout(function() {
                    //SET THE HEIGHT OF THE IFRAME
                    var height = $('#iframe').contents().find('body').height();
                    //if its zero
                    if (height > 30) {
                        height += 30;
                    }
                    $('#iframe').height(height);
                }, 100);
            });
        });
    </script>
@endpush
