import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_routes.dart';
import 'package:fuodz/models/service.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class ServiceProviderSection extends StatelessWidget {
  const ServiceProviderSection({
    required this.service,
    super.key,
  });
  //
  final Service service;

  //
  @override
  Widget build(BuildContext context) {
    return VStack(
      [
        "Provider".tr().text.medium.xl.make(),
        HStack(
          [
            //provider logo
            CustomImage(
              imageUrl: service.vendor.logo,
              width: 50,
              height: 50,
            ).box.roundedSM.clip(Clip.antiAlias).make(),
            //provider details
            VStack(
              [
                service.vendor.name.text.semiBold.lg.make(),
                "${service.vendor.phone}".text.medium.sm.make(),
                "${service.vendor.address}".text.light.sm.maxLines(1).make(),
              ],
            ).px12().expand(),
          ],
        )
            .box
            .p8
            .color(context.theme.colorScheme.surface)
            .roundedSM
            .make()
            .onInkTap(
          () {
            Navigator.of(context).pushNamed(
              AppRoutes.vendorDetails,
              arguments: service.vendor,
            );
          },
        ),
      ],
      spacing: 6,
    );
  }
}
