import 'dart:async';
import 'package:cloud_firestore/cloud_firestore.dart' hide Order;
import 'package:fl_location/fl_location.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/models/delivery_address.dart';
import 'package:fuodz/models/order.dart';
import 'package:fuodz/services/location.service.dart';
import 'package:fuodz/services/taxi/taxi_polylines.service.dart';
import 'package:fuodz/services/taxi/taxi_trip_booking_code.service.dart';
import 'package:fuodz/view_models/taxi/taxi.vm.dart';
import 'package:fuodz/views/pages/taxi/widgets/statuses/arrived.view.dart';
import 'package:fuodz/views/pages/taxi/widgets/statuses/enroute.view.dart';
import 'package:fuodz/views/pages/taxi/widgets/statuses/pickup.view.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:supercharged/supercharged.dart';

class OnGoingTaxiBookingService extends TaxiPolylinesService {
  TaxiViewModel taxiViewModel;
  OnGoingTaxiBookingService(this.taxiViewModel) : super(taxiViewModel);
  //
  FirebaseFirestore firebaseFireStore = FirebaseFirestore.instance;
  StreamSubscription? tripUpdateStream;
  StreamSubscription? locationStreamSubscription;

  //
  DeliveryAddress? pickupLocation;
  DeliveryAddress? dropoffLocation;
  LatLng? driverPosition;
  final pickupMarkerId = MarkerId('sourcePin');
  final dropoffMarkerId = MarkerId('destPin');

  //dispose
  void dispose() {
    tripUpdateStream?.cancel();
    locationStreamSubscription?.cancel();
  }

  //
  //get current on going trip
  Future<Order?> getOnGoingTrip() async {
    //
    Order? order;
    taxiViewModel.setBusy(true);
    // AlertService.showLoading();
    try {
      order = await taxiViewModel.taxiRequest.getOnGoingTrip();
      loadTripUIByOrderStatus();
    } catch (error) {
      print("trip ongoing error ==> $error");
    }
    taxiViewModel.setBusy(false);
    // AlertService.stopLoading();
    return order;
  }

  //Zoom to pickup location
  zoomToPickupLocation([LatLng? point]) async {
    //
    taxiViewModel.taxiGoogleMapManagerService.removeMapMarker(pickupMarkerId);
    taxiViewModel.taxiGoogleMapManagerService.gMapMarkers.add(
      Marker(
        markerId: pickupMarkerId,
        position: point ??
            LatLng(
              taxiViewModel.onGoingOrderTrip?.taxiOrder?.pickupLatitude
                      .toDouble() ??
                  0.0,
              taxiViewModel.onGoingOrderTrip?.taxiOrder?.pickupLongitude
                      .toDouble() ??
                  0.0,
            ),
        icon: taxiViewModel.taxiGoogleMapManagerService.sourceIcon!,
        anchor: Offset(0.5, 0.5),
      ),
    );
    //
    taxiViewModel.notifyListeners();
    //actually zoom now
    taxiViewModel.taxiGoogleMapManagerService.googleMapController
        ?.animateCamera(
      CameraUpdate.newCameraPosition(
        CameraPosition(
          target: point ??
              LatLng(
                taxiViewModel.onGoingOrderTrip?.taxiOrder?.pickupLatitude
                        .toDouble() ??
                    0.0,
                taxiViewModel.onGoingOrderTrip?.taxiOrder?.pickupLongitude
                        .toDouble() ??
                    0.0,
              ),
          zoom: 16,
        ),
      ),
    );
  }

  //Zoom to dropoff location
  zoomToDropoffLocation() async {
    //
    taxiViewModel.taxiGoogleMapManagerService.removeMapMarker(dropoffMarkerId);
    taxiViewModel.taxiGoogleMapManagerService.gMapMarkers.add(
      Marker(
        markerId: dropoffMarkerId,
        position: LatLng(
          taxiViewModel.onGoingOrderTrip?.taxiOrder?.dropoffLatitude
                  .toDouble() ??
              0.00,
          taxiViewModel.onGoingOrderTrip?.taxiOrder?.dropoffLongitude
                  .toDouble() ??
              0.00,
        ),
        icon: taxiViewModel.taxiGoogleMapManagerService.destinationIcon!,
        anchor: Offset(0.5, 0.5),
      ),
    );
    //
    taxiViewModel.notifyListeners();
    //actually zoom now
    taxiViewModel.taxiGoogleMapManagerService.googleMapController
        ?.animateCamera(
      CameraUpdate.newCameraPosition(
        CameraPosition(
          target: LatLng(
            taxiViewModel.onGoingOrderTrip?.taxiOrder?.pickupLatitude
                    .toDouble() ??
                0.00,
            taxiViewModel.onGoingOrderTrip?.taxiOrder?.pickupLongitude
                    .toDouble() ??
                0.00,
          ),
          zoom: 16,
        ),
      ),
    );
  }

  //Zoom to bound within driver location & dropoff location
  zoomToTripBoundLocation() async {
    //
    locationStreamSubscription =
        LocationService().getNewLocationStream().listen(
      (event) {
        //
        driverPosition = LatLng(event.latitude, event.longitude);
        //zoom to driver and dropoff latbound
        updateCameraLocation(
          driverPosition!,
          LatLng(
            dropoffLocation?.latitude ?? 0.00,
            dropoffLocation?.longitude ?? 0.00,
          ),
          taxiViewModel.taxiGoogleMapManagerService.googleMapController!,
        );
      },
    );
  }

