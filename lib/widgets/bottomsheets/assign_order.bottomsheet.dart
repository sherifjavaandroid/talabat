import 'package:flutter/material.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/assign_order.vm.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/custom_list_view.dart';
import 'package:fuodz/widgets/custom_text_form_field.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:localize_and_translate/localize_and_translate.dart';

class AssignOrderBottomSheet extends StatelessWidget {
  AssignOrderBottomSheet({
    Key? key,
    required this.onConfirm,
  }) : super(key: key);

  final Function(int) onConfirm;

  //
  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<AssignOrderViewModel>.reactive(
      viewModelBuilder: () => AssignOrderViewModel(),
      onViewModelReady: (vm) => vm.initialise(),
      builder: (context, vm, child) {
        return SafeArea(
          child: VStack(
            [
              //
              "Assign Order To:".tr().text.semiBold.xl.make(),
              UiSpacer.verticalSpace(),
              CustomTextFormField(
                onChanged: vm.filterDrivers,
              ),
              UiSpacer.verticalSpace(),
              //
              CustomListView(
                isLoading: vm.isBusy,
                dataSet: vm.drivers,
                separatorBuilder: (ctx, index) => 2.heightBox,
                padding: EdgeInsets.all(0),
                itemBuilder: (context, index) {
                  //
                  final driver = vm.drivers[index];
                  return HStack(
                    [
                      //
                      Radio(
                        value: driver.id,
                        groupValue: vm.selectedDriverId,
                        onChanged: vm.changeSelectedDriver,
                      ),

                      //
                      "${driver.name}".text.lg.light.make().expand(),
                      //online/offline
                      "[${(driver.isOnline ? "Online".tr() : "Offline".tr())}]"
                          .text
                          .color(driver.isOnline ? Colors.green : Colors.red)
                          .make(),
                    ],
                  )
                      .onInkTap(() => vm.changeSelectedDriver(driver.id))
                      .wFull(context);
                },
              ).py12().expand(),

              //
              CustomButton(
                title: "Assign".tr(),
                onPressed: vm.selectedDriverId != null
                    ? () => onConfirm(vm.selectedDriverId!)
                    : null,
              ),
            ],
          ).p20().h(context.safePercentHeight * 90),
        );
      },
    );
  }
}
