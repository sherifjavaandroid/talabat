import 'package:flutter/material.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/taxi/taxi.vm.dart';
import 'package:fuodz/views/pages/taxi/widgets/taxi_amount.view.dart';
import 'package:fuodz/views/pages/taxi/widgets/taxi_contact.view.dart';
import 'package:fuodz/views/pages/taxi/widgets/taxi_customer.view.dart';
import 'package:fuodz/views/pages/taxi/widgets/taxi_eta.view.dart';
import 'package:fuodz/views/pages/taxi/widgets/taxi_pickup.view.dart';
import 'package:fuodz/views/pages/taxi/widgets/taxi_swipe.view.dart';
import 'package:measure_size/measure_size.dart';
import 'package:sliding_up_panel/sliding_up_panel.dart';
import 'package:velocity_x/velocity_x.dart';

class PickupTaxiView extends StatelessWidget {
  const PickupTaxiView(this.taxiViewModel, {Key? key}) : super(key: key);

  final TaxiViewModel taxiViewModel;
  @override
  Widget build(BuildContext context) {
    final borderRadius = BorderRadius.only(
      topLeft: Radius.circular(20),
      topRight: Radius.circular(20),
    );

    //
    return Positioned(
      bottom: 0,
      left: 0,
      right: 0,
      child: MeasureSize(
        onChange: (size) {
          // taxiViewModel.taxiGoogleMapManagerService
          //     .updateGoogleMapPadding(size.height + 10);
          taxiViewModel.taxiGoogleMapManagerService
              .updateGoogleMapPadding(context.percentHeight * 20);
        },
        child: SlidingUpPanel(
          borderRadius: borderRadius,
          minHeight: context.percentHeight * 20,
          panelBuilder: (ScrollController sc) => VStack(
            [
              UiSpacer.vSpace(10),
              UiSpacer.slideIndicator(),
              TaxiETAView(
                taxiViewModel,
                taxiViewModel.onGoingOrderTrip!.taxiOrder!.pickupLatLng,
              ).py12(),
              HStack(
                [
                  TaxiCustomerView(taxiViewModel.onGoingOrderTrip!.user)
                      .expand(),
                  TaxiAmountView(taxiViewModel.onGoingOrderTrip!),
                ],
              ),
              UiSpacer.divider().py8(),
              TaxiPickupView(taxiViewModel),
              UiSpacer.divider().py8(),
              TaxiContactView(taxiViewModel),
              UiSpacer.divider().py8(),
              TaxiActionSwipeView(taxiViewModel),
              UiSpacer.vSpace(),
            ],
          )
              .p12()
              .scrollVertical()
              .box
              .color(context.theme.colorScheme.surface)
              .shadow2xl
              .outerShadow
              .withRounded(value: borderRadius.topLeft.x)
              .make(),
        ),
      ),
    );
  }
}
