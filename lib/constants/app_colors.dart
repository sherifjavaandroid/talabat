import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/services/local_storage.service.dart';
import 'package:velocity_x/velocity_x.dart';

class AppColor {
  static Color get accentColor => Vx.hexToColor(colorEnv('accentColor'));
  static Color get primaryColor => Vx.hexToColor(colorEnv('primaryColor'));
  static Color get primaryColorDark =>
      Vx.hexToColor(colorEnv('primaryColorDark'));
  static Color get cursorColor => accentColor;

  //material color
  static MaterialColor get accentMaterialColor => MaterialColor(
        Vx.getColorFromHex(colorEnv('accentColor')),
        <int, Color>{
          50: Vx.hexToColor(colorEnv('accentColor')),
          100: Vx.hexToColor(colorEnv('accentColor')),
          200: Vx.hexToColor(colorEnv('accentColor')),
          300: Vx.hexToColor(colorEnv('accentColor')),
          400: Vx.hexToColor(colorEnv('accentColor')),
          500: Vx.hexToColor(colorEnv('accentColor')),
          600: Vx.hexToColor(colorEnv('accentColor')),
          700: Vx.hexToColor(colorEnv('accentColor')),
          800: Vx.hexToColor(colorEnv('accentColor')),
          900: Vx.hexToColor(colorEnv('accentColor')),
        },
      );
  static MaterialColor get primaryMaterialColor => MaterialColor(
        Vx.getColorFromHex(colorEnv('primaryColor')),
        <int, Color>{
          50: Vx.hexToColor(colorEnv('primaryColor')),
          100: Vx.hexToColor(colorEnv('primaryColor')),
          200: Vx.hexToColor(colorEnv('primaryColor')),
          300: Vx.hexToColor(colorEnv('primaryColor')),
          400: Vx.hexToColor(colorEnv('primaryColor')),
          500: Vx.hexToColor(colorEnv('primaryColor')),
          600: Vx.hexToColor(colorEnv('primaryColor')),
          700: Vx.hexToColor(colorEnv('primaryColor')),
          800: Vx.hexToColor(colorEnv('primaryColor')),
          900: Vx.hexToColor(colorEnv('primaryColor')),
        },
      );

  //onboarding colors
  static Color get onboarding1Color =>
      Vx.hexToColor(colorEnv('onboarding1Color'));
  static Color get onboarding2Color =>
      Vx.hexToColor(colorEnv('onboarding2Color'));
  static Color get onboarding3Color =>
      Vx.hexToColor(colorEnv('onboarding3Color'));

  static Color get onboardingIndicatorDotColor =>
      Vx.hexToColor(colorEnv('onboardingIndicatorDotColor'));
  static Color get onboardingIndicatorActiveDotColor =>
      Vx.hexToColor(colorEnv('onboardingIndicatorActiveDotColor'));

  //Shimmer Colors
  static Color shimmerBaseColor = Colors.grey.shade300;
  static Color shimmerHighlightColor = Colors.grey.shade200;

  //inputs
  static Color get inputFillColor => Colors.grey.shade200;
  static Color get iconHintColor => Colors.grey.shade500;

  static Color get openColor => Vx.hexToColor(colorEnv('openColor'));
  static Color get closeColor => Vx.hexToColor(colorEnv('closeColor'));
  static Color get deliveryColor => Vx.hexToColor(colorEnv('deliveryColor'));
  static Color get pickupColor => Vx.hexToColor(colorEnv('pickupColor'));
  static Color get ratingColor => Vx.hexToColor(colorEnv('ratingColor'));

  static Color getStausColor(String status) {
    String color = "#000000";
    //'pending','preparing','enroute','failed','cancelled','delivered'
    switch (status) {
      case "pending":
        color = colorEnv('pendingColor');
        break;
      case "preparing":
        color = colorEnv('preparingColor');
        break;
      case "enroute":
        color = colorEnv('enrouteColor');
        break;
      case "failed":
        color = colorEnv('failedColor');
        break;
      case "cancelled":
        color = colorEnv('cancelledColor');
        break;
      case "delivered":
        color = colorEnv('deliveredColor');
        break;
      case "successful":
        color = colorEnv('successfulColor');
        break;
      default:
        color = colorEnv('pendingColor');
    }

    return Vx.hexToColor(color);
  }

  //saving
  static Future<bool> saveColorsToLocalStorage(String colorsMap) async {
    return await LocalStorageService.prefs!
        .setString(AppStrings.appColors, colorsMap);
  }

  static dynamic appColorsObject;
  static Future<void> getColorsFromLocalStorage() async {
    appColorsObject =
        LocalStorageService.prefs!.getString(AppStrings.appColors);
    if (appColorsObject != null) {
      appColorsObject = jsonDecode(appColorsObject);
    }
  }

  static String colorEnv(String colorRef) {
    //
    getColorsFromLocalStorage();
    //
    final selectedColor =
        appColorsObject != null ? appColorsObject[colorRef] : "#000000";
    return selectedColor;
  }
}
