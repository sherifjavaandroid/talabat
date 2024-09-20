@section('title', __('Vendor Document Request'))
<div>

    <x-baseview title="{{ __('Vendor Document Request') }}">
        <livewire:tables.vendor-document-request-table />
    </x-baseview>

    {{-- upload documents modal --}}
    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Upload') }}" action="uploadDocument">

            <p class="text-xl font-semibold">{{ __('Upload Documents') }}</p>
            <x-label title="{{ __('Documents') }}" />
            <x-input.filepond wire:model="documents" name="documents" multiple="true"
                acceptedFileTypes="['image/*','application/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document']"
                allowImagePreview="false" allowFileSizeValidation="true" maxFileSize="10mb" />

        </x-modal>
    </div>

    {{-- details --}}
    <div x-data="{ open: @entangle('showDetails') }">
        <x-modal :withForm="false">

            <p class="text-xl font-semibold">{{ __('Document Request') }}</p>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-details.item title="{{ __('Vendor') }}" text="{{ $selectedModel->model->name ?? '' }}" />
                <x-details.item title="{{ __('Status') }}">
                    <p class="text-sm font-medium">
                        {{ $selectedModel->model->is_active ?? 0 ? __('Active') : __('Inactive') }}
                    </p>
                </x-details.item>
            </div>
            <hr class="my-2" />
            <div>
                @if ($selectedModel != null)
                    @if ($selectedModel->documents != null && count($selectedModel->documents) > 0)
                        <div class="mt-1 grid grid-cols-1 lg:grid-cols-2 gap-4">
                            @foreach ($selectedModel->documents ?? [] as $document)
                                @if (isMediaImage($document))
                                    <a href="{{ $document->getUrl() }}" target="_blank">
                                        <img src="{{ $document->getUrl() }}"
                                            class="h-24 rounded-sm object-cover w-full" />
                                    </a>
                                @else
                                    <a href="{{ $document->getUrl() }}" target="_blank">
                                        <div class="h-24 border rounded overflow-hidden p-2 border-primary-500">
                                            <p class="line-clamp-1">
                                                <span class='font-bold'>{{ __('Name') }}</span>:
                                                {{ $document->file_name }}
                                            </p>
                                            <p>
                                                <span class='font-bold'>{{ __('Size') }}</span>:
                                                {{ $document->human_readable_size }}
                                            </p>
                                            <p class="text-sm italic text-primary-500 hover:underline">
                                                {{ __('Click to download/preview') }}
                                            </p>
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm font-semibold text-center p-4">{{ __('No documents found') }}</p>
                    @endif
                @endif
            </div>
        </x-modal>
    </div>

</div>
