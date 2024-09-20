import 'dart:io';
import 'package:flutter_background/flutter_background.dart';
import 'package:fuodz/services/background_order.service.dart';
import 'package:fuodz/services/location.service.dart';
import 'package:fuodz/services/order_manager.service.dart';
import 'package:fuodz/services/taxi_background_order.service.dart';
import 'package:localize_and_translate/localize_and_translate.dart';

import 'app_permission_handler.service.dart';

class AppbackgroundService {
  //

  startBg() async {
    final permitted =
        await AppPermissionHandlerService().handleLocationRequest();
    if (!permitted) {
      return;
    }
    await LocationService().prepareLocationListener();
    await OrderManagerService().startListener();
    BackgroundOrderService();
    TaxiBackgroundOrderService();

    //
    if (Platform.isAndroid) {
      //
      final androidConfig = FlutterBackgroundAndroidConfig(
        notificationTitle: "Background service".tr(),
        notificationText: "Background notification to keep app running".tr(),
        notificationImportance: AndroidNotificationImportance.Default,
        notificationIcon: AndroidResource(
          name: 'notification_icon',
          defType: 'drawable',
        ), // Default is ic_launcher from folder mipmap
      );

      //check for permission
      //CALL THE PERMISSION HANDLER
      final allowed =
          await AppPermissionHandlerService().handleBackgroundRequest();
      //
      if (allowed) {
        await FlutterBackground.initialize(androidConfig: androidConfig);
        await FlutterBackground.enableBackgroundExecution();
      }
    }
  }

  void stopBg() {
    // Platform.isAndroid
    if (Platform.isAndroid) {
      bool enabled = FlutterBackground.isBackgroundExecutionEnabled;
      if (enabled) {
        FlutterBackground.disableBackgroundExecution();
      }
    }
    OrderManagerService().stopListener();
  }
}
