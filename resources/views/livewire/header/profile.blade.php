<div>
    <header class="z-10 py-4 bg-white shadow-md ">
        <div
            class="container flex items-center justify-between h-full px-6 mx-auto text-primary-600 dark:text-primary-300">
            <!-- Mobile hamburger -->
            <button class="p-1 mr-5 -ml-1 rounded-md md:hidden focus:outline-none focus:shadow-outline-primary"
                @click="toggleSideMenu" aria-label="Menu">
                <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>
            {{-- hide/show menu on desktop --}}
            <button class="hidden p-1 mr-5 -ml-1 rounded-md md:block focus:outline-none focus:shadow-outline-primary"
                @click="toggleDesktopSideMenu" id="toggleSideMenuBtn" aria-label="Menu">
                <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>
            <ul class="flex items-center flex-shrink-0 space-x-6 ltr:ml-auto rtl:mr-auto">


                <!-- Profile menu -->
                <li class="relative">
                    <div class="flex items-center align-middle">
                        {{-- lanaguage selector  --}}
                        <x-select-xs :options='$languages' name="locale" :defer="false" />
                        {{-- vendor selector  --}}
                        @hasanyrole('manager')
                            @livewire('header.vendor-selector')
                        @endhasanyrole
                        {{-- profile menu  --}}
                        <button class="flex items-center align-middle focus:outline-none" @click="toggleProfileMenu"
                            @keydown.escape="closeProfileMenu" aria-label="Account" aria-haspopup="true">
                            {{-- profile name --}}
                            <p class="hidden mx-4 md:block">{{ Auth::user()->name }}</p>

                            {{-- profile photo --}}
                            <img class="object-cover w-8 h-8 ml-2 {{ isRTL() ? 'ml-2' : 'mr-2' }} rounded-full"
                                src="{{ Auth::user()->photo }}" alt="" aria-hidden="true" />


                        </button>
                    </div>
                    <template x-if="isProfileMenuOpen">
                        <ul x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0" @click.away="closeProfileMenu"
                            @keydown.escape="closeProfileMenu"
                            class="absolute right-0 w-56 p-2 mt-2 space-y-2 text-gray-600 bg-white border border-gray-100 rounded-md shadow-md dark:border-gray-700 dark:text-gray-300 "
                            aria-label="submenu">
                            <li class="flex">
                                <a class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200"
                                    href="{{ route('profile') }}">
                                    <svg class="w-4 h-4 mr-3" aria-hidden="true" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    <span>{{ __('Profile') }}</span>
                                </a>
                            </li>

                            <li class="flex cursor-pointer logout" @click="logout">
                                <div
                                    class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200">
                                    <svg class="w-4 h-4 mr-3" aria-hidden="true" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path
                                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                    <span>{{ __('Log out') }}</span>
                                </div>
                            </li>
                        </ul>
                    </template>
                </li>
            </ul>
        </div>
    </header>

</div>
