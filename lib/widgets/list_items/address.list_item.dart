import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/models/address.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:velocity_x/velocity_x.dart';

class AddressListItem extends StatelessWidget {
  const AddressListItem(
    this.address, {
    required this.onAddressSelected,
    Key? key,
  }) : super(key: key);

  final Address address;
  final Function(Address) onAddressSelected;
  @override
  Widget build(BuildContext context) {
    return HStack(
      [
        //
        Icon(
          FlutterIcons.location_pin_ent,
          color: Colors.white,
          size: 16,
        ).box.p4.roundedFull.color(Colors.grey.shade400).make(),
        UiSpacer.hSpace(10),
        //
        VStack(
          [
            "${address.featureName}"
                .text
                .maxLines(2)
                .ellipsis
                .minFontSize(13)
                .maxFontSize(13)
                .semiBold
                .lg
                .make(),
            //address line
            if (address.addressLine != address.featureName) 3.heightBox,
            //address
            if (address.addressLine != address.featureName)
              "${address.addressLine}"
                  .text
                  .maxLines(2)
                  .ellipsis
                  .thin
                  .light
                  .sm
                  .color(
                      context.isDarkMode ? Colors.white : Colors.grey.shade700)
                  .make(),
          ],
        ).expand(),
      ],
      crossAlignment: CrossAxisAlignment.center,
      alignment: MainAxisAlignment.center,
    ).p12().onInkTap(
      () {
        onAddressSelected(address);
      },
    ).material(color: Colors.transparent);
  }
}
