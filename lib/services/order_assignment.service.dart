import 'dart:convert';
import 'package:cloud_firestore/cloud_firestore.dart';
import 'package:fl_location/fl_location.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/models/new_order.dart';
import 'package:geolocator/geolocator.dart';
import 'package:velocity_x/velocity_x.dart';

import 'auth.service.dart';

class OrderAssignmentService {
  //check if driver can handle the new order
  static Future<bool> driverCanHandleOrder(
    Map<String, dynamic> json,
    String docRefString,
  ) async {
    bool handle = await checkForSameVehicleType(json);
    if (handle) {
      final canPickup = await driverWithinPickup(json);
      if (canPickup) {
        handle = await runDriverNotifiedTransaction(json, docRefString);
      } else {
        handle = false;
      }
    }
    return handle;
  }

  //
  static Future<bool> checkForSameVehicleType(Map<String, dynamic> json) async {
    final vehicleTypeID = json['vehicle_type_id'];
    if (vehicleTypeID == null || vehicleTypeID == 0) {
      return true;
    }
    final driverVehicle = await AuthServices.getDriverVehicle();
    if (driverVehicle == null) {
      return false;
    }
    return driverVehicle.vehicleTypeId == vehicleTypeID;
  }

  //
  static Future<bool> driverWithinPickup(Map<String, dynamic> json) async {
    try {
      dynamic pickupJson = json['pickup'];
      if (pickupJson is String) {
        pickupJson = jsonDecode(pickupJson);
      }

      Pickup? pickup = Pickup.fromJson(pickupJson);
      final cLoc = await FlLocation.getLocation(timeLimit: 5.seconds);
      //get pickup distance
      double distance = Geolocator.distanceBetween(
        cLoc.latitude,
        cLoc.longitude,
        pickup.lat!.toDouble(),
        pickup.long!.toDouble(),
      );
      distance = distance / 1000;
      //check distance
      return distance <= AppStrings.driverSearchRadius;
    } catch (error) {
      return false;
    }
  }

  //run transaction to let other driver know you are currently bein notified
  static Future<bool> runDriverNotifiedTransaction(
    Map<String, dynamic> json,
    String docRefString,
  ) async {
    final driver = await AuthServices.getCurrentUser();
    return (await FirebaseFirestore.instance.runTransaction<bool>(
      (transaction) async {
        // Get the document
        DocumentReference docRef = FirebaseFirestore.instance.doc(docRefString);
        DocumentSnapshot snapshot = await transaction.get(docRef);

        if (!snapshot.exists) {
          // throw Exception("User does not exist!");
          return false;
        }

        //check if i was informed already
        List? informedDrivers = (snapshot.data() as Map)['informed'] as List?;
        if (informedDrivers != null && informedDrivers.contains(driver.id)) {
          return false;
        }

        //check if already ignored
        List ignoredDrivers = (snapshot.data() as Map)['ignored'] as List;
        if (ignoredDrivers.contains(driver.id)) {
          return false;
        }

        int maxDriverNotifiable =
            int.tryParse((snapshot.data() as Map)['notifiable'].toString()) ??
                1;
        if (informedDrivers == null) {
          informedDrivers = [driver.id];
        } else if (informedDrivers.length < maxDriverNotifiable &&
            !informedDrivers.contains(driver.id)) {
          informedDrivers.add(driver.id);
        } else {
          return false;
        }

        // Perform an update on the document
        transaction.update(docRef, {'informed': informedDrivers});

        return true;
      },
      maxAttempts: 2,
    ).catchError(
      (error) {
        print(error);
        return false;
      },
    ));
    // .then((value) => print("Follower count updated to $value"))
  }

  //release order for other drivers
  static Future<bool> releaseOrderForotherDrivers(
    Map<String, dynamic> json,
    String docRefString,
  ) async {
    final driver = await AuthServices.getCurrentUser();
    final done = (await FirebaseFirestore.instance.runTransaction<bool>(
      (transaction) async {
        // Get the document
        DocumentReference docRef = FirebaseFirestore.instance.doc(docRefString);
        DocumentSnapshot snapshot = await transaction.get(docRef);

        if (!snapshot.exists) {
          // throw Exception("User does not exist!");
          return false;
        }

        //remove driver id from noified drivers
        List? informedDrivers = (snapshot.data() as Map)['informed'] as List?;
        if (informedDrivers == null) {
          informedDrivers = [];
        } else if (informedDrivers.contains(driver.id)) {
          informedDrivers.remove(driver.id);
        }

        //add driver id to list of ignored drivers
        List? ignoredDrivers = (snapshot.data() as Map)['ignored'] as List?;
        if (ignoredDrivers == null) {
          ignoredDrivers = [driver.id];
        } else if (!ignoredDrivers.contains(driver.id)) {
          ignoredDrivers.add(driver.id);
        } else {
          return false;
        }

        // Perform an update on the document
        transaction.update(
          docRef,
          {
            'ignored': ignoredDrivers,
            "informed": informedDrivers,
          },
        );
        return true;
      },
      maxAttempts: 2,
    ).catchError((error) {
      print(error);
      return false;
    }));
    // .then((value) => print("Follower count updated to $value"))

    return done;
  }
}
