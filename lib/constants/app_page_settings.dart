import 'package:fuodz/constants/app_strings.dart';

class AppPageSettings extends AppStrings {
  //
  static int get maxVendorDocumentCount {
    try {
      if (AppStrings.env('page') == null ||
          AppStrings.env('page')["settings"] == null) {
        return 2;
      }
      return int.parse(
          AppStrings.env('page')['settings']["vendorDocumentCount"].toString());
    } catch (e) {
      return 2;
    }
  }

  static String get vendorDocumentInstructions {
    if (AppStrings.env('page') == null ||
        AppStrings.env('page')["settings"] == null) {
      return "";
    }
    return AppStrings.env('page')['settings']["vendorDocumentInstructions"];
  }
}
