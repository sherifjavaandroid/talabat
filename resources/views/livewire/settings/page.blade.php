<div wire:init="setupEditors">
    <x-form action="savePageSettings" :noClass="true">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <div>
                    <x-input.summernote name="driverDocumentInstructions"
                        title="{{ __('Driver Verification Document Instructions') }}"
                        id="driverDocumentInstructionsEdit" />
                </div>
                <x-input title="{{ __('Max Driver Selectable Documents') }}" name="driverDocumentCount" type="number" />
            </div>
            <hr class="my-12 block md:hidden" />
            {{--  --}}
            <div>
                <div>
                    <x-input.summernote name="vendorDocumentInstructions"
                        title="{{ __('Vendor Verification Document Instructions') }}"
                        id="vendorDocumentInstructionsEdit" />
                </div>
                <x-input title="{{ __('Max Vendor Selectable Documents') }}" name="vendorDocumentCount"
                    type="number" />
            </div>
        </div>
        <x-buttons.primary title="{{ __('Save Changes') }}" />
    </x-form>


</div>
