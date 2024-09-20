import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/taxi.vm.dart';
import 'package:fuodz/view_models/taxi_new_order_summary.vm.dart';
import 'package:fuodz/views/pages/taxi/widgets/order_taxi.button.dart';
import 'package:fuodz/views/pages/taxi/widgets/step_two/new_style_taxi_order_vehicle_type.list_view.dart';
import 'package:fuodz/views/pages/taxi/widgets/step_two/new_taxi_order_payment_method.selection_view.dart';
import 'package:fuodz/widgets/buttons/custom_text_button.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:measure_size/measure_size.dart';
import 'package:velocity_x/velocity_x.dart';

class NewTaxiOrderSummaryCollapsed extends StatelessWidget {
  const NewTaxiOrderSummaryCollapsed(
    this.newTaxiOrderSummaryViewModel, {
    Key? key,
  }) : super(key: key);

  final NewTaxiOrderSummaryViewModel newTaxiOrderSummaryViewModel;

  @override
  Widget build(BuildContext context) {
    final TaxiViewModel vm = newTaxiOrderSummaryViewModel.taxiViewModel;
    return MeasureSize(
      onChange: (size) {
        vm.updateGoogleMapPadding(height: size.height + Vx.dp40);
      },
      child: VStack(
        [
          VStack(
            [
              //
              HStack(
                [
                  //previous
                  CustomTextButton(
                    padding: EdgeInsets.zero,
                    title: "Back".tr(),
                    onPressed: () => vm.closeOrderSummary(clear: false),
                  ).h(24),
                  // UiSpacer.swipeIndicator().px12().expand(),
                  Spacer(),
                  //cancel book
                  CustomTextButton(
                    padding: EdgeInsets.zero,
                    title: "Cancel".tr(),
                    titleColor: Colors.red,
                    onPressed: vm.closeOrderSummary,
                  ).h(24),
                ],
                alignment: MainAxisAlignment.spaceBetween,
              ).px20().pOnly(top: 10),

              //vehicle types
              NewTaxiVehicleTypeListView(
                vm: vm,
              ).wFull(context),
            ],
          ),
          Divider(
            color: Colors.grey.shade300,
            height: 10,
            thickness: 0.8,
          ).pOnly(bottom: 8),
          //action group
          VStack(
            [
              HStack(
                [
                  //selected payment method
                  NewTaxiOrderPaymentMethodSelectionView(
                    vm: newTaxiOrderSummaryViewModel,
                  ).expand(flex: 6),
                  UiSpacer.hSpace(),
                  //discount icon button
                  VxBadge(
                    child: IconButton(
                      onPressed: newTaxiOrderSummaryViewModel.openCoupnDialog,
                      icon: Icon(
                        Icons.local_offer,
                        color: Colors.grey,
                      ),
                    ).box.roundedSM.gray200.px8.make(),
                    color: AppColor.primaryColor,
                    size: 20,
                    count: newTaxiOrderSummaryViewModel.taxiViewModel.coupon !=
                            null
                        ? 1
                        : 0,
                  ),
                ],
              ).px(12),
              UiSpacer.vSpace(10),
              OrderTaxiButton(vm),
            ],
          ),
        ],
      )
          .box
          .color(context.theme.colorScheme.surface)
          .topRounded(value: 5)
          .outerShadowXl
          .make(),
    );
  }
}
