import 'package:flutter/material.dart';
import 'package:fuodz/models/order.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/order_cancellation.view_model.dart';
import 'package:fuodz/widgets/busy_indicator.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/custom_text_form_field.dart';
import 'package:group_radio_button/group_radio_button.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class OrderCancellationBottomSheet extends StatefulWidget {
  OrderCancellationBottomSheet({
    required this.onSubmit,
    required this.order,
    Key? key,
  }) : super(key: key);

  final Function(String) onSubmit;
  final Order order;
  @override
  _OrderCancellationBottomSheetState createState() =>
      _OrderCancellationBottomSheetState();
}

class _OrderCancellationBottomSheetState
    extends State<OrderCancellationBottomSheet> {
  String _selectedReason = "";
  TextEditingController reasonTEC = TextEditingController();

  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<OrderCancellationViewModel>.reactive(
        viewModelBuilder: () =>
            OrderCancellationViewModel(context, widget.order),
        onViewModelReady: (model) => model.initialise(),
        builder: (context, vm, child) {
          return VStack(
            [
              //
              "Order Cancellation".tr().text.semiBold.xl.make(),
              "Please state why you want to cancel order".tr().text.make(),
              //default reasons
              VStack(
                [
                  (vm.isBusy || vm.busy(vm.reasons))
                      ? BusyIndicator().p(12).centered()
                      : RadioGroup<String>.builder(
                          spacebetween: Vx.dp48,
                          groupValue: _selectedReason,
                          onChanged: (value) => setState(() {
                            _selectedReason = value ?? "";
                          }),
                          items: vm.reasons,
                          itemBuilder: (item) => RadioButtonBuilder(
                            item.tr().capitalized,
                          ),
                        ).py12(),
                  //custom
                  _selectedReason == "custom"
                      ? CustomTextFormField(
                          labelText: "Reason".tr(),
                          textEditingController: reasonTEC,
                        ).py12()
                      : UiSpacer.emptySpace(),
                ],
              ).py(10),
              //
              CustomButton(
                title: "Submit".tr(),
                onPressed: () {
                  if (_selectedReason == "custom") {
                    _selectedReason = reasonTEC.text;
                  }
                  //
                  widget.onSubmit(_selectedReason);
                },
              ),
            ],
          )
              .p20()
              .scrollVertical()
              .pOnly(bottom: context.mq.viewInsets.bottom)
              .h(
                //80% of screen height
                context.percentHeight * 80,
              );
        });
  }
}
