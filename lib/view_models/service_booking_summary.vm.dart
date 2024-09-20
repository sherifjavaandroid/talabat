import 'package:cool_alert/cool_alert.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/models/checkout.dart';
import 'package:fuodz/models/coupon.dart';
import 'package:fuodz/models/payment_method.dart';
import 'package:fuodz/models/service.dart';
import 'package:fuodz/requests/cart.request.dart';
import 'package:fuodz/requests/payment_method.request.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/services/alert.service.dart';
import 'package:fuodz/services/app.service.dart';
import 'package:fuodz/view_models/checkout_base.vm.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:fuodz/extensions/context.dart';

class ServiceBookingSummaryViewModel extends CheckoutBaseViewModel {
  //
  List<Map> calFees = [];
  //
  ServiceBookingSummaryViewModel(BuildContext context, this.service) {
    this.viewContext = context;
    vendor = service!.vendor;
    AppService().vendorId = vendor?.id;
    fetchPaymentOptions();

    //prepare checkout
    checkout = CheckOut();
    final subTotal = double.parse(
      ((service!.showDiscount ? service!.discountPrice! : service!.price) *
              (!(service!.isFixed) ? (service!.selectedQty ?? 1) : 1))
          .toString(),
    );
    checkout!.subTotal = subTotal;
    //add price of selected options
    service!.selectedOptions.forEach((option) {
      checkout!.subTotal += option.price;
    });
  }
//
  CartRequest cartRequest = CartRequest();
  PaymentMethodRequest paymentOptionRequest = PaymentMethodRequest();
  TextEditingController noteTEC = TextEditingController();
  //coupons
  bool canApplyCoupon = false;
  Coupon? coupon;
  TextEditingController couponTEC = TextEditingController();

  //
  CheckOut? checkout = CheckOut();
  Service? service;
  double subTotal = 0.0;
  double total = 0.0;
  final currencySymbol = AppStrings.currencySymbol;
  //
  List<PaymentMethod> paymentMethods = [];
  PaymentMethod? selectedPaymentMethod;

  void initialise() async {
    fetchPaymentOptions();
    updateTotalOrderSummary();
  }

  //get payment options
  fetchPaymentOptions({int? vendorId}) async {
    setBusyForObject(paymentMethods, true);
    try {
      paymentMethods = await paymentOptionRequest.getPaymentOptions(
        vendorId: vendorId ?? service!.vendor.id,
      );
      //
      clearErrors();
    } catch (error) {
      print("Error getting payment methods ==> $error");
    }
    setBusyForObject(paymentMethods, false);
  }

  isSelected(PaymentMethod paymentMethod) {
    return paymentMethod.id == selectedPaymentMethod?.id;
  }

  @override
  changeSelectedPaymentMethod(
    PaymentMethod? paymentMethod, {
    bool callTotal = true,
  }) {
    selectedPaymentMethod = paymentMethod;
    checkout?.paymentMethod = paymentMethod;
    notifyListeners();
  }

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
        vendorTypeId: vendor!.vendorType.id,
      );
      //
      if (coupon == null) {
        throw "Invalid coupon code".tr();
      }
      //
      if (coupon!.useLeft <= 0) {
        throw "Coupon use limit exceeded".tr();
      } else if (coupon!.expired) {
        throw "Coupon has expired".tr();
      }
      clearErrors();
      //re-calculate the cart price with coupon
      //
      if (coupon!.percentage == 1) {
        checkout!.discount = (coupon!.discount / 100) * checkout!.subTotal;
      } else {
        checkout!.discount = coupon!.discount;
      }
      //
      updateTotalOrderSummary();
    } catch (error) {
      print("error ==> $error");
      setErrorForObject("coupon", error);
    }
    setBusyForObject("coupon", false);
  }

  //
  @override
  updateTotalOrderSummary() async {
    //
    Map<String, dynamic> payload = {
      "delivery_address_id": deliveryAddress?.id,
      "coupon_code": checkout!.coupon?.code ?? "",
      "vendor_id": vendor!.id,
      "service_id": service!.id,
      "options_ids": service!.selectedOptions.map((e) => e.id).toList(),
      "qty": service!.selectedQty ?? 1,
    };

    setBusy(true);
    try {
      final mCheckout = await checkoutRequest.serviceOrderSummary(payload);
      checkout!.copyWith(
        subTotal: mCheckout.subTotal,
        discount: mCheckout.discount,
        deliveryFee: mCheckout.deliveryFee,
        tax: mCheckout.tax,
        total: mCheckout.total,
        totalWithTip: mCheckout.totalWithTip,
        token: mCheckout.token,
        fees: mCheckout.fees,
      );
    } catch (error) {
      print("Error getting order summary ==> $error");
      toastError("$error");
    }
    setBusy(false);
    //
    notifyListeners();
  }

  //
  placeOrder({bool ignore = false}) async {
    //
    if (isScheduled && checkout!.deliverySlotDate.isEmptyOrNull) {
      //
      AlertService.error(
        title: "Schedule Date".tr(),
        text: "Please select your desire order date".tr(),
      );
    } else if (isScheduled && checkout!.deliverySlotTime.isEmptyOrNull) {
      //
      AlertService.error(
        title: "Schedule Time".tr(),
        text: "Please select your desire order time".tr(),
      );
    } else if (!isPickup && service!.location && deliveryAddress == null) {
      //
      AlertService.error(
        title: "Booking address".tr(),
        text: "Please select booking address".tr(),
      );
    } else if (service!.location && delievryAddressOutOfRange && !isPickup) {
      //
      AlertService.error(
        title: "Booking address".tr(),
        text: "Booking address is out of vendor booking range".tr(),
      );
    } else if (selectedPaymentMethod == null) {
      AlertService.error(
        title: "Payment Methods".tr(),
        text: "Please select a payment method".tr(),
      );
    } else if (!ignore && !verifyVendorOrderAmountCheck()) {
      print("Failed");
    }
    //process the new order
    else {
      processOrderPlacement();
    }
  }

  //
  processOrderPlacement() async {
    //process the order placement
    setBusy(true);
    //set the total with discount as the new total
    checkout!.total = checkout!.totalWithTip;
    //
    final apiResponse = await checkoutRequest.newServiceOrder(
      checkout!,
      fees: calFees,
      service: service!,
      service_amount: checkout!.subTotal,
      note: noteTEC.text,
    );
    //not error
    if (apiResponse.allGood) {
      //cash payment

      final paymentLink = apiResponse.body["link"].toString();
      if (!paymentLink.isEmptyOrNull) {
        viewContext.pop();
        showOrdersTab(context: viewContext);
        openWebpageLink(paymentLink);
      }
      //cash payment
      else {
        CoolAlert.show(
            context: viewContext,
            type: CoolAlertType.success,
            title: "Checkout".tr(),
            text: apiResponse.message,
            barrierDismissible: false,
            onConfirmBtnTap: () {
              viewContext.pop(true);
              showOrdersTab(context: viewContext);
            });
      }
    } else {
      CoolAlert.show(
        context: viewContext,
        type: CoolAlertType.error,
        title: "Checkout".tr(),
        text: apiResponse.message,
      );
    }
    setBusy(false);
  }
}
