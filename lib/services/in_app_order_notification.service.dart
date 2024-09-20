import 'dart:io';

import 'package:flutter/material.dart';
import 'package:fuodz/widgets/bottomsheets/new_order_alert.bottomsheet.dart';
import 'package:permission_handler/permission_handler.dart';
import 'package:singleton/singleton.dart';

import '../widgets/bottomsheets/overlay_permission.bottomsheet.dart';

class InAppOrderNotificationService {
  /// Factory method that reuse same instance automatically
  factory InAppOrderNotificationService() =>
      Singleton.lazy(() => InAppOrderNotificationService._());

  BuildContext? viewContext;

  /// Private constructor
  InAppOrderNotificationService._() {}

  //
  handleBringAppToForeground(BuildContext viewContext) async {
    //
    this.viewContext = viewContext;

    //if not android
    if (!Platform.isAndroid) {
      return;
    }

    PermissionStatus status = await Permission.systemAlertWindow.status;
    if (status.isPermanentlyDenied) {
      return;
    }
    if (status.isDenied) {
      // We didn't ask for permission yet or the permission has been denied before, but not permanently.
      final result = await showModalBottomSheet(
        context: viewContext,
        isDismissible: false,
        isScrollControlled: true,
        builder: (context) {
          return OverlayPermissionBottomSheet();
        },
      );
      //
      if (result != null && result is bool && result) {
        status = await Permission.systemAlertWindow.request();
      }
    }

    //   //handle background service
    //   bool hasPermissions = await FlutterBackground.hasPermissions;
    //   if (!hasPermissions) {
    //     // We didn't ask for permission yet or the permission has been denied before, but not permanently.
    //     final result = await showModalBottomSheet(
    //       context: viewContext,
    //       isDismissible: false,
    //       isScrollControlled: true,
    //       backgroundColor: Colors.transparent,
    //       builder: (context) {
    //         return BackgroundPermissionBottomSheet();
    //       },
    //     );
    //     //
    //     if (result != null && result is bool && result) {
    //       handleBGService();
    //     }
    //   } else {
    //     handleBGService();
    //   }
    // }

    // handleBGService() async {
    //   PermissionStatus status =
    //       await Permission.ignoreBatteryOptimizations.request();
    //   if (!status.isGranted) {
    //     return;
    //   }
    //   //
    //   //
    //   final androidConfig = FlutterBackgroundAndroidConfig(
    //     notificationTitle: "New Order Listening".tr(),
    //     notificationText:
    //         "We are listening for new orders in the background".tr(),
    //     notificationImportance: AndroidNotificationImportance.High,
    //     notificationIcon: AndroidResource(name: 'notification_icon'),
    //     enableWifiLock: true,
    //     showBadge: true,
    //   );

    //   try {
    //     await FlutterBackground.initialize(androidConfig: androidConfig);
    //     await FlutterBackground.enableBackgroundExecution();
    //   } catch (error) {
    //     log("Error ==> $error");
    //   }
  }

  //
  handleNewOrderAlert(notificationPayloadData) async {
    //fetch order from api
    int orderId = int.parse("${notificationPayloadData['order_id']}");
    showModalBottomSheet(
      context: viewContext!,
      isDismissible: false,
      enableDrag: false,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) {
        return NewOrderAlertBottomsheet(orderId: orderId);
      },
    );
  }
}
