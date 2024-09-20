import 'dart:convert';
import 'dart:io';

import 'package:awesome_notifications/awesome_notifications.dart'
    hide NotificationModel;
import 'package:flutter/services.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/models/new_order.dart';
import 'package:fuodz/models/new_taxi_order.dart';
import 'package:fuodz/models/notification.dart';
import 'package:fuodz/services/auth.service.dart';
import 'package:fuodz/services/background_order.service.dart';
import 'package:fuodz/services/firebase.service.dart';
import 'package:fuodz/services/taxi_background_order.service.dart';

import 'local_storage.service.dart';

class NotificationService {
  //
  static const platform = MethodChannel('notifications.manage');
  //
  static initializeAwesomeNotification() async {
    await AwesomeNotifications().initialize(
      // set the icon to null if you want to use the default app icon
      'resource://drawable/notification_icon',
      [
        appNotificationChannel(),
        newOrderNotificationChannel(),
      ],
    );
    //requet notifcation permission if not allowed
    AwesomeNotifications().isNotificationAllowed().then((isAllowed) {
      if (!isAllowed) {
        // Insert here your friendly dialog box before call the request method
        // This is very important to not harm the user experience
        AwesomeNotifications().requestPermissionToSendNotifications();
      }
    });
  }

  static Future<void> clearIrrelevantNotificationChannels() async {
    if (!Platform.isAndroid) {
      return;
    }
    try {
      // get channels
      final List<dynamic> notificationChannels =
          await platform.invokeMethod('getChannels');

      //confirm is more than the required channels is found
      final notificationChannelNames = notificationChannels
          .map(
            (e) => e.toString().split(" -- ")[1],
          )
          .toList();

      //
      final totalFound = notificationChannelNames
          .where(
            (e) =>
                e.toLowerCase() ==
                appNotificationChannel().channelName?.toLowerCase(),
          )
          .toList();

      if (totalFound.length > 1) {
        //delete all app created notifications
        for (final notificationChannel in notificationChannels) {
          //
          final notificationChannelData = "$notificationChannel".split(" -- ");
          final notificationChannelId = notificationChannelData[0];
          final notificationChannelName = notificationChannelData[1];
          final isSystemOwned =
              notificationChannelName.toLowerCase() == "miscellaneous";
          //
          if (!isSystemOwned) {
            //
            await platform.invokeMethod(
              'deleteChannel',
              {"id": notificationChannelId},
            );
          }
        }

        //
        await initializeAwesomeNotification();
      }
    } on PlatformException catch (e) {
      print("Failed to get notificaiton channels: '${e.message}'.");
    }
  }

  static NotificationChannel appNotificationChannel() {
    //firebase fall back channel key
    //fcm_fallback_notification_channel
    return NotificationChannel(
      channelKey: 'basic_channel',
      channelName: 'Basic notifications',
      channelDescription: 'Notification channel for app',
      importance: NotificationImportance.High,
      soundSource: "resource://raw/alert",
      playSound: true,
    );
  }

  static NotificationChannel newOrderNotificationChannel() {
    return NotificationChannel(
      channelKey: 'new_order_channel',
      channelName: 'New Order notifications',
      channelDescription: 'New Order notification channel for app',
      importance: NotificationImportance.High,
      soundSource: "resource://raw/neworderalert",
      playSound: true,
    );
  }

  //
  static Future<List<NotificationModel>> getNotifications() async {
    //
    final pref = await LocalStorageService.getPrefs();
    final notificationsStringList = pref.getString(
          AppStrings.notificationsKey,
        ) ??
        null;

    if (notificationsStringList == null) {
      return [];
    }

    return (jsonDecode(notificationsStringList) as List)
        .asMap()
        .entries
        .map((notificationObject) {
      //
      return NotificationModel(
        index: notificationObject.key,
        title: notificationObject.value["title"],
        body: notificationObject.value["body"],
        image: notificationObject.value["image"],
        read: notificationObject.value["read"] is bool
            ? notificationObject.value["read"]
            : false,
        timeStamp: notificationObject.value["timeStamp"],
      );
    }).toList();
  }

  static Future<void> addNotification(NotificationModel notification) async {
    //
    final notifications = await getNotifications();
    notifications.insert(0, notification);

    //
    if (LocalStorageService.prefs == null) {
      await LocalStorageService.getPrefs();
    }
    await LocalStorageService.prefs!.setString(
      AppStrings.notificationsKey,
      jsonEncode(notifications),
    );
  }

  static void updateNotification(NotificationModel notificationModel) async {
    //
    final notifications = await getNotifications();
    notifications.removeAt(notificationModel.index!);
    notifications.insert(notificationModel.index!, notificationModel);
    await LocalStorageService.prefs!.setString(
      AppStrings.notificationsKey,
      jsonEncode(notifications),
    );
  }

  static listenToActions() {
    AwesomeNotifications().setListeners(
      onActionReceivedMethod: onActionReceivedMethod,
    );
  }

  @pragma('vm:entry-point')
  static Future<void> onActionReceivedMethod(
      ReceivedAction receivedAction) async {
    //if notification is from order
    // print("Hello");
    if (receivedAction.payload != null &&
        receivedAction.payload!.containsKey("newOrder")) {
      final newOrderRawData = jsonDecode(
        receivedAction.payload?["newOrder"] ?? "{}",
      );

      //
      // await Future.delayed(Duration(seconds: 10));
      if (AuthServices.driverVehicle != null) {
        final newOrder = NewTaxiOrder.fromJson(newOrderRawData);
        TaxiBackgroundOrderService().showNewOrderStream.add(newOrder);
      } else {
        final newOrder = NewOrder.fromJson(newOrderRawData, clean: true);
        BackgroundOrderService().showNewOrderInAppAlert(newOrder);
      }

      //close current
    } else {
      FirebaseService().saveNewNotification(
        null,
        title: receivedAction.title,
        body: receivedAction.body,
      );
      FirebaseService().notificationPayloadData = receivedAction.payload;
      FirebaseService().selectNotification("");
    }
  }
}
