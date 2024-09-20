import 'package:flutter/material.dart';
import 'package:fuodz/services/auth.service.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/taxi/taxi.vm.dart';
import 'package:fuodz/views/pages/taxi/widgets/online_status_swipe.btn.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:measure_size/measure_size.dart';
import 'package:velocity_x/velocity_x.dart';

class IdleTaxiView extends StatelessWidget {
  const IdleTaxiView(this.taxiViewModel, {Key? key}) : super(key: key);

  final TaxiViewModel taxiViewModel;
  @override
  Widget build(BuildContext context) {
    return Positioned(
      bottom: 0,
      left: 0,
      right: 0,
      child: MeasureSize(
        onChange: (size) {
          taxiViewModel.taxiGoogleMapManagerService
              .updateGoogleMapPadding(size.height + 10);
        },
        child: VStack(
          [
            //
            Visibility(
              visible: taxiViewModel.appService.driverIsOnline,
              child: VStack(
                [
                  LinearProgressIndicator(
                    minHeight: 4,
                  ).wFull(context),
                  "Searching for order"
                      .tr()
                      .text
                      .extraBold
                      .sm
                      .makeCentered()
                      .p8(),
                ],
              ),
            ),

            //Online/offline
            OnlineStatusSwipeButton(taxiViewModel),
            //
            VStack(
              [
                "Vehicle Type".tr().text.light.make(),
                HStack(
                  [
                    CustomImage(
                            imageUrl:
                                AuthServices.driverVehicle!.vehicleType.photo)
                        .wh(32, 32),
                    UiSpacer.hSpace(5),
                    "${AuthServices.driverVehicle!.vehicleType.name}"
                        .text
                        .xl
                        .semiBold
                        .make()
                        .expand(),
                  ],
                ),
                UiSpacer.vSpace(),
                "Vehicle Details".tr().text.thin.make(),
                HStack(
                  [
                    "${AuthServices.driverVehicle?.carModel.carMake?.name} (${AuthServices.driverVehicle?.carModel.name}) - ${AuthServices.driverVehicle?.regNo} - ${AuthServices.driverVehicle?.color.toUpperCase()}"
                        .text
                        .xl
                        .semiBold
                        .make()
                        .expand(),
                  ],
                ),
              ],
            ).p20(),
          ],
        )
            .box
            .color(context.theme.colorScheme.surface)
            .shadow2xl
            .outerShadow
            .make(),
      ),
    );
  }
}
