import 'dart:async';
import 'dart:io';

import 'package:assets_audio_player/assets_audio_player.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/models/order.dart';
import 'package:random_string/random_string.dart';
import 'package:rxdart/rxdart.dart';
import 'package:singleton/singleton.dart';
import 'package:flutter_image_compress/flutter_image_compress.dart';
import 'package:path_provider/path_provider.dart' as path_provider;

class AppService {
  //

  /// Factory method that reuse same instance automatically
  factory AppService() => Singleton.lazy(() => AppService._());

  /// Private constructor
  AppService._() {}

  final GlobalKey<NavigatorState> navigatorKey = GlobalKey<NavigatorState>();
  BehaviorSubject<int> homePageIndex = BehaviorSubject<int>();
  BehaviorSubject<bool> refreshAssignedOrders = BehaviorSubject<bool>();
  BehaviorSubject<Order> addToAssignedOrders = BehaviorSubject<Order>();
  bool driverIsOnline = false;
  StreamSubscription? actionStream;
  List<int> ignoredOrders = [];
  AssetsAudioPlayer assetsAudioPlayer = AssetsAudioPlayer();

  changeHomePageIndex({int index = 2}) async {
    print("Changed Home Page");
    homePageIndex.add(index);
  }

  //
  void playNotificationSound() {
    try {
      assetsAudioPlayer.stop();
    } catch (error) {
      print("Error stopping audio player");
    }

    //
    assetsAudioPlayer.open(
      Audio("assets/audio/alert.mp3"),
      loopMode: LoopMode.single,
      // notificationSettings: NotificationSettings(
      //   nextEnabled: false,
      //   prevEnabled: false,
      //   stopEnabled: false,
      //   seekBarEnabled: false,
      // ),
      showNotification: false,
      playInBackground: PlayInBackground.enabled,
    );
  }

  void stopNotificationSound() {
    try {
      assetsAudioPlayer.stop();
    } catch (error) {
      print("Error stopping audio player");
    }
  }

  Future<File?> compressFile(File file, {int quality = 50}) async {
    final dir = await path_provider.getTemporaryDirectory();
    final targetPath =
        dir.absolute.path + "/temp_" + randomAlphaNumeric(10) + ".jpg";
    //
    var result = await FlutterImageCompress.compressAndGetFile(
      file.absolute.path,
      targetPath,
      quality: quality,
    );

    print("File size ==> ${file.lengthSync()}");
    print("Compressed File size ==> ${result?.lengthSync()}");
    return result;
  }
}
