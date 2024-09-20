import 'dart:typed_data';

import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_images.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:fuodz/view_models/taxi/taxi.vm.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:flutter_polyline_points/flutter_polyline_points.dart';
import 'package:velocity_x/velocity_x.dart';

class TaxiGoogleMapManagerService {
  TaxiViewModel? taxiViewModel;
  GoogleMapController? googleMapController;
  String? mapStyle;
  EdgeInsets googleMapPadding = EdgeInsets.only(top: kToolbarHeight);
  Set<Polyline> gMapPolylines = {};
  // this will hold each polyline coordinate as Lat and Lng pairs
  List<LatLng> polylineCoordinates = [];
  Set<Marker> gMapMarkers = {};
  MarkerId driverMarkerId = MarkerId("driverIcon");
  PolylinePoints polylinePoints = PolylinePoints();
// for my custom icons
  BitmapDescriptor? sourceIcon;
  BitmapDescriptor? destinationIcon;
  BitmapDescriptor? driverIcon;
  bool canShowMap = true;

  TaxiGoogleMapManagerService(this.taxiViewModel) {
    setSourceAndDestinationIcons();
  }

  onMapReady(GoogleMapController controller) {
    googleMapController = controller;
    setGoogleMapStyle();
  }

  onMapCameraMoveStarted() {
    taxiViewModel?.taxiLocationService.pauseAutoZoomToLocation();
  }

  onMapCameraIdle() {
    taxiViewModel?.taxiLocationService.handleAutoZoomToLocation();
  }

  void setGoogleMapStyle() async {
    if (taxiViewModel == null) {
      return;
    }
    String value =
        await DefaultAssetBundle.of(taxiViewModel!.viewContext).loadString(
      'assets/json/google_map_style.json',
    );
    //
    mapStyle = value;
    taxiViewModel?.notifyListeners();
  }

  setSourceAndDestinationIcons() async {
    sourceIcon = await BitmapDescriptor.fromAssetImage(
      ImageConfiguration(devicePixelRatio: 2.5),
      AppImages.pickupLocation,
    );
    //
    destinationIcon = await BitmapDescriptor.fromAssetImage(
      ImageConfiguration(devicePixelRatio: 2.5),
      AppImages.dropoffLocation,
    );
    //
    final Uint8List markerIcond = await Utils().getBytesFromCanvas(
      ((taxiViewModel?.viewContext.percentWidth ?? 1) * 13).ceil(),
      ((taxiViewModel?.viewContext.percentWidth ?? 1) * 25).ceil(),
      AppImages.driverCar,
    );
    driverIcon = BitmapDescriptor.fromBytes(markerIcond);
  }

  //
  //
  zoomToCurrentLocation() async {
    // myLocationListener?.cancel();
    // if (await AppPermissionHandlerService().isLocationGranted()) {
    //   final currentPosition = await Geolocator.getCurrentPosition();
    //   if (currentPosition != null) {
    //     zoomToLocation(currentPosition.latitude, currentPosition.longitude);
    //   }
    // }
    // //
    // myLocationListener =
    //     LocationService().location.onLocationChanged.listen((locationData) {
    //   //actually zoom now
    //   zoomToLocation(locationData.latitude, locationData.longitude);
    // });
  }

  //
  zoomToLocation(double lat, double lng) {
    googleMapController?.animateCamera(
      CameraUpdate.newCameraPosition(
        CameraPosition(
          target: LatLng(lat, lng),
          zoom: 16,
        ),
      ),
    );
  }

  void updateGoogleMapPadding([double? height]) {
    googleMapPadding = EdgeInsets.only(
      top: googleMapPadding.top,
      bottom: height ?? googleMapPadding.bottom,
    );
    taxiViewModel?.notifyListeners();
  }

  clearMapData() {
    clearMapMarkers();
    polylineCoordinates.clear();
    gMapPolylines.clear();
    taxiViewModel?.uiStream.add(null);
    taxiViewModel?.notifyListeners();
  }

  //
  clearMapMarkers({bool clearDriver = false}) {
    if (clearDriver) {
      gMapMarkers = {};
    } else {
      gMapMarkers.removeWhere((e) => e.markerId != driverMarkerId);
    }
    taxiViewModel?.notifyListeners();
  }

  removeMapMarker(MarkerId markerId) {
    gMapMarkers.removeWhere((e) => e.markerId == markerId);
    taxiViewModel?.notifyListeners();
  }
}
