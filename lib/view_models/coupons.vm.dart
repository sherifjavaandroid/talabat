import 'package:flutter/src/widgets/framework.dart';
import 'package:fuodz/models/coupon.dart';
import 'package:fuodz/models/vendor_type.dart';
import 'package:fuodz/requests/coupon.request.dart';
import 'package:fuodz/view_models/base.view_model.dart';
import 'package:fuodz/views/pages/coupon/coupon_details.page.dart';
import 'package:velocity_x/velocity_x.dart';

class CouponsViewModel extends MyBaseViewModel {
  CouponsViewModel(
    BuildContext context,
    this.vendorType, {
    this.coupon,
    this.byLocation = false,
  }) {
    this.viewContext = context;
  }

  //
  List<Coupon> coupons = [];
  Coupon? coupon;
  VendorType? vendorType;
  bool byLocation;
  CouponRequest couponRequest = CouponRequest();

  //
  initialise() {
    fetchCoupons();
  }

  //
  fetchCoupons() async {
    setBusy(true);
    try {
      //filter by location if user selects delivery address
      coupons = await couponRequest.fetchCoupons(
        params: {
          "vendor_type_id": vendorType?.id,
        },
      );

      clearErrors();
    } catch (error) {
      print("error loading coupons ==> $error");
      setError(error);
    }
    setBusy(false);
  }

  couponSelected(Coupon coupon) async {
    viewContext.nextPage(CouponDetailsPage(coupon));
  }

  fetchCouponDetails() async {
    setBusyForObject("coupon", true);
    try {
      coupon = await couponRequest.fetchCoupon(coupon!.id);
    } catch (error) {
      toastError("$error");
      setError(error);
    }
    setBusyForObject("coupon", false);
  }
}
