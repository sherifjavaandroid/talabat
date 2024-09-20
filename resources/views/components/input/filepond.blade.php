<div>

    @if (!empty($title ?? ''))
        <x-label :title="$title" />
    @endif

    <div class="my-2" wire:ignore x-data x-init="() => {
        const post = FilePond.create($refs.{{ $attributes->get('ref') ?? 'input' }});
        post.setOptions({
            server: {
                process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                    @this.upload('{{ $attributes->whereStartsWith('wire:model')->first() }}', file, load, error, progress)
                },
                revert: (filename, load) => {
                    @this.removeUpload('{{ $attributes->whereStartsWith('wire:model')->first() }}', filename, load)
                },
            },
            allowMultiple: {{ $attributes->has('multiple') ? 'true' : 'false' }},
            allowImagePreview: {{ $attributes->has('allowImagePreview') ? 'true' : 'false' }},
            imagePreviewMaxHeight: {{ $attributes->has('imagePreviewMaxHeight') ? $attributes->get('imagePreviewMaxHeight') : '256' }},
            allowFileTypeValidation: {{ $attributes->has('allowFileTypeValidation') ? 'true' : 'false' }},
            acceptedFileTypes: {!! $attributes->get('acceptedFileTypes') ?? 'null' !!},
            allowFileSizeValidation: {{ $attributes->has('allowFileSizeValidation') ? 'true' : 'false' }},
            maxFileSize: {!! $attributes->has('maxFileSize') ? "'" . $attributes->get('maxFileSize') . "'" : 'null' !!},
            oninit: () => {
                @if($attributes->has('allowAddFileEvent'))
                livewire.emit('setFilePondState', post);
                @endif
            },
        });
    }">
        <input type="file" x-ref="{{ $attributes->get('ref') ?? 'input' }}" />
    </div>
    @error($name ?? '')
        <span class="mt-1 text-xs text-red-700">{{ $message }}</span>
    @enderror
</div>

@push('styles')
    @if ($attributes->has('grid'))
        @php
            $grid = $attributes->get('grid');
            $widthPercentage = 100 / $grid - 1.0;
            $widhtEm = $widthPercentage / 100;
        @endphp
        <style>
            .filepond--item {
                width: calc({{ $widthPercentage }}% - {{ $widhtEm }}em);
            }
        </style>
    @endif
@endpush

@push('scripts')
    @if ($attributes->has('allowAddFileEvent'))
        <script>
            const editableId = '#{{ $attributes->get('id') ?? 'input' }}';
            var thisFilePond;
            //jquery on ready
            $(function() {

                livewire.on('setFilePondState', (filePond) => {
                    thisFilePond = filePond;
                });


                livewire.on('filePondClear', (filePond) => {
                    thisFilePond.removeFiles();
                });

                livewire.on('filepond-add-file', (id, url, name) => {
                    if (editableId == id) {
                        fetch(url)
                            .then(response => {
                                const contentType = response.headers.get('content-type');
                                return response.blob().then(blob => {
                                    const file = new File([blob], name ?? 'photo', {
                                        type: contentType
                                    });

                                    thisFilePond.addFile(file);
                                });
                            });
                    }
                });
            });
        </script>
    @endif
@endpush
