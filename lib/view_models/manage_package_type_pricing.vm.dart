import 'package:cool_alert/cool_alert.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/models/package_type.dart';
import 'package:fuodz/models/package_type_pricing.dart';
import 'package:fuodz/requests/package_type_pricing.request.dart';
import 'package:fuodz/view_models/base.view_model.dart';
import 'package:fuodz/extensions/context.dart';
import 'package:localize_and_translate/localize_and_translate.dart';

class ManagePackageTypePricingViewModel extends MyBaseViewModel {
  //
  ManagePackageTypePricingViewModel(
    BuildContext context,
    this.packageTypePricing,
  ) {
    this.viewContext = context;
  }

  //
  PackageTypePricingRequest packageTypePricingRequest =
      PackageTypePricingRequest();
  List<PackageType> packageTypes = [];
  PackageTypePricing? packageTypePricing;

  void initialise() {
    fetchPackageTypes();
  }

  //
  fetchPackageTypes() async {
    setBusyForObject(packageTypes, true);

    try {
      packageTypes = await packageTypePricingRequest.getPackageTypes();
      clearErrors();
    } catch (error) {
      print("Package Types Error ==> $error");
      setError(error);
    }

    setBusyForObject(packageTypes, false);
  }

  //update pricing
  processUpdate() async {
    if (formBuilderKey.currentState!.saveAndValidate()) {
      //
      setBusy(true);

      try {
        try {
          final apiResponse = await packageTypePricingRequest.updateDetails(
            packageTypePricing!,
            data: formBuilderKey.currentState!.value,
          );
          //
          //show dialog to present state
          CoolAlert.show(
              context: viewContext,
              type: apiResponse.allGood
                  ? CoolAlertType.success
                  : CoolAlertType.error,
              title: "Successfull".tr(),
              text: apiResponse.message,
              onConfirmBtnTap: () {
                viewContext.pop();
                viewContext.pop(true);
              });
          clearErrors();
        } catch (error) {
          print("Update Status Package Type Pricing Error ==> $error");
          setError(error);
        }
        clearErrors();
      } catch (error) {
        print("Package Types Error ==> $error");
        setError(error);
      }

      setBusy(false);
    }
  }

  //New pricing
  processNewPricing() async {
    if (formBuilderKey.currentState!.saveAndValidate()) {
      //
      setBusy(true);

      try {
        try {
          final apiResponse = await packageTypePricingRequest.newPricing(
            formBuilderKey.currentState!.value,
          );
          //
          //show dialog to present state
          CoolAlert.show(
              context: viewContext,
              type: apiResponse.allGood
                  ? CoolAlertType.success
                  : CoolAlertType.error,
              title: "Successfull".tr(),
              text: apiResponse.message,
              onConfirmBtnTap: () {
                viewContext.pop();
                viewContext.pop(true);
              });
          clearErrors();
        } catch (error) {
          print("Update Status Package Type Pricing Error ==> $error");
          setError(error);
        }
        clearErrors();
      } catch (error) {
        print("Package Types Error ==> $error");
        setError(error);
      }

      setBusy(false);
    }
  }
}
