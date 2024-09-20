@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/wysiwyg.min.css') }}" />
    <style>
        .ql-container {
            min-height: 33vh !important;
            height: 100%;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .ql-editor {
            height: 100%;
            flex: 1;
            overflow-y: auto;
            width: 100%;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/wysisyg.min.js') }}"></script>
    <script src="{{ asset('js/wysisyg-handler.js') }}"></script>
@endpush
