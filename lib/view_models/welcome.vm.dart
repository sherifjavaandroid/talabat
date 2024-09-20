import 'dart:async';

import 'package:flutter/material.dart';
import 'package:fuodz/models/vendor_type.dart';
import 'package:fuodz/requests/vendor_type.request.dart';
import 'package:fuodz/services/auth.service.dart';
import 'package:fuodz/view_models/base.view_model.dart';
import 'package:fuodz/views/pages/vendor/featured_vendors.page.dart';

class WelcomeViewModel extends MyBaseViewModel {
  //
  WelcomeViewModel(BuildContext context) {
    this.viewContext = context;
  }

  Widget? selectedPage;
  List<VendorType> vendorTypes = [];
  VendorTypeRequest vendorTypeRequest = VendorTypeRequest();
  bool showGrid = true;
  StreamSubscription? authStateSub;

  //
  //
  initialise({bool initial = true}) async {
    //
    preloadDeliveryLocation();
    //
    if (refreshController.isRefresh) {
      refreshController.refreshCompleted();
    }

    if (!initial) {
      pageKey = GlobalKey();
      notifyListeners();
    }

    await getVendorTypes();
    listenToAuth();
  }

  listenToAuth() {
    authStateSub = AuthServices.listenToAuthState().listen((event) {
      genKey = GlobalKey();
      notifyListeners();
    });
  }

  getVendorTypes() async {
    setBusy(true);
    try {
      vendorTypes = await vendorTypeRequest.index();
      clearErrors();
    } catch (error) {
      setError(error);
    }
    setBusy(false);
  }

  openFeaturedVendors() async {
    Navigator.of(viewContext).push(
      MaterialPageRoute(
        builder: (context) => FeaturedVendorsPage(),
      ),
    );
  }
}
