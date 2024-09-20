import 'dart:io';

import 'package:cool_alert/cool_alert.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_file_limit.dart';
import 'package:fuodz/extensions/dynamic.dart';
import 'package:fuodz/models/checkout.dart';
import 'package:fuodz/models/vendor.dart';
import 'package:fuodz/requests/vendor.request.dart';
import 'package:fuodz/utils/permission_utils.dart';
import 'package:fuodz/view_models/checkout_base.vm.dart';
import 'package:image_picker/image_picker.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:fuodz/extensions/context.dart';

class PharmacyUploadPrescriptionViewModel extends CheckoutBaseViewModel {
  //
  PharmacyUploadPrescriptionViewModel(BuildContext context, this.vendor) {
    this.viewContext = context;
    this.checkout = CheckOut(subTotal: 0.00);
    this.canSelectPaymentOption = true;
  }

  //
  VendorRequest vendorRequest = VendorRequest();
  Vendor? vendor;
  final picker = ImagePicker();
  List<File> prescriptionPhotos = [];

  void initialise() async {
    calculateTotal = false;
    super.initialise();
  }

  //
  fetchVendorDetails() async {
    //
    setBusyForObject(vendor, true);
    try {
      vendor = await vendorRequest.vendorDetails(vendor!.id);
    } catch (error) {
      print("Error ==> $error");
    }
    setBusyForObject(vendor, false);
  }

  //
  void changePhoto() async {
    //check for permission first
    try {
      final permission = await PermissionUtils.handleImagePermissionRequest(
        viewContext,
      );
      if (!permission) {
        return;
      }
    } catch (error) {
      print("Error ==> $error");
      toastError("$error");
      return;
    }
    //End of permission check

    //
    final pickedFiles = await picker.pickMultiImage();
    if (prescriptionPhotos.isNotEmpty) {
      prescriptionPhotos.addAll(
        pickedFiles.map((e) => File(e.path)).toList(),
      );
    } else {
      prescriptionPhotos = pickedFiles.map((e) => File(e.path)).toList();
    }

    //
    if (prescriptionPhotos.length > AppFileLimit.prescriptionFileLimit) {
      prescriptionPhotos = prescriptionPhotos.sublist(
        0,
        AppFileLimit.prescriptionFileLimit,
      );
      //
      CoolAlert.show(
        context: viewContext,
        type: CoolAlertType.warning,
        title: "Prescription".tr(),
        text: "You can only upload %s prescription at a time"
            .tr()
            .fill([AppFileLimit.prescriptionFileLimit]),
      );
    }
    //
    notifyListeners();
  }

  void removePhoto(int index) {
    prescriptionPhotos.removeAt(index);
    //refresh list to have new index
    prescriptionPhotos = prescriptionPhotos.toList();
    notifyListeners();
  }

  //
  placeOrder({bool ignore = false}) async {
    //
    if (!isPickup && deliveryAddress == null) {
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
    } else if (prescriptionPhotos.isEmpty) {
      //
      CoolAlert.show(
        context: viewContext,
        type: CoolAlertType.error,
        title: "Prescription".tr(),
        text: "Please upload prescription".tr(),
      );
    }
    //process the new order
    else {
      processOrderPlacement();
    }
  }

  //
  processOrderPlacement() async {
    setBusy(true);

    try {
      //set the total with discount as the new total
      checkout!.total = checkout!.totalWithTip;
      //
      final apiResponse = await checkoutRequest.newPrescriptionOrder(
        checkout!,
        vendor!,
        photos: prescriptionPhotos,
        note: noteTEC.text,
      );
      //not error
      if (apiResponse.allGood) {
        //cash payment

        final paymentLink = "";
        // apiResponse.body["link"].toString();
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
              showOrdersTab(context: viewContext);
              if (Navigator.of(viewContext).canPop()) {
                viewContext.pop();
              }
            },
          );
        }
      } else {
        CoolAlert.show(
          context: viewContext,
          type: CoolAlertType.error,
          title: "Checkout".tr(),
          text: apiResponse.message,
        );
      }
    } catch (error) {
      toastError("$error");
    }
    setBusy(false);
  }
}
