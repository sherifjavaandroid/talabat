import 'dart:async';
import 'package:cloud_firestore/cloud_firestore.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/models/new_order.dart';
import 'package:fuodz/models/user.dart';
import 'package:fuodz/models/vehicle.dart';
import 'package:fuodz/requests/auth.request.dart';
import 'package:fuodz/services/app.service.dart';
import 'package:fuodz/services/appbackground.service.dart';
import 'package:fuodz/services/auth.service.dart';
import 'package:fuodz/services/local_storage.service.dart';
import 'package:fuodz/services/location.service.dart';
import 'package:fuodz/services/order_assignment.service.dart';
import 'package:fuodz/services/order_manager.service.dart';
import 'package:fuodz/services/update.service.dart';
import 'package:fuodz/view_models/base.view_model.dart';
import 'package:fuodz/widgets/bottomsheets/new_order_alert.bottomsheet.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:georange/georange.dart';

class HomeViewModel extends MyBaseViewModel with UpdateService {
  //
  HomeViewModel(BuildContext context) {
    this.viewContext = context;
  }

  //
  // bool isOnline = true;
  int currentIndex = 0;
  User? currentUser;
  Vehicle? driverVehicle;
  PageController pageViewController = PageController(initialPage: 0);
  StreamSubscription? homePageChangeStream;
  StreamSubscription? locationReadyStream;
  FirebaseFirestore firebaseFirestore = FirebaseFirestore.instance;
  GeoRange georange = GeoRange();
  StreamSubscription? newOrderStream;
  AuthRequest authRequest = AuthRequest();

  @override
  void initialise() async {
    //
    handleAppUpdate(viewContext);
    //
    currentUser = await AuthServices.getCurrentUser();
    driverVehicle = await AuthServices.getDriverVehicle();
    //
    AppService().driverIsOnline =
        LocalStorageService.prefs!.getBool(AppStrings.onlineOnApp) ?? false;
    notifyListeners();

    //
    await OrderManagerService().monitorOnlineStatusListener();
    notifyListeners();

    //
    locationReadyStream = LocationService().locationDataAvailable.stream.listen(
      (event) {
        if (event) {
          print("abut call ==> listenToNewOrders");
          listenToNewOrders();
        }
      },
    );

    //
    homePageChangeStream = AppService().homePageIndex.stream.listen(
      (index) {
        //
        onTabChange(index);
      },
    );

    //INCASE OF previous driver online state
    handleNewOrderServices();
  }

  //
  dispose() {
    super.dispose();
    cancelAllListeners();
  }

  cancelAllListeners() async {
    homePageChangeStream?.cancel();
    newOrderStream?.cancel();
  }

  //
  onPageChanged(int index) {
    currentIndex = index;
    notifyListeners();
  }

  //
  onTabChange(int index) {
    currentIndex = index;
    pageViewController.animateToPage(
      currentIndex,
      duration: Duration(microseconds: 5),
      curve: Curves.bounceInOut,
    );
    notifyListeners();
  }

  void toggleOnlineStatus() async {
    setBusy(true);
    try {
      //
      final apiResponse = await authRequest.updateProfile(
        isOnline: !AppService().driverIsOnline,
      );
      if (apiResponse.allGood) {
        //
        AppService().driverIsOnline = !AppService().driverIsOnline;
        await LocalStorageService.prefs!.setBool(
          AppStrings.onlineOnApp,
          AppService().driverIsOnline,
        );
        //
        viewContext.showToast(
          msg: "Updated Successfully".tr(),
          bgColor: Colors.green,
          textColor: Colors.white,
        );

        //
        handleNewOrderServices();
      } else {
        viewContext.showToast(
          msg: "${apiResponse.message}",
          bgColor: Colors.red,
        );
      }
    } catch (error) {
      viewContext.showToast(msg: "$error", bgColor: Colors.red);
    }
    setBusy(false);
  }

  handleNewOrderServices() {
    if (AppService().driverIsOnline) {
      listenToNewOrders();
      AppbackgroundService().startBg();
    } else {
      //
      // LocationService().clearLocationFromFirebase();
      cancelAllListeners();
      AppbackgroundService().stopBg();
    }
  }

  //NEW ORDER STREAM
  listenToNewOrders() async {
    //close any previous listener
    newOrderStream?.cancel();
    //start the background service
    startNewOrderBackgroundService();
  }

  NewOrder? showingNewOrder;
  void showNewOrderAlert(NewOrder newOrder) async {
    //

    if (showingNewOrder == null || showingNewOrder!.docRef != newOrder.docRef) {
      showingNewOrder = newOrder;
      print("called showNewOrderAlert");
      final result = await showModalBottomSheet(
        context: AppService().navigatorKey.currentContext!,
        isDismissible: false,
        enableDrag: false,
        builder: (context) {
          return NewOrderAlertBottomSheet(newOrder);
        },
      );

      //
      if (result is bool && result) {
        AppService().refreshAssignedOrders.add(true);
      } else {
        await OrderAssignmentService.releaseOrderForotherDrivers(
          newOrder.toJson(),
          newOrder.docRef!,
        );
      }
    }
  }
}
