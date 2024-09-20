import 'package:fuodz/constants/api.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:package_info_plus/package_info_plus.dart';

class AppDynamicLink extends AppStrings {
  static String get dynamicLinkPrefix {
    try {
      if (AppStrings.env('dynamic_link') == null) {
        return Api.baseUrl;
      }
      return AppStrings.env('dynamic_link')["prefix"].toString();
    } catch (e) {
      print(e);
      return Api.baseUrl;
    }
  }

  static Future<String> get androidDynamicLinkId async {
    if (AppStrings.env('dynamic_link') == null) {
      final platformInfo = await PackageInfo.fromPlatform();
      return platformInfo.packageName;
    }
    return AppStrings.env('dynamic_link')["android"].toString();
  }

  static Future<String> get iOSDynamicLinkId async {
    if (AppStrings.env('dynamic_link') == null) {
      final platformInfo = await PackageInfo.fromPlatform();
      return platformInfo.packageName;
    }
    return AppStrings.env('dynamic_link')["ios"].toString();
  }
}
