<div>
    <x-modal-lg confirmText="{{ __('Save') }}" action="saveDays" :clickAway="false">
        <p class="text-xl font-semibold">{{ __('Set vendor open/close time') }}</p>
        <div class="space-y-1">
            <p class="text-red-500 text-sm">{{ __('Note') }}</p>
            <p class="text-xs text-gray-600">
                {{ __('Vendor must setup open and close time before order scheduling can work, even if the vendor enabled the option to allow customer to schedule order.') }}
            </p>
            <p class="text-xs text-gray-600">
                {{ __('Vendor also need to setup open/close time for system to auto close/open vendor to accept order.') }}
            </p>
        </div>
        <div class="flex items-center py-3 mt-5 gap-2 border-t border-b">
            <div class="w-4/12">{{ __('Day') }}</div>
            <div class="w-4/12">
                {{ __('Openning Time') }}
            </div>
            <div class="w-4/12 pl-2">
                {{ __('Closing Time') }}
            </div>
        </div>
        @if (!empty($workingDays))
            @foreach ($workingDays as $key => $workingDay)
                <div class="flex items-start my-1 pb-3 gap-2 border-b">
                    <div class="w-4/12">
                        <x-select :options="$days ?? []" name="workingDays.{{ $key }}.day_id"
                            selected="{{ $workingDays[$key]['day_id'] }}" />
                    </div>
                    <div class="w-4/12">
                        <x-input title="" type="time" name="workingDays.{{ $key }}.open"
                            noMargin="true" />
                    </div>
                    <div class="w-4/12 pl-2">
                        <x-input title="" type="time" name="workingDays.{{ $key }}.close"
                            noMargin="true" />
                    </div>
                    <div class="flex items-center ml-2 space-x-2">
                        <x-buttons.plain title="{{ __('Delete') }}" wireClick="removeDay('{{ $key }}')"
                            bgColor="bg-red-500">
                            <x-heroicon-o-trash class="w-5 h-5" />
                        </x-buttons.plain>
                    </div>
                </div>
            @endforeach
        @endif
        <x-buttons.primary title="{{ __('New') }}" type="button" wireClick="addNewTiming" />
    </x-modal-lg>
</div>
