import 'dart:async';
import 'dart:convert';
import 'dart:io';

import 'package:dartx/dartx.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/models/delivery_address.dart';
import 'package:fuodz/services/app.service.dart';
import 'package:fuodz/services/local_storage.service.dart';
import 'package:fuodz/widgets/bottomsheets/location_permission.bottomsheet.dart';
import 'package:geolocator/geolocator.dart';
import 'package:location/location.dart';
// import 'package:geocoder/geocoder.dart';
import 'package:rxdart/rxdart.dart';
import 'geocoder.service.dart';

class LocationService {
  //
  static Location location = new Location();

  static bool? serviceEnabled;
  static PermissionStatus? _permissionGranted;
  static LocationData? _locationData;
  static Address? currenctAddress;
  static DeliveryAddress? deliveryaddress;
  static StreamSubscription? currentLocationListener;

  //
  static BehaviorSubject<Address> currenctAddressSubject =
      BehaviorSubject<Address>();
  // stream for delivery address
  static BehaviorSubject<DeliveryAddress> currenctDeliveryAddressSubject =
      BehaviorSubject<DeliveryAddress>();
  // static Stream<Address> get currenctAddressStream =>
  //     _currenctAddressSubject.stream;

  static Future<void> prepareLocationListener([
    bool oneTime = false,
  ]) async {
    _permissionGranted = await location.hasPermission();
    if (_permissionGranted == PermissionStatus.denied) {
      //
      bool requestPermission = true;
      if (!Platform.isIOS) {
        requestPermission = await showRequestDialog();
      }
      if (requestPermission) {
        _permissionGranted = await location.requestPermission();
        if (_permissionGranted != PermissionStatus.granted) {
          return;
        }
      }
    }

    serviceEnabled = await location.serviceEnabled();
    if (serviceEnabled == null || serviceEnabled! == false) {
      serviceEnabled = await location.requestService();
      if (serviceEnabled == null || serviceEnabled! == false) {
        return;
      }
    }

    _startLocationListner(oneTime);
  }

  static Future<bool> showRequestDialog() async {
    //
    var requestResult = false;
    //
    await showDialog(
      context: AppService().navigatorKey.currentContext!,
      builder: (context) {
        return LocationPermissionDialog(onResult: (result) {
          requestResult = result;
        });
      },
    );

    //
    return requestResult;
  }

  static void _startLocationListner([
    bool oneTime = false,
  ]) async {
    //
    //update location every 100meters
    // await location.changeSettings(distanceFilter: 50);
    // //listen
    // currentLocationListener =
    //     location.onLocationChanged.listen((LocationData currentLocation) {
    //   // Use current location
    //   _locationData = currentLocation;
    //   //
    //   geocodeCurrentLocation(true);
    // });

    //listen
    currentLocationListener =
        Geolocator.getPositionStream().listen((Position currentLocation) {
      // Use current location
      _locationData = LocationData.fromMap(currentLocation.toJson());
      //
      geocodeCurrentLocation(true);
    });

    //get the current location on send to listeners
    _locationData = await location.getLocation();
    geocodeCurrentLocation(oneTime);
  }

  //
  static Future<void> geocodeCurrentLocation(
      [bool closeListener = false]) async {
    if (_locationData != null) {
      final coordinates = new Coordinates(
        _locationData?.latitude ?? 0.0,
        _locationData?.longitude ?? 0.0,
      );

      try {
        //
        final addresses = await GeocoderService().findAddressesFromCoordinates(
          coordinates,
        );
        //
        currenctAddress = addresses.first;
        //
        if (currenctAddress != null) {
          currenctAddressSubject.add(currenctAddress!);
          //set and save for next time
          final mDeliveryaddress = DeliveryAddress(
            name: currenctAddress!.featureName,
            address: currenctAddress!.addressLine,
            latitude: currenctAddress!.coordinates?.latitude,
            longitude: currenctAddress!.coordinates?.longitude,
          );
          if (deliveryaddress == null) {
            saveSelectedAddressLocally(mDeliveryaddress);
          }
        }
      } catch (error) {
        print("Error get location ==> $error");
      }
    }

    //
    if (closeListener) {
      print("Location listener closed");
      currentLocationListener?.cancel();
    }
  }

  //coordinates to address
  static Future<Address?> addressFromCoordinates({
    required double lat,
    required double lng,
  }) async {
    Address? address;
    final coordinates = new Coordinates(
      lat,
      lng,
    );

    try {
      //
      final addresses = await GeocoderService().findAddressesFromCoordinates(
        coordinates,
      );
      //
      address = addresses.first;
    } catch (error) {
      print("Issue with addressFromCoordinates ==> $error");
    }
    return address;
  }

  //Helper methods

  //get current lat
  static double? get cLat {
    return LocationService.currenctAddress?.coordinates?.latitude;
  }

  //get current lng
  static double? get cLng {
    return LocationService.currenctAddress?.coordinates?.longitude;
  }

  //
  static saveSelectedAddressLocally(DeliveryAddress? mDeliveryaddress) async {
    deliveryaddress = mDeliveryaddress;
    if (mDeliveryaddress != null) {
      final pref = await LocalStorageService.getPrefs();
      await pref.setString(
        "LOCAL_ADDRESS",
        jsonEncode(mDeliveryaddress.toJson()),
      );
      //
      currenctDeliveryAddressSubject.add(mDeliveryaddress);
    }
  }

  //
  static Future<DeliveryAddress?> getLocallySaveAddress() async {
    final pref = await LocalStorageService.getPrefs();
    final rawData = pref.getString("LOCAL_ADDRESS");
    if (rawData != null && rawData.isNotNullOrBlank) {
      return DeliveryAddress.fromJson(jsonDecode(rawData));
    }
    return null;
  }

  //MISC.
  static double? getFetchByLocationLat() {
    return LocationService.deliveryaddress?.latitude ??
        LocationService.currenctAddress?.coordinates?.latitude;
  }

  static double? getFetchByLocationLng() {
    return LocationService.deliveryaddress?.longitude ??
        LocationService.currenctAddress?.coordinates?.longitude;
  }
}
