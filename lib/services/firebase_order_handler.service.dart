import 'dart:async';
import 'dart:convert';
// import 'dart:developer';
import 'dart:isolate';

import 'package:cloud_firestore/cloud_firestore.dart';
import 'package:firebase_core/firebase_core.dart';

class FirebaseOrderHandlerService {
  static ReceivePort port = ReceivePort();
  static StreamSubscription? newOrderListener;

  @pragma('vm:entry-point')
  static void startAutoOrderAssignment(List<dynamic> dataSet) async {
    String data = dataSet[0];
    SendPort sendPort = dataSet[1];
    Map<String, dynamic> json = jsonDecode(data);
    String firebaseNode = "searchingOrders";
    if (json['vehicle'] != null) {
      firebaseNode = "searchingTaxiOrders";
    }
    //firebase listen to new order
    await Firebase.initializeApp();
    FirebaseFirestore firebaseFirestore = FirebaseFirestore.instance;
    newOrderListener?.cancel();
    newOrderListener = firebaseFirestore
        .collection(firebaseNode)
        .snapshots()
        .distinct()
        .listen((event) {
      //
      if (event.size > 0) {
        for (var doc in event.docs) {
          sendPort.send([doc.reference.path, doc.data()]);
        }
      } else {
        // sendPort.send("No data");
        // log("BG ==> No data");
      }
    });
  }
}
