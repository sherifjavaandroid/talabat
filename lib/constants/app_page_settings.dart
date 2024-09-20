import 'package:fuodz/constants/app_strings.dart';

class AppPageSettings extends AppStrings {
  //
  static int get maxDriverDocumentCount {
    try {
      if (AppStrings.env('page') == null ||
          AppStrings.env('page')["settings"] == null) {
        return 2;
      }
      return int.parse(
          AppStrings.env('page')['settings']["driverDocumentCount"].toString());
    } catch (error) {
      return 2;
    }
  }

  static String get driverDocumentInstructions {
    if (AppStrings.env('page') == null ||
        AppStrings.env('page')["settings"] == null) {
      return "";
    }
    return AppStrings.env('page')['settings']["driverDocumentInstructions"];
  }
}
