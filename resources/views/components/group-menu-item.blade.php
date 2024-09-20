@php
    $currentRoute = Route::currentRouteName();
    $isActive = false;
    //make sure the provided route is not empty
    if (isset($routePath) && !empty($routePath)) {
        //make sure the route add prefix
        $backendPrefix = env('BACKEND_ROUTE_PREFIX');
        if (!empty($backendPrefix)) {
            $tartsWithPrefix = Str::startsWith($routePath, $backendPrefix);
            if (!$tartsWithPrefix) {
                $routePath = $backendPrefix . '/' . $routePath;
            }
        }
        if (Request::is($routePath)) {
            $isActive = true;
        }
    }
@endphp
<div class="relative" x-data="{ isOpen: {{ $isActive ? 'true' : 'false' }} }">



    <p class="flex items-center px-6 py-3 text-white cursor-pointer" x-on:click="isOpen = !isOpen ">

        @if (!empty($icon))
            {{--  <x-icon name="camera" class="w-5 h-5 mr-4" />  --}}
            @if (isRTL())
                {{ svg($icon)->class('w-5 h-5 ml-4') }}
            @else
                {{ svg($icon)->class('w-5 h-5 mr-4') }}
            @endif
        @endif
        @if ($users ?? false)
            <x-heroicon-o-users class="w-5 h-5 mr-4" />
        @elseif($products ?? false)
            <x-heroicon-o-archive class="w-5 h-5 mr-4" />
        @elseif($orders ?? false)
            <x-heroicon-o-shopping-bag class="w-5 h-5 mr-4" />
        @elseif($earnings ?? false)
            <x-heroicon-o-cash class="w-5 h-5 mr-4" />
        @elseif($payouts ?? false)
            <x-heroicon-o-clipboard-check class="w-5 h-5 mr-4" />
        @elseif($settings ?? false)
            <x-heroicon-o-cog class="w-5 h-5 mr-4" />
        @elseif($package ?? false)
            <x-heroicon-o-globe class="w-5 h-5 mr-4" />
        @endif
        {{ $title ?? '' }}
        <span class="ltr:ml-auto rtl:mr-auto">
            <x-heroicon-o-chevron-down class="w-5 h-5" x-show="isOpen" />
            <x-heroicon-o-chevron-left class="w-5 h-5" x-show="!isOpen" />
        </span>
    </p>
    <div class="bg-primary-800" x-show="isOpen" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90">
        {{ $slot }}
    </div>


</div>
