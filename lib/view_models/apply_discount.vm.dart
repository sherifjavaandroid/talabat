import 'package:flutter/material.dart';
import 'package:fuodz/models/coupon.dart';
import 'package:fuodz/requests/cart.request.dart';
import 'package:fuodz/view_models/base.view_model.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class ApplyDiscountViewModel extends MyBaseViewModel {
  //
  ApplyDiscountViewModel(BuildContext context, this.coupon) {
    this.viewContext = context;
  }

  CartRequest cartRequest = CartRequest();
  Coupon? coupon;
  bool canApplyCoupon = false;
  TextEditingController couponTEC = TextEditingController();

  //functions
  void initialise() async {}

  couponCodeChange(String code) {
    canApplyCoupon = code.isNotBlank;
    notifyListeners();
  }

  //
  applyCoupon() async {
    //
    setBusyForObject("coupon", true);
    try {
      coupon = await cartRequest.fetchCoupon(
        couponTEC.text,
        vendorTypeId: vendorType?.id,
      );
      if (coupon == null) {
        throw "Coupon not found".tr();
      }
      //
      if (coupon!.useLeft <= 0) {
        coupon = null;
        throw "Coupon use limit exceeded".tr();
      } else if (coupon!.expired) {
        coupon = null;
        throw "Coupon has expired".tr();
      }
      clearErrors();
      setBusyForObject("coupon", false);
      //pop the page and show the coupon
      Navigator.pop(viewContext, coupon);

      //
    } catch (error) {
      print("error ==> $error");
      setErrorForObject("coupon", error);
      setBusyForObject("coupon", false);
    }
  }
}
