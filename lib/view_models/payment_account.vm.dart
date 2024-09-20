import 'package:flutter/material.dart';
import 'package:fuodz/models/payment_account.dart';
import 'package:fuodz/requests/payment_account.request.dart';
import 'package:fuodz/view_models/base.view_model.dart';
import 'package:fuodz/views/pages/payment_account/edit_payment_account.page.dart';
import 'package:fuodz/views/pages/payment_account/new_payment_account.page.dart';
import 'package:pull_to_refresh_flutter3/pull_to_refresh_flutter3.dart';
import 'package:fuodz/extensions/context.dart';

class PaymentAccountViewModel extends MyBaseViewModel {
  //
  PaymentAccountViewModel(BuildContext context) {
    this.viewContext = context;
  }

  //
  PaymentAccountRequest paymentAccountRequest = PaymentAccountRequest();
  RefreshController refreshController = RefreshController();

  List<PaymentAccount> paymentAccounts = [];
  int queryPage = 1;

  //
  initialise() async {
    await getPaymentAccounts();
  }

  getPaymentAccounts({bool initialLoading = true}) async {
    //
    if (initialLoading) {
      setBusyForObject(paymentAccounts, true);
      refreshController.refreshCompleted();
      queryPage = 1;
    } else {
      queryPage = queryPage + 1;
    }

    try {
      //
      final mWalletTransactions = await paymentAccountRequest.paymentAccounts(
        page: queryPage,
      );
      //
      if (initialLoading) {
        paymentAccounts = mWalletTransactions;
      } else {
        paymentAccounts.addAll(mWalletTransactions);
        refreshController.loadComplete();
      }
      clearErrors();
    } catch (error) {
      print("paymentAccounts error ==> $error");
      setErrorForObject(paymentAccounts, error);
    }
    setBusyForObject(paymentAccounts, false);
  }

  //

  void openNewPaymentAccount() async {
    final result = await viewContext.push(
      (context) => NewPaymentAccountPage(),
    );

    //
    if (result != null && result is PaymentAccount) {
      paymentAccounts.insert(0, result);
      notifyListeners();
    }
  }

  void editPaymentAccount(PaymentAccount paymentAccount) async {
    final result = await viewContext.push(
      (context) => EditPaymentAccountPage(paymentAccount),
    );

    //
    if (result != null && result is PaymentAccount) {
      //
      final index = paymentAccounts.indexWhere(
        (element) => paymentAccount.id == element.id,
      );
      paymentAccounts[index] = result;
      notifyListeners();
    }
  }
}
