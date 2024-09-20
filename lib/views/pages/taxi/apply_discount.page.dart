import 'package:flutter/material.dart';
import 'package:fuodz/models/coupon.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/apply_discount.vm.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/custom_text_form_field.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class ApplyDiscountPage extends StatefulWidget {
  const ApplyDiscountPage({
    this.coupon,
    super.key,
  });

  final Coupon? coupon;
  @override
  State<ApplyDiscountPage> createState() => _ApplyDiscountPageState();
}

class _ApplyDiscountPageState extends State<ApplyDiscountPage> {
  @override
  Widget build(BuildContext context) {
    return BasePage(
      showAppBar: true,
      showLeadingAction: true,
      title: "Apply Discount".tr(),
      elevation: 0.5,
      body: ViewModelBuilder<ApplyDiscountViewModel>.reactive(
        viewModelBuilder: () => ApplyDiscountViewModel(context, widget.coupon),
        onViewModelReady: (vm) => vm.initialise(),
        builder: (context, vm, child) {
          return VStack(
            [
              "Save on your ride! Enter your discount code below to enjoy a reduced fare on your upcoming trip. Whether you're commuting to work, heading to the airport, or just exploring the city, we've got you covered with great savings. Don't miss out on this opportunity to travel smart and save big!"
                  .tr()
                  .text
                  .make(),
              1.heightBox,
              //
              CustomTextFormField(
                hintText: "Coupon Code".tr(),
                textEditingController: vm.couponTEC,
                errorText: vm.hasErrorForKey("coupon")
                    ? vm.error("coupon").toString()
                    : null,
                onChanged: vm.couponCodeChange,
              ),
              //

              VStack(
                [
                  CustomButton(
                    title: "Apply".tr(),
                    isFixedHeight: true,
                    loading: vm.busy("coupon"),
                    onPressed: vm.canApplyCoupon ? vm.applyCoupon : null,
                  ).h(Vx.dp56).wFull(context),
                  //
                  vm.hasErrorForKey("coupon")
                      ? UiSpacer.verticalSpace(space: 12)
                      : UiSpacer.verticalSpace(space: 1),
                ],
              ),

              //option to remove applied coupon
              if (vm.coupon != null)
                VStack(
                  [
                    HStack(
                      [
                        Divider().expand(),
                        "Coupon Applied".tr().text.make().px12(),
                        Divider().expand(),
                      ],
                    ),
                    UiSpacer.verticalSpace(space: 12),
                    CustomButton(
                      title: "Remove Coupon".tr(),
                      isFixedHeight: true,
                      onPressed: () {
                        Navigator.pop(context, false);
                      },
                    ).h(Vx.dp56).wFull(context),
                  ],
                ),
            ],
            crossAlignment: CrossAxisAlignment.start,
            alignment: MainAxisAlignment.start,
            spacing: 20,
          ).p20().scrollVertical();
        },
      ),
    );
  }
}
