import 'package:flutter/material.dart';
import 'package:fuodz/views/pages/splash.page.dart';
import 'package:rxdart/rxdart.dart';
import 'package:singleton/singleton.dart';

class AppService {
  // var neworderNotificationId = Random().nextInt(20);
  var neworderNotificationId = 0000012;
  //

  /// Factory method that reuse same instance automatically
  factory AppService() => Singleton.lazy(() => AppService._());

  /// Private constructor
  AppService._() {}

  final GlobalKey<NavigatorState> navigatorKey = GlobalKey<NavigatorState>();
  BehaviorSubject<int> homePageIndex = BehaviorSubject<int>();
  BehaviorSubject<bool> refreshAssignedOrders = BehaviorSubject<bool>();

  //
  changeHomePageIndex({int index = 2}) async {
    print("Changed Home Page");
    homePageIndex.add(index);
  }

  reloadApp() async {
    navigatorKey.currentState!.pushAndRemoveUntil(
      MaterialPageRoute(
        builder: (context) => SplashPage(),
      ),
      (route) => false,
    );
  }
}
