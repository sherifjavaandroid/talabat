import 'package:cool_alert/cool_alert.dart';
import 'package:dartx/dartx.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_routes.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/extensions/string.dart';
import 'package:fuodz/models/checkout.dart';
import 'package:fuodz/models/delivery_address.dart';
import 'package:fuodz/models/vendor.dart';
import 'package:fuodz/models/payment_method.dart';
import 'package:fuodz/requests/checkout.request.dart';
import 'package:fuodz/requests/delivery_address.request.dart';
import 'package:fuodz/requests/vendor.request.dart';
import 'package:fuodz/requests/payment_method.request.dart';
import 'package:fuodz/services/app.service.dart';
import 'package:fuodz/services/cart.service.dart';
import 'package:fuodz/view_models/payment.view_model.dart';
import 'package:fuodz/widgets/bottomsheets/delivery_address_picker.bottomsheet.dart';
import 'package:jiffy/jiffy.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:fuodz/extensions/context.dart';

class CheckoutBaseViewModel extends PaymentViewModel {
  //
  CheckoutRequest checkoutRequest = CheckoutRequest();
  DeliveryAddressRequest deliveryAddressRequest = DeliveryAddressRequest();
  PaymentMethodRequest paymentOptionRequest = PaymentMethodRequest();

  VendorRequest vendorRequest = VendorRequest();
  TextEditingController driverTipTEC = TextEditingController();
  TextEditingController noteTEC = TextEditingController();
  DeliveryAddress? deliveryAddress;
  bool isPickup = false;
  bool isScheduled = false;
  List<String> availableTimeSlots = [];
  bool delievryAddressOutOfRange = false;
  bool canSelectPaymentOption = true;
  Vendor? vendor;
  CheckOut? checkout;
  bool calculateTotal = true;

  //
  List<PaymentMethod> paymentMethods = [];
  PaymentMethod? selectedPaymentMethod;
  //
  bool paymentTermsAgreed = false;

  void initialise() async {
    await fetchVendorDetails();
    prefetchDeliveryAddress();
    fetchPaymentOptions();
    updateTotalOrderSummary();
  }

  //
  fetchVendorDetails() async {
    //
    vendor = CartServices.productsInCart[0].product?.vendor;

    //
    setBusy(true);
    try {
      vendor = await vendorRequest.vendorDetails(
        vendor!.id,
        params: {
          "type": "brief",
        },
      );
      setVendorRequirement();
    } catch (error) {
      print("Error Getting Vendor Details ==> $error");
    }
    setBusy(false);
  }

  setVendorRequirement() {
    if (vendor!.allowOnlyDelivery) {
      isPickup = false;
    } else if (vendor!.allowOnlyPickup) {
      isPickup = true;
    }
  }

  //start of schedule related
  changeSelectedDeliveryDate(String string, int index) {
    checkout?.deliverySlotDate = string;
    availableTimeSlots = vendor!.deliverySlots[index].times;
    notifyListeners();
  }

  changeSelectedDeliveryTime(String time) {
    checkout?.deliverySlotTime = time;
    notifyListeners();
  }

  //end of schedule related
  //
  prefetchDeliveryAddress() async {
    setBusyForObject(deliveryAddress, true);
    //
    try {
      //
      checkout!.deliveryAddress = deliveryAddress =
          await deliveryAddressRequest.preselectedDeliveryAddress(
        vendorId: vendor?.id,
      );

      if (checkout?.deliveryAddress != null) {
        //
        checkDeliveryRange();
        updateTotalOrderSummary();
      }
    } catch (error) {
      print("Error Fetching preselected Address ==> $error");
    }
    setBusyForObject(deliveryAddress, false);
  }

  //
  fetchPaymentOptions({int? vendorId}) async {
    setBusyForObject(paymentMethods, true);
    try {
      paymentMethods = await paymentOptionRequest.getPaymentOptions(
        vendorId: vendorId != null ? vendorId : vendor?.id,
        params: {
          "is_pickup": isPickup ? 1 : 0,
        },
      );
      //
      updatePaymentOptionSelection();
      clearErrors();
    } catch (error) {
      print("Regular Error getting payment methods ==> $error");
    }
    setBusyForObject(paymentMethods, false);
  }

  //
  fetchTaxiPaymentOptions() async {
    setBusyForObject(paymentMethods, true);
    try {
      paymentMethods = await paymentOptionRequest.getTaxiPaymentOptions();
      //
      updatePaymentOptionSelection();
      clearErrors();
    } catch (error) {
      print("Taxi Error getting payment methods ==> $error");
    }
    setBusyForObject(paymentMethods, false);
  }

