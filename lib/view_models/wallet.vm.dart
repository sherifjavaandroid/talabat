import 'dart:async';

import 'package:flutter/material.dart';
import 'package:fuodz/models/wallet.dart';
import 'package:fuodz/models/wallet_transaction.dart';
import 'package:fuodz/requests/wallet.request.dart';
import 'package:fuodz/services/app.service.dart';
import 'package:fuodz/view_models/payment.view_model.dart';
import 'package:fuodz/views/pages/wallet/wallet_transfer.page.dart';
import 'package:fuodz/widgets/bottomsheets/wallet_amount_entry.bottomsheet.dart';
import 'package:fuodz/widgets/finance/wallet_address.bottom_sheet.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:pull_to_refresh_flutter3/pull_to_refresh_flutter3.dart';
import 'package:fuodz/extensions/context.dart';

class WalletViewModel extends PaymentViewModel {
  //
  WalletViewModel(BuildContext context) {
    this.viewContext = context;
  }

  //
  WalletRequest walletRequest = WalletRequest();
  Wallet? wallet;
  RefreshController refreshController = RefreshController();
  List<WalletTransaction> walletTransactions = [];
  int queryPage = 1;
  StreamSubscription<bool>? refreshWalletBalanceSub;

  //
  initialise() async {
    await loadWalletData();

    refreshWalletBalanceSub = AppService().refreshWalletBalance.listen(
      (value) {
        loadWalletData();
      },
    );
  }

  dispose() {
    super.dispose();
    refreshWalletBalanceSub?.cancel();
  }

  //
  loadWalletData() async {
    if (refreshController.isRefresh) {
      refreshController.refreshCompleted();
    }

    getWalletBalance();
    getWalletTransactions();
  }

  //
  getWalletBalance() async {
    setBusy(true);
    try {
      wallet = await walletRequest.walletBalance();
      clearErrors();
    } catch (error) {
      setError(error);
    }
    setBusy(false);
  }

  getWalletTransactions({bool initialLoading = true}) async {
    //
    if (initialLoading) {
      setBusyForObject(walletTransactions, true);
      refreshController.refreshCompleted();
      queryPage = 1;
    } else {
      queryPage = queryPage + 1;
    }

    try {
      //
      final mWalletTransactions = await walletRequest.walletTransactions(
        page: queryPage,
      );
      //
      if (initialLoading) {
        walletTransactions = mWalletTransactions;
      } else {
        walletTransactions.addAll(mWalletTransactions);
        refreshController.loadComplete();
      }
      clearErrors();
    } catch (error) {
      print("Wallet transactions error ==> $error");
      setErrorForObject(walletTransactions, error);
    }
    setBusyForObject(walletTransactions, false);
  }

  //
  showAmountEntry() async {
    await showModalBottomSheet(
      context: viewContext,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) {
        return WalletAmountEntryBottomSheet(
          onSubmit: (String amount) {
            viewContext.pop();
            initiateWalletTopUp(amount);
          },
        );
      },
    );
  }

  //
  initiateWalletTopUp(String amount) async {
    setBusy(true);

    try {
      final link = await walletRequest.walletTopup(amount);
      // await openExternalWebpageLink(link);
      await openWebpageLink(link, embeded: true);
      clearErrors();
    } catch (error) {
      setError(error);
      toastError("$error");
      print("error >> $error");
    }
    setBusy(false);
  }

  //Wallet transfer
  showWalletTransferEntry() async {
    final result = await viewContext.push(
      (context) => WalletTransferPage(wallet!),
    );

    //
    if (result == null) {
      return;
    }
    //
    getWalletBalance();
    getWalletTransactions();
  }

  showMyWalletAddress() async {
    setBusyForObject(showMyWalletAddress, true);
    final apiResponse = await walletRequest.myWalletAddress();
    //
    if (apiResponse.allGood) {
      showModalBottomSheet(
        isScrollControlled: true,
        context: viewContext,
        isDismissible: false,
        backgroundColor: Colors.transparent,
        builder: (ctx) => WalletAddressBottomSheet(apiResponse),
      );
    } else {
      toastError(apiResponse.message ?? "Error loading wallet address".tr());
    }
    setBusyForObject(showMyWalletAddress, false);
  }
}
