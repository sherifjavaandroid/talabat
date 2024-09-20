import 'dart:convert';
import 'dart:io';
import 'dart:math';

import 'package:awesome_notifications/awesome_notifications.dart'
    hide NotificationModel;
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:firestore_chat/firestore_chat.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_routes.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/constants/app_ui_settings.dart';
import 'package:fuodz/constants/printer_values.dart';
import 'package:fuodz/models/notification.dart';
import 'package:fuodz/models/order.dart';
import 'package:fuodz/requests/order.request.dart';
import 'package:fuodz/services/app.service.dart';
import 'package:fuodz/services/chat.service.dart';
import 'package:fuodz/services/notification.service.dart';
import 'package:fuodz/services/printer.service.dart';
import 'package:fuodz/views/pages/home.page.dart';
import 'package:singleton/singleton.dart';
import 'package:localize_and_translate/localize_and_translate.dart';

class FirebaseService {
  //
  /// Factory method that reuse same instance automatically
  factory FirebaseService() => Singleton.lazy(() => FirebaseService._());

  /// Private constructor
  FirebaseService._() {}

  //
  NotificationModel? notificationModel;
  FirebaseMessaging firebaseMessaging = FirebaseMessaging.instance;
  dynamic notificationPayloadData;

  setUpFirebaseMessaging() async {
    //Request for notification permission
    /*NotificationSettings settings = */
    await firebaseMessaging.requestPermission();
    //subscribing to all topic
    firebaseMessaging.subscribeToTopic("all");

    //on notification tap tp bring app back to life
    FirebaseMessaging.onMessageOpenedApp.listen((RemoteMessage message) {
      print("FCM Open app");
      saveNewNotification(message);
      selectNotification("From onMessageOpenedApp");
      //
      refreshOrdersList(message);
    });

    //normal notification listener
    FirebaseMessaging.onMessage.listen((RemoteMessage message) {
      print("FCM Received");
      saveNewNotification(message);
      showNotification(message);
      //
      refreshOrdersList(message);
    });
  }

  //write to notification list
  saveNewNotification(
    RemoteMessage? message, {
    String? title,
    String? body,
  }) {
    //
    notificationPayloadData = message != null ? message.data : null;
    if (message?.notification == null &&
        message?.data != null &&
        message?.data["title"] == null &&
        title == null) {
      return;
    }
    //Saving the notification
    notificationModel = NotificationModel();
    notificationModel!.title =
        message?.notification?.title ?? title ?? message?.data["title"] ?? "";
    notificationModel!.body =
        message?.notification?.body ?? body ?? message?.data["body"] ?? "";
    //
    try {
      final imageUrl = message?.data["image"] ??
          (Platform.isAndroid
              ? message?.notification?.android?.imageUrl
              : message?.notification?.apple?.imageUrl);
      notificationModel!.image = imageUrl;
    } catch (error) {
      print("Error ==> $error");
    }
    notificationModel!.timeStamp = DateTime.now().millisecondsSinceEpoch;

    //add to database/shared pref
    NotificationService.addNotification(notificationModel!);
  }

  //
  showNotification(RemoteMessage message) async {
    if (message.notification == null && message.data["title"] == null) {
      return;
    }

    //
    notificationPayloadData = message.data;

    //
    try {
      //
      final imageUrl = message.data["image"] ??
          (Platform.isAndroid
              ? message.notification?.android?.imageUrl
              : message.notification?.apple?.imageUrl);

      //crete notification content
      NotificationContent notificationContent = NotificationContent(
        id: Random().nextInt(20),
        channelKey: getNotificationChannelKey(message),
        title: message.data["title"] ?? message.notification?.title,
        body: message.data["body"] ?? message.notification?.body,
        bigPicture: imageUrl,
        icon: "resource://drawable/notification_icon",
        notificationLayout: imageUrl != null
            ? NotificationLayout.BigPicture
            : NotificationLayout.Default,
        payload: Map<String, String>.from(message.data),
      );
      //for new order
      final isOrder = notificationPayloadData != null &&
          notificationPayloadData["is_order"] != null;
      if (isOrder) {
        //new status
        final statues = ["pending", "new"];
        bool isPending = statues.contains(notificationPayloadData["status"]);
        Map<String, String> payload = Map<String, String>.from(message.data);
        if (isPending) {
          notificationContent = NotificationContent(
            id: AppService().neworderNotificationId,
            channelKey: getNotificationChannelKey(message),
            title: message.data["title"] ?? message.notification?.title,
            body: message.data["body"] ?? message.notification?.body,
            bigPicture: imageUrl,
            icon: "resource://drawable/notification_icon",
            notificationLayout: imageUrl != null
                ? NotificationLayout.BigPicture
                : NotificationLayout.Default,
            payload: payload,
            //
            wakeUpScreen: true,
            fullScreenIntent: true,
            locked: true,
            autoDismissible: false,
            criticalAlert: true,
          );
        }
      }

      //
      AwesomeNotifications().createNotification(content: notificationContent);

      ///
    } catch (error) {
      print("Notification Show error ===> ${error}");
    }
  }

