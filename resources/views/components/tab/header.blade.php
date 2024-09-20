<div :class="openTab == {{ $tab ?? 0 }} ? 'border-b-2 border-primary-500 text-primary-600 font-bold' : ''"
    class="flex items-center px-2 py-2 cursor-pointer" x-on:click="openTab = {{ $tab ?? 0 }}">
    {{ $slot ?? '' }}
    <span class="font-medium text-sm">{{ $title ?? '' }}</span>
</div>
