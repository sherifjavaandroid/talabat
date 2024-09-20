import 'package:flutter/material.dart';
import 'package:flutter_form_builder/flutter_form_builder.dart';
import 'package:fuodz/constants/app_page_settings.dart';
import 'package:fuodz/models/vehicle.dart';
import 'package:fuodz/services/custom_form_builder_validator.service.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/new_vehicle.vm.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/cards/document_selection.view.dart';
import 'package:fuodz/widgets/custom_type_ahead_field.input.dart';
import 'package:fuodz/widgets/states/custom_loading.state.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class NewVehiclePage extends StatefulWidget {
  const NewVehiclePage({Key? key}) : super(key: key);

  @override
  State<NewVehiclePage> createState() => _NewVehiclePageState();
}

class _NewVehiclePageState extends State<NewVehiclePage> {
  @override
  Widget build(BuildContext context) {
    //
    final inputDec = InputDecoration(
      border: OutlineInputBorder(),
    );

    return ViewModelBuilder<NewVehicleViewModel>.reactive(
      viewModelBuilder: () => NewVehicleViewModel(context),
      onViewModelReady: (vm) => vm.initialise(),
      builder: (context, vm, child) {
        return BasePage(
          showAppBar: true,
          showLeadingAction: true,
          title: "New Vehicle".tr(),
          body: FormBuilder(
            key: vm.formBuilderKey,
            child: VStack(
              [
                "Vehicle Details".tr().text.semiBold.xl.make().py12(),
                UiSpacer.vSpace(10),
                CustomLoadingStateView(
                  loading: vm.busy(vm.carMakes),
                  child: CustomTypeAheadField<CarMake>(
                    textEditingController: vm.carMakeTEC,
                    title: "Car Make".tr(),
                    items: vm.carMakes,
                    itemBuilder: (context, suggestion) {
                      return ListTile(
                        title: Text("${suggestion.name}"),
                      );
                    },
                    suggestionsCallback: (value) async {
                      return vm.carMakes
                          .where(
                            (e) => e.name
                                .toLowerCase()
                                .contains(value.toLowerCase()),
                          )
                          .toList();
                    },
                    onSuggestionSelected: vm.onCarMakeSelected,
                  ),
                ),
                CustomLoadingStateView(
                  loading: vm.busy(vm.carModels),
                  child: CustomTypeAheadField<CarModel>(
                    textEditingController: vm.carModelTEC,
                    title: "Car Model".tr(),
                    items: vm.carModels,
                    itemBuilder: (context, suggestion) {
                      return ListTile(
                        title: Text("${suggestion.name}"),
                      );
                    },
                    suggestionsCallback: (value) async {
                      return vm.carModels
                          .where(
                            (e) => e.name
                                .toLowerCase()
                                .contains(value.toLowerCase()),
                          )
                          .toList();
                    },
                    onSuggestionSelected: vm.onCarModelSelected,
                  ).py20(),
                ),

                //
                CustomLoadingStateView(
                  loading: vm.busy(vm.vehicleTypes),
                  child: FormBuilderDropdown(
                    name: 'vehicle_type_id',
                    decoration: inputDec.copyWith(
                      labelText: "Vehicle Type".tr(),
                      hintText: 'Select Vehicle Type'.tr(),
                    ),
                    validator: CustomFormBuilderValidator.required,
                    items: vm.vehicleTypes
                        .map(
                          (type) => DropdownMenuItem(
                            value: type.id,
                            child: '${type.name}'.text.make(),
                          ),
                        )
                        .toList(),
                  ),
                ),

                //
                FormBuilderTextField(
                  name: "reg_no",
                  validator: CustomFormBuilderValidator.required,
                  decoration: inputDec.copyWith(
                    labelText: "Registration Number".tr(),
                  ),
                  textInputAction: TextInputAction.next,
                ).py20(),
                FormBuilderTextField(
                  name: "color",
                  validator: CustomFormBuilderValidator.required,
                  decoration: inputDec.copyWith(
                    labelText: "Color".tr(),
                  ),
                  textInputAction: TextInputAction.next,
                ),

                UiSpacer.divider().py20(),

                //business documents
                DocumentSelectionView(
                  title: "Documents".tr(),
                  instruction: AppPageSettings.driverDocumentInstructions,
                  max: AppPageSettings.maxDriverDocumentCount,
                  onSelected: vm.onDocumentsSelected,
                ).py20(),

                UiSpacer.divider().py12(),

                //
                CustomButton(
                  title: "Save".tr(),
                  loading: vm.isBusy,
                  onPressed: vm.processSave,
                ).centered().py20(),
              ],
            )
                .scrollVertical(padding: EdgeInsets.all(20))
                .pOnly(bottom: context.mq.viewInsets.bottom),
          ),
        );
      },
    );
  }
}
