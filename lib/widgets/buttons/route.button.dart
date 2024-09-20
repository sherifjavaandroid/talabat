import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/models/vendor.dart';
import 'package:map_launcher/map_launcher.dart';
import 'package:url_launcher/url_launcher_string.dart';
import 'package:velocity_x/velocity_x.dart';

class RouteButton extends StatelessWidget {
  const RouteButton(
    this.vendor, {
    this.lat,
    this.lng,
    Key? key,
  }) : super(key: key);

  final Vendor? vendor;
  final double? lat;
  final double? lng;
  @override
  Widget build(BuildContext context) {
    double locLat =
        double.tryParse("${this.vendor?.latitude}") ?? this.lat ?? 0.0;
    double locLng =
        double.tryParse("${this.vendor?.longitude}") ?? this.lng ?? 0.0;

    //
    if (vendor == null && (lat == null || lng == null)) {
      return SizedBox.shrink();
    }

    return Icon(
      FlutterIcons.navigation_fea,
      size: 24,
      color: Colors.white,
    ).p8().box.color(AppColor.primaryColor).roundedSM.make().onInkTap(() async {
      //
      if (await MapLauncher.isMapAvailable(MapType.google) ?? false) {
        await MapLauncher.showDirections(
          mapType: MapType.google,
          destination: Coords(
            locLat,
            locLng,
          ),
          destinationTitle: vendor != null ? vendor?.name : "",
        );
      } else if (await MapLauncher.isMapAvailable(MapType.apple) ?? false) {
        await MapLauncher.showDirections(
          mapType: MapType.apple,
          destination: Coords(
            locLat,
            locLng,
          ),
          destinationTitle: vendor != null ? vendor?.name : "",
        );
      } else {
        String googleUrl = 'comgooglemaps://?center=$lat,$lng';
        String appleUrl = 'https://maps.apple.com/?sll=$lat,$lng';
        if (await canLaunchUrlString("comgooglemaps://")) {
          await launchUrlString(googleUrl);
        } else if (await canLaunchUrlString(appleUrl)) {
          await launchUrlString(appleUrl);
        } else {
          throw 'Could not launch url';
        }
      }
    });
  }
}
