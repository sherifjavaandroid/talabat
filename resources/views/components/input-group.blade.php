<div>
    {{-- slot label --}}
    {{ $label }}
    <div class="relative mt-2 rounded-md shadow-sm">
        {{-- slot input --}}
        {{ $input }}

        <div class="border-2 ltr:border-left rtl:border-right absolute inset-y-0 right-0 flex items-center p-1">
            {{-- slot append --}}
            {{ $append }}
        </div>
    </div>
</div>
