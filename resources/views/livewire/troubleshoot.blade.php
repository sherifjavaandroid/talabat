@section('title', __('Troubleshoot'))
<div>

    <x-baseview title="{{ __('Troubleshoot') }} ">
        {{-- sync boundaries --}}
        <div class="grid grid-cols-1 gap-6 mt-10 md:grid-cols-2 lg:grid-cols-3">
            {{-- countries --}}
            <x-settings-item title="{{ __('Sync Geo Boundaries') }}({{ __('Countries') }})"
                wireClick="fixCountiresBoundaries">
                <x-heroicon-o-globe class="w-5 h-5 {{ isRTL() ? 'ml-4' : 'mr-4' }}" />
                <x-slot name="info">
                    <div class="text-xs p-2">
                        <p>
                            {{ __('All the boundaries of the countries will be synced with the latest data. This helps with parcel delivery operation restrictions.') }}
                            <br />
                            <span class="text-red-500">
                                {{ __('These process might be longer depending on the size of your countries data') }}
                            </span>
                        </p>
                    </div>
                </x-slot>
            </x-settings-item>
            {{-- states --}}
            <x-settings-item title="{{ __('Sync Geo Boundaries') }}({{ __('States') }})" wireClick="fixStatesBoundaries">
                <x-heroicon-o-globe class="w-5 h-5 {{ isRTL() ? 'ml-4' : 'mr-4' }}" />
                <x-slot name="info">
                    <div class="text-xs p-2">
                        <p>
                            {{ __('All the boundaries of the States will be synced with the latest data. This helps with parcel delivery operation restrictions.') }}
                            <br />
                            <span class="text-red-500">
                                {{ __('These process might be longer depending on the size of your states data') }}
                            </span>
                        </p>
                    </div>
                </x-slot>
            </x-settings-item>
            {{-- cities --}}
            <x-settings-item title="{{ __('Sync Geo Boundaries') }}({{ __('Cities') }})"
                wireClick="fixCitiesBoundaries">
                <x-heroicon-o-globe class="w-5 h-5 {{ isRTL() ? 'ml-4' : 'mr-4' }}" />
                <x-slot name="info">
                    <div class="text-xs p-2">
                        <p>
                            {{ __('All the boundaries of the Cities will be synced with the latest data. This helps with parcel delivery operation restrictions.') }}
                            <br />
                            <span class="text-red-500">
                                {{ __('These process might be longer depending on the size of your cities data') }}
                            </span>
                        </p>
                    </div>
                </x-slot>
            </x-settings-item>
        </div>
        <div class="grid grid-cols-1 gap-6 mt-10 md:grid-cols-2 lg:grid-cols-3">
            {{-- fix image --}}
            <x-settings-item title="{{ __('Fix Image(Not Loading)') }}" wireClick="fixImage">
                <x-heroicon-o-photograph class="w-5 h-5 {{ isRTL() ? 'ml-4' : 'mr-4' }}" />
            </x-settings-item>
            {{-- fix image --}}
            <x-settings-item title="{{ __('Clear Cache') }}" wireClick="fixCache">
                <x-heroicon-o-desktop-computer class="w-5 h-5 {{ isRTL() ? 'ml-4' : 'mr-4' }}" />
            </x-settings-item>

            {{-- fix notification --}}
            <x-settings-item title="{{ __('Notification Error') }}" wireClick="fixNotification">
                <x-heroicon-o-bell class="w-5 h-5 {{ isRTL() ? 'ml-4' : 'mr-4' }}" />
            </x-settings-item>

            {{-- auto assignment --}}
            <x-settings-item title="{{ __('Auto assignment') }}" wireClick="fixAutoassignment">
                <x-heroicon-o-bell class="w-5 h-5 rtl:ml-4 ltr:mr-4" />
            </x-settings-item>

            {{-- referal code --}}
            <x-settings-item title="{{ __('User referral code') }}" wireClick="fixReferralCodes">
                <x-heroicon-o-cursor-click class="w-5 h-5 rtl:ml-4 ltr:mr-4" />
            </x-settings-item>

            {{-- role/permssions --}}
            <x-settings-item title="{{ __('User roles/permissions') }}" wireClick="fixUserPermission">
                <x-heroicon-o-identification class="w-5 h-5 rtl:ml-4 ltr:mr-4" />
            </x-settings-item>

            {{-- missing roles --}}
            <x-settings-item title="{{ __('User missing roles') }}" wireClick="fixMissingUserRoles">
                <x-heroicon-o-identification class="w-5 h-5 rtl:ml-4 ltr:mr-4" />
            </x-settings-item>

            {{-- Model Translations --}}
            <x-settings-item title="{{ __('Model Translations') }}" wireClick="fixModelTranslations">
                <x-heroicon-o-globe-alt class="w-5 h-5 rtl:ml-4 ltr:mr-4" />
            </x-settings-item>

            {{-- Product Translations --}}
            <x-settings-item title="{{ __('Product Translations') }}" wireClick="fixProductTranslations">
                <x-heroicon-o-globe-alt class="w-5 h-5 rtl:ml-4 ltr:mr-4" />
            </x-settings-item>

            {{-- Model Translations Fallback --}}
            <x-settings-item title="{{ __('Model Translations Fallback') }}" wireClick="fixTranslationFallback">
                <x-heroicon-o-globe-alt class="w-5 h-5 rtl:ml-4 ltr:mr-4" />
            </x-settings-item>

            {{-- fix arabic trans value --}}
            <x-settings-item title="{{ __('Fix Arabic Translation Values') }}" wireClick="fixArabicTranslationModels">
                <x-heroicon-o-globe-alt class="w-5 h-5 rtl:ml-4 ltr:mr-4" />
            </x-settings-item>

            {{-- generate firebase indexes --}}
            <x-settings-item title="{{ __('Generate Firestore Indexes Links') }}" wireClick="fixFirestoreIndexesLink">
                <x-heroicon-o-link class="w-5 h-5 rtl:ml-4 ltr:mr-4" />
            </x-settings-item>

            {{-- update driver records in firebase --}}
            <x-settings-item title="{{ __('Fix Regular Driver Firebase Issue') }}"
                wireClick="fixFirebaseDriverRecords">
                <x-heroicon-o-link class="w-5 h-5 rtl:ml-4 ltr:mr-4" />
                <x-slot name="info">
                    <div class="text-xs p-2">
                        <p>
                            {{ __('This will try fix the regular drivers record in the firebase that was setting wrongly') }}
                        </p>
                        <p class="text-red-500">
                            {{ __('These process might be longer depending on the size of your driver record') }}
                        </p>
                    </div>
                </x-slot>
            </x-settings-item>

            {{-- try clear browser tokens --}}
            <x-settings-item title="{{ __('Clear Browser Notification Tokens') }}" wireClick="fixWebBrowserFCMTokens">
                <x-heroicon-o-desktop-computer class="w-5 h-5 {{ isRTL() ? 'ml-4' : 'mr-4' }}" />
            </x-settings-item>


        </div>
    </x-baseview>

    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal>
            <p class="text-xl font-semibold">{{ __('Auto Assignment checks') }}</p>
            <hr class="my-2" />
            @foreach ($autoAssignmentChecks as $key => $autoAssignmentCheck)
                <div class="flex items-center py-2 my-2 ">

                    <div class="w-6/12">{{ Str::title(str_ireplace('_', ' ', $key)) }}</div>
                    <div class="w-full h-1 mx-2 border-b border-dashed"></div>
                    <div
                        class="text-white rounded-full p-1 {{ $autoAssignmentCheck ? 'bg-green-500' : 'bg-red-500' }}">
                        @if ($autoAssignmentCheck)
                            <x-heroicon-o-check class="w-4 h-4 " />
                        @else
                            <x-heroicon-o-x class="w-4 h-4" />
                        @endif
                    </div>

                </div>
            @endforeach
        </x-modal>
    </div>


</div>
