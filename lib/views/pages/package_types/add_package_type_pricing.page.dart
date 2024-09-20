import 'package:flutter/material.dart';
import 'package:flutter_form_builder/flutter_form_builder.dart';
import 'package:fuodz/services/custom_form_builder_validator.service.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/manage_package_type_pricing.vm.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/busy_indicator.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:localize_and_translate/localize_and_translate.dart';

class NewPackagePricingPage extends StatelessWidget {
  const NewPackagePricingPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<ManagePackageTypePricingViewModel>.reactive(
      viewModelBuilder: () => ManagePackageTypePricingViewModel(context, null),
      onViewModelReady: (vm) => vm.initialise(),
      builder: (context, vm, child) {
        return BasePage(
          showLeadingAction: true,
          showAppBar: true,
          title: "New Package Type Pricing".tr(),
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
                      initialValue: false,
                      name: 'price_per_kg',
                      onChanged: (value) {},
                      title: "Charge per KG".tr().text.make(),
                    ),
                    //km/distance charge
                    FormBuilderCheckbox(
                      initialValue: false,
                      name: 'price_per_km',
                      onChanged: (value) {},
                      title: "Charge per KM".tr().text.make(),
                    ),
                    //auto_ready
                    FormBuilderCheckbox(
                      initialValue: false,
                      name: 'auto_assignment',
                      onChanged: (value) {},
                      title: "Auto move to ready".tr().text.make(),
                    ),
                    //extra_fields
                    FormBuilderCheckbox(
                      initialValue: false,
                      name: 'field_required',
                      onChanged: (value) {},
                      title: "Extra fields required (e.g kg, width)"
                          .tr()
                          .text
                          .make(),
                    ),
                    //Active
                    FormBuilderCheckbox(
                      initialValue: true,
                      name: 'is_active',
                      onChanged: (value) {},
                      title: "Active".tr().text.make(),
                    ),
                    //
                    CustomButton(
                      title: "Save Pricing".tr(),
                      loading: vm.isBusy,
                      onPressed: vm.processNewPricing,
                    ).centered().py12(),
                  ],
                ),
              ).p20().scrollVertical()),
        );
      },
    );
  }
}