  updatePaymentOptionSelection() {
    if (checkout != null && checkout!.total <= 0.00) {
      canSelectPaymentOption = false;
    } else {
      canSelectPaymentOption = true;
    }
    //
    if (!canSelectPaymentOption) {
      final selectedPaymentMethod = paymentMethods.firstOrNullWhere(
        (e) => e.isCash == 1,
      );
      changeSelectedPaymentMethod(selectedPaymentMethod, callTotal: false);
    }
  }

  //
  Future<DeliveryAddress> showDeliveryAddressPicker() async {
    //
    final mDeliveryAddress = await showModalBottomSheet(
      context: viewContext,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) {
        return DeliveryAddressPicker(
          onSelectDeliveryAddress: (deliveryAddress) {
            this.deliveryAddress = deliveryAddress;
            checkout?.deliveryAddress = deliveryAddress;
            //
            checkDeliveryRange();
            updateTotalOrderSummary();
            //
            notifyListeners();
            viewContext.pop(deliveryAddress);
          },
        );
      },
    );
    return mDeliveryAddress;
  }

  //
  togglePickupStatus(bool? value) {
    //
    if (vendor!.allowOnlyPickup) {
      value = true;
    } else if (vendor!.allowOnlyDelivery) {
      value = false;
    }
    isPickup = value ?? false;
    //remove delivery address if pickup
    if (isPickup) {
      checkout?.deliveryAddress = null;
    } else {
      checkout?.deliveryAddress = deliveryAddress;
    }

    updateTotalOrderSummary();
    notifyListeners();
    fetchPaymentOptions();
  }

  //
  toggleScheduledOrder(bool? value) async {
    isScheduled = value ?? false;
    checkout?.isScheduled = isScheduled;
    //remove delivery address if pickup
    checkout?.pickupDate = null;
    checkout?.deliverySlotDate = "";
    checkout?.pickupTime = null;
    checkout?.deliverySlotTime = "";

    await Jiffy.locale(translator.activeLocale.languageCode);

    notifyListeners();
  }

  //
  void checkDeliveryRange() {
    delievryAddressOutOfRange =
        vendor!.deliveryRange < (deliveryAddress!.distance ?? 0);
    if (deliveryAddress?.can_deliver != null) {
      delievryAddressOutOfRange = (deliveryAddress?.can_deliver ?? false) ==
          false; //if vendor has set delivery range
    }
    notifyListeners();
  }

  //
  isSelected(PaymentMethod paymentMethod) {
    return paymentMethod.id == selectedPaymentMethod?.id;
  }

  changeSelectedPaymentMethod(
    PaymentMethod? paymentMethod, {
    bool callTotal = true,
  }) {
    selectedPaymentMethod = paymentMethod;
    checkout?.paymentMethod = paymentMethod;
    if (callTotal) {
      updateTotalOrderSummary();
    }
    notifyListeners();
  }

  //update total/order amount summary
  updateTotalOrderSummary() async {
    //generate order summary
    Map<String, dynamic> payload = {
      "pickup": isPickup ? 1 : 0,
      "delievryAddressOutOfRange": delievryAddressOutOfRange ? 1 : 0,
      "tip": driverTipTEC.text,
      "delivery_address_id": deliveryAddress?.id,
      "coupon_code": checkout!.coupon?.code ?? "",
      "vendor_id": vendor!.id,
      "products":
          CartServices.productsInCart.map((e) => e.toCheckout()).toList(),
    };

    setBusy(true);
    try {
      final mCheckout = await checkoutRequest.orderSummary(payload);
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
    updatePaymentOptionSelection();
    notifyListeners();
  }

  //
  bool pickupOnlyProduct() {
    //
    final product = CartServices.productsInCart.firstOrNullWhere(
      (e) => !e.product?.canBeDelivered,
    );

    return product != null;
  }

  //
  placeOrder({bool ignore = false}) async {
    //
    if (isScheduled && checkout!.deliverySlotDate.isEmptyOrNull) {
      //
      CoolAlert.show(
        context: viewContext,
        type: CoolAlertType.error,
        title: "Delivery Date".tr(),
        text: "Please select your desire order date".tr(),
      );
    } else if (isScheduled && checkout!.deliverySlotTime.isEmptyOrNull) {
      //
      CoolAlert.show(
        context: viewContext,
        type: CoolAlertType.error,
        title: "Delivery Time".tr(),
        text: "Please select your desire order time".tr(),
      );
    } else if (!isPickup && pickupOnlyProduct()) {
      //
      CoolAlert.show(
        context: viewContext,
        type: CoolAlertType.error,
        title: "Product".tr(),
        text:
            "There seems to be products that can not be delivered in your cart"
                .tr(),
      );
    } else if (!isPickup && deliveryAddress == null) {
      //
      CoolAlert.show(
        context: viewContext,
        type: CoolAlertType.error,
        title: "Delivery address".tr(),
        text: "Please select delivery address".tr(),
      );
    } else if (delievryAddressOutOfRange && !isPickup) {
      //
      CoolAlert.show(
        context: viewContext,
        type: CoolAlertType.error,
        title: "Delivery address".tr(),
        text: "Delivery address is out of vendor delivery range".tr(),
      );
    } else if (selectedPaymentMethod == null) {
      CoolAlert.show(
        context: viewContext,
        type: CoolAlertType.error,
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
    final apiResponse = await checkoutRequest.newOrder(
      checkout!,
      tip: driverTipTEC.text,
      note: noteTEC.text,
    );

    //notify wallet view to update, just incase wallet was use for payment
    AppService().refreshWalletBalance.add(true);
    CartServices.refreshState();

    //not error
    if (apiResponse.allGood) {
      //cash payment

      final paymentLink = apiResponse.body["link"].toString();
      if (!paymentLink.isEmptyOrNull) {
        viewContext.pop();
        showOrdersTab(context: viewContext);
        dynamic result;
        // if (["offline", "razorpay"]
        if (["offline"].contains(checkout!.paymentMethod?.slug ?? "offline")) {
          result = await openExternalWebpageLink(paymentLink);
        } else {
          result = await openWebpageLink(paymentLink);
        }
        print("Result from payment ==> $result");
      }
      //cash payment
      else {
        CoolAlert.show(
            context: viewContext,
            type: CoolAlertType.success,
            title: "Checkout".tr(),
            text: apiResponse.message,
            confirmBtnText: "Ok".tr(),
            barrierDismissible: false,
            onConfirmBtnTap: () {
              viewContext.pop(true);
              showOrdersTab(context: viewContext);

              if (Navigator.of(viewContext).canPop()) {
                Navigator.of(viewContext).popUntil(
                  (route) => route == AppRoutes.homeRoute || route.isFirst,
                );
              }
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

  //
  showOrdersTab({
    required BuildContext context,
  }) {
    //clear cart items
    CartServices.clearCart();
    //switch tab to orders
    AppService().changeHomePageIndex(index: 1);
    //pop until home page
    if (Navigator.of(context).canPop()) {
      Navigator.of(context).popUntil(
        (route) => route.settings.name == AppRoutes.homeRoute || route.isFirst,
      );
    }
  }

  //
  bool verifyVendorOrderAmountCheck() {
    //if vendor set min/max order
    final orderVendor = checkout?.cartItems?.first.product?.vendor ?? vendor;
    //if order is less than the min allowed order by this vendor
    //if vendor is currently open for accepting orders

    if (!vendor!.isOpen &&
        !(checkout!.isScheduled ?? false) &&
        !(checkout!.isPickup ?? false)) {
      //vendor is closed
      CoolAlert.show(
        context: viewContext,
        type: CoolAlertType.error,
        title: "Vendor is not open".tr(),
        text: "Vendor is currently not open to accepting order at the moment"
            .tr(),
      );
      return false;
    } else if (orderVendor?.minOrder != null &&
        orderVendor!.minOrder! > checkout!.subTotal) {
      ///
      CoolAlert.show(
        context: viewContext,
        type: CoolAlertType.error,
        title: "Minimum Order Value".tr(),
        text: "Order value/amount is less than vendor accepted minimum order"
                .tr() +
            "${AppStrings.currencySymbol} ${orderVendor.minOrder}"
                .currencyFormat(),
      );
      return false;
    }
    //if order is more than the max allowed order by this vendor
    else if (orderVendor?.maxOrder != null &&
        orderVendor!.maxOrder! < checkout!.subTotal) {
      //
      CoolAlert.show(
        context: viewContext,
        type: CoolAlertType.error,
        title: "Maximum Order Value".tr(),
        text: "Order value/amount is more than vendor accepted maximum order"
                .tr() +
            "${AppStrings.currencySymbol} ${orderVendor.maxOrder}"
                .currencyFormat(),
      );
      return false;
    }
    return true;
  }
}
