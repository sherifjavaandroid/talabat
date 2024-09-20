import 'package:fuodz/constants/app_strings.dart';

class AppUISettings extends AppStrings {
  //CHAT UI
  static bool get canVendorChat {
    if (AppStrings.env('ui') == null || AppStrings.env('ui')["chat"] == null) {
      return true;
    }
    return AppStrings.env('ui')['chat']["canVendorChat"] == "1";
  }

  static bool get canVendorChatSupportMedia {
    if (AppStrings.env('ui') == null || AppStrings.env('ui')["chat"] == null) {
      return true;
    }
    try {
      dynamic isSupportMedia =
          AppStrings.env('ui')['chat']["canVendorChatSupportMedia"] ?? false;
      return (isSupportMedia is bool
          ? isSupportMedia
          : int.parse("$isSupportMedia") == 1);
    } catch (e) {
      return false;
    }
  }

  static bool get canCustomerChat {
    if (AppStrings.env('ui') == null || AppStrings.env('ui')["chat"] == null) {
      return true;
    }
    return AppStrings.env('ui')['chat']["canCustomerChat"] == "1";
  }

  static bool get canDriverChat {
    if (AppStrings.env('ui') == null || AppStrings.env('ui')["chat"] == null) {
      return true;
    }
    return AppStrings.env('ui')['chat']["canDriverChat"] == "1";
  }

  //call
  static bool get canCallDriver {
    if (AppStrings.env('ui') == null || AppStrings.env('ui')["call"] == null) {
      return true;
    }
    return [1, "1"]
        .contains(AppStrings.env('ui')['call']["canDriverVendorCall"]);
  }

  static bool get canCallCustomer {
    if (AppStrings.env('ui') == null || AppStrings.env('ui')["call"] == null) {
      return true;
    }
    return [1, "1"]
        .contains(AppStrings.env('ui')['call']["canCustomerVendorCall"]);
  }
}
