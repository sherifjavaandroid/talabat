import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/view_models/base.view_model.dart';
import 'package:fuodz/widgets/cards/custom.visibility.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class VendorHeader extends StatefulWidget {
  const VendorHeader({
    Key? key,
    required this.model,
    this.showSearch = true,
    this.bottomPadding = true,
    required this.onrefresh,
  }) : super(key: key);

  final MyBaseViewModel model;
  final bool showSearch;
  final bool bottomPadding;
  final Function onrefresh;

  @override
  _VendorHeaderState createState() => _VendorHeaderState();
}

class _VendorHeaderState extends State<VendorHeader> {
  @override
  void initState() {
    super.initState();
    //
    if (widget.model.deliveryaddress == null) {
      widget.model.fetchCurrentLocation();
    }
  }

  @override
  Widget build(BuildContext context) {
    return HStack(
      [
        //
        HStack(
          [
            //location icon
            Icon(
              FlutterIcons.location_pin_sli,
              size: 24,
            ).onInkTap(
              widget.model.useUserLocation,
            ),

            //
            VStack(
              [
                //
                HStack(
                  [
                    //
                    "Delivery Location".tr().text.sm.semiBold.make(),
                    //
                    Icon(
                      FlutterIcons.chevron_down_fea,
                    ).px4(),
                  ],
                ),
                "${widget.model.deliveryaddress?.address}"
                    .text
                    .maxLines(1)
                    .ellipsis
                    .base
                    .make(),
              ],
            )
                .onInkTap(() {
                  widget.model.pickDeliveryAddress(
                    vendorCheckRequired: false,
                    onselected: widget.onrefresh,
                  );
                })
                .px12()
                .expand(),
          ],
        ).expand(),

        //
        CustomVisibilty(
          visible: widget.showSearch,
          child: Icon(
            FlutterIcons.search_fea,
            size: 20,
          )
              .p8()
              .onInkTap(() {
                widget.model.openSearch();
              })
              .box
              .roundedSM
              .clip(Clip.antiAlias)
              .color(context.theme.colorScheme.surface)
              .outerShadowSm
              .make(),
        ),
      ],
    )
        .p12()
        .box
        .color(context.theme.colorScheme.surface)
        .bottomRounded()
        .outerShadowSm
        .make()
        .pOnly(bottom: widget.bottomPadding ? Vx.dp20 : 0);
  }
}
