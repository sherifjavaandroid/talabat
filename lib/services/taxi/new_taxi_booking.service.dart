import 'dart:async';
import 'package:circular_countdown_timer/circular_countdown_timer.dart';
import 'package:cloud_firestore/cloud_firestore.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/services/app.service.dart';
import 'package:fuodz/services/appbackground.service.dart';
import 'package:fuodz/services/order_assignment.service.dart';
import 'package:fuodz/services/taxi_background_order.service.dart';
import 'package:fuodz/view_models/taxi/taxi.vm.dart';

class NewTaxiBookingService {
  TaxiViewModel taxiViewModel;
  NewTaxiBookingService(this.taxiViewModel);
  StreamSubscription? myLocationListener;
  bool showNewTripView = false;
  CountDownController countDownTimerController = CountDownController();
  GlobalKey newAlertViewKey = GlobalKey<FormState>();
  //
  FirebaseFirestore firebaseFireStore = FirebaseFirestore.instance;
  StreamSubscription? newOrderStreamSubscription;
  StreamSubscription? locationStreamSubscription;

  //dispose
  void dispose() {
    myLocationListener?.cancel();
    newOrderStreamSubscription?.cancel();
  }

  //
  toggleVisibility(bool value) async {
    //
    taxiViewModel.appService.driverIsOnline = value;
    final updated = await taxiViewModel.syncDriverNewState();
    //
    if (updated) {
      if (value && taxiViewModel.onGoingOrderTrip == null) {
        startNewOrderListener();
        AppbackgroundService().startBg();
      } else {
        stopListeningToNewOrder();
        AppbackgroundService().stopBg();
      }
    }
  }

  //start lisntening for new orders
  startNewOrderListener() {
    //
    print("Cancel any previous listener");
    newOrderStreamSubscription?.cancel();
    print("start listening to new taxi order");
    //
    TaxiBackgroundOrderService().taxiViewModel = taxiViewModel;
    // TaxiBackgroundOrderService().showNewOrderStream = BehaviorSubject();
    // //
    // newOrderStreamSubscription =
    //     TaxiBackgroundOrderService().showNewOrderStream.stream.listen(
    //   (event) {
    //     print("order listener called");
    //     if (event is String) {
    //       if (event == "countdown") {
    //         countDownCompleted();
    //       } else if (event == "show") {
    //         showNewOrderAlert(event);
    //       } else if (event == "stop") {
    //         stopListeningToNewOrder();
    //       } else if (event == "zoom") {
    //         zoomToNewOrderPoint();
    //       }
    //     }

    //     showNewOrderAlert(event);
    //   },
    // );
  }

  //stop listening to new orders
  stopListeningToNewOrder() {
    locationStreamSubscription?.cancel();
    newOrderStreamSubscription?.cancel();
  }

//   //
//   showNewOrderAlert(dynamic data) async {
//     //
//     try {
//       taxiViewModel.newOrder =
//           (data is NewTaxiOrder) ? data : NewTaxiOrder.fromJson(data);

// // zoomToNewOrderPoint

// //show new taxi alert
//       //
//       final result = await showModalBottomSheet(
//         isDismissible: false,
//         enableDrag: false,
//         context: taxiViewModel.viewContext,
//         backgroundColor: Colors.transparent,
//         builder: (context) {
//           return IncomingNewOrderAlert(taxiViewModel, taxiViewModel.newOrder);
//         },
//       );

//       print("New alert result ==> $result");
//       //
//       if (result != null) {
//         taxiViewModel.onGoingOrderTrip = result;
//         taxiViewModel.onGoingTaxiBookingService.loadTripUIByOrderStatus();
//         taxiViewModel.notifyListeners();
//       } else {
//         taxiViewModel.taxiGoogleMapManagerService.clearMapData();
//         taxiViewModel.taxiGoogleMapManagerService.zoomToCurrentLocation();
//         taxiViewModel.taxiGoogleMapManagerService.updateGoogleMapPadding(20);
//         countDownCompleted();
//       }
//     } catch (error) {
//       print("show new order alert error ==> $error");
//     }
//   }

  void countDownCompleted() async {
    try {
      countDownTimerController.pause();
    } catch (e) {
      print("countDownTimerController error ==> $e");
    }
    AppService().stopNotificationSound();
    showNewTripView = false;
    taxiViewModel.taxiGoogleMapManagerService.zoomToCurrentLocation();
    taxiViewModel.notifyListeners();
    await OrderAssignmentService.releaseOrderForotherDrivers(
      taxiViewModel.newOrder!.toJson(),
      taxiViewModel.newOrder!.docRef!,
    );
    startNewOrderListener();
  }

  void processOrderAcceptance() {}
}
