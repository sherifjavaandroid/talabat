import 'dart:io';

import 'package:basic_utils/basic_utils.dart';
import 'package:dio/dio.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/models/vendor.dart';
import 'package:fuodz/services/http.service.dart';
import 'package:fuodz/services/location.service.dart';
import 'package:geolocator/geolocator.dart';
import 'package:jiffy/jiffy.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:flutter_image_compress/flutter_image_compress.dart';

class Utils {
  static bool get isArabic => translator.activeLocale.languageCode == "ar";

  static TextDirection get textDirection =>
      isArabic ? TextDirection.rtl : TextDirection.ltr;

  static bool get currencyLeftSided {
    final uiConfig = AppStrings.uiConfig;
    if (uiConfig != null && uiConfig["currency"] != null) {
      final currencylOCATION = uiConfig["currency"]["location"] ?? 'left';
      return currencylOCATION.toLowerCase() == "left";
    } else {
      return true;
    }
  }

  static bool isDark(Color color) {
    return ColorUtils.calculateRelativeLuminance(
            color.red, color.green, color.blue) <
        0.5;
  }

  static bool isPrimaryColorDark([Color? mColor]) {
    final color = mColor ?? AppColor.primaryColor;
    return ColorUtils.calculateRelativeLuminance(
            color.red, color.green, color.blue) <
        0.5;
  }

  static Color textColorByTheme([bool reversed = false]) {
    if (reversed) {
      return !isPrimaryColorDark() ? Colors.white : Colors.black;
    }
    return isPrimaryColorDark() ? Colors.white : Colors.black;
  }

  static Color textColorByBrightness(BuildContext context,
      [bool reversed = false]) {
    if (reversed) {
      return !context.isDarkMode ? Colors.white : Colors.black;
    }
    return context.isDarkMode ? Colors.white : Colors.black;
  }

  static Color textColorByColor(Color color) {
    return isPrimaryColorDark(color) ? Colors.white : Colors.black;
  }

  static Color textColorByPrimaryColor() {
    return isPrimaryColorDark() ? Colors.white : Colors.black;
  }

  static Color greayColorByBrightness(BuildContext context) {
    return context.isDarkMode ? Colors.grey[300]! : Colors.grey[400]!;
  }

  static setJiffyLocale() async {
    String cLocale = translator.activeLocale.languageCode;
    List<String> supportedLocales = Jiffy.getAllAvailableLocales();
    if (supportedLocales.contains(cLocale)) {
      await Jiffy.locale(translator.activeLocale.languageCode);
    } else {
      await Jiffy.locale("en");
    }
  }

  static Future<File?> compressFile({
    required File file,
    String? targetPath,
    int quality = 40,
    CompressFormat format = CompressFormat.jpeg,
  }) async {
    if (targetPath == null) {
      targetPath =
          "${file.parent.path}/compressed_${file.path.split('/').last}";
    }

    if (kDebugMode) {
      print("file path ==> $targetPath");
    }

    FlutterImageCompress.validator.ignoreCheckExtName = true;
    var result = await FlutterImageCompress.compressAndGetFile(
      file.absolute.path,
      targetPath,
      quality: quality,
      format: format,
    );
    if (kDebugMode) {
      print("unCompress file size ==> ${file.lengthSync()}");
      if (result != null) {
        print("Compress file size ==> ${result.lengthSync()}");
      } else {
        print("compress failed");
      }
    }

    return result;
  }

  static bool isDefaultImg(String? url) {
    return url == null ||
        url.isEmpty ||
        url == "default.png" ||
        url == "default.jpg" ||
        url == "default.jpeg" ||
        url.contains("default.png");
  }

  //get vendor distance to current location
  static double vendorDistance(Vendor vendor) {
    if (vendor.latitude.isEmptyOrNull || vendor.longitude.isEmptyOrNull) {
      return 0;
    }

    //if location service current location is not available
    if (LocationService.currenctAddress == null) {
      return 0;
    }

    //get distance
    double distance = Geolocator.distanceBetween(
      LocationService.currenctAddress?.coordinates?.latitude ?? 0,
      LocationService.currenctAddress?.coordinates?.longitude ?? 0,
      double.parse(vendor.latitude),
      double.parse(vendor.longitude),
    );

    //convert distance to km
    distance = distance / 1000;
    return distance;
  }

  //
  //get country code
  static Future<String> getCurrentCountryCode() async {
    String countryCode = "US";
    try {
      //make request to get country code
      final response = await HttpService().dio!.get(
            "http://ip-api.com/json/?fields=countryCode",
            //timeout like 2seconds
            options: Options(
              receiveTimeout: 2000,
              sendTimeout: 2000,
            ),
          );
      //get the country code
      countryCode = response.data["countryCode"];
    } catch (e) {
      try {
        countryCode = AppStrings.countryCode
            .toUpperCase()
            .replaceAll("AUTO", "")
            .replaceAll("INTERNATIONAL", "")
            .split(",")[0];
      } catch (e) {
        countryCode = "us";
      }
    }

    return countryCode.toUpperCase();
  }
}
