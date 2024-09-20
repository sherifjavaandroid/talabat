import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_ui_sizes.dart';
import 'package:fuodz/models/coupon.dart';
import 'package:fuodz/requests/taxi.request.dart';
import 'package:fuodz/services/geocoder.service.dart';
import 'package:fuodz/view_models/base.view_model.dart';
import 'package:fuodz/view_models/taxi.vm.dart';
import 'package:fuodz/views/shared/payment_method_selection.page.dart';
import 'package:fuodz/views/pages/taxi/apply_discount.page.dart';
import 'package:sliding_up_panel/sliding_up_panel.dart';
import 'package:fuodz/extensions/context.dart';

class NewTaxiOrderSummaryViewModel extends MyBaseViewModel {
  //
  NewTaxiOrderSummaryViewModel(BuildContext context, this.taxiViewModel) {
    this.viewContext = context;
  }

  TaxiRequest taxiRequest = TaxiRequest();
  GeocoderService geocoderService = GeocoderService();
  final TaxiViewModel taxiViewModel;
  PanelController panelController = PanelController();
  double customViewHeight = AppUISizes.taxiNewOrderSummaryHeight;

  initialise() {}

  //
  updateLoadingheight() {
    customViewHeight = AppUISizes.taxiNewOrderHistoryHeight;
    notifyListeners();
  }

  resetStateViewheight([double height = 0]) {
    customViewHeight = AppUISizes.taxiNewOrderIdleHeight + height;
    notifyListeners();
  }

  closePanel() async {
    clearFocus();
    await panelController.close();
    notifyListeners();
  }

  clearFocus() {
    FocusScope.of(taxiViewModel.viewContext).requestFocus(new FocusNode());
  }

  openPanel() async {
    await panelController.open();
    notifyListeners();
  }

  void openPaymentMethodSelection() async {
    //
    if (taxiViewModel.paymentMethods.isEmpty) {
      await taxiViewModel.fetchTaxiPaymentOptions();
    }
    final mPaymentMethod = await viewContext.push(
      (context) => PaymentMethodSelectionPage(
        list: taxiViewModel.paymentMethods,
      ),
    );
    if (mPaymentMethod != null) {
      taxiViewModel.changeSelectedPaymentMethod(
        mPaymentMethod,
        callTotal: false,
      );
    }

    notifyListeners();
  }

  void openCoupnDialog() async {
    //
    final result = await Navigator.of(viewContext).push(
      MaterialPageRoute(
        builder: (context) => ApplyDiscountPage(
          coupon: taxiViewModel.coupon,
        ),
      ),
    );
    //
    if (result != null && result is Coupon) {
      this.taxiViewModel.coupon = result;
      this.taxiViewModel.calculateTotalAmount();
    } else if (result != null && result is bool && result == false) {
      this.taxiViewModel.coupon = null;
      this.taxiViewModel.calculateTotalAmount();
    }
  }
}
