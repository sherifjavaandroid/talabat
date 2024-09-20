@section('title',__('System Upgrade'))
<div>

    <x-baseview title="{{ __('System Update') }}">

        <div class="grid grid-cols-1 gap-6 mt-10 md:grid-cols-2 lg:grid-cols-3">

            {{-- update --}}
            <x-settings-item title="{{ __('Update System') }}" wireClick="upgradeAppSystem">
                <x-heroicon-o-cloud-upload class="w-5 h-5 mr-4" />
            </x-settings-item>

            {{-- roll back --}}
            <x-settings-item title="{{ __('Roll Back Update') }}" wireClick="showEditModal">
                <x-heroicon-o-sort-descending class="w-5 h-5 mr-4" />
            </x-settings-item>

            {{-- terminal --}}
            @production
            <x-settings-item title="{{ __('Terminal') }}" wireClick="showCreateModal">
                <x-heroicon-o-terminal class="w-5 h-5 mr-4" />
            </x-settings-item>
            @endproduction

            {{-- update --}}
            {{--  <x-settings-item title="{{ __('Update Remotely') }}" wireClick="getRemoteVersions">
                <x-heroicon-o-cloud-download class="w-5 h-5 mr-4" />
            </x-settings-item>  --}}

        </div>


        {{-- rollback upgrade  --}}
        <div x-data="{ open: @entangle('showEdit') }">
            <x-modal confirmText="{{ __('Run') }}" action="rollBackUpgrade">
                <p class="text-xl font-semibold">{{ __('Rollback Version') }}</p>
                <p class="my-5 text-sm font-semibold text-red-500">{{ __('This is only to rollback the versison code, so you can re-run the upgrade script again') }}</p>
                <x-select title="{{ __('Version Code') }}" :options='$verisonCodes' name="verison_code" :defer="true" />
            </x-modal>
        </div>

        {{-- normal upgrade  --}}
        <div x-data="{ open: @entangle('showCreate') }">
            <x-modal confirmText="{{ __('Run') }}" action="runTerminalCommand">
                <p class="text-xl font-semibold">{{ __('Terminal') }}</p>
                <p class="my-5 text-sm font-semibold text-red-500">{{ __('Be very careful when using terminal. Only run verified commands here') }}</p>
                <textarea class="w-full h-56 p-2 text-white bg-gray-800 border-gray-200" placeholder="{{ __('Use with care') }}" wire:model.defer="terminalCommand"></textarea>
                <div class="py-2 text-sm text-red-500 border-t">
                    {{ $terminalError }}
                </div>
            </x-modal>
        </div>


        {{-- remotely download upgrade  --}}
        <div x-data="{ open: @entangle('showAssign') }" >
            <x-modal :withForm="false" :clickAway="false" >

                <div x-data="{opened: false}">
                    <p class="flex text-xl font-medium">
                        <span class="flex-grow">{{ __('Configuration') }}</span>
                        <span x-show="opened">
                            <x-buttons.plain onClick="opened = false">
                                <x-heroicon-o-chevron-up class="w-3 h-4" />
                            </x-buttons.plain>
                        </span>
                        <span x-show="!opened">
                            <x-buttons.plain onClick="opened = true">
                                <x-heroicon-o-chevron-down class="w-3 h-4" />
                            </x-buttons.plain>
                        </span>
                    </p>
                    <div x-show="opened">
                        <x-form action="saveCodecanyon">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <x-input title="Codecanyon Purchase Code" name="purchase_code" />
                                <x-input title="Codecanyon Buyer Username" name="buyer_username" />
                            </div>
                            <x-buttons.primary title="{{ __('Save') }}" />
                        </x-form>
                    </div>
                </div>
                <hr class="my-2"/>
                <p class="text-xl font-semibold">{{ __('Download Update Remotely') }}</p>
                {{ __('List all available updates and a download button') }}
                <hr/>
                @foreach ($downloadableVersions ?? [] as $downloadableVersion)
                <div class="flex items-center p-2 border-b">
                    <p class="flex-grow text-sm font-semibold">{{ $downloadableVersion['version'] }}</p>
                    <a href="{{ $downloadableVersion['link'] }}" target="_blank" download class="px-2 py-1 text-sm text-white bg-green-600 rounded-full hover:shadow">
                        {{ __("Download") }}
                    </a>
                </div>
                @endforeach
                <x-buttons.primary title="{{ __('Refresh') }}" wireClick="getRemoteVersions"  />

            </x-modal>
        </div>


    </x-baseview>


</div>
