import 'dart:async';

import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_routes.dart';
import 'package:fuodz/models/order.dart';
import 'package:fuodz/requests/order.request.dart';
import 'package:fuodz/services/app.service.dart';
import 'package:fuodz/view_models/base.view_model.dart';
import 'package:pull_to_refresh_flutter3/pull_to_refresh_flutter3.dart';
import 'package:url_launcher/url_launcher_string.dart';

class OrdersViewModel extends MyBaseViewModel {
  //
  OrdersViewModel(BuildContext context) {
    this.viewContext = context;
  }

  //
  OrderRequest orderRequest = OrderRequest();
  List<Order> orders = [];
  List<String> statuses = [
    'All',
    'Pending',
    'Scheduled',
    'Preparing',
    'Enroute',
    'Failed',
    'Cancelled',
    'Delivered'
  ];
  String selectedStatus = "All";
  //
  int queryPage = 1;
  RefreshController refreshController = RefreshController();
  StreamSubscription? refreshOrderStream;

  void initialise() async {
    refreshOrderStream = AppService().refreshAssignedOrders.listen((refresh) {
      if (refresh) {
        fetchMyOrders();
      }
    });

    await fetchMyOrders();
  }

  dispose() {
    super.dispose();
    refreshOrderStream?.cancel();
  }

  //
  void statusChanged(value) {
    selectedStatus = value;
    notifyListeners();
    fetchMyOrders();
  }

  //
  fetchMyOrders({bool initialLoading = true}) async {
    if (initialLoading) {
      setBusy(true);
      refreshController.refreshCompleted();
      queryPage = 1;
    } else {
      queryPage++;
    }

    try {
      final mOrders = await orderRequest.getOrders(
        page: queryPage,
        status: selectedStatus == "All" ? "" : selectedStatus.toLowerCase(),
      );
      if (!initialLoading) {
        orders.addAll(mOrders);
        refreshController.loadComplete();
      } else {
        orders = mOrders;
      }
      clearErrors();
    } catch (error) {
      print("Order Error ==> $error");
      setError(error);
    }

    setBusy(false);
  }

  //
  openPaymentPage(Order order) async {
    launchUrlString(order.paymentLink);
  }

  openOrderDetails(Order order) async {
    final result = await Navigator.of(viewContext).pushNamed(
      AppRoutes.orderDetailsRoute,
      arguments: order,
    );

    //
    if (result != null && result is Order) {
      final orderIndex = orders.indexWhere((e) => e.id == result.id);
      orders[orderIndex] = result;
      notifyListeners();
    } else if (result != null && result is bool) {
      fetchMyOrders();
    }
  }

  void openLogin() async {
    await Navigator.of(viewContext).pushNamed(AppRoutes.loginRoute);
    notifyListeners();
    fetchMyOrders();
  }
}
