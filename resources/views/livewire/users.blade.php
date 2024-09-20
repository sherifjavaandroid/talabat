@section('title', __('Users'))
<div>

    <x-baseview title="{{ __('Users') }}" :showNew="true">
        <livewire:tables.user-table />
    </x-baseview>

    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Save') }}" action="save" :clickAway="false">
            <p class="text-xl font-semibold">{{ __('Create User Account') }}</p>
            <x-input title="{{ __('Name') }}" name="name" placeholder="John" />
            <div class="grid grid-cols-1 gap-0 md:gap-4 md:grid-cols-2">
                <x-input title="{{ __('Email') }}" name="email" placeholder="info@mail.com" />
                <x-phoneselector />
            </div>
            <x-input title="{{ __('Login Password') }}" name="password" type="password"
                placeholder="**********************" />
            <x-select title="{{ __('Role') }}" :options='$roles' name="role" :defer="false" />

            @if (($roleName ?? '') == 'driver')
                <x-input title="{{ __('Commission') }}" name="commission" placeholder="" />
            @endif
            <hr class="my-4" />
            <p>
                {{ __('You can manage user wallet balance from the wallet transaction page') }}:
                <a href="{{ route('wallet.transactions') }}"
                    class="underline text-primary-500">{{ __('Wallet Transactions') }}</a>
            </p>
            <x-form-errors />

        </x-modal>
    </div>

    {{-- edit  --}}
    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Update') }}" action="update" :clickAway="false">

            <p class="text-xl font-semibold">{{ __('Edit User Account') }}</p>
            <x-input title="{{ __('Name') }}" name="name" placeholder="John" />
            <div class="grid grid-cols-1 gap-0 md:gap-4 md:grid-cols-2">
                <x-input title="{{ __('Email') }}" name="email" placeholder="info@mail.com" />
                <x-phoneselector inputId="phoneEdit" />
            </div>
            <x-input title="{{ __('Login Password') }}" name="password" type="password"
                placeholder="**********************" />
            <x-select title="{{ __('Role') }}" :options='$roles' name="role" :defer="false" />
            @if (($roleName ?? '') == 'driver')
                <x-input title="{{ __('Commission') }}" name="commission" placeholder="" />
            @endif
            <hr class="my-4" />
            <p>
                {{ __('You can manage user wallet balance from the wallet transaction page') }}:
                <a href="{{ route('wallet.transactions') }}"
                    class="underline text-primary-500">{{ __('Wallet Transactions') }}</a>
            </p>
            <x-form-errors />

        </x-modal>
    </div>

    {{-- assign form --}}
    <div x-data="{ open: @entangle('showAssign') }">
        <x-modal confirmText="{{ __('Assign') }}" action="assignVendors" :clickAway="false">

            <p class="text-xl font-semibold">{{ __('Assign Vendors To City Admin') }}</p>
            <x-select2 title="{{ __('Vendors') }}" :options="$vendors" name="vendorsIDs" id="vendorsSelect2"
                :multiple="true" width="100" :ignore="true" />

        </x-modal>
    </div>

    {{-- details modal --}}
    <div x-data="{ open: @entangle('showDetails') }">
        <x-modal>

            <p class="text-xl font-semibold">
                {{ $selectedModel != null ? $selectedModel->name : '' }}
                {{ __('Details') }}</p>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-details.item title="{{ __('Name') }}" text="{{ $selectedModel->name ?? '' }}" />
                @production
                    <x-details.item title="{{ __('Phone') }}" text="{{ $selectedModel->phone ?? '' }}" />
                    <x-details.item title="{{ __('Email') }}" text="{{ $selectedModel->email ?? '' }}" />
                @else
                    <x-details.item title="{{ __('Phone') }}"
                        text="{{ Str::padLeft('', Str::of($selectedModel->phone ?? '')->length(), '*') }}" />
                    <x-details.item title="{{ __('Email') }}"
                        text="{{ Str::padLeft('', Str::of($selectedModel->email ?? '')->length(), '*') }}" />
                @endproduction

                <x-details.item title="{{ __('Referral Code') }}" text="{{ $selectedModel->code ?? '' }}" />
                <x-details.item title="{{ __('Wallet') }}"
                    text="{{ currencyFormat($selectedModel->wallet->balance ?? 0.0) }}" />
                @if (($selectedModel->role_name ?? '') == 'driver')
                    <x-details.item title="{{ __('Commission') }}%" text="{{ $selectedModel->commission ?? '' }}" />
                @endif
                <x-details.item title="{{ __('Role') }}">
                    @php
                        $roles = $selectedModel->roles ?? [];
                        $roleCollection = collect($roles);
                        $roleNames = $roleCollection
                            ->map(function ($role) {
                                return $role->name ?? '';
                            })
                            ->toArray();
                        $roleNames = implode(', ', $roleNames);
                    @endphp
                    <x-details.p text="{{ $roleNames ?? '' }}" />
                </x-details.item>

                <div>
                    <x-label title="{{ __('Status') }}" />
                    <x-table.active :model="$selectedModel" />
                </div>
            </div>

            <hr class="my-4" />
            <p class="font-light">{{ __('Documents') }}</p>
            <div class="grid grid-cols-1 gap-2 pt-4 mt-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($selectedModel->documents ?? [] as $document)
                    <a href="{{ $document }}" target="_blank"><img src="{{ $document }}"
                            class="object-cover border border-gray-200" /></a>
                @endforeach
            </div>

            @if (setting('qrcodeLogin', false))
                @production
                    <hr class="my-4" />
                    <p class="font-light">{{ __('Scan to login') }}</p>
                    <p class="text-xs font-thin">{{ __('Only works with mobile apps') }}</p>
                    <div class="items-center justify-center text-center">
                        <div class="w-56 h-56 mx-auto">
                            @if ($selectedModel != null && ($showDetails ?? false))
                                <img src="{{ (new \chillerlan\QRCode\QRCode())->render($selectedModel->qrcodeLogin ?? '') }}"
                                    alt="{{ __('QR Code Login') }}" class="mx-auto" />
                            @endif
                        </div>
                    </div>
                @endproduction
            @endif

        </x-modal>
    </div>

</div>
@include('layouts.partials.phoneselector')
