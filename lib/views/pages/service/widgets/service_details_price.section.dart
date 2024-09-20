import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/models/service.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:localize_and_translate/localize_and_translate.dart';

class ServiceDetailsPriceSectionView extends StatelessWidget {
  const ServiceDetailsPriceSectionView(this.service,
      {this.onlyPrice = false, Key? key})
      : super(key: key);

  final Service service;
  final bool onlyPrice;

  @override
  Widget build(BuildContext context) {
    return HStack(
      [
        "${AppStrings.currencySymbol}"
            .text
            .xl
            .medium
            .color(AppColor.primaryColor)
            .make(),
        service.price.text.semiBold.color(AppColor.primaryColor).xl3.make(),
        ("/" + "${service.duration}".tr()).text.medium.xl2.make(),
        UiSpacer.horizontalSpace(space: 5),
        //discount
        Visibility(
          visible: !onlyPrice,
          child: service.showDiscount
              ? "${AppStrings.currencySymbol}${service.price - service.discountPrice} ${'Off'.tr()}"
                  .text
                  .white
                  .semiBold
                  .make()
                  .p2()
                  .px4()
                  .box
                  .red500
                  .roundedLg
                  .make()
              : UiSpacer.emptySpace(),
        ),
        //
        UiSpacer.emptySpace().expand(),
        //rating
        Visibility(
          visible: !onlyPrice,
          child: VxRating(
            value: double.parse((service.vendor?.rating ?? 5.0).toString()),
            count: 5,
            isSelectable: false,
            onRatingUpdate: (value) {},
            selectionColor: AppColor.ratingColor,
            normalColor: Colors.grey,
          ),
        ),
      ],
    );
  }
}
