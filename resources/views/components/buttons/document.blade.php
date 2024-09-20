<x-buttons.plain title="{{ __('Documents') }}" wireClick="$emit('showDocuments', {{ $model->id }} ) " bgColor="bg-blue-500">
    <x-heroicon-o-document-text class="w-5 h-5"/>
</x-buttons.plain>