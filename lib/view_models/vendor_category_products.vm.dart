import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_routes.dart';
import 'package:fuodz/models/category.dart';
import 'package:fuodz/models/product.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/models/vendor.dart';
import 'package:fuodz/requests/product.request.dart';
import 'package:fuodz/view_models/base.view_model.dart';
import 'package:pull_to_refresh_flutter3/pull_to_refresh_flutter3.dart';

class VendorCategoryProductsViewModel extends MyBaseViewModel {
  //
  VendorCategoryProductsViewModel(
    BuildContext context,
    this.category,
    this.vendor,
  ) {
    this.viewContext = context;
  }

  ProductRequest _productRequest = ProductRequest();
  RefreshController refreshContoller = RefreshController();
  List<RefreshController> refreshContollers = [];
  List<int> refreshContollerKeys = [];

  //
  Category category;
  Vendor? vendor;
  Map<int, List> categoriesProducts = {};
  Map<int, int> categoriesProductsQueryPages = {};
  final currencySymbol = AppStrings.currencySymbol;

  initialise() {
    //
    refreshContollers = List.generate(
      category.subcategories.length,
      (index) => new RefreshController(),
    );
    refreshContollerKeys = List.generate(
      category.subcategories.length,
      (index) => category.subcategories[index].id,
    );
    category.subcategories.forEach((element) {
      loadMoreProducts(element.id);
      categoriesProductsQueryPages[element.id] = 1;
    });
  }

  void productSelected(Product product) async {
    await Navigator.of(viewContext).pushNamed(
      AppRoutes.product,
      arguments: product,
    );

    //
    notifyListeners();
  }

  RefreshController getRefreshController(int key) {
    int index = refreshContollerKeys.indexOf(key);
    return refreshContollers[index];
  }

  loadMoreProducts(int id, {bool initialLoad = true}) async {
    int queryPage = categoriesProductsQueryPages[id] ?? 1;
    if (initialLoad) {
      queryPage = 1;
      categoriesProductsQueryPages[id] = queryPage;
      getRefreshController(id).refreshCompleted();
      setBusyForObject(id, true);
    } else {
      categoriesProductsQueryPages[id] = ++queryPage;
    }

    //load the products by subcategory id
    try {
      final mProducts = await _productRequest.getProdcuts(
        page: queryPage,
        queryParams: {
          "sub_category_id": id,
          "vendor_id": vendor?.id,
        },
      );

      //
      if (initialLoad) {
        categoriesProducts[id] = mProducts;
      } else {
        categoriesProducts[id]?.addAll(mProducts);
      }
    } catch (error) {}

    //
    if (initialLoad) {
      setBusyForObject(id, false);
    } else {
      getRefreshController(id).loadComplete();
    }

    //
    notifyListeners();
  }
}
