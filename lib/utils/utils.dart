import 'dart:io';

import 'package:basic_utils/basic_utils.dart';
import 'package:eva_icons_flutter/eva_icons_flutter.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:flutter_image_compress/flutter_image_compress.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/models/vendor.dart';
import 'package:fuodz/services/http.service.dart';
import 'package:fuodz/views/pages/package_types/package_type_pricing.page.dart';
import 'package:fuodz/views/pages/product/products.page.dart';
import 'package:fuodz/views/pages/service/service.page.dart';

import 'package:html/parser.dart';
import 'package:jiffy/jiffy.dart';
import 'package:localize_and_translate/localize_and_translate.dart';

class Utils {
  //
  static bool get isArabic => translator.activeLocale.languageCode == "ar";

  static TextDirection get textDirection =>
      isArabic ? TextDirection.rtl : TextDirection.ltr;
  //
  static IconData vendorIconIndicator(Vendor? vendor) {
    return ((vendor == null || (!vendor.isPackageType && !vendor.isServiceType))
        ? EvaIcons.archiveOutline
        : vendor.isServiceType
            ? EvaIcons.radioOutline
            : EvaIcons.cubeOutline);
  }

  //
  static String vendorTypeIndicator(Vendor? vendor) {
    return ((vendor == null || (!vendor.isPackageType && !vendor.isServiceType))
        ? 'Products'
        : vendor.isServiceType
            ? "Services"
            : 'Pricing');
  }

  static Widget vendorSectionPage(Vendor? vendor) {
    return ((vendor == null || (!vendor.isPackageType && !vendor.isServiceType))
        ? ProductsPage()
        : vendor.isServiceType
            ? ServicePage()
            : PackagePricingPage());
  }

  static bool get currencyLeftSided {
    final uiConfig = AppStrings.uiConfig;
    if (uiConfig != null && uiConfig["currency"] != null) {
      final currencylOCATION = uiConfig["currency"]["location"] ?? 'left';
      return currencylOCATION.toLowerCase() == "left";
    } else {
      return true;
    }
  }

  static String removeHTMLTag(String str) {
    var document = parse(str);
    return parse(document.body?.text).documentElement?.text ?? str;
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

  static Color textColorByTheme() {
    return isPrimaryColorDark() ? Colors.white : Colors.black;
  }

  static Color textColorByColor(Color color) {
    return isPrimaryColorDark(color) ? Colors.white : Colors.black;
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

  static setJiffyLocale() async {
    String cLocale = translator.activeLocale.languageCode;
    List<String> supportedLocales = Jiffy.getSupportedLocales();
    if (supportedLocales.contains(cLocale)) {
      await Jiffy.setLocale(translator.activeLocale.languageCode);
    } else {
      await Jiffy.setLocale("en");
    }
  }

  //
  static bool isDefaultImg(String? url) {
    return url == null ||
        url.isEmpty ||
        url == "default.png" ||
        url == "default.jpg" ||
        url == "default.jpeg" ||
        url.contains("default.png");
  }

  //
  //
  //get country code
  static Future<String> getCurrentCountryCode() async {
    String countryCode = "US";
    try {
      //make request to get country code
      final response = await HttpService().dio.get(
            "http://ip-api.com/json/?fields=countryCode",
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
