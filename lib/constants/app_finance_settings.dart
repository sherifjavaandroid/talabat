import 'package:fuodz/constants/app_strings.dart';

class AppFinanceSettings extends AppStrings {
  static bool get collectDeliveryFeeInCash {
    try {
      final allowCashDeliveryFee =
          AppStrings.env('finance')["delivery"]["collectDeliveryCash"];
      if (allowCashDeliveryFee == "1" || allowCashDeliveryFee == 1) {
        return true;
      }
    } catch (error) {}

    return false;
  }
}
