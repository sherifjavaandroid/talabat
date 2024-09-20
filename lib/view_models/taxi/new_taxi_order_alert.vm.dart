import 'package:circular_countdown_timer/circular_countdown_timer.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/models/new_taxi_order.dart';
import 'package:fuodz/requests/order.request.dart';
import 'package:fuodz/requests/taxi.request.dart';
import 'package:fuodz/services/app.service.dart';
import 'package:fuodz/services/auth.service.dart';
import 'package:fuodz/services/firestore.service.dart';
import 'package:fuodz/view_models/base.view_model.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:fuodz/extensions/context.dart';

class NewTaxiOrderAlertViewModel extends MyBaseViewModel {
  //
  OrderRequest orderRequest = OrderRequest();
  TaxiRequest taxiRequest = TaxiRequest();
  NewTaxiOrder newOrder;
  bool canDismiss = false;
  CountDownController countDownTimerController = CountDownController();
  NewTaxiOrderAlertViewModel(this.newOrder, BuildContext context) {
    this.viewContext = context;
  }

  initialise() {
    //
    AppService().playNotificationSound();
    //
    countDownTimerController.start();
  }

  void processOrderAcceptance() async {
    setBusy(true);
    try {
      final order = await orderRequest.acceptNewOrder(
        newOrder.id,
        status: "preparing",
      );
      AppService().assetsAudioPlayer.stop();
      await FirestoreService().freeDriverOrderNode();
      //
      viewContext.pop(order);
      // return;
    } catch (error) {
      viewContext.showToast(
        msg: "$error",
        bgColor: Colors.red,
        textColor: Colors.white,
        textSize: 20,
      );

      //
      canDismiss = true;
    }
    setBusy(false);
    //
    if (canDismiss) {
      AppService().assetsAudioPlayer.stop();
      viewContext.pop();
    }
  }

  void countDownCompleted(bool started) async {
    print('Countdown Ended');
    if (started) {
      if (isBusy) {
        canDismiss = true;
      } else {
        AppService().assetsAudioPlayer.stop();
        viewContext.pop();
        //STOP NOTIFICATION SOUND
        AppService().stopNotificationSound();
        //silently reject order assignment
        setBusy(true);
        try {
          //
          await taxiRequest.rejectAssignment(
            newOrder.id,
            AuthServices.currentUser!.id,
          );
          await FirestoreService().freeDriverOrderNode();
        } catch (error) {
          print("error ignoring trip assignment ==> $error");
        }
        setBusy(false);
      }
    }
  }
}