  //handle on notification selected
  Future selectNotification(String? payload) async {
    if (payload == null) {
      return;
    }
    try {
      //
      final isChat = notificationPayloadData != null &&
          notificationPayloadData["is_chat"] != null;
      final isOrder = notificationPayloadData != null &&
          notificationPayloadData["is_order"] != null;
      //
      if (isChat) {
        //
        dynamic user = jsonDecode(notificationPayloadData['user']);
        dynamic peer = jsonDecode(notificationPayloadData['peer']);
        String chatPath = notificationPayloadData['path'];
        //
        Map<String, PeerUser> peers = {
          '${user['id']}': PeerUser(
            id: '${user['id']}',
            name: "${user['name']}",
            image: "${user['photo']}",
          ),
          '${peer['id']}': PeerUser(
            id: '${peer['id']}',
            name: "${peer['name']}",
            image: "${peer['photo']}",
          ),
        };
        //
        final peerRole = peer["role"];
        //
        final chatEntity = ChatEntity(
          onMessageSent: ChatService.sendChatMessage,
          mainUser: peers['${user['id']}']!,
          peers: peers,
          //don't translate this
          path: chatPath,
          title: peer["role"] == null
              ? "Chat with".tr() + " ${peer['name']}"
              : peerRole == 'vendor'
                  ? "Chat with vendor".tr()
                  : "Chat with driver".tr(),
          supportMedia: AppUISettings.canVendorChatSupportMedia,
        );
        Navigator.of(AppService().navigatorKey.currentContext!).pushNamed(
          AppRoutes.chatRoute,
          arguments: chatEntity,
        );
      }
      //order
      else if (isOrder) {
        //
        try {
          //fetch order from api
          int orderId = int.parse("${notificationPayloadData!['order_id']}");
          Order order = await OrderRequest().getOrderDetails(id: orderId);
          //
          Navigator.of(AppService().navigatorKey.currentContext!).pushNamed(
            AppRoutes.orderDetailsRoute,
            arguments: order,
          );
        } catch (error) {
          //navigate to orders page
          await Navigator.of(AppService().navigatorKey.currentContext!).push(
            MaterialPageRoute(
              builder: (_) => HomePage(),
            ),
          );
          //then switch to orders tab
          AppService().changeHomePageIndex();
        }
      }
      //regular notifications
      else {
        Navigator.of(AppService().navigatorKey.currentContext!).pushNamed(
            AppRoutes.notificationDetailsRoute,
            arguments: notificationModel);
      }
    } catch (error) {
      print("Error opening Notification ==> $error");
    }
  }

  //refresh orders list if the notification is about assigned order
  void refreshOrdersList(RemoteMessage message) async {
    if (message.data["is_order"] != null) {
      await Future.delayed(Duration(seconds: 3));
      AppService().refreshAssignedOrders.add(true);
      //Handle auto print order
      if (PrinterSettingValues.isAutoPrintEnabled) {
        //fetch order from api
        int orderId = int.parse("${notificationPayloadData!['order_id']}");
        Order order = await OrderRequest().getOrderDetails(id: orderId);
        //print order
        await PrinterService().handleAutoPrint(order);
      }
    }
  }

  //get notification sound
  String getNotificationChannelKey(RemoteMessage message) {
    //check if message is from order
    if (message.data["is_order"] != null) {
      final orderStatus = message.data["status"] ?? "new";
      if (orderStatus == "pending") {
        return NotificationService.appNotificationChannel(
          mChannelKey: AppStrings.newOrderNotificationChannelKey,
          mSoundSource: AppStrings.newOrderNotificationSoundSource,
        ).channelKey!;
      }
    }
    return NotificationService.appNotificationChannel().channelKey!;
  }
}
