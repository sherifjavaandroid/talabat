import 'dart:async';
import 'package:flutter/material.dart';
import 'package:fuodz/models/product.dart';
import 'package:fuodz/models/user.dart';
import 'package:fuodz/models/vendor_type.dart';
import 'package:fuodz/requests/product.request.dart';
import 'package:fuodz/services/auth.service.dart';
import 'package:fuodz/view_models/base.view_model.dart';
import 'package:pull_to_refresh_flutter3/pull_to_refresh_flutter3.dart';

class GroceryViewModel extends MyBaseViewModel {
  //
  GroceryViewModel(BuildContext context, VendorType vendorType) {
    this.viewContext = context;
    this.vendorType = vendorType;
  }

  //
  User? currentUser;
  StreamSubscription? currentLocationChangeStream;

  //
  ProductRequest productRequest = ProductRequest();
  RefreshController refreshController = RefreshController();
  List<Product> productPicks = [];

  void initialise() async {
    preloadDeliveryLocation();
    //
    if (AuthServices.authenticated()) {
      currentUser = await AuthServices.getCurrentUser(force: true);
      notifyListeners();
    }
    //get today picks
    getTodayPicks();
  }

  //
  dispose() {
    super.dispose();
    currentLocationChangeStream?.cancel();
  }

  //
  getTodayPicks() async {
    //
    setBusyForObject(productPicks, true);
    try {
      productPicks = await productRequest.getProdcuts(
        queryParams: {
          "vendor_type_id": vendorType?.id,
          "type": "best",
        },
      );
    } catch (error) {
      print("getTodayPicks Error ==> $error");
    }
    setBusyForObject(productPicks, false);
  }
}
