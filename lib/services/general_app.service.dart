import 'dart:async';
import 'package:firebase_core/firebase_core.dart';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:fuodz/services/firebase.service.dart';

class GeneralAppService {
  //

//Hnadle background message
  @pragma('vm:entry-point')
  static Future<void> onBackgroundMessageHandler(
    RemoteMessage remoteMessage,
  ) async {
    //if it has not data then it is a normal notification, so ignore it
    if (remoteMessage.data.isEmpty) return;
    await Firebase.initializeApp();
    await FirebaseService().saveNewNotification(remoteMessage);
    //normal notifications
    FirebaseService().showNotification(remoteMessage);
  }
}
