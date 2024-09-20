<div class=" w-full md:w-10/12 lg:w-6/12">
    <x-form noClass="true" action="saveCategorySettings">
        <div class='grid grid-cols-1 gap-4 md:grid-cols-2'>

            <x-input title="{{ __('Width') }}" name="categorySize_w" type="number" />
            <x-input title="{{ __('Height') }}" name="categorySize_h" type="number" />
            <x-input title="{{ __('Category Text Size') }}" name="categorySize_text_size" type="number" />
            <x-input title="{{ __('Category Per Row') }}" name="categoryPerRow" type="number" />
            <x-input title="{{ __('Category Per Page') }}" name="categoryPerPage" type="number" />
            <x-select title="{{ __('Listing Style') }}" :options="['List', 'Grid']" name="categoryStyle" />
        </div>
        <x-buttons.primary title="{{ __('Save') }}" />
    </x-form>
</div>
