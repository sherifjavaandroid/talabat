<aside class="z-20 flex-shrink-0 hidden w-64 overflow-y-auto bg-primary-500 md:block" x-show="isDesktopMenuOpen"
    x-transition:enter="transition slide-in-out duration-150" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition slide-in-out duration-150"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    <div class="py-4 text-gray-500 dark:text-gray-400">
        <a class="flex items-center space-x-4 text-lg font-bold text-gray-100 ltr:ml-6 rtl:mr-6"
            href="{{ route('dashboard') }}">
            <img src="{{ appLogo() }}" class="w-16 h-16 rounded" />
            <div class="ltr:pl-6 rtl:pr-6">
                <p>{{ setting('websiteName', env('APP_NAME')) }}</p>
                <p class="text-xs text-gray-200">{{ __('version') }} {{ setting('appVerison', '1.0.0') }}</p>
            </div>
        </a>
        @include('layouts.partials.nav.menu')
    </div>
</aside>
