import 'dart:async';
import 'package:cloud_firestore/cloud_firestore.dart';
import 'package:fl_location/fl_location.dart' hide LocationAccuracy;
import 'package:fl_location_platform_interface/src/models/location_accuracy.dart'
    as FLAccuracy;
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/models/delivery_address.dart';
import 'package:fuodz/services/app.service.dart';
import 'package:fuodz/services/auth.service.dart';
import 'package:geoflutterfire2/geoflutterfire2.dart';
import 'package:geolocator/geolocator.dart' hide LocationPermission;
import 'package:georange/georange.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:rxdart/rxdart.dart';
import 'package:singleton/singleton.dart';

class LocationService {
  /// Factory method that reuse same instance automatically
  factory LocationService() => Singleton.lazy(() => LocationService._());

  /// Private constructor
  LocationService._() {}

  //
  GeoFlutterFire geoFlutterFire = GeoFlutterFire();
  GeoRange georange = GeoRange();
  //  Geolocator location = Geolocator();
  //  LocationSettings locationSettings;
  Location? currentLocationData;
  DeliveryAddress? currentLocation;
  bool? serviceEnabled;
  FirebaseFirestore firebaseFireStore = FirebaseFirestore.instance;
  BehaviorSubject<bool> locationDataAvailable =
      BehaviorSubject<bool>.seeded(false);
  BehaviorSubject<double> driverLocationEarthDistance =
      BehaviorSubject<double>.seeded(0.00);
  int lastUpdated = 0;
  StreamSubscription? locationUpdateStream;

  //
  Future<void> prepareLocationListener() async {
    //handle missing permission
    await handlePermissionRequest();
    _startLocationListner();
  }

  Future<bool?> handlePermissionRequest({bool background = false}) async {
    if (!await FlLocation.isLocationServicesEnabled) {
      throw "Location service is disabled. Please enable it and try again".tr();
    }

    var locationPermission = await FlLocation.checkLocationPermission();
    if (locationPermission == LocationPermission.deniedForever) {
      // Cannot request runtime permission because location permission is denied forever.
      throw "Location permission denied permanetly. Please check on location permission on app settings"
          .tr();
    } else if (locationPermission == LocationPermission.denied) {
      // Ask the user for location permission.
      locationPermission = await FlLocation.requestLocationPermission();
      if (locationPermission == LocationPermission.denied ||
          locationPermission == LocationPermission.deniedForever) {
        throw "Location permission denied. Please check on location permission on app settings"
            .tr();
      }
    }

    // // Location permission must always be allowed (LocationPermission.always)
    // // to collect location data in the background.
    // if (background == true &&
    //     locationPermission == LocationPermission.whileInUse) {
    //   return false;
    // }

    // Location services has been enabled and permission have been granted.
    return true;
  }

  Stream<dynamic> getNewLocationStream() {
    // return Geolocator.getPositionStream(
    //   locationSettings: LocationSettings(
    //     accuracy: LocationAccuracy.high,
    //     interval: AppStrings.timePassLocationUpdate * 1000,
    //     distanceFilter: (AppStrings.distanceCoverLocationUpdate ?? 5).toInt(),
    //   ),
    // );
    try {
      return FlLocation.getLocationStream(
        accuracy: FLAccuracy.LocationAccuracy.high,
        //seconds to milliseconds
        interval: AppStrings.timePassLocationUpdate * 1000,
        distanceFilter: AppStrings.distanceCoverLocationUpdate,
        // distanceFilter: 0,
      );
    } catch (error) {
      return Geolocator.getPositionStream(
        locationSettings: LocationSettings(
          accuracy: LocationAccuracy.high,
          // interval: AppStrings.timePassLocationUpdate * 1000,
          distanceFilter: (AppStrings.distanceCoverLocationUpdate).toInt(),
        ),
      );
    }
  }

  void _startLocationListner() async {
    //handle first time
    syncFirstTimeLocation();
    //listen
    locationUpdateStream?.cancel();
    locationUpdateStream = getNewLocationStream().listen(
      (currentPosition) {
        //
        if (currentPosition != null) {
          print("Location changed ==> $currentPosition");
          // Use current location
          if (currentLocation == null) {
            currentLocation = DeliveryAddress();
            locationDataAvailable.add(true);
          }

          currentLocation?.latitude = currentPosition.latitude;
          currentLocation?.longitude = currentPosition.longitude;
          currentLocationData = Location.fromJson(currentPosition.toJson());
          //
          syncLocationWithFirebase(currentLocationData!);
        } else {
          print("Location changed ==> null");
        }
      },
    );
  }

  syncFirstTimeLocation() async {
    try {
      //get current location
      Location currentLocation = await FlLocation.getLocation(
        accuracy: FLAccuracy.LocationAccuracy.high,
      );

      if (this.currentLocation == null) {
        this.currentLocation = DeliveryAddress();
        locationDataAvailable.add(true);
      }

      this.currentLocation?.latitude = currentLocation.latitude;
      this.currentLocation?.longitude = currentLocation.longitude;
      this.currentLocationData = Location.fromJson(currentLocation.toJson());
      //
      syncLocationWithFirebase(currentLocationData!);
    } catch (error) {
      print("Error getting first time location => $error");
    }
  }

//
  syncCurrentLocFirebase() {
    syncLocationWithFirebase(currentLocationData!);
  }

  //
  syncLocationWithFirebase(Location currentLocation) async {
    final driverId = (await AuthServices.getCurrentUser()).id.toString();
    //
    if (AppService().driverIsOnline) {
      print("Send to fcm");
      //get distance to earth center
      Point driverLocation = Point(
        latitude: currentLocation.latitude,
        longitude: currentLocation.longitude,
      );
      Point earthCenterLocation = Point(
        latitude: 0.00,
        longitude: 0.00,
      );
      //
      var earthDistance = georange.distance(
        earthCenterLocation,
        driverLocation,
      );
      //
      GeoFirePoint geoRepLocation = geoFlutterFire.point(
        latitude: driverLocation.latitude,
        longitude: driverLocation.longitude,
      );

      //
      final driverLocationDocs =
          await firebaseFireStore.collection("drivers").doc(driverId).get();

      //
      final docRef = driverLocationDocs.reference;

      if (driverLocationDocs.data() == null) {
        docRef.set(
          {
            "id": driverId,
            "lat": currentLocation.latitude,
            "long": currentLocation.longitude,
            "rotation": currentLocation.heading,
            "earth_distance": earthDistance,
            "range": AppStrings.driverSearchRadius,
            "coordinates": GeoPoint(
              currentLocation.latitude,
              currentLocation.longitude,
            ),
            "g": geoRepLocation.data,
            "online": AppService().driverIsOnline ? 1 : 0,
          },
        );
      } else {
        docRef.update(
          {
            "id": driverId,
            "lat": currentLocation.latitude,
            "long": currentLocation.longitude,
            "rotation": currentLocation.heading,
            "earth_distance": earthDistance,
            "range": AppStrings.driverSearchRadius,
            "coordinates": GeoPoint(
              currentLocation.latitude,
              currentLocation.longitude,
            ),
            "g": geoRepLocation.data,
            "online": AppService().driverIsOnline ? 1 : 0,
          },
        );
      }

      driverLocationEarthDistance.add(earthDistance);
      lastUpdated = DateTime.now().millisecondsSinceEpoch;
    }
  }

  //
  clearLocationFromFirebase() async {
    final driverId = (await AuthServices.getCurrentUser()).id.toString();
    await firebaseFireStore.collection("drivers").doc(driverId).delete();
  }
}
