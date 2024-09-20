import 'dart:io';

import 'package:app_to_foreground/app_to_foreground.dart';
import 'package:flutter/material.dart';
import 'package:flutter_overlay_window/flutter_overlay_window.dart';
import 'package:fuodz/services/app.service.dart';
import 'package:fuodz/view_models/taxi/taxi.vm.dart';
import 'package:fuodz/views/pages/taxi/widgets/incoming_new_order_alert.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:rxdart/rxdart.dart';
import 'dart:convert';
import 'package:awesome_notifications/awesome_notifications.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/models/new_taxi_order.dart';
import 'package:fuodz/services/extened_order_service.dart';
import 'package:fuodz/services/notification.service.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:singleton/singleton.dart';

class TaxiBackgroundOrderService extends ExtendedOrderService {
  //
  /// Factory method that reuse same instance automatically
  factory TaxiBackgroundOrderService() =>
      Singleton.lazy(() => TaxiBackgroundOrderService._());

  /// Private constructor
  TaxiBackgroundOrderService._() {
    this.fbListener();
  }

  BehaviorSubject<dynamic> showNewOrderStream = BehaviorSubject();
  NewTaxiOrder? newOrder;
  TaxiViewModel? taxiViewModel;

  processOrderNotification(NewTaxiOrder newOrder) async {
    //not in background
    if (appIsInBackground()) {
      //send notification to phone notification tray
      //check if overflay is permitted
      if (Platform.isAndroid && await FlutterOverlayWindow.isActive()) {
        AppToForeground.appToForeground();
        showNewOrderInAppAlert(newOrder);
      } else {
        showNewOrderNotificationAlert(newOrder);
      }
    } else {
      showNewOrderInAppAlert(newOrder);
    }
  }

  //handle showing new order alert bottom sheet to driver in app
  showNewOrderInAppAlert(NewTaxiOrder newOrder) async {
    try {
      taxiViewModel?.newOrder = newOrder;
      taxiViewModel?.newTaxiBookingService.stopListeningToNewOrder();
      //send zoom to new order point via stream
      taxiViewModel?.onGoingTaxiBookingService.zoomToPickupLocation(
        LatLng(
          taxiViewModel!.newOrder!.pickup!.lat!,
          taxiViewModel!.newOrder!.pickup!.long!,
        ),
      );
      //
      final result = await showModalBottomSheet(
        isDismissible: false,
        enableDrag: false,
        context: AppService().navigatorKey.currentContext!,
        backgroundColor: Colors.transparent,
        builder: (context) {
          return IncomingNewOrderAlert(
            taxiViewModel!,
            taxiViewModel!.newOrder!,
          );
        },
      );

      if (result != null) {
        taxiViewModel?.onGoingOrderTrip = result;
        taxiViewModel?.onGoingTaxiBookingService.loadTripUIByOrderStatus();
        taxiViewModel?.notifyListeners();
      } else {
        taxiViewModel?.taxiGoogleMapManagerService.clearMapData();
        taxiViewModel?.taxiGoogleMapManagerService.zoomToCurrentLocation();
        taxiViewModel?.taxiGoogleMapManagerService.updateGoogleMapPadding(20);
        taxiViewModel?.newTaxiBookingService.countDownCompleted();
      }
    } catch (e) {
      print(e);
    }
  }

  showNewOrderNotificationAlert(
    NewTaxiOrder newOrder, {
    int notifcationId = 10,
  }) async {
    //
    // await LocalStorageService.getPrefs();
    //show action notification to driver
    AwesomeNotifications().createNotification(
      content: NotificationContent(
        id: notifcationId,
        ticker: "${AppStrings.appName}",
        channelKey:
            NotificationService.newOrderNotificationChannel().channelKey!,
        title: "New Order Alert".tr(),
        backgroundColor: AppColor.primaryColorDark,
        body: ("Pickup Location".tr() +
            ": " +
            "${newOrder.pickup?.address} (${newOrder.pickupDistance.toInt().ceil()}km)"),
        notificationLayout: NotificationLayout.BigText,
        //
        payload: {
          "id": newOrder.id.toString(),
          "notifcationId": notifcationId.toString(),
          "newOrder": jsonEncode(newOrder.toJson()),
        },
      ),
      actionButtons: [
        NotificationActionButton(
          key: "accept",
          label: "Accept".tr(),
          color: Colors.green,
        ),
        NotificationActionButton(
          key: "reject",
          label: "Reject".tr(),
          color: Colors.red,
        ),
      ],
    );

    return;
  }
}
