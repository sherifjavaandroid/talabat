import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_routes.dart';
import 'package:fuodz/models/cart.dart';
import 'package:fuodz/models/checkout.dart';
import 'package:fuodz/models/vendor.dart';
import 'package:fuodz/services/alert.service.dart';
import 'package:fuodz/services/cart.service.dart';
import 'package:fuodz/view_models/checkout_base.vm.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:dartx/dartx.dart';

class MultipleCheckoutViewModel extends CheckoutBaseViewModel {
  List<Vendor> vendors = [];
  List<Map<String, dynamic>> orderData = [];
  double totalTax = 0;
  double totalDeliveryFee = 0;
  List<double> taxes = [];
  List<double> vendorFees = [];
  List<double> subtotals = [];

  MultipleCheckoutViewModel(BuildContext context, CheckOut checkout) {
    this.viewContext = context;
    this.checkout = checkout;
  }

  //
  void initialise() async {
    super.initialise();
    fetchVendorsDetails();

    //
    updateTotalOrderSummary();
  }

  //
  fetchVendorsDetails() async {
    //
    vendors = CartServices.productsInCart
        .map((e) => e.product!.vendor)
        .toList()
        .toSet()
        .toList();

    vendors = vendors.distinctBy((model) => model.id).toList();
    //
    setBusy(true);
    try {
      for (var i = 0; i < vendors.length; i++) {
        vendors[i] = await vendorRequest.vendorDetails(
          vendors[i].id,
          params: {
            "type": "brief",
          },
        );
      }
    } catch (error) {
      print("Error Getting Vendor Details ==> $error");
    }
    setBusy(false);
  }

  //update total/order amount summary
  @override
  updateTotalOrderSummary() async {
    //clear previous data
    checkout?.tax = 0;
    checkout?.deliveryFee = 0;
    orderData = [];
    totalTax = 0;
    totalDeliveryFee = 0;
    taxes = [];
    vendorFees = [];
    subtotals = [];
    //
    setBusy(true);
    try {
      for (var index = 0; index < vendors.length; index++) {
        final mVendor = vendors[index];
        await updateOrderData(mVendor, index);
      }
      //total tax number
      totalTax = taxes.sum();
      checkout!.tax = totalTax;
      checkout!.subTotal = subtotals.sum();
      //total
      checkout!.total = (checkout!.subTotal - checkout!.discount) +
          totalDeliveryFee +
          checkout!.tax;
      //totalfees
      checkout!.total += vendorFees.sum();
    } catch (error) {
      print("Error Updating Order Data ==> $error");
      toastError("$error");
    }
    setBusy(false);
    // updateCheckoutTotalAmount();
    updatePaymentOptionSelection();
    notifyListeners();
  }

//calcualte for each vendor and prepare jsonobject for checkout
  updateOrderData(Vendor mVendor, int index) async {
    //
    //generate order summary
    List<Cart> vendorCartItems = CartServices.productsInCart
        .where((e) => e.product!.vendor.id == mVendor.id)
        .toList();
    Map<String, dynamic> payload = {
      "pickup": isPickup ? 1 : 0,
      "delievryAddressOutOfRange": delievryAddressOutOfRange ? 1 : 0,
      "tip": driverTipTEC.text,
      "delivery_address_id": deliveryAddress?.id,
      "coupon_code": checkout!.coupon?.code ?? "",
      "vendor_id": mVendor.id,
      "products": vendorCartItems.map((e) => e.toCheckout()).toList(),
    };

    final mCheckout = await checkoutRequest.orderSummary(payload);
    double deliveryFee = mCheckout.deliveryFee;
    double calTax = mCheckout.tax;
    double vendorSubtotal = mCheckout.subTotal;
    checkout!.tax += calTax;
    totalTax += double.tryParse(mVendor.tax) ?? 0;
    totalDeliveryFee += mCheckout.deliveryFee;
    // taxes.add(calTax);
    //if tax has index replace it else add it
    if (taxes.indices.contains(index)) {
      taxes[index] = calTax;
    } else {
      taxes.add(calTax);
    }

    //
    if (subtotals.indices.contains(index)) {
      subtotals[index] = vendorSubtotal;
    } else {
      subtotals.add(vendorSubtotal);
    }

    //
    double vendorDiscount = mCheckout.discount;
    //total amount for that single order
    double vendorTotal = (vendorSubtotal - vendorDiscount);
    vendorTotal += deliveryFee + calTax;

    //fees
    List<Map> feesObjects = mCheckout.fees.map((e) => e.toJson()).toList();
    double totalVendorFees = mCheckout.totalFee;
    vendorTotal += totalVendorFees;
    //vendor total fee
    if (vendorFees.indices.contains(index)) {
      vendorFees[index] = totalVendorFees;
    } else {
      vendorFees.add(totalVendorFees);
    }

    //
    final orderObject = {
      "vendor_id": mVendor.id,
      "delivery_fee": deliveryFee,
      "tax": calTax,
      "sub_total": vendorSubtotal,
      "discount": vendorDiscount,
      "tip": 0,
      "total": vendorTotal,
      "fees": feesObjects,
      "token": mCheckout.token,
    };

    //prepare order data
    final orderDataIndex = orderData.indexWhere(
      (e) => e.containsKey("vendor_id") && e["vendor_id"] == mVendor.id,
    );
    if (orderDataIndex >= 0) {
      orderData[orderDataIndex] = orderObject;
    } else {
      orderData.add(orderObject);
    }
  }

//
  @override
  processOrderPlacement() async {
    //process the order placement
    setBusy(true);

    try {
      //prepare order data
      List<Map<String, dynamic>> vendorsOrderData = [];
      orderData.forEach((e) {
        Map<String, dynamic> vendorOrderData = {};
        vendorOrderData.addAll(e);
        vendorsOrderData.add(vendorOrderData);
      });

      //set the total with discount as the new total
      checkout!.total = checkout!.totalWithTip;
      //
      final apiResponse = await checkoutRequest.newMultipleVendorOrder(
        checkout!,
        tip: driverTipTEC.text,
        note: noteTEC.text,
        payload: {
          "data": vendorsOrderData,
        },
      );
      //not error
      if (apiResponse.allGood) {
        //any payment
        await AlertService.success(
          title: "Checkout".tr(),
          text: apiResponse.message,
        );
        showOrdersTab(context: viewContext);
        if (Navigator.canPop(viewContext)) {
          Navigator.of(viewContext).popUntil(
            (route) {
              return route.settings.name == AppRoutes.homeRoute ||
                  route.isFirst;
            },
          );
        }
      } else {
        await AlertService.error(
          title: "Checkout".tr(),
          text: apiResponse.message,
        );
      }
    } catch (error) {
      print("Error Placing Order ==> $error");
      toastError("$error");
    }
    setBusy(false);
  }
}
