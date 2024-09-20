import 'dart:async';
import 'package:fuodz/models/order.dart';

class OrderService {
  //

//Hnadle background message
  static Future<dynamic> openOrderPayment(Order order, dynamic vm) async {
    //
    if ((order.paymentMethod?.slug ?? "offline") != "offline") {
      return vm.openWebpageLink(order.paymentLink);
    } else {
      return vm.openExternalWebpageLink(order.paymentLink);
    }
  }
}
