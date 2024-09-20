@section('title', __('Database Backups'))
<div>

    <x-baseview title="{{ __('Database Backups') }}" showButton="true">
        @production
            <div class="w-40 ml-auto">
                <x-buttons.primary title="{{ __('New Backup') }}" wireClick='newBackUp'>
                    <x-heroicon-o-plus class="w-5 h-5" />
                </x-buttons.primary>
            </div>
        @endproduction

        {{-- table data --}}
        <table class="w-full mt-5 overflow-hidden bg-white border rounded shadow">
            <thead>
                <tr class="bg-gray-300 border-b">
                    <td class="p-2">S/N</td>
                    <td class="p-2">{{ __('File Name') }}</td>
                    <td class="p-2">{{ __('File Size') }}</td>
                    <td class="p-2">{{ __('Created') }}</td>
                    @production
                        <td class="p-2">{{ __('Actions') }}</td>
                    @endproduction
                </tr>
            </thead>
            <tbody>
                @php
                    $count = 1;
                @endphp
                @foreach ($backups as $backup)
                    @php
                        $infoPath = pathinfo($backup);
                        $extension = $infoPath['extension'] ?? '';
                    @endphp
                    @if ($extension == 'zip')
                        <tr class="border-b">
                            <td class="p-2">{{ $count }}</td>
                            <td class="p-2">{{ basename($backup) }}</td>
                            <td class="p-2">{{ Storage::size($backup) / 1000 }} KB</td>
                            <td class="p-2">
                                {{ \Carbon\Carbon::createFromTimestamp(Storage::lastModified($backup))->format("d M Y \\a\\t h:i a") }}
                            </td>

                            @production
                                <td class="flex p-2 space-x-4">
                                    {{-- Actions --}}
                                    <x-buttons.plain wireClick="downloadBackup('{{ $backup }}')" title="Download">
                                        <x-heroicon-o-cloud-download class="w-5 h-5 mr-2" />
                                        <span class="">Download</span>
                                    </x-buttons.plain>
                                    <x-buttons.delete id="'{{ $backup }}'" />
                                </td>
                            @endproduction
                        </tr>
                        @php
                            $count++;
                        @endphp
                    @endif
                @endforeach
                @if ($count <= 1)
                    <tr class="border-b">
                        <td class="p-2 text-center" colspan="5">
                            {{ __('No Backup Yet') }}
                        </td>
                    </tr>
                @endempty
        </tbody>

    </table>

</x-baseview>


</div>
