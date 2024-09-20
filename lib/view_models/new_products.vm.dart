import 'dart:io';
import 'package:cool_alert/cool_alert.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/models/menu.dart';
import 'package:fuodz/models/product_category.dart';
import 'package:fuodz/models/product_tag.dart';
import 'package:fuodz/models/vendor.dart';
import 'package:fuodz/requests/product.request.dart';
import 'package:fuodz/requests/vendor.request.dart';
import 'package:fuodz/services/auth.service.dart';
import 'package:fuodz/view_models/product_manage.vm.dart';
import 'package:fuodz/views/pages/shared/text_editor.page.dart';
import 'package:fuodz/extensions/context.dart';
import 'package:localize_and_translate/localize_and_translate.dart';

class NewProductViewModel extends ProductManageViewModel {
  //
  NewProductViewModel(BuildContext context) {
    this.viewContext = context;
  }

  //
  // Product? product;
  String? productDescription;
  ProductRequest productRequest = ProductRequest();
  VendorRequest vendorRequest = VendorRequest();
  List<ProductTag> tags = [];
  List<ProductCategory> categories = [];
  List<ProductCategory> subCategories = [];
  List<ProductCategory> unFilterSubCategories = [];
  List<Menu> menus = [];
  List<File> selectedPhotos = [];

  void initialise() {
    fetchProductTags();
    fetchProductCategories();
    fetchProductSubCategories();
    fetchMenus();
  }

  //
  fetchProductTags() async {
    setBusyForObject(tags, true);

    try {
      tags = await productRequest.getProductTags(
        vendorTypeId:
            (await AuthServices.getCurrentVendor(force: true)).vendorType?.id,
      );
      clearErrors();
    } catch (error) {
      print("Categories Error ==> $error");
      setError(error);
    }

    setBusyForObject(tags, false);
  }

  //
  fetchProductCategories() async {
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

  fetchProductSubCategories() async {
    setBusyForObject(subCategories, true);

    try {
      unFilterSubCategories = await productRequest.getProductCategories(
        subCat: true,
        vendorTypeId:
            (await AuthServices.getCurrentVendor(force: true)).vendorType?.id,
      );
      clearErrors();
    } catch (error) {
      print("subCategories Error ==> $error");
      setError(error);
    }

    setBusyForObject(subCategories, false);
  }

  fetchMenus() async {
    setBusyForObject(menus, true);

    try {
      final response = await vendorRequest.getVendorDetails();
      final vendor = Vendor.fromJson(response["vendor"]);
      menus = vendor.menus;
      print("$menus");
      clearErrors();
    } catch (error) {
      print("menus Error ==> $error");
      setError(error);
    }

    setBusyForObject(menus, false);
  }

  //
  onImagesSelected(List<File> files) {
    selectedPhotos = files;
    notifyListeners();
  }

  //
  processNewProduct() async {
    if (formBuilderKey.currentState!.saveAndValidate()) {
      //
      setBusy(true);

      try {
        Map<String, dynamic> productData = Map.from(
          formBuilderKey.currentState!.value,
        );
        //append option group data
        productData = appendOptionGroupData(productData);

        final categoryIds = productData["category_ids"];
        final subCategoryIds = productData["sub_category_ids"];
        final menuIds = productData["menu_ids"];
        //reassing the values
        if (categoryIds == null ||
            (categoryIds is List && categoryIds.isEmpty)) {
          productData["category_ids"] = "[]";
        }
        if (subCategoryIds == null ||
            (subCategoryIds is List && subCategoryIds.isEmpty)) {
          productData["sub_category_ids"] = "[]";
        }
        if (menuIds == null || (menuIds is List && menuIds.isEmpty)) {
          productData["menu_ids"] = "[]";
        }

        productData.addAll({
          "description": productDescription,
        });

        final apiResponse = await productRequest.newProduct(
          productData,
          photos: selectedPhotos,
        );
        //
        //show dialog to present state
        final result = await CoolAlert.show(
          context: viewContext,
          type:
              apiResponse.allGood ? CoolAlertType.success : CoolAlertType.error,
          title: "New Product".tr(),
          text: apiResponse.message,
          onConfirmBtnTap: () {
            if (apiResponse.allGood) {
              viewContext.pop(true);
            } else {
              viewContext.pop();
            }
          },
        );

        if (result != null && result) {
          viewContext.pop(true);
        }
        clearErrors();
      } catch (error) {
        print("New product Error ==> $error");
        setError(error);
      }

      setBusy(false);
    }
  }

  //
  void filterSubcategories(List<String?>? categoryIds) {
    categoryIds ??= [];
    subCategories = unFilterSubCategories.where(
      (e) {
        return categoryIds!.contains(e.categoryId.toString());
      },
    ).toList();
    notifyListeners();
  }

  handleDescriptionEdit() async {
    //get the description
    final result = await viewContext.push(
      (context) => CustomTextEditorPage(
        title: "Product Description".tr(),
        content: productDescription,
      ),
    );
    //
    if (result != null) {
      productDescription = result;
      notifyListeners();
    }
  }
}
