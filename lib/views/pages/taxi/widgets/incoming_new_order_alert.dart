import 'package:circular_countdown_timer/circular_countdown_timer.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/constants/app_taxi_settings.dart';
import 'package:fuodz/extensions/dynamic.dart';
import 'package:fuodz/models/new_taxi_order.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/taxi/new_taxi_order_alert.vm.dart';
import 'package:fuodz/view_models/taxi/taxi.vm.dart';
import 'package:fuodz/widgets/busy_indicator.dart';
import 'package:fuodz/widgets/buttons/custom_text_button.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:measure_size/measure_size.dart';
import 'package:stacked/stacked.dart';
import 'package:swipebuttonflutter/swipebuttonflutter.dart';
import 'package:velocity_x/velocity_x.dart';

class IncomingNewOrderAlert extends StatefulWidget {
  const IncomingNewOrderAlert(this.taxiViewModel, this.newTaxiOrder, {Key? key})
      : super(key: key);

  final TaxiViewModel taxiViewModel;
  final NewTaxiOrder newTaxiOrder;

  @override
  _IncomingNewOrderAlertState createState() => _IncomingNewOrderAlertState();
}

class _IncomingNewOrderAlertState extends State<IncomingNewOrderAlert> {
  //
  bool started = false;
  late NewTaxiOrderAlertViewModel vm;

  //
  @override
  void initState() {
    super.initState();
    vm = NewTaxiOrderAlertViewModel(widget.newTaxiOrder, context);
    WidgetsBinding.instance.addPostFrameCallback((_) {
      started = true;
      vm.initialise();
    });
  }

  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<NewTaxiOrderAlertViewModel>.reactive(
      viewModelBuilder: () => vm,
      builder: (context, vm, child) {
        return MeasureSize(
          onChange: (size) {
            widget.taxiViewModel.taxiGoogleMapManagerService
                .updateGoogleMapPadding(size.height);
          },
          child: VStack(
            [
              //
              HStack(
                [
                  //title
                  "New Order Alert"
                      .tr()
                      .text
                      .semiBold
                      .xl
                      .make()
                      .py12()
                      .expand(),

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
                    onComplete: () {
                      widget.taxiViewModel.taxiGoogleMapManagerService
                          .clearMapData();
                      widget.taxiViewModel.taxiLocationService.zoomToLocation();
                      widget.taxiViewModel.taxiGoogleMapManagerService
                          .updateGoogleMapPadding(20);
                      vm.countDownCompleted(started);
                    },
                  ),
                ],
              ),
              //for no info to show
              Visibility(
                visible: !AppTaxiSettings.showTaxiDropoffInfo &&
                    !AppTaxiSettings.showTaxiPickupInfo,
                child: VStack(
                  [
                    HStack(
                      [
                        "Pickup Distance".tr().text.medium.make().expand(),
                        "${vm.newOrder.pickupDistance.numCurrency}km"
                            .text
                            .medium
                            .xl
                            .make(),
                      ],
                    ),
                    HStack(
                      [
                        "Trip Distance".tr().text.lg.make().expand(),
                        "${vm.newOrder.tripDistance}km".text.medium.xl.make(),
                      ],
                    ),
                  ],
                ),
              ),

              //pickup info
              Visibility(
                visible: AppTaxiSettings.showTaxiPickupInfo,
                child: VStack(
                  [
                    "Pickup Location".tr().text.medium.make(),
                    "${vm.newOrder.pickup?.address} (${vm.newOrder.pickup?.distance}km)"
                        .text
                        .semiBold
                        .lg
                        .maxLines(2)
                        .make(),
                    10.heightBox,
                  ],
                ),
              ),
              //dropoff info
              Visibility(
                visible: AppTaxiSettings.showTaxiDropoffInfo,
                child: VStack(
                  [
                    "Dropoff Location".tr().text.medium.make(),
                    "${vm.newOrder.dropoff?.address} (${vm.newOrder.dropoff?.distance}km)"
                        .text
                        .semiBold
                        .lg
                        .maxLines(2)
                        .make(),
                    15.heightBox,
                  ],
                ),
              ),
              //fee
              HStack(
                [
                  "Trip Fare".tr().text.medium.make().expand(),
                  10.widthBox,
                  "${AppStrings.currencySymbol} ${vm.newOrder.amount}"
                      .currencyFormat()
                      .text
                      .semiBold
                      .xl
                      .make(),
                ],
              ),

              //swipe to accept
              VStack(
                [
                  10.heightBox,
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
                ],
              ).py12(),
              "Swipe to accept order".tr().text.makeCentered().py(1),

              // cancel button
              SafeArea(
                child: VStack(
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
                    10.heightBox,
                  ],
                ),
              ),
            ],
          )
              .px(20)
              .py(10)
              .box
              .color(context.theme.colorScheme.surface)
              .topRounded()
              .shadow
              .make(),
        );
      },
    );
  }
}
