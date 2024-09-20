import 'package:flutter/material.dart';
import 'package:flutter_form_builder/flutter_form_builder.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/models/option_group.dart';
import 'package:fuodz/services/custom_form_builder_validator.service.dart';
import 'package:fuodz/view_models/product_manage.vm.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class ProductVariationSection extends StatelessWidget {
  const ProductVariationSection({
    Key? key,
    this.optionGroups = const [],
    required this.onAddOptionGroup,
    required this.onAddOption,
    required this.vm,
  }) : super(key: key);

  final List<OptionGroup> optionGroups;
  final ProductManageViewModel vm;
  final Function onAddOptionGroup;
  final Function(int) onAddOption;

  @override
  Widget build(BuildContext context) {
    final inputBorder = OutlineInputBorder(
      borderRadius: BorderRadius.circular(5),
    );
    return VStack(
      [
        "Variations/Option Groups".text.medium.make(),
        ...optionGroups.mapIndexed(
          (variation, index) {
            return Container(
              padding: EdgeInsets.all(10),
              decoration: BoxDecoration(
                border: Border.all(
                  color: AppColor.primaryColor,
                ),
                borderRadius: BorderRadius.circular(5),
              ),
              child: VStack(
                [
                  HStack(
                    [
                      FormBuilderTextField(
                        name: 'option_groups.$index.name',
                        initialValue: variation.name,
                        //style to be small,
                        style: TextStyle(
                          fontSize: 12,
                        ),
                        decoration: InputDecoration(
                          labelText: 'Name'.tr(),
                          border: inputBorder,
                          contentPadding: EdgeInsets.all(8),
                        ),
                        validator: (value) =>
                            CustomFormBuilderValidator.required(value),
                      ).expand(),
                      //remove
                      InkWell(
                        onTap: () {
                          vm.removeOptionGroup(index);
                        },
                        child: Icon(
                          Icons.close,
                        ),
                      ),
                    ],
                    spacing: 20,
                  ),

                  //multiple and required
                  HStack(
                    [
                      //deliverable
                      FormBuilderCheckbox(
                        initialValue: variation.required == 1,
                        name: 'option_groups.$index.required',
                        valueTransformer: (value) => (value ?? false) ? 1 : 0,
                        title: "Required".tr().text.make(),
                      ).expand(),
                      //deliverable
                      FormBuilderCheckbox(
                        initialValue: variation.multiple == 1,
                        name: 'option_groups.$index.multiple',
                        onChanged: (value) {
                          vm.onMultipleChange(index, value ?? false);
                        },
                        valueTransformer: (value) => (value ?? false) ? 1 : 0,
                        title: "Multiple".tr().text.make(),
                      ).expand(),
                    ],
                    spacing: 10,
                  ),

                  //
                  //if multiple is selected, then required should be selected
                  if (variation.multiple == 1)
                    FormBuilderTextField(
                      initialValue: variation.maxOptions?.toString() ?? "",
                      name: 'option_groups.$index.max_options',
                      //style to be small,
                      style: TextStyle(
                        fontSize: 12,
                      ),
                      decoration: InputDecoration(
                        labelText: 'Max Options'.tr(),
                        border: inputBorder,
                        contentPadding: EdgeInsets.all(8),
                      ),
                      keyboardType: TextInputType.number,
                      validator: (value) => CustomFormBuilderValidator.compose([
                        CustomFormBuilderValidator.required(value),
                        CustomFormBuilderValidator.numeric(value),
                      ]),
                    ),

                  //option group options
                  Container(
                    padding: EdgeInsets.all(10),
                    decoration: BoxDecoration(
                      border: Border.all(
                        color: AppColor.primaryColor,
                      ),
                      borderRadius: BorderRadius.circular(5),
                    ),
                    child: VStack(
                      [
                        ...variation.options.mapIndexed(
                          (variationOption, optionIndex) {
                            return HStack(
                              [
                                FormBuilderTextField(
                                  initialValue: variationOption.name,
                                  name:
                                      'option_groups.$index.options.$optionIndex.name',
                                  //style to be small,
                                  style: TextStyle(
                                    fontSize: 12,
                                  ),
                                  decoration: InputDecoration(
                                    labelText: 'Name'.tr(),
                                    border: inputBorder,
                                    contentPadding: EdgeInsets.all(8),
                                  ),

                                  validator: (value) =>
                                      CustomFormBuilderValidator.required(
                                          value),
                                ).expand(),
                                FormBuilderTextField(
                                  initialValue:
                                      variationOption.price.toString(),
                                  name:
                                      'option_groups.$index.options.$optionIndex.price',
                                  //style to be small,
                                  style: TextStyle(
                                    fontSize: 12,
                                  ),
                                  decoration: InputDecoration(
                                    labelText: 'Price'.tr(),
                                    border: inputBorder,
                                    contentPadding: EdgeInsets.all(8),
                                  ),

                                  validator: (value) =>
                                      CustomFormBuilderValidator.compose([
                                    CustomFormBuilderValidator.required(value),
                                    CustomFormBuilderValidator.numeric(value),
                                  ]),
                                  keyboardType: TextInputType.numberWithOptions(
                                    decimal: true,
                                    signed: true,
                                  ),
                                ).expand(),
                                //remove icon
                                Visibility(
                                  visible: variation.options.length > 1,
                                  child: InkWell(
                                    onTap: () {
                                      vm.removeOption(index, optionIndex);
                                    },
                                    child: Icon(
                                      Icons.close,
                                    ),
                                  ),
                                ),
                              ],
                              spacing: 15,
                              crossAlignment: CrossAxisAlignment.start,
                            );
                          },
                        ).toList(),
                        //add option button
                        ElevatedButton(
                          style: ElevatedButton.styleFrom(
                            elevation: 0,
                          ),
                          onPressed: () {
                            onAddOption(index);
                          },
                          child: "Add new Option".tr().text.make(),
                        ),
                      ],
                      spacing: 12,
                    ),
                  ),
                ],
                spacing: 10,
              ),
            );
          },
        ).toList(),

        //ADD BUTTON
        CustomButton(
          child:
              "Add new Variation/Option Group".tr().text.sm.bold.makeCentered(),
          onPressed: () {
            //
            onAddOptionGroup();
          },
        ),
      ],
      spacing: 10,
    );
  }
}