  //
  loadTripUIByOrderStatus({bool forceRefresh = true}) {
    //
    taxiViewModel.newFormKey();
    //
    if (forceRefresh) {
      startHandlingOnGoingTrip();
    }

    //
    Widget? tripUi = null;
    print("trip ongoing STATUS ==> ${taxiViewModel.onGoingOrderTrip?.status}");
    //
    switch (taxiViewModel.onGoingOrderTrip?.status) {
      case "pending":
        tripUi = PickupTaxiView(taxiViewModel);
        drawPolyLinesToPickup();
        break;
      case "preparing":
        tripUi = PickupTaxiView(taxiViewModel);
        drawPolyLinesToPickup();
        break;
      case "ready":
        tripUi = ArrivedTaxiView(taxiViewModel);
        break;
      case "enroute":
        tripUi = EnrouteTaxiView(taxiViewModel);
        drawTripPolyLines();
        break;
      case "delivered":
        taxiViewModel.taxiGoogleMapManagerService.clearMapData();
        // zoomToDropoffLocation();
        refreshSwipeBtnActionKey();
        tripUpdateStream?.cancel();
        taxiViewModel.notifyListeners();
        break;
      case "failed":
        refreshSwipeBtnActionKey();
        taxiViewModel.taxiGoogleMapManagerService.clearMapData();
        taxiViewModel.newTaxiBookingService.startNewOrderListener();
        break;
      case "cancelled":
        refreshSwipeBtnActionKey();
        taxiViewModel.taxiGoogleMapManagerService.clearMapData();
        taxiViewModel.newTaxiBookingService.startNewOrderListener();
        break;
      default:
        taxiViewModel.taxiGoogleMapManagerService.clearMapData();
        // zoomToDropoffLocation();
        refreshSwipeBtnActionKey();
        tripUpdateStream?.cancel();
        taxiViewModel.notifyListeners();
        break;
    }

    //
    taxiViewModel.uiStream.add(tripUi);
  }

  //
  String get getNewStateStatus {
    //
    String status = "Arrived";
    switch ((taxiViewModel.onGoingOrderTrip?.status ?? "").toLowerCase()) {
      case "pending":
        status = "Arrived";
        break;
      case "preparing":
        status = "Arrived";
        break;
      case "ready":
        status = "Start Trip";
        break;
      case "enroute":
        status = "End Trip";
        break;
      default:
        break;
    }
    return status;
  }

  //
  String getNextOrderStateStatus() {
    //
    String status = "ready";
    switch ((taxiViewModel.onGoingOrderTrip?.status ?? "").toLowerCase()) {
      case "preparing":
        status = "ready";
        break;
      case "ready":
        status = "enroute";
        break;
      case "enroute":
        status = "delivered";
        break;
      default:
        break;
    }
    return status;
  }

  //
  void startHandlingOnGoingTrip() async {
    //
    tripUpdateStream?.cancel();
    // if (!(tripUpdateStream?.isPaused ?? true)) {
    //   return;
    // }
    //set new on trip step
    tripUpdateStream = firebaseFireStore
        .collection("orders")
        .doc("${taxiViewModel.onGoingOrderTrip?.code}")
        .snapshots()
        .listen(
      (event) async {
        //update the rest onGoingTrip details
        if (event.data() != null && event.data()!.containsKey("status")) {
          //assing the status
          final orderStatus = event.data()!["status"];
          taxiViewModel.onGoingOrderTrip?.status = orderStatus;
          //
          print("Order Status Update ==> YEAHHH!!!!!!");
          taxiViewModel.notifyListeners();
        } else {
          //change status to cancelled if the data has been deleted but still exists locally
          taxiViewModel.onGoingOrderTrip?.status = "cancelled";
        }
        loadTripUIByOrderStatus(forceRefresh: false);
      },
    );
    //start order details listening stream
  }

  void startHandlingCompletedTrip(tripOrder) {
    taxiViewModel.notifyListeners();
    if (tripOrder != null) {
      taxiViewModel.showUserRating(tripOrder);
    }
  }

  GlobalKey swipeBtnActionKey = new GlobalKey();
  Future<bool> processOrderStatusUpdate() async {
    //
    taxiViewModel.setBusy(true);
    try {
      //
      final nextOrderStatus = getNextOrderStateStatus();
      //bookign code collection is required
      await TaxiTripBookingCodeService.handle(
        taxiViewModel,
        nextOrderStatus,
      );

      LatLng? clatlng;
      try {
        Location? currentLocationData = LocationService().currentLocationData;
        clatlng = LatLng(
          currentLocationData?.latitude ?? 0.00,
          currentLocationData?.longitude ?? 0.00,
        );
        // currentLocationData = await Geolocator.getCurrentPosition()
        //     .timeout(const Duration(seconds: 5));
      } catch (e) {
        print("location error ==> $e");
      }

      //allow
      try {
        taxiViewModel.onGoingOrderTrip =
            await taxiViewModel.orderRequest.updateOrder(
          id: taxiViewModel.onGoingOrderTrip!.id,
          status: nextOrderStatus,
          location: clatlng,
        );
      } catch (error) {
        taxiViewModel.onGoingOrderTrip =
            await taxiViewModel.orderRequest.updateOrder(
          id: taxiViewModel.onGoingOrderTrip!.id,
          status: nextOrderStatus,
        );
      }

      //show on order completed processes
      if (nextOrderStatus == "delivered") {
        startHandlingCompletedTrip(taxiViewModel.onGoingOrderTrip);
      }
      swipeBtnActionKey = new GlobalKey();
      taxiViewModel.notifyListeners();
      taxiViewModel.setBusy(false);
      loadTripUIByOrderStatus();
      return true;
    } catch (error) {
      taxiViewModel.setBusy(false);
      taxiViewModel.toastError("$error");
      return false;
    }
  }

  //
  void refreshSwipeBtnActionKey() {
    swipeBtnActionKey = new GlobalKey();
    taxiViewModel.onGoingOrderTrip = null;
    taxiViewModel.notifyListeners();
  }
}
