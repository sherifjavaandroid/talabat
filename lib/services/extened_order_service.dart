import 'dart:async';

import 'package:flutter_fgbg/flutter_fgbg.dart';
import 'package:fuodz/services/local_storage.service.dart';
import 'package:fuodz/services/overlay.service.dart';

import 'app.service.dart';

class ExtendedOrderService {
  StreamSubscription<FGBGType>? subscriptionFGBGType;

  void fbListener() {
    //
    LocalStorageService.prefs!.setBool("appInBackground", false);
    //
    subscriptionFGBGType = FGBGEvents.stream.listen(
      (event) async {
        final appInBackground = (event == FGBGType.background);
        LocalStorageService.prefs!.setBool("appInBackground", appInBackground);
        //app is now in background, show overlay floating app bubble
        if (appInBackground && AppService().driverIsOnline) {
          //show overlay floating app bubble
          OverlayService().showFloatingBubble();
        } else {
          OverlayService().closeFloatingBubble();
        }
      },
    );
  }

  bool appIsInBackground() {
    return LocalStorageService.prefs!.getBool("appInBackground") ?? false;
  }

  void dispose() {
    subscriptionFGBGType?.cancel();
  }
}
