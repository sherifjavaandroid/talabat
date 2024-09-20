import 'package:flutter/material.dart';
import 'package:flutter_form_builder/flutter_form_builder.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/models/service.dart';
import 'package:fuodz/services/custom_form_builder_validator.service.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/edit_service.vm.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/cards/multiple_image_selector.dart';
import 'package:fuodz/widgets/html_text_view.dart';
import 'package:fuodz/widgets/states/loading_indicator.view.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:localize_and_translate/localize_and_translate.dart';

class EditServicePage extends StatelessWidget {
  const EditServicePage(this.service, {Key? key}) : super(key: key);

  final Service service;
  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<EditServiceViewModel>.reactive(
      viewModelBuilder: () => EditServiceViewModel(context, service),
      onViewModelReady: (vm) => vm.initialise(),
      builder: (context, vm, child) {
        return BasePage(
          showAppBar: true,
          showLeadingAction: true,
          title: "Edit Service".tr(),
          body: FormBuilder(
            key: vm.formBuilderKey,
            child: VStack(
              [
                //categories
                LoadingIndicator(
                  loading: vm.busy(vm.categories),
                  child: VStack(
                    [
                      "Category".tr().text.make(),
                      FormBuilderDropdown(
                        name: "category_id",
                        initialValue: vm.service.categoryId.toString(),
                        items: vm.categories
                            .map(
                              (category) => DropdownMenuItem(
                                value: '${category.id}',
                                child: Text('${category.name}'),
                              ),
                            )
                            .toList(),
                        validator: CustomFormBuilderValidator.required,
                        onChanged: vm.fetchSubCategories,
                      ),
                    ],
                  ),
                ),
                UiSpacer.verticalSpace(),
                //sub categories
                LoadingIndicator(
                  loading: vm.busy(vm.subcategories),
                  child: VStack(
                    [
                      "Sub Category".tr().text.make(),
                      FormBuilderDropdown<int>(
                        name: "subcategory_id",
                        decoration: InputDecoration(
                          hintText: "",
                        ),
                        onChanged: (value) {
                          vm.selectedSubCategoryId = value;
                          vm.notifyListeners();
                        },
                        initialValue: vm.service.subcategoryId != null
                            ? vm.service.subcategoryId
                            : null,
                        items: vm.subcategories
                            .map(
                              (subcategory) => DropdownMenuItem(
                                value: subcategory.id,
                                child: Text('${subcategory.name}'),
                              ),
                            )
                            .toList(),
                      ),
                    ],
                  ),
                ),

                UiSpacer.verticalSpace(),

                //name
                FormBuilderTextField(
                  name: 'name',
                  initialValue: service.name,
                  decoration: InputDecoration(
                    labelText: 'Name'.tr(),
                  ),
                  onChanged: (value) {},
                  validator: CustomFormBuilderValidator.required,
                ),
                UiSpacer.verticalSpace(),
                VStack(
                  [
                    //hstack with Description text expanded and edit button
                    HStack(
                      [
                        "Description".tr().text.make().expand(),
                        CustomButton(
                          title: vm.service.description == null
                              ? "Add".tr()
                              : "Edit".tr(),
                          onPressed: vm.handleDescriptionEdit,
                          icon: vm.service.description == null
                              ? FlutterIcons.add_mdi
                              : FlutterIcons.edit_mdi,
                        ).h(30),
                      ],
                    ),
                    UiSpacer.vSpace(10),
                    //preview description
                    HtmlTextView(vm.service.description ?? "", padding: 0),
                  ],
                ).p(10).box.border().roundedSM.make(),
                UiSpacer.verticalSpace(),
                //image
                MultipleImageSelectorView(
                  photos: service.photos,
                  onImagesSelected: vm.onImagesSelected,
                ),
                UiSpacer.verticalSpace(),
                //durations
                LoadingIndicator(
                  loading: vm.busy(vm.serviceDurations),
                  child: VStack(
                    [
                      "Duration Type".tr().text.make(),
                      FormBuilderDropdown<String>(
                        name: "duration",
                        validator: CustomFormBuilderValidator.required,
                        initialValue:
                            vm.selectedServiceDuration ?? vm.service.duration,
                        decoration: InputDecoration(
                          hintText: "",
                        ),
                        onChanged: (value) {
                          vm.selectedServiceDuration = value;
                          vm.notifyListeners();
                        },
                        items: vm.serviceDurations
                            .map(
                              (serviceDuration) => DropdownMenuItem(
                                value: '$serviceDuration',
                                child: Text('${serviceDuration}'),
                              ),
                            )
                            .toList(),
                      ),
                    ],
                  ),
                ),
                UiSpacer.verticalSpace(),
                //pricing
                HStack(
                  [
                    //price
                    FormBuilderTextField(
                      name: 'price',
                      initialValue: service.price.toString(),
                      decoration: InputDecoration(
                        labelText: 'Price'.tr(),
                      ),
                      onChanged: (value) {},
                      validator: (value) => CustomFormBuilderValidator.compose([
                        CustomFormBuilderValidator.required(value),
                        CustomFormBuilderValidator.numeric(value),
                      ]),
                      keyboardType: TextInputType.numberWithOptions(
                        decimal: true,
                        signed: true,
                      ),
                    ).expand(),
                    UiSpacer.horizontalSpace(),
                    //Discount price
                    FormBuilderTextField(
                      name: 'discount_price',
                      initialValue: service.discountPrice.toString(),
                      decoration: InputDecoration(
                        labelText: 'Discount Price'.tr(),
                      ),
                      onChanged: (value) {},
                      validator: (value) => CustomFormBuilderValidator.compose([
                        CustomFormBuilderValidator.required(value),
                        CustomFormBuilderValidator.numeric(value),
                      ]),
                      keyboardType: TextInputType.numberWithOptions(
                        decimal: true,
                        signed: true,
                      ),
                    ).expand(),
                  ],
                ),

                //checkbox
                HStack(
                  [
                    //Location Required
                    FormBuilderCheckbox(
                      initialValue: service.location == 1,
                      name: 'location',
                      onChanged: (value) {},
                      valueTransformer: (value) => (value ?? false) ? 1 : 0,
                      title: "Location Required".tr().text.make(),
                    ).expand(),
                    UiSpacer.horizontalSpace(),
                    //Active
                    FormBuilderCheckbox(
                      initialValue: service.isActive == 1,
                      name: 'is_active',
                      onChanged: (value) {},
                      valueTransformer: (value) => (value ?? false) ? 1 : 0,
                      title: "Active".tr().text.make(),
                    ).expand(),
                  ],
                ),
                //

                //
                CustomButton(
                  title: "Save".tr(),
                  icon: FlutterIcons.save_fea,
                  loading: vm.isBusy,
                  onPressed: vm.processUpdateService,
                ).wFull(context).py12(),
                UiSpacer.verticalSpace(),
                UiSpacer.verticalSpace(),
              ],
            )
                .p20()
                .scrollVertical()
                .pOnly(bottom: context.mq.viewInsets.bottom),
          ),
        );
      },
    );
  }
}
