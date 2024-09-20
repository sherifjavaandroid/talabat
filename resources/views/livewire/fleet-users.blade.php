@section('title', __('Users') )
<div>

    <x-baseview title="{{ __('Users') }}" :showNew="true">
        <livewire:tables.fleet-user-table />
    </x-baseview>

    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Save') }}" action="save">
            <p class="text-xl font-semibold">{{ __('Create User Account') }}</p>
            <x-input title="{{ __('Name') }}" name="name" placeholder="John" />
            <x-input title="{{ __('Email') }}" name="email" placeholder="info@mail.com" />
            <x-input title="{{ __('Phone') }}" name="phone" placeholder="" />
            <x-input title="{{ __('Login Password') }}" name="password" type="password" placeholder="**********************" />
            <x-select title="{{ __('Role') }}" :options='$roles' name="role" :defer="false" />
        </x-modal>
    </div>

    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Update') }}" action="update">

            <p class="text-xl font-semibold">{{ __('Edit User Account') }}</p>
            <x-input title="{{ __('Name') }}" name="name" placeholder="John" />
            <x-input title="{{ __('Email') }}" name="email" placeholder="info@mail.com" />
            <x-input title="{{ __('Phone') }}" name="phone" placeholder="" />
            <x-input title="{{ __('Login Password') }}" name="password" type="password" placeholder="**********************" />
            <x-select title="{{ __('Role') }}" :options='$roles' name="updateRole" selected="{{ !empty($selectedModel) ? $selectedModel->role_id : '1' }}" :defer="false" />

        </x-modal>
    </div>

    {{-- details modal --}}
    <div x-data="{ open: @entangle('showDetails') }">
        <x-modal>

            <p class="text-xl font-semibold">
                {{ $selectedModel != null ? $selectedModel->name : '' }}
                {{ __("Details") }}</p>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-details.item title="{{ __('Name') }}" text="{{ $selectedModel->name ?? '' }}" />
                @production
                <x-details.item title="{{ __('Phone') }}" text="{{ $selectedModel->phone ?? '' }}" />
                <x-details.item title="{{ __('Email') }}" text="{{ $selectedModel->email ?? '' }}" />
                @else
                <x-details.item title="{{ __('Phone') }}" text="{{ Str::padLeft('', Str::of($selectedModel->phone ?? '')->length(), '*') }}" />
                <x-details.item title="{{ __('Email') }}" text="{{ Str::padLeft('', Str::of($selectedModel->email ?? '')->length(), '*') }}" />
                @endproduction

                <x-details.item title="{{ __('Referral Code') }}" text="{{ $selectedModel->code ?? '' }}" />
                <x-details.item title="{{ __('Wallet') }}" text="{{ currencyFormat($selectedModel->wallet->balance ?? 0.00) }}" />
                @if (($selectedModel->role_name ?? '') == "driver")
                <x-details.item title="{{ __('Commission') }}%" text="{{ $selectedModel->commission ?? '' }}" />
                @endif
                <x-details.item title="{{ __('Role') }}" text="{{ $selectedModel->role_name ?? '' }}" />

                <div>
                    <x-label title="{{ __('Status') }}" />
                    <x-table.active :model="$selectedModel" />
                </div>
            </div>

            <hr class="my-4" />
            <p class="font-light">{{ __('Documents') }}</p>
            <div class="grid grid-cols-1 gap-2 pt-4 mt-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($selectedModel->documents ?? [] as $document)
                <a href="{{ $document }}" target="_blank"><img src="{{ $document }}" class="object-cover border border-gray-200" /></a>
                @endforeach
            </div>

        </x-modal>
    </div>
</div>
