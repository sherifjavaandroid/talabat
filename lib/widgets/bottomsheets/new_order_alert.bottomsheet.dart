import 'package:circular_countdown_timer/circular_countdown_timer.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/extensions/dynamic.dart';
import 'package:fuodz/models/new_order.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/new_order_alert.vm.dart';
import 'package:fuodz/widgets/busy_indicator.dart';
import 'package:fuodz/widgets/buttons/custom_text_button.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:swipebuttonflutter/swipebuttonflutter.dart';
import 'package:velocity_x/velocity_x.dart';

class NewOrderAlertBottomSheet extends StatefulWidget {
  NewOrderAlertBottomSheet(
    this.newOrder, {
    Key? key,
  }) : super(key: key);

  final NewOrder newOrder;
  @override
  _NewOrderAlertBottomSheetState createState() =>
      _NewOrderAlertBottomSheetState();
}

class _NewOrderAlertBottomSheetState extends State<NewOrderAlertBottomSheet> {
  //
  bool started = false;
  NewOrderAlertViewModel? vm;

  //
  @override
  void initState() {
    super.initState();
    vm ??= NewOrderAlertViewModel(widget.newOrder, context);
    WidgetsBinding.instance.addPostFrameCallback((_) {
      started = true;
      vm?.initialise();
    });
  }

  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<NewOrderAlertViewModel>.reactive(
      viewModelBuilder: () => vm!,
      builder: (context, vm, child) {
        return VStack(
          [
            HStack(
              [
                //title
                "New Order Alert".tr().text.bold.xl.make().py12().expand(),

                //countdown
                CircularCountDownTimer(
                  duration: AppStrings.alertDuration,
                  controller: vm.countDownTimerController,
                  initialDuration: vm.newOrder.initialAlertDuration,
                  width: 30,
                  height: 30,
                  ringColor: Colors.grey.shade300,
                  ringGradient: null,
                  fillColor: AppColor.accentColor,
                  fillGradient: null,
                  backgroundColor: AppColor.primaryColorDark,
                  backgroundGradient: null,
                  strokeWidth: 4.0,
                  strokeCap: StrokeCap.round,
                  textStyle: TextStyle(
                    fontSize: 14,
                    color: Colors.white,
                    fontWeight: FontWeight.bold,
                  ),
                  textFormat: CountdownTextFormat.S,
                  isReverse: true,
                  isReverseAnimation: false,
                  isTimerTextShown: true,
                  autoStart: false,
                  onStart: () {
                    print('Countdown Started');
                  },
                  onComplete: () => vm.countDownCompleted(started),
                ),
              ],
            ),
            "Pickup Location".tr().text.medium.make(),
            "${widget.newOrder.pickup?.address} (${widget.newOrder.pickup?.distance}km)"
                .text
                .semiBold
                .lg
                .maxLines(2)
                .make(),
            UiSpacer.verticalSpace(space: 10),
            //
            "Dropoff Location".tr().text.medium.make(),
            "${widget.newOrder.dropoff?.address} (${widget.newOrder.dropoff?.distance}km)"
                .text
                .semiBold
                .lg
                .maxLines(2)
                .make(),
            UiSpacer.verticalSpace(space: 10),
            //fee
            HStack(
              [
                VStack(
                  [
                    "Delivery Fee".tr().text.medium.make(),
                    "${AppStrings.currencySymbol} ${widget.newOrder.amount}"
                        .currencyFormat()
                        .text
                        .semiBold
                        .xl
                        .make(),
                  ],
                ).expand(),
                UiSpacer.horizontalSpace(),
                VStack(
                  [
                    "Total".tr().text.medium.make(),
                    "${AppStrings.currencySymbol} ${widget.newOrder.total}"
                        .currencyFormat()
                        .text
                        .semiBold
                        .xl
                        .make(),
                  ],
                ),
              ],
            ),

            UiSpacer.verticalSpace(),

            Visibility(
              visible: widget.newOrder.isParcel,
              child: VStack(
                [
                  "Package Type".tr().text.make(),
                  "${widget.newOrder.packageType}".text.semiBold.lg.make(),
                  UiSpacer.verticalSpace(),
                ],
              ),
            ),
            SwipingButton(
              height: 50,
              backgroundColor: AppColor.accentColor.withOpacity(0.50),
              swipeButtonColor: AppColor.primaryColorDark,
              swipePercentageNeeded: 0.80,
              text: "Accept".tr(),
              onSwipeCallback: vm.processOrderAcceptance,
            ).wFull(context).box.make().h(vm.isBusy ? 0 : 50),
            vm.isBusy
                ? BusyIndicator().centered().p20()
                : UiSpacer.emptySpace(),
            "Swipe to accept order".tr().text.makeCentered().py4(),
            // cancel button
            VStack(
              [
                5.heightBox,
                CustomTextButton(
                  title: "Reject Order".tr(),
                  titleColor: Colors.red,
                  onPressed: () {
                    //reject order for this driver
                    vm.countDownCompleted(started);
                  },
                ).wFull(context),
                20.heightBox,
              ],
            ),
            UiSpacer.verticalSpace(),
          ],
        ).p20().scrollVertical();
      },
    );
  }
}
