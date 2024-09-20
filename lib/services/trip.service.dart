import 'dart:async';

import 'package:cloud_firestore/cloud_firestore.dart';
import 'package:dartx/dartx.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:geolocator/geolocator.dart';
import 'package:singleton/singleton.dart';
import 'package:geohash_plus/geohash_plus.dart';

class TripService {
  FirebaseFirestore firestore = FirebaseFirestore.instance;

  /// Factory method that reuse same instance automatically
  factory TripService() => Singleton.lazy(() => TripService._());

  /// Private constructor
  TripService._() {}
//
  Future<int?> generatePossibleDriverETA({
    required double lat,
    required double lng,
    int? vehicleTypeId,
  }) async {
    try {
      //
      double driverSearchRange = double.tryParse(
            AppStrings.env("driverSearchRadius").toString(),
          ) ??
          10;
      //generate location geohash
      int precision = getPrecision(driverSearchRange);
      GeoHash geohashClass = GeoHash.encode(lat, lng, precision: precision);
      String geoHash = geohashClass.hash;
      String minGeoHash = geoHash.substring(0, geoHash.length - 1);
      String maxGeoHash = geoHash + "\u{f}";
      //print hashes
      print("minGeoHash ==> $minGeoHash");
      print("maxGeoHash ==> $maxGeoHash");

      //
      Query<Map<String, dynamic>> driversQuery = firestore
          .collection("drivers")
          .where("free", isEqualTo: 1)
          .where("online", isEqualTo: 1)
          .where("g.geohash", isGreaterThanOrEqualTo: minGeoHash)
          .where("g.geohash", isLessThanOrEqualTo: maxGeoHash);

      //if vehicle type is provided
      if (vehicleTypeId != null) {
        driversQuery =
            driversQuery.where("vehicle_type_id", isEqualTo: vehicleTypeId);
      }

      final drivers = await driversQuery.limit(5).get();

      //
      List<double> driversETA = [];
      double driverAvgSpeed =
          AppStrings.env("taxi")["drivingSpeed"].toString().toDoubleOrNull() ??
              40;
      //
      drivers.docs.forEach((driver) {
        print(driver.data());
        double driverLat = double.parse(driver.data()["lat"].toString());
        double driverLng = double.parse(
            (driver.data()["lng"] ?? driver.data()["long"]).toString());
        double driverDistance =
            Geolocator.distanceBetween(driverLat, driverLng, lat, lng);
        //convert to km
        driverDistance = driverDistance / 1000;
        //calculate ETA
        double driverETA = driverDistance / driverAvgSpeed;
        driversETA.add(driverETA);
      });

      //calculate average ETA
      double avgETA = driversETA.average();
      return avgETA.ceil();
    } catch (error) {
      print("Error getting possibleDriverETA ==> $error");
      return null;
    }
  }

  //MISC.
  static int getPrecision(double km) {
    /*
      * 1	≤ 5,000km	×	5,000km
      * 2	≤ 1,250km	×	625km
      * 3	≤ 156km	×	156km
      * 4	≤ 39.1km	×	19.5km
      * 5	≤ 4.89km	×	4.89km
      * 6	≤ 1.22km	×	0.61km
      * 7	≤ 153m	×	153m
      * 8	≤ 38.2m	×	19.1m
      * 9	≤ 4.77m	×	4.77m
      *
     */

    if (km <= 0.00477)
      return 9;
    else if (km <= 0.0382)
      return 8;
    else if (km <= 0.153)
      return 7;
    else if (km <= 1.22)
      return 6;
    else if (km <= 4.89)
      return 5;
    else if (km <= 39.1)
      return 4;
    else if (km <= 156)
      return 3;
    else if (km <= 1250)
      return 2;
    else
      return 1;
  }

  //dispose
}
