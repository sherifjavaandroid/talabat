import 'package:cool_alert/cool_alert.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/models/currency.dart';
import 'package:fuodz/models/earning.dart';
import 'package:fuodz/models/payment_account.dart';
import 'package:fuodz/requests/payment_account.request.dart';
import 'package:fuodz/services/auth.service.dart';
import 'package:fuodz/view_models/base.view_model.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:fuodz/extensions/context.dart';

class PayoutViewModel extends MyBaseViewModel {
  //

  PaymentAccountRequest paymentAccountRequest = PaymentAccountRequest();
  Currency? currency;
  Earning? earning;
  bool showPayout = false;
  List<PaymentAccount>? paymentAccounts = [];
  PaymentAccount? selectedPaymentAccount;
  TextEditingController amountTEC = TextEditingController();
  double totalEarningAmount;

  PayoutViewModel(BuildContext context, this.totalEarningAmount) {
    this.viewContext = context;
  }

  void initialise() async {
    fetchPaymentAccounts();
  }

  void fetchPaymentAccounts() async {
    //
    if (paymentAccounts != null && paymentAccounts!.isNotEmpty) {
      return;
    }
    //
    setBusyForObject(paymentAccounts, true);
    try {
      paymentAccounts = (await paymentAccountRequest.paymentAccounts(page: 0))
          .filter((e) => e.isActive)
          .toList();
    } catch (error) {
      print("paymentAccounts error ==> $error");
    }
    setBusyForObject(paymentAccounts, false);
  }

  processPayoutRequest() async {
    //
    if (selectedPaymentAccount == null) {
      toastError("Please select payment account".tr());
      //
    } else if (formKey.currentState!.validate()) {
      setBusyForObject(selectedPaymentAccount, true);
      //
      final apiResponse = await paymentAccountRequest.requestPayout(
        {
          "amount": amountTEC.text,
          "payment_account_id": selectedPaymentAccount?.id,
          "vendor_id": (await AuthServices.getCurrentUser()).vendor_id,
        },
      );
      //
      CoolAlert.show(
        context: viewContext,
        type: apiResponse.allGood ? CoolAlertType.success : CoolAlertType.error,
        title: "Request Payout".tr(),
        text:
            apiResponse.allGood ? "Successful".tr() : "${apiResponse.message}",
        onConfirmBtnTap: apiResponse.allGood
            ? () {
                viewContext.pop();
                viewContext.pop();
              }
            : null,
      );

      setBusyForObject(selectedPaymentAccount, false);
    }
  }
}
