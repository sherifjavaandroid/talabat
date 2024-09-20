import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:supercharged/supercharged.dart';

class AppUIStyles extends AppStrings {
  //
  static int themeUIStyle() {
    dynamic uiEnv = AppStrings.env("ui");
    if (uiEnv == null ||
        uiEnv["home"] == null ||
        uiEnv['home']["homeViewStyle"] == null) {
      return 1;
    }
    return (uiEnv['home']["homeViewStyle"].toString().toInt()) ?? 1;
  }

  static bool get isModern => themeUIStyle() == 2;
  static bool get isOriginal => [1, null].contains(themeUIStyle());

//   vendortypeHeight
// vendortypeWidth
// vendortypeImageStyle

  //vendor type sizes
  static double get vendorTypeWidth {
    try {
      dynamic uiEnv = AppStrings.env("ui");
      if (uiEnv == null ||
          uiEnv["home"] == null ||
          uiEnv['home']["vendortypeWidth"] == null) {
        return double.infinity;
      }
      return double.tryParse(uiEnv['home']["vendortypeWidth"].toString()) ??
          double.infinity;
    } catch (e) {
      print(e);
      return double.infinity;
    }
  }

  static double get vendorTypeHeight {
    try {
      dynamic uiEnv = AppStrings.env("ui");
      if (uiEnv == null ||
          uiEnv["home"] == null ||
          uiEnv['home']["vendortypeHeight"] == null) {
        return 60;
      }
      return (uiEnv['home']["vendortypeHeight"].toString().toDouble()) ?? 60;
    } catch (e) {
      print(e);
      return 60;
    }
  }

  static BoxFit get vendorTypeImageStyle {
    dynamic uiEnv = AppStrings.env("ui");
    if (uiEnv == null ||
        uiEnv["home"] == null ||
        uiEnv['home']["vendortypeImageStyle"] == null) {
      return BoxFit.cover;
    }

    //
    BoxFit boxFit = BoxFit.cover;

    switch (uiEnv['home']["vendortypeImageStyle"].toString()) {
      case "center":
        boxFit = BoxFit.contain;
        break;
      case "contain":
        boxFit = BoxFit.contain;
        break;
      case "cover":
        boxFit = BoxFit.cover;
        break;
      case "fill":
        boxFit = BoxFit.fill;
        break;
      case "none":
        boxFit = BoxFit.none;
        break;
      case "scaleDown":
        boxFit = BoxFit.scaleDown;
        break;
      default:
        return BoxFit.cover;
    }

    return boxFit;
  }
}
