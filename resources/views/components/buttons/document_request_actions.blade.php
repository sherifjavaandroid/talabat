<div class="flex items-center gap-x-2">
    <x-buttons.show :model="$model" />
    {{-- upload icon --}}
    @if ($model->status == 'pending')
        <x-buttons.plain title="{{ __('Upload') }}" bgColor="bg-primary-600 space-x-1"
            wireClick="$emit('initiateDocumentUpload','{{ $model->id ?? ($id ?? '') }}')">
            <x-heroicon-o-cloud-upload class="w-5 h-5" />
            <span>{{ __('Upload') }}</span>
        </x-buttons.plain>
        {{-- only is status is pending --}}

        {{-- approve button --}}
        <x-buttons.plain title="{{ __('Approve') }}" bgColor="bg-green-500"
            wireClick="initiateActivate({{ $model->id ?? ($id ?? '') }})">
            {{ __('Approve') }}
        </x-buttons.plain>
        {{-- reject button --}}
        <x-buttons.plain title="{{ __('Reject') }}" bgColor="bg-red-500"
            wireClick="initiateDeactivate({{ $model->id ?? ($id ?? '') }})">
            {{ __('Reject') }}
        </x-buttons.plain>
    @endif

</div>
