import 'package:cloud_firestore/cloud_firestore.dart';
import 'package:fuodz/services/auth.service.dart';

import 'package:singleton/singleton.dart';

class FirestoreService {
  //
  /// Factory method that reuse same instance automatically
  factory FirestoreService() => Singleton.lazy(() => FirestoreService._());

  /// Private constructor
  FirestoreService._() {}

  //
  FirebaseFirestore firebaseFireStore = FirebaseFirestore.instance;

  //
  freeDriverOrderNode() async {
    final driver = await AuthServices.getCurrentUser(force: true);
    String driverId = driver.id.toString();
    await firebaseFireStore
        .collection("driver_new_order")
        .doc(driverId)
        .delete();
  }
}
