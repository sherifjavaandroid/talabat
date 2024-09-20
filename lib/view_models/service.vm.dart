import 'package:flutter/material.dart';
import 'package:fuodz/models/service.dart';
import 'package:fuodz/requests/service.request.dart';
import 'package:fuodz/services/auth.service.dart';
import 'package:fuodz/view_models/base.view_model.dart';
import 'package:fuodz/views/pages/service/new_service.page.dart';
import 'package:fuodz/views/pages/service/service_details.page.dart';
import 'package:pull_to_refresh_flutter3/pull_to_refresh_flutter3.dart';
import 'package:fuodz/extensions/context.dart';

class ServiceViewModel extends MyBaseViewModel {
  //
  ServiceViewModel(BuildContext context) {
    this.viewContext = context;
  }

  //
  ServiceRequest _serviceRequest = ServiceRequest();
  List<Service> services = [];
  RefreshController refreshController = RefreshController();
  int queryPage = 1;
  String keyword = "";

  void initialise() {
    fetchMyServices();
  }

  //
  fetchMyServices({bool initialLoading = true}) async {
    if (initialLoading) {
      queryPage = 1;
      setBusy(true);
    } else {
      queryPage += 1;
    }
    refreshController.refreshCompleted();

    try {
      final mServices = await _serviceRequest.getServices(
        queryParams: {
          "vendor_id": await AuthServices.currentVendor?.id,
          "keyword": keyword,
        },
        page: queryPage,
      );

      //
      if (initialLoading) {
        services = mServices;
      } else {
        services.addAll(mServices);
        refreshController.loadComplete();
      }
      clearErrors();
    } catch (error) {
      print("Package Type Pricing Error ==> $error");
      setError(error);
    }

    setBusy(false);
  }

  //
  serviceSearch(String value) {
    keyword = value;
    fetchMyServices();
  }

  openServiceDetails(Service service) async {
    final result =
        await viewContext.push((context) => ServiceDetailsPage(service));
    if (result != null && result) {
      services.removeWhere((e) => e.id == service.id);
    } else if (result != null && result is Service) {
      final index = services.indexWhere((e) => e.id == service.id);
      if (index >= 0) {
        services[index] = result;
      }
    }
    notifyListeners();
  }

  void newService() async {
    final result = await viewContext.push((context) => NewServicePage());
    if (result != null) {
      fetchMyServices();
    }
  }
}
