import 'package:fuodz/constants/app_strings.dart';
import 'package:supercharged/supercharged.dart';

class AppTaxiSettings extends AppStrings {
  static bool get requiredBookingCode {
    if (AppStrings.env('taxi') == null ||
        AppStrings.env('taxi')["requestBookingCode"] == null) {
      return false;
    }
    return true;
  }

  static bool get requiredBookingCodeBeforeTrip {
    if (AppStrings.env('taxi') == null ||
        AppStrings.env('taxi')["requestBookingCode"] == null) {
      return false;
    }
    return ["both", "before"]
        .contains(AppStrings.env('taxi')["requestBookingCode"]);
  }

  static bool get requiredBookingCodeAfterTrip {
    if (AppStrings.env('taxi') == null ||
        AppStrings.env('taxi')["requestBookingCode"] == null) {
      return false;
    }
    return ["both", "after"]
        .contains(AppStrings.env('taxi')["requestBookingCode"]);
  }

  //
  static bool get showTaxiPickupInfo {
    if (AppStrings.env('taxi') == null ||
        AppStrings.env('taxi')["showTaxiPickupInfo"] == null) {
      return true;
    }

    //
    if (AppStrings.env('taxi')["showTaxiPickupInfo"] is bool) {
      return AppStrings.env('taxi')["showTaxiPickupInfo"];
    }

    //
    int value =
        AppStrings.env('taxi')["showTaxiPickupInfo"].toString().toInt() ?? 0;
    return value == 1;
  }

  static bool get showTaxiDropoffInfo {
    if (AppStrings.env('taxi') == null ||
        AppStrings.env('taxi')["showTaxiDropoffInfo"] == null) {
      return true;
    }

    //
    if (AppStrings.env('taxi')["showTaxiDropoffInfo"] is bool) {
      return AppStrings.env('taxi')["showTaxiDropoffInfo"];
    }

    //
    int value =
        AppStrings.env('taxi')["showTaxiDropoffInfo"].toString().toInt() ?? 0;
    return value == 1;
  }
}
