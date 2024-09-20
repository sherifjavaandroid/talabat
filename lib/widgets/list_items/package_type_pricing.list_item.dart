import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/extensions/dynamic.dart';
import 'package:fuodz/models/package_type_pricing.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:localize_and_translate/localize_and_translate.dart';

class PackageTypePricingListItem extends StatelessWidget {
  //
  const PackageTypePricingListItem(
    this.packageTypePricing, {
    this.isLoading = false,
    required this.onEditPressed,
    required this.onToggleStatusPressed,
    required this.onDeletePressed,
    Key? key,
  }) : super(key: key);

  //
  final PackageTypePricing packageTypePricing;
  final bool isLoading;
  final Function(PackageTypePricing) onEditPressed;
  final Function(PackageTypePricing) onToggleStatusPressed;
  final Function(PackageTypePricing) onDeletePressed;
  @override
  Widget build(BuildContext context) {
    //
    final currencySymbol = AppStrings.currencySymbol;

    //
    return VStack(
      [
        VStack(
          [
            HStack(
              [
                //
                CustomImage(imageUrl: packageTypePricing.packageType.photo)
                    .wh(Vx.dp64, Vx.dp64)
                    .box
                    .clip(Clip.antiAlias)
                    .roundedSM
                    .make(),

                //Details
                //
                VStack(
                  [
                    //name
                    packageTypePricing.packageType.name.text.xl.semiBold.make(),
                    //name
                    "${packageTypePricing.packageType.description}"
                        .text
                        .light
                        .maxLines(2)
                        .overflow(TextOverflow.ellipsis)
                        .make()
                  ],
                ).expand(),
              ],
            ),

            //
            //Package & disctance price
            HStack(
              [
                //
                VStack(
                  [
                    //base price
                    "Base Price".tr().text.sm.medium.make(),
                    //description
                    "$currencySymbol ${packageTypePricing.basePrice}"
                        .currencyFormat()
                        .text
                        .lg
                        .semiBold
                        .make(),
                  ],
                ).expand(),

                //
                VStack(
                  [
                    //Package price
                    "Package Price".tr().text.sm.medium.make(),
                    //description
                    "$currencySymbol ${packageTypePricing.sizePrice}"
                        .currencyFormat()
                        .text
                        .lg
                        .semiBold
                        .make(),
                  ],
                ).expand(),
                //
                VStack(
                  [
                    //Distance price
                    "Distance Price".tr().text.sm.medium.make(),
                    //description
                    "$currencySymbol ${packageTypePricing.distancePrice}"
                        .currencyFormat()
                        .text
                        .lg
                        .semiBold
                        .make(),
                  ],
                ).expand(),
              ],
              crossAlignment: CrossAxisAlignment.start,
              alignment: MainAxisAlignment.spaceBetween,
            ).px20().py12(),

            //Auto ready & extra fileds
            HStack(
              [
                VStack(
                  [
                    //Package price
                    "Auto Ready".tr().text.sm.medium.make(),
                    //description
                    "${packageTypePricing.autoReady}"
                        .text
                        .color(packageTypePricing.autoReady
                            ? Colors.green
                            : Colors.red)
                        .lg
                        .semiBold
                        .make(),
                  ],
                ).expand(),
                //
                VStack(
                  [
                    //Distance price
                    "Extra fields".tr().text.sm.medium.make(),
                    "${packageTypePricing.extraFields}"
                        .text
                        .color(packageTypePricing.extraFields
                            ? Colors.green
                            : Colors.red)
                        .lg
                        .semiBold
                        .make(),
                  ],
                ).expand(),

                ////Max booking days
                VStack(
                  [
                    //
                    "Max booking days".tr().text.sm.medium.make(),
                    //description
                    "${packageTypePricing.maxBookingDays}"
                        .text
                        .lg
                        .semiBold
                        .make(),
                  ],
                ).expand(),
              ],
              crossAlignment: CrossAxisAlignment.start,
              alignment: MainAxisAlignment.spaceBetween,
            ).px20(),
          ],
        ).p8(),

        UiSpacer.divider(),
        //actions
        HStack(
          [
            //
            CustomButton(
              loading: isLoading,
              height: 30,
              icon: FlutterIcons.edit_fea,
              // title: "Edit",
              onPressed: () => onEditPressed(packageTypePricing),
              color: Colors.grey,
            ),
            CustomButton(
              loading: isLoading,
              height: 30,
              icon: packageTypePricing.isActive != 1
                  ? FlutterIcons.check_ant
                  : FlutterIcons.close_ant,
              // title:
              //     (packageTypePricing.isActive != 1 ? "Activate" : "Deactivate")
              //         .tr(),
              onPressed: () => onToggleStatusPressed(packageTypePricing),
              color: packageTypePricing.isActive != 1
                  ? Colors.green
                  : Colors.red[400],
            ).px12(),
            CustomButton(
              loading: isLoading,
              height: 30,
              icon: FlutterIcons.delete_ant,
              // title: "Delete".tr(),
              onPressed: () => onDeletePressed(packageTypePricing),
              color: Colors.red,
            ),
          ],
          alignment: MainAxisAlignment.spaceEvenly,
          crossAlignment: CrossAxisAlignment.center,
        ).centered().p8(),
      ],
    ).box.outerShadow.color(context.cardColor).make();
  }
}
