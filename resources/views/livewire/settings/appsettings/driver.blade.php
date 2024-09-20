<x-form noClass="true" action="saveDriverSettings">
    <div class='grid grid-cols-1 gap-4 mb-10 md:grid-cols-2 lg:grid-cols-3'>

        <div class="block mt-4 text-sm">
            <p>{{ __('Allow taxi driver to switch to regular driver') }}</p>
            <x-checkbox title="{{ __('Enable') }}" name="enableDriverTypeSwitch" :defer="true" />
        </div>

        <div class="block mt-4 text-sm">
            <x-input title="{{ __('Accept Time Duration(seconds)') }}" name="alertDuration" type="number" />
        </div>

        <div class="block mt-4 text-sm">
            <x-input title="{{ __('Driver order search radius') }}(KM)" name="driverSearchRadius" type="number" />
        </div>

        <div class="block mt-4 text-sm">
            <x-input title="{{ __('Driver Max Acceptable Order') }}" name="maxDriverOrderAtOnce" type="number" />
        </div>
        <div class="block mt-4 text-sm">
            <x-input title="{{ __('Number of driver to be notified of new order') }}"
                name="maxDriverOrderNotificationAtOnce" type="number" />
        </div>
        <div class="block mt-4 text-sm">
            <x-input title="{{ __('Resend rejected auto-assignment notification(minutes)') }}"
                name="clearRejectedAutoAssignment" type="number" />
        </div>

        <div class="block mt-4 text-sm">
            <x-input title="{{ __('Emergency Contact for drivers and customers') }}" name="emergencyContact" />
        </div>

        {{-- Location updating --}}
        <div class="block mt-4 text-sm">
            <x-input title="{{ __('Location Update Distance(Meter)') }}" name="distanceCoverLocationUpdate" />
        </div>
        <div class="block mt-4 text-sm">
            <x-input title="{{ __('Location Update Time(Seconds)') }}" name="timePassLocationUpdate" />
        </div>
        <div class="block mt-4 text-sm">
            <x-select title="{{ __('Auto-Assignment Status') }}" :options="$statuses ?? []" name="autoassignmentStatus" />
        </div>
        <div class="block mt-4 text-sm">
            <x-select title="{{ __('Deliver Auto-Assignment Alert') }}" :options="$systemTypes ?? []" name="autoassignmentsystem"
                :defer="false" />
            <div class="mt-1 text-xs {{ $autoassignmentsystem == 0 ? '' : 'hidden' }}">
                <p>
                    <span class="font-bold">{{ __('Matching via server') }}:</span>
                    {{ __('The server fetches order ready for assignment, also fetch nearby driver base on order location, then notify each paired driver about new order. All from the server') }}
                </p>
                <p class="font-bold underline">{{ __('Pro') }}</p>
                <p>{{ __('Fewer read/write to firebase firestore. Less/No extra billing cost on firebase.') }}
                </p>
                <p class="font-bold underline">{{ __('Con') }}</p>
                <p>{{ __('Sometimes the order alert can take a bit longer to deliver to the driver device.') }}
                </p>
            </div>
            <div class="mt-1 text-xs {{ $autoassignmentsystem != 0 ? '' : 'hidden' }}">
                <p class="mb-1">
                    <span class="font-bold">{{ __('On Driver Device') }}:</span>
                    {{ __('The server fetch order ready for assignment and push it to firestore, then each driver device will fetch the order and check if they are within range to handle the order') }}
                </p>
                <p class="font-bold underline">{{ __('Pro') }}</p>
                <p>{{ __('Driver alert order is store on the firestore for the driver app to read from. Its faster in most cases') }}
                </p>
                <p class="font-bold underline">{{ __('Con') }}</p>
                <p>{{ __('Sometimes not accurate. Addes to a lot of firebase read and write, making your billing go up depending on the volume of orders') }}
                </p>
            </div>
        </div>
        <div class="block mt-4 text-sm {{ $autoassignmentsystem == 0 ? '' : 'hidden' }}">
            <x-select title="{{ __('Fetch Nearby Driver Location') }}" :options="$fetchDriversystemTypes ?? []" name="fetchNearbyDriverSystem"
                :defer="false" />
            <div class="mt-1 text-xs {{ $fetchNearbyDriverSystem == 0 ? '' : 'hidden' }}">
                <p>
                    <span class="font-bold">{{ __('Server Fetch API') }}:</span>
                    {{ __('Direct try fetch nearby drivers from firestore from the server via api') }}
                </p>
                <p class="font-bold underline">{{ __('Pro') }}</p>
                <p>{{ __('No need for uploading firebase functions. No extra billing cost on firebase.') }}
                </p>
                <p class="font-bold underline">{{ __('Con') }}</p>
                <p>{{ __('Might not always be accurate. Need to create extra indexes, check the firebase section in the project documentation for the needed firebase indexes') }}
                </p>
            </div>
            <div class="mt-1 text-xs {{ $fetchNearbyDriverSystem != 0 ? '' : 'hidden' }}">
                <p class="mb-1">
                    <span class="font-bold">{{ __('Firebase Cloud Function') }}:</span>
                    {{ __('Fecth driver via the firebase functions.') }}
                </p>
                <p class="font-bold underline">{{ __('Pro') }}</p>
                <p>{{ __('More accurate. No need to create extra indexes') }}
                </p>
                <p class="font-bold underline">{{ __('Con') }}</p>
                <p>{{ __('Addes to a lot of firebase read and write, making your billing go up depending on the volume of orders') }}
                </p>
            </div>
        </div>
    </div>
    {{-- save button --}}
    <div class="flex justify-end mt-4">
        <x-buttons.primary class="ml-4">
            {{ __('Save') }}
        </x-buttons.primary>
    </div>
</x-form>
