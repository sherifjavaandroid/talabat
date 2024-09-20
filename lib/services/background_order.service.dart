import 'dart:convert';
import 'dart:io';
import 'package:app_to_foreground/app_to_foreground.dart';
import 'package:awesome_notifications/awesome_notifications.dart';
import 'package:flutter/material.dart';
import 'package:flutter_overlay_window/flutter_overlay_window.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/models/new_order.dart';
import 'package:fuodz/services/app.service.dart';
import 'package:fuodz/services/notification.service.dart';
import 'package:fuodz/services/order_assignment.service.dart';
import 'package:fuodz/widgets/bottomsheets/new_order_alert.bottomsheet.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:singleton/singleton.dart';
import 'package:velocity_x/velocity_x.dart';
import 'extened_order_service.dart';

class BackgroundOrderService extends ExtendedOrderService {
  //
  /// Factory method that reuse same instance automatically
  factory BackgroundOrderService() =>
      Singleton.lazy(() => BackgroundOrderService._());

  /// Private constructor
  BackgroundOrderService._() {
    this.fbListener();
  }
  // StreamController<NewOrder> showNewOrderStream = StreamController.broadcast();
  NewOrder? newOrder;

  //
  processOrderNotification(NewOrder newOrder) async {
    //
    if (appIsInBackground()) {
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
  showNewOrderInAppAlert(NewOrder newOrder) async {
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

  //
  //show notification
  showNewOrderNotificationAlert(
    NewOrder newOrder, {
    int notifcationId = 10,
  }) async {
    //
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
            "${newOrder.pickup?.address} (${newOrder.pickup?.distance?.numCurrency}km)"),
        //
        payload: {
          "id": newOrder.id.toString(),
          "notifcationId": notifcationId.toString(),
          "newOrder": jsonEncode(newOrder.toJson()),
        },
        notificationLayout: NotificationLayout.BigText,
        category: NotificationCategory.Transport,
        criticalAlert: true,
      ),
      actionButtons: [
        NotificationActionButton(
          key: "open",
          label: "Open".tr(),
          color: AppColor.primaryColor,
        ),
      ],
    );

    return;
  }
}
