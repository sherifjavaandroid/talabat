import 'dart:developer';

import 'package:flutter/material.dart';
import 'package:fuodz/models/order.dart';
import 'package:fuodz/requests/order.request.dart';
import 'base.view_model.dart';

class OrderCancellationViewModel extends MyBaseViewModel {
  //
  OrderRequest orderRequest = OrderRequest();
  List<String> reasons = [];
  Order order;

  OrderCancellationViewModel(BuildContext context, this.order) {
    this.viewContext = context;
  }

  void initialise() async {
    setBusyForObject(reasons, true);
    try {
      reasons = await orderRequest.orderCancellationReasons(order: order);
    } catch (error) {
      log("Error ==> $error");
    }
    setBusyForObject(reasons, false);
  }
}
