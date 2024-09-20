import 'package:flutter/material.dart';
import 'package:fuodz/models/checkout.dart';
import 'package:fuodz/view_models/checkout_base.vm.dart';

class CheckoutViewModel extends CheckoutBaseViewModel {
  //

  //
  CheckoutViewModel(
    BuildContext context,
    CheckOut checkout,
  ) {
    this.viewContext = context;
    this.checkout = checkout;
  }
}
