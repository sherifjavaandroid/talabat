@section('title', __('Options') )
<div>

    <x-baseview title="{{ __('Options') }}" :showNew="true" :newInfo="true">
        <livewire:tables.option-table />
    </x-baseview>

    {{-- new form --}}
    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Save') }}" action="save">
            <p class="text-xl font-semibold">{{ __('Create Option') }}</p>

            <x-input title="{{ __('Name') }}" name="name" />
            <x-media-upload title="{{ __('Image') }}" name="photo" :photo="$photo"
                :photoInfo="$photoInfo" types="PNG or JPEG" rules="image/*" />


            <x-input title="{{ __('Description') }}" name="description" />
            <x-input title="{{ __('Price') }}" name="price" />

            {{-- products --}}
            <livewire:component.autocomplete-input title="{{ __('Product') }}" column="name"
                model="Product" emitFunction="autocompleteProductSelected"
                updateQueryClauseName="productQueryClasueUpdate" :clear="true" :queryClause="$productSearchClause"
                onclearCalled="clearAutocompleteFieldsEvent" />

            {{-- selected products --}}
            <x-item-chips :items="$selectedProducts ?? []" onRemove="removeSelectedProduct" />


            <x-select title="{{ __('Option Group') }}" :options="$optionGroups ?? []"
                name="option_group_id" />

            <x-checkbox title="{{ __('Active') }}" name="isActive" :defer="false" />

        </x-modal>
    </div>

    {{-- update form --}}
    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Update') }}" action="update">
            <p class="text-xl font-semibold">{{ __('Update Option') }}</p>

            <x-input title="Name" name="name" />
            <x-media-upload title="{{ __('Image') }}" name="photo"
                preview="{{ $selectedModel->photo ?? '' }}" :photo="$photo"
                :photoInfo="$photoInfo" types="PNG or JPEG" rules="image/*" />


            <x-input title="{{ __('Description') }}" name="description" />
            <x-input title="{{ __('Price') }}" name="price" />

            {{-- products --}}
            <livewire:component.autocomplete-input title="{{ __('Product') }}" column="name"
                model="Product" emitFunction="autocompleteProductSelected"
                updateQueryClauseName="productQueryClasueUpdate" :clear="true" :queryClause="$productSearchClause"
                onclearCalled="clearAutocompleteFieldsEvent" />

            {{-- selected products --}}
            <x-item-chips :items="$selectedProducts ?? []" onRemove="removeSelectedProduct" />

            <x-select title="{{ __('Option Group') }}" :options="$optionGroups ?? []"
                name="option_group_id" />

            <x-checkbox title="{{ __('Active') }}" name="isActive" :defer="false" />
        </x-modal>
    </div>

    {{-- details modal --}}
    <div x-data="{ open: @entangle('showDetails') }">
        <x-modal-lg>

            <p class="text-xl font-semibold">{{ $selectedModel->name ?? '' }}
                {{ __('Products') }}</p>
            <div class='grid grid-cols-1 my-4 md:grid-cols-2 lg:grid-cols-3'>
                @foreach($selectedModel->products ?? [] as $key => $product)
                    <div><b>{{ $key + 1 }}.</b> {{ $product['name'] }}</div>
                @endforeach
            </div>

        </x-modal-lg>
    </div>


</div>
