import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_images.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class EmptySearch extends StatelessWidget {
  const EmptySearch({
    this.type = "",
    Key? key,
  }) : super(key: key);
  final String type;
  @override
  Widget build(BuildContext context) {
    return VStack(
      [
        //Image
        Image.asset(
          AppImages.emptySearch,
          height: context.screenWidth * 0.6,
          width: context.screenWidth * 0.6,
          fit: BoxFit.contain,
        ).centered(),
        15.heightBox,
        //title
        getTitle(type).text.xl2.semiBold.center.makeCentered(),
        5.heightBox,
        //body
        getBody(type).text.center.lg.medium.makeCentered(),
      ],
      crossAlignment: CrossAxisAlignment.center,
    ).p(12);
  }

  String getTitle(String type) {
    switch (type) {
      case "vendor":
        return "No Vendor Found".tr();
      case "product":
        return "No Product Found".tr();
      case "service":
        return "No Service Found".tr();
      default:
        return "No Result Found".tr();
    }
  }

  String getBody(String type) {
    switch (type) {
      case "vendor":
        return "Sorry, no vendor match your search. Please try again or explore different categories. Check back for updates. Happy searching!"
            .tr();
      case "product":
        return "Sorry, no product match your search. Please try again or explore different categories. Check back for updates. Happy searching!"
            .tr();
      case "service":
        return "Sorry, no service match your search. Please try again or explore different categories. Check back for updates. Happy searching!"
            .tr();
      default:
        return "There seems to be no result".tr();
    }
  }
}
