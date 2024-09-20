import 'package:flutter/material.dart';
import 'package:fuodz/models/category.dart';
import 'package:fuodz/models/search.dart';
import 'package:fuodz/models/vendor_type.dart';
import 'package:fuodz/requests/category.request.dart';
import 'package:fuodz/services/navigation.service.dart';
import 'package:fuodz/view_models/base.view_model.dart';
import 'package:fuodz/views/pages/category/subcategories.page.dart';
import 'package:pull_to_refresh_flutter3/pull_to_refresh_flutter3.dart';
import 'package:velocity_x/velocity_x.dart';

class CategoriesViewModel extends MyBaseViewModel {
  CategoriesViewModel(BuildContext context, {this.vendorType}) {
    this.viewContext = context;
  }

  //
  CategoryRequest _categoryRequest = CategoryRequest();
  RefreshController refreshController = RefreshController();

  //
  List<Category> categories = [];
  VendorType? vendorType;
  int queryPage = 1;

  //
  initialise({bool all = false}) async {
    setBusy(true);
    try {
      categories = await _categoryRequest.categories(
        vendorTypeId: vendorType?.id,
        page: queryPage,
        full: all ? 1 : 0,
      );
      clearErrors();
    } catch (error) {
      setError(error);
    }
    setBusy(false);
  }

  //
  loadMoreItems([bool initialLoading = false, bool all = true]) async {
    if (initialLoading) {
      setBusy(true);
      queryPage = 1;
      refreshController.refreshCompleted();
    } else {
      queryPage += 1;
    }
    //
    try {
      final mCategories = await _categoryRequest.categories(
        vendorTypeId: vendorType?.id,
        page: queryPage,
        full: all ? 1 : 0,
      );
      clearErrors();

      //
      if (initialLoading) {
        categories = mCategories;
      } else {
        categories.addAll(mCategories);
      }
    } catch (error) {
      setError(error);
    }
    if (initialLoading) {
      setBusy(false);
    }
    refreshController.loadComplete();
    notifyListeners();
  }

  //
  categorySelected(Category category) async {
    Widget page;
    if (category.hasSubcategories) {
      page = SubcategoriesPage(category: category);
    } else {
      final search = Search(
        vendorType: category.vendorType,
        category: category,
        showType: (category.vendorType?.isService ?? false) ? 5 : 4,
      );
      page = NavigationService().searchPageWidget(search);
    }
    viewContext.nextPage(page);
  }
}
