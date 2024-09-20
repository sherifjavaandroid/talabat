import 'package:flutter/material.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:velocity_x/velocity_x.dart';

class ColorUtils extends Utils {
  //
  static Color shuffleColorByMode(
    BuildContext context, {
    required Color lightMode,
    required Color darkMode,
  }) {
    //check if the current theme is dark or light
    return context.isDarkMode ? darkMode : lightMode;
  }
}
