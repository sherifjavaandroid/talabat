import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/constants/app_ui_settings.dart';
import 'package:fuodz/models/vendor.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/widgets/buttons/call.button.dart';
import 'package:fuodz/widgets/buttons/route.button.dart';
import 'package:fuodz/widgets/html_text_view.dart';
import 'package:fuodz/widgets/tags/close.tag.dart';
import 'package:fuodz/widgets/tags/delivery.tag.dart';
import 'package:fuodz/widgets/tags/open.tag.dart';
import 'package:fuodz/widgets/tags/pickup.tag.dart';
import 'package:fuodz/widgets/tags/time.tag.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class VendorFullProfileBottomSheet extends StatelessWidget {
  const VendorFullProfileBottomSheet(this.vendor, {super.key});
  final Vendor vendor;
  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      height: context.percentHeight * 90,
      child: VStack(
        [
          UiSpacer.swipeIndicator().py(12),
          //name
          "${vendor.name}".text.xl2.bold.make().px(12),

          //tags
          Wrap(
            children: [
              //is open
              vendor.isOpen ? OpenTag() : CloseTag(),

              //can deliveree
              if (vendor.delivery == 1) DeliveryTag(),

              //can pickup
              if (vendor.pickup == 1) PickupTag(),

              //prepare time
              TimeTag(
                "${vendor.prepareTime} ${vendor.prepareTimeUnit}",
                iconData: FlutterIcons.clock_outline_mco,
              ),
              //delivery time
              TimeTag(
                "${vendor.deliveryTime} ${vendor.deliveryTimeUnit}",
                iconData: FlutterIcons.ios_bicycle_ion,
              ),
            ],
            spacing: 12,
            runSpacing: 12,
            crossAxisAlignment: WrapCrossAlignment.center,
          ).px(12),

          VStack(
            [
              //address
              if (vendor.address.isNotEmptyAndNotNull &&
                  AppUISettings.showVendorAddress)
                HStack(
                  [
                    "${vendor.address}".text.lg.make().expand(),
                    RouteButton(vendor, size: 22),
                  ],
                  spacing: 10,
                  crossAlignment: CrossAxisAlignment.center,
                  alignment: MainAxisAlignment.center,
                ),

              // phone
              if (vendor.phone.isNotEmptyAndNotNull &&
                  AppUISettings.showVendorPhone)
                HStack(
                  [
                    "${vendor.phone}".text.lg.make().expand(),
                    CallButton(vendor, size: 22),
                  ],
                  spacing: 10,
                  crossAlignment: CrossAxisAlignment.center,
                  alignment: MainAxisAlignment.center,
                ),
            ],
            spacing: 8,
          ).px(12),
          //working hours
          Divider(color: Colors.grey.shade400, thickness: 1.6),
          VStack(
            [
              "Working hours".tr().text.bold.uppercase.make(),
              //
              VStack(
                vendor.days.map(
                  (opDay) {
                    return HStack(
                      [
                        "${opDay.name}".tr().text.medium.make().expand(),
                        "${opDay.openTime} - ${opDay.closeTime}"
                            .text
                            .medium
                            .make(),
                      ],
                      alignment: MainAxisAlignment.spaceBetween,
                    );
                  },
                ).toList(),
                spacing: 4,
              ),

              // empty: days
              Visibility(
                visible: vendor.days.isEmpty,
                child: HStack(
                  [
                    ("Sunday".tr() + " - " + "Saturday".tr())
                        .text
                        .medium
                        .make()
                        .expand(),
                    "All Hours".tr().text.medium.make(),
                  ],
                  alignment: MainAxisAlignment.spaceBetween,
                ),
              ),
            ],
            spacing: 4,
          ).px(12),

          //description
          Divider(color: Colors.grey.shade400, thickness: 1.6),
          VStack(
            [
              "Description".tr().text.bold.uppercase.make(),
              HtmlTextView(
                vendor.description,
                padding: EdgeInsets.zero,
              ),
            ],
            spacing: 4,
          ).px(12),
        ],
        spacing: 10,
      ).scrollVertical(),
    );
  }
}
