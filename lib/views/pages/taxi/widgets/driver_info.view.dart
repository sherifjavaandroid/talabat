import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/models/driver.dart';
import 'package:fuodz/models/order.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class TaxiDriverInfoView extends StatelessWidget {
  const TaxiDriverInfoView(
    this.driver, {
    required this.order,
    Key? key,
  }) : super(key: key);
  final Order order;
  final Driver driver;
  @override
  Widget build(BuildContext context) {
    return VStack(
      [
        // basic info
        HStack(
          [
            //
            CustomImage(
              imageUrl: driver.photo,
              width: 50,
              height: 50,
            ).box.roundedFull.clip(Clip.antiAlias).make(),
            //driver info
            VStack(
              [
                "${driver.name}".text.medium.xl.make(),
                //rating
                VxRating(
                  size: 14,
                  maxRating: 5.0,
                  value: driver.rating ?? 0.0,
                  isSelectable: false,
                  onRatingUpdate: (value) {},
                  selectionColor: AppColor.ratingColor,
                ),
              ],
            ).px12().expand(),
            //vehicle info
            VStack(
              [
                "${driver.vehicle?.reg_no}".text.xl2.semiBold.make(),
                "${driver.vehicle?.vehicleInfo}".text.medium.sm.make(),
              ],
              crossAlignment: CrossAxisAlignment.end,
            ),
          ],
        ),

        //handling driver status
        "${driverTripStatus(order)}"
            .text
            .xl
            .color(Utils.textColorByColor(AppColor.getStausColor(order.status)))
            .makeCentered()
            .p4()
            .box
            .withRounded(value: 5)
            .border(
              color: AppColor.getStausColor(order.status).withOpacity(0.6),
            )
            .color(AppColor.getStausColor(order.status))
            .make()
            .pOnly(top: 20),
      ],
    );
  }

  String driverTripStatus(Order order) {
    //if order is not null, convert status to human readable representation of driver status
    //status: 'pending','preparing','ready','enroute','delivered','failed','cancelled'
    switch (order.status) {
      case "pending":
        return "Searching for driver".tr();
      case "preparing":
        return "Driver on the way to you".tr();
      case "ready":
        return "Driver has arrived your pickup location".tr();
      case "enroute":
        return "Driver enroute to dropoff location".tr();
      case "delivered":
        return "Driver has dropped you".tr();
      case "completed":
        return "Trip completed".tr();
      case "failed":
        return "Trip failed".tr();
      default:
        return "Driver is on the way".tr();
    }
  }
}
