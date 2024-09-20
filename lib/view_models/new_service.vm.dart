import 'dart:io';
import 'package:cool_alert/cool_alert.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/models/product_category.dart';
import 'package:fuodz/requests/product.request.dart';
import 'package:fuodz/requests/service.request.dart';
import 'package:fuodz/requests/vendor.request.dart';
import 'package:fuodz/services/auth.service.dart';
import 'package:fuodz/view_models/base.view_model.dart';
import 'package:fuodz/views/pages/shared/text_editor.page.dart';
import 'package:fuodz/extensions/context.dart';
import 'package:localize_and_translate/localize_and_translate.dart';

class NewServiceViewModel extends MyBaseViewModel {
  //
  NewServiceViewModel(BuildContext context) {
    this.viewContext = context;
  }

  //
  ServiceRequest serviceRequest = ServiceRequest();
  ProductRequest productRequest = ProductRequest();
  VendorRequest vendorRequest = VendorRequest();
  String? description;
  //
  int? selectedCategoryId;
  int? selectedSubCategoryId;
  String? selectedServiceDuration;
  //
  List<ProductCategory> categories = [];
  List<ProductCategory> subcategories = [];
  List<String> serviceDurations = [];
  List<File>? selectedPhotos = [];

  void initialise() {
    fetchVendorTypeCategories();
    fetchServiceDurations();
  }

  //
  fetchVendorTypeCategories() async {
    setBusyForObject(categories, true);

    try {
      categories = await productRequest.getProductCategories(
        vendorTypeId:
            (await AuthServices.getCurrentVendor(force: true)).vendorType?.id,
      );
      clearErrors();
    } catch (error) {
      print("Categories Error ==> $error");
      setError(error);
    }

    setBusyForObject(categories, false);
  }

  fetchSubCategories(int? categoryId) async {
    selectedCategoryId = categoryId;
    setBusyForObject(subcategories, true);

    try {
      subcategories = await productRequest.fetchSubCategories(
        categoryId: categoryId,
      );
      clearErrors();
    } catch (error) {
      print("Categories Error ==> $error");
      setError(error);
    }

    setBusyForObject(subcategories, false);
  }

  fetchServiceDurations() async {
    setBusyForObject(serviceDurations, true);

    try {
      serviceDurations = await serviceRequest.getServiceDurations();
      clearErrors();
    } catch (error) {
      print("serviceDurations Error ==> $error");
      setError(error);
    }

    setBusyForObject(serviceDurations, false);
  }

  //
  onImagesSelected(List<File> files) {
    selectedPhotos = files;
    notifyListeners();
  }

  //
  processNewService() async {
    if (formBuilderKey.currentState!.saveAndValidate() &&
        validateSelectedPhotos()) {
      //
      setBusy(true);

      try {
        final apiResponse = await serviceRequest.newService(
          data: {
            ...formBuilderKey.currentState!.value,
            "description": description,
          },
          photos: selectedPhotos,
        );

        //show dialog to present state
        CoolAlert.show(
            context: viewContext,
            type: apiResponse.allGood
                ? CoolAlertType.success
                : CoolAlertType.error,
            title: "New Service".tr(),
            text: apiResponse.message,
            onConfirmBtnTap: () {
              viewContext.pop();
              if (apiResponse.allGood) {
                viewContext.pop(true);
              }
            });
        clearErrors();
      } catch (error) {
        print("new service Error ==> $error");
        setError(error);
      }

      setBusy(false);
    }
  }

  bool validateSelectedPhotos() {
    if (selectedPhotos == null || selectedPhotos!.isEmpty) {
      CoolAlert.show(
        context: viewContext,
        type: CoolAlertType.warning,
        title: "Update Service".tr(),
        text: "Please select at least one photo for service".tr(),
      );
      return false;
    }
    return true;
  }

  handleDescriptionEdit() async {
    //get the description
    final result = await viewContext.push(
      (context) => CustomTextEditorPage(
        title: "Service Description".tr(),
        content: description ?? "",
      ),
    );
    //
    if (result != null) {
      description = result;
      notifyListeners();
    }
  }
}
