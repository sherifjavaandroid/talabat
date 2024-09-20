<div>
    <hr />
    @foreach ($menu as $navItem)
        @if (Route::has($navItem->route))
            @if (empty($navItem->roles ?? '') && empty($navItem->permissions ?? ''))
                <x-menu-item title="{{ $navItem->name }}" route="{{ $navItem->route }}">
                    {{ svg($navItem->icon)->class('w-5 h-5') }}
                </x-menu-item>
            @else
                @if ($navItem->permissions == null || empty($navItem->permissions))
                    @hasanyrole($navItem->roles)
                        <x-menu-item title="{{ $navItem->name }}" route="{{ $navItem->route }}">
                            {{ svg($navItem->icon)->class('w-5 h-5') }}
                        </x-menu-item>
                    @endhasanyrole
                @elseif($navItem->permissions != null && !empty($navItem->permissions))
                    @can($navItem->permissions)
                        <x-menu-item title="{{ $navItem->name }}" route="{{ $navItem->route }}">
                            {{ svg($navItem->icon)->class('w-5 h-5') }}
                        </x-menu-item>
                    @endcan
                @endif
            @endif
        @endif
    @endforeach
</div>
