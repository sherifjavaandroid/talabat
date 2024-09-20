import 'package:flutter/material.dart';
import 'package:flutter_form_builder/flutter_form_builder.dart';
import 'package:fuodz/models/package_type_pricing.dart';
import 'package:fuodz/services/custom_form_builder_validator.service.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/manage_package_type_pricing.vm.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/busy_indicator.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:localize_and_translate/localize_and_translate.dart';

class EditPackagePricingPage extends StatelessWidget {
  const EditPackagePricingPage(this.packageTypePricing, {Key? key})
      : super(key: key);

  final PackageTypePricing packageTypePricing;
  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<ManagePackageTypePricingViewModel>.reactive(
      viewModelBuilder: () => ManagePackageTypePricingViewModel(
        context,
        packageTypePricing,
      ),
      onViewModelReady: (vm) => vm.initialise(),
      builder: (context, vm, child) {
        return BasePage(
          showLeadingAction: true,
          showAppBar: true,
          title: "Edit Package Type Pricing".tr(),
          body: SafeArea(
              top: true,
              bottom: false,
              child: FormBuilder(
                key: vm.formBuilderKey,
                child: VStack(
                  [
                    //package types
                    vm.busy(vm.packageTypes)
                        ? BusyIndicator().centered()
                        : FormBuilderDropdown(
                            initialValue: packageTypePricing.packageTypeId,
                            name: 'package_type_id',
                            decoration: InputDecoration(
                              labelText: 'Package Type'.tr(),
                              hintText: 'Select Package Type'.tr(),
                            ),
                            validator: CustomFormBuilderValidator.required,
                            items: vm.packageTypes
                                .map(
                                  (packageType) => DropdownMenuItem(
                                    value: packageType.id,
                                    child: '${packageType.name}'.text.make(),
                                  ),
                                )
                                .toList(),
                          ),
                    UiSpacer.verticalSpace(),
                    //max_booking_days
                    FormBuilderTextField(
                      name: 'max_booking_days',
                      initialValue:
                          packageTypePricing.maxBookingDays.toString(),
                      decoration: InputDecoration(
                        labelText: 'Max booking days'.tr(),
                      ),
                      onChanged: (value) {},
                      validator: (value) => CustomFormBuilderValidator.compose([
                        CustomFormBuilderValidator.required(value),
                        CustomFormBuilderValidator.numeric(value),
                      ]),
                      keyboardType: TextInputType.number,
                    ),
                    UiSpacer.verticalSpace(),
                    //base price
                    FormBuilderTextField(
                      name: 'base_price',
                      initialValue: packageTypePricing.basePrice.toString(),
                      decoration: InputDecoration(
                        labelText: 'Base Price'.tr(),
                      ),
                      onChanged: (value) {},
                      // valueTransformer: (text) => num.tryParse(text),
                      validator: (value) => CustomFormBuilderValidator.compose([
                        CustomFormBuilderValidator.required(value),
                        CustomFormBuilderValidator.numeric(value),
                      ]),
                      keyboardType: TextInputType.numberWithOptions(
                        decimal: true,
                        signed: true,
                      ),
                    ),
                    UiSpacer.verticalSpace(),
                    //package price
                    FormBuilderTextField(
                      name: 'size_price',
                      initialValue: packageTypePricing.sizePrice.toString(),
                      decoration: InputDecoration(
                        labelText: 'Package Price'.tr(),
                      ),
                      onChanged: (value) {},
                      // valueTransformer: (text) => num.tryParse(text),
                      validator: (value) => CustomFormBuilderValidator.compose([
                        CustomFormBuilderValidator.required(value),
                        CustomFormBuilderValidator.numeric(value),
                      ]),
                      keyboardType: TextInputType.numberWithOptions(
                        decimal: true,
                        signed: true,
                      ),
                    ),
                    UiSpacer.verticalSpace(),
                    //distance price
                    FormBuilderTextField(
                      name: 'distance_price',
                      initialValue: packageTypePricing.distancePrice.toString(),
                      decoration: InputDecoration(
                        labelText: 'Distance Price'.tr(),
                      ),
                      onChanged: (value) {},
                      // valueTransformer: (text) => num.tryParse(text),
                      validator: (value) => CustomFormBuilderValidator.compose([
                        CustomFormBuilderValidator.required(value),
                        CustomFormBuilderValidator.numeric(value),
                      ]),
                      keyboardType: TextInputType.numberWithOptions(
                        decimal: true,
                        signed: true,
                      ),
                    ),
                    //
                    UiSpacer.verticalSpace(),
                    //kg charge
                    FormBuilderCheckbox(
                      name: 'price_per_kg',
                      initialValue: packageTypePricing.pricePerKg == 1,
                      onChanged: (value) {},
                      title: "Charge per KG".tr().text.make(),
                    ),
                    //km/distance charge
                    FormBuilderCheckbox(
                      name: 'price_per_km',
                      initialValue: packageTypePricing.pricePerKm == 1,
                      onChanged: (value) {},
                      title: "Charge per KM".tr().text.make(),
                    ),
                    //auto_ready
                    FormBuilderCheckbox(
                      name: 'auto_assignment',
                      initialValue: packageTypePricing.autoReady,
                      onChanged: (value) {},
                      title: "Auto move to ready".tr().text.make(),
                    ),
                    //extra_fields
                    FormBuilderCheckbox(
                      name: 'field_required',
                      initialValue: packageTypePricing.extraFields,
                      onChanged: (value) {},
                      title: "Extra fields required (e.g kg, width)"
                          .tr()
                          .text
                          .make(),
                    ),
                    //Active
                    FormBuilderCheckbox(
                      name: 'is_active',
                      initialValue: packageTypePricing.isActive == 1,
                      onChanged: (value) {},
                      title: "Active".tr().text.make(),
                    ),
                    //
                    CustomButton(
                      title: "Update Pricing".tr(),
                      loading: vm.isBusy,
                      onPressed: vm.processUpdate,
                    ).centered().py12(),
                  ],
                ),
              ).p20().scrollVertical()),
        );
      },
    );
  }
}
