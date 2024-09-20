import 'dart:convert';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/models/new_order.dart';
import 'package:fuodz/services/location.service.dart';
import 'package:geolocator/geolocator.dart';
import 'package:georange/georange.dart';
import 'package:supercharged/supercharged.dart';

NewTaxiOrder newTaxiOrderFromJson(String str) =>
    NewTaxiOrder.fromJson(json.decode(str));

String newTaxiOrderToJson(NewTaxiOrder data) => json.encode(data.toJson());

class NewTaxiOrder {
  NewTaxiOrder({
    required this.id,
    this.pickup,
    this.status,
    this.driverId,
    this.code,
    this.vehicleTypeId,
    this.tripDistance,
    this.serverPickupDistance,
    this.dropoff,
    this.earthDistance,
    this.expiresAt,
    this.docRef,
    this.amount = "0.00",
    this.total = "0.00",
  });

  Pickup? pickup;
  String? status;
  dynamic driverId;
  int id;
  String total;
  String amount;
  String? code;
  int? vehicleTypeId;
  double? tripDistance;
  double? serverPickupDistance;
  Dropoff? dropoff;
  double? earthDistance;
  int? expiresAt;
  String? docRef;

  factory NewTaxiOrder.fromJson(Map<String, dynamic> json) {
    String amount = json["amount"] == null ? "" : json["amount"];
    NewTaxiOrder model = NewTaxiOrder(
      id: json["id"].toString().toInt()!,
      amount: amount,
      total: json["total"] == null ? amount : json["total"].toString(),
      pickup: Pickup.fromJson(jsonDecode(json["pickup"])),
      status: json["status"].toString(),
      driverId: json["driver_id"].toString().toInt() ?? null,
      code: json["code"].toString(),
      vehicleTypeId: json["vehicle_type_id"].toString().toInt(),
      tripDistance: json["trip_distance"].toString().toDouble(),
      serverPickupDistance: json["pickup_distance"] != null
          ? json["pickup_distance"].toString().toDouble()
          : null,
      dropoff: Dropoff.fromJson(jsonDecode(json["dropoff"])),
      earthDistance: json["earth_distance"].toString().toDouble(),
      expiresAt: json["expiresAt"] != null
          ? json["expiresAt"]
          : DateTime.now().millisecondsSinceEpoch +
              (AppStrings.alertDuration * 1000),
    );

    //if pickup and dropoff distance is not available, calculate it
    if (model.pickup?.distance == null) {
      final location1 = Point(
        latitude: model.pickup!.lat!,
        longitude: model.pickup!.long!,
      );
      final location2 = Point(
        latitude: LocationService().currentLocation?.latitude ?? 0.00,
        longitude: LocationService().currentLocation?.longitude ?? 0.00,
      );
      model.pickup?.distance = GeoRange().distance(location1, location2);
    }

    //if pickup and dropoff distance is not available, calculate it
    if (model.dropoff?.distance == null) {
      final location1 = Point(
        latitude: model.pickup!.lat!,
        longitude: model.pickup!.long!,
      );
      final location2 = Point(
        latitude: model.dropoff!.lat!,
        longitude: model.dropoff!.long!,
      );
      model.dropoff?.distance = GeoRange().distance(location1, location2);
    }

    return model;
  }

  double get pickupDistance {
    return serverPickupDistance ??
        Geolocator.distanceBetween(
              LocationService().currentLocation?.latitude ?? 0.00,
              LocationService().currentLocation?.longitude ?? 0.00,
              pickup!.lat!.toDouble(),
              pickup!.long!.toDouble(),
            ) /
            1000;
  }

  Map<String, dynamic> toJson() => {
        "pickup": jsonEncode(pickup?.toJson()),
        "status": status,
        "driver_id": driverId,
        "id": id,
        "code": code,
        "vehicle_type_id": vehicleTypeId,
        "trip_distance": tripDistance,
        "pickup_distance": serverPickupDistance,
        "dropoff": jsonEncode(dropoff?.toJson()),
        "earth_distance": earthDistance,
        "expiresAt": expiresAt,
        "amount": amount,
        "total": total,
      };

  //
  int get initialAlertDuration {
    if (expiresAt == null) {
      return AppStrings.alertDuration;
    }
    int duration = 0;
    final timePast = expiresAt! - DateTime.now().millisecondsSinceEpoch;
    if (timePast > 0) {
      duration = AppStrings.alertDuration - (timePast ~/ 1000);
    } else {
      duration = AppStrings.alertDuration - 1;
    }
    return duration;
  }
}
