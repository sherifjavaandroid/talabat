import 'dart:async';

import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/services/app_permission_handler.service.dart';
import 'package:fuodz/services/location.service.dart';
import 'package:fuodz/view_models/taxi/taxi.vm.dart';
import 'package:georange/georange.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:supercharged/supercharged.dart';
import 'package:rxdart/rxdart.dart';

class TaxiLocationService {
  //
  TaxiViewModel? taxiViewModel;
  StreamSubscription? myLocationListener;
  BehaviorSubject<int> etaStream = BehaviorSubject<int>();
  Timer? _timer;
  Timer? _etaTimer;
  Marker? driverMarker;

  //
  TaxiLocationService(this.taxiViewModel) {
    //
    startLocationListener();
  }

  dispose() {
    myLocationListener?.cancel();
    _timer?.cancel();
    _etaTimer?.cancel();
  }

  //
  startLocationListener() async {
    if (await AppPermissionHandlerService().isLocationGranted()) {
      taxiViewModel?.taxiGoogleMapManagerService.canShowMap = true;
      taxiViewModel?.notifyListeners();
      startListeningToDriverLocation();
    }
  }

  //
  startListeningToDriverLocation() async {
    //
    myLocationListener?.cancel();
    //
    myLocationListener = LocationService().getNewLocationStream().listen(
      (event) {
        //
        if (driverMarker == null) {
          //new driver maker
          driverMarker = Marker(
            markerId: taxiViewModel!.taxiGoogleMapManagerService.driverMarkerId,
            position: LatLng(
              event.latitude,
              event.longitude,
            ),
            rotation: event.heading,
            icon: taxiViewModel!.taxiGoogleMapManagerService.driverIcon!,
            anchor: Offset(0.5, 0.5),
          );

//
          taxiViewModel!.taxiGoogleMapManagerService.gMapMarkers =
              taxiViewModel!.taxiGoogleMapManagerService.gMapMarkers
                  .replaceFirstWhere(
                    (marker) =>
                        marker.markerId ==
                        taxiViewModel!
                            .taxiGoogleMapManagerService.driverMarkerId,
                    driverMarker!,
                  )
                  .toSet();
        } else {
          //update driver maker
          driverMarker = driverMarker?.copyWith(
            positionParam: LatLng(
              event.latitude,
              event.longitude,
            ),
            rotationParam: event.heading,
          );

          //
          taxiViewModel!.taxiGoogleMapManagerService.gMapMarkers
              .add(driverMarker!);
        }

        //
        taxiViewModel?.notifyListeners();
        zoomToLocation();
      },
    );
  }

  zoomToLocation() async {
    //
    taxiViewModel!.taxiGoogleMapManagerService.googleMapController
        ?.animateCamera(
      CameraUpdate.newCameraPosition(
        CameraPosition(
          target: driverMarker?.position ?? LatLng(0.00, 0.00),
          zoom: 16,
        ),
      ),
    );

    //
    pauseAutoZoomToLocation();
  }

  pauseAutoZoomToLocation() async {
    _timer?.cancel();
  }

  handleAutoZoomToLocation() async {
    _timer?.cancel();
    _timer = Timer.periodic(Duration(seconds: 5), (Timer timer) {
      zoomToLocation();
    });
  }

  void requestLocationPermissionForGoogleMap() async {
    await AppPermissionHandlerService().handleLocationRequest();
    taxiViewModel!.taxiLocationService.startLocationListener();
  }

  //ETA section
  startETAListener(LatLng latLng) async {
    _etaTimer?.cancel();
    _etaTimer = Timer.periodic(Duration(seconds: 10), (Timer timer) {
      calculatedETAToLocation(latLng);
    });
  }

  calculatedETAToLocation(LatLng latLng) {
    //
    final startPoint = Point(
      latitude: driverMarker!.position.latitude,
      longitude: driverMarker!.position.longitude,
    );
    final endPoint = Point(
      latitude: latLng.latitude,
      longitude: latLng.longitude,
    );
    final distance = GeoRange().distance(startPoint, endPoint);
    double etaInHours = (distance /
        (AppStrings.env("taxi")["drivingSpeed"] ?? "50")
            .toString()
            .toDouble()!);
    final eta = (etaInHours * 60).ceil();
    etaStream.add(eta);
  }
}
