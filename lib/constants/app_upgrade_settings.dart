import 'dart:io';

import 'package:fuodz/constants/app_strings.dart';
import 'package:package_info/package_info.dart';

class AppUpgradeSettings extends AppStrings {
  static Future<bool> showUpgrade() async {
    await AppStrings.getAppSettingsFromLocalStorage();
    if (AppStrings.env('upgrade') != null &&
        AppStrings.env('upgrade')["driver"] != null) {
      final androidNewVersion = int.parse(
        AppStrings.env('upgrade')["driver"]["android"].toString(),
      );
      final iosNewVersion = int.parse(
        AppStrings.env('upgrade')["driver"]["ios"].toString(),
      );

      //
      final packageInfo = await PackageInfo.fromPlatform();

      //
      return int.parse(packageInfo.buildNumber) <
          (Platform.isIOS ? iosNewVersion : androidNewVersion);
    }
    return false;
  }

  static bool forceUpgrade() {
    if (AppStrings.env('upgrade') != null &&
        AppStrings.env('upgrade')["driver"] != null) {
      final force = int.parse(
        AppStrings.env('upgrade')["driver"]["force"].toString(),
      );
      return force == 1;
    }
    return false;
  }
}
