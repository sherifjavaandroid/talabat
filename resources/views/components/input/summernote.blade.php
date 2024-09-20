<div @if ($ignore ?? true) wire:ignore @endif>
    @isset($title)
        <x-label :title="$title" />
    @endisset
    <div class="relative inline-block w-full">
        <textarea name="{{ $name ?? '' }}" id="{{ $id ?? '' }}" class="w-full summernote"></textarea>
        {{--  --}}
        <input type="hidden" id="{{ $id ?? ($name ?? '') }}-Input"
            @if ($defer ?? true) wire:model.defer='{{ $name ?? '' }}'
        @else
            wire:model='{{ $name ?? '' }}' @endif />
    </div>

    {{--  --}}
    @pushOnce('styles')
        {{-- <link href="{{ asset('css/summernote-lite.min.css') }}" rel="stylesheet"> --}}
        <style>
            .ck-editor__editable_inline {
                min-height: 100px;
                max-height: 400px;
            }
        </style>
    @endpushOnce
    @pushOnce('scripts')
        {{-- <script src="{{ asset('js/jquery-3.4.1.slim.min.js') }}" type="module"></script> --}}
        {{-- <script src="{{ asset('js/summernote-lite.min.js') }}" type="module"></script> --}}
        <script src="{{ asset('js/ckeditor.js') }}" type="module"></script>
        <script src="{{ asset('js/summernote-handler.js') }}" type="module"></script>
    @endpushOnce

</div>
