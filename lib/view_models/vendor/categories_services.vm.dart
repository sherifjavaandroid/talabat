import 'package:flutter/material.dart';

import 'package:fuodz/models/category.dart';
import 'package:fuodz/models/service.dart';
import 'package:fuodz/models/vendor_type.dart';
import 'package:fuodz/requests/category.request.dart';
import 'package:fuodz/requests/service.request.dart';
import 'package:fuodz/view_models/base.view_model.dart';
import 'package:fuodz/views/pages/service/service_details.page.dart';
import 'package:fuodz/extensions/context.dart';

class CategoriesServicesViewModel extends MyBaseViewModel {
  //
  ServiceRequest _serviceRequest = ServiceRequest();
  CategoryRequest _categoryRequest = CategoryRequest();
  //
  List<Category> categories = [];
  VendorType? vendorType;
  int? maxCategories;

  CategoriesServicesViewModel(
    BuildContext context,
    this.vendorType, {
    this.maxCategories,
  }) {
    this.viewContext = context;
  }

  //
  initialise() async {
    setBusy(true);
    try {
      categories = await _categoryRequest.categories(
        vendorTypeId: vendorType?.id,
      );
      clearErrors();
    } catch (error) {
      print("PopularServicesViewModel Error ==> $error");
      setError(error);
    }

    //fetch each category services
    if (maxCategories != null) {
      categories = categories.take(maxCategories!).toList();
    }

    setBusy(false);

    for (var category in categories) {
      fetchCategoryServices(category);
    }
  }

  fetchCategoryServices(Category category) async {
    setBusyForObject("category.${category.id}", true);
    try {
      final index = categories.indexOf(category);
      List<Service> _services = await _serviceRequest.getServices(
        queryParams: {
          "category_id": category
              .id, //this is the category id, it is used to filter the services
        },
      );
      categories[index].services.clear();
      categories[index].services.addAll(_services);
      notifyListeners();
    } catch (error) {
      print("CategoriesServicesViewModel Error ==> $error");
    }
    setBusyForObject("category.${category.id}", false);
  }

  //
  serviceSelected(Service service) {
    viewContext.push(
      (context) => ServiceDetailsPage(service),
    );
  }
}
