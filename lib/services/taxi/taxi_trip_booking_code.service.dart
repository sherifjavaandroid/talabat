import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_taxi_settings.dart';
import 'package:fuodz/view_models/taxi/taxi.vm.dart';
import 'package:fuodz/views/pages/taxi/widgets/taxi_booking_code_verification_bottomsheet.dart';
import 'package:localize_and_translate/localize_and_translate.dart';

class TaxiTripBookingCodeService {
  static Future<void> handle(
    TaxiViewModel taxiViewModel,
    String nextOrderStatus,
  ) async {
    //check if booking code is required for taxi trip
    if (AppTaxiSettings.requiredBookingCode) {
      //check if the status is starting trip and check if bokking code is required
      if ((nextOrderStatus == "enroute" &&
              AppTaxiSettings.requiredBookingCodeBeforeTrip) ||
          (["delivered", "compeleted", "success", "successful"]
                  .contains(nextOrderStatus) &&
              AppTaxiSettings.requiredBookingCodeAfterTrip)) {
        //
        final orderVerifyCode =
            taxiViewModel.onGoingOrderTrip?.verificationCode;
        final result = await showModalBottomSheet(
          context: taxiViewModel.viewContext,
          backgroundColor: Colors.transparent,
          builder: (ctx) {
            return TaxiBookingCodeVerificationBottomSheet(
                orderVerifyCode ?? "");
          },
        );
        //
        if (result == null || !(result as bool)) {
          throw "Taxi Booking verification code is invalid".tr();
        }
      }
    }
  }
}
