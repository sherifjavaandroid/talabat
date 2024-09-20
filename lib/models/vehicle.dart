// To parse this JSON data, do
//
//     final vehicle = vehicleFromJson(jsonString);

import 'dart:convert';
import 'package:supercharged/supercharged.dart';

Vehicle vehicleFromJson(String str) => Vehicle.fromJson(json.decode(str));

String vehicleToJson(Vehicle data) => json.encode(data.toJson());

class Vehicle {
  Vehicle({
    required this.id,
    required this.carModelId,
    required this.driverId,
    required this.vehicleTypeId,
    required this.regNo,
    required this.color,
    required this.photo,
    required this.carModel,
    required this.vehicleType,
    required this.verified,
    this.isActive = 0,
  });

  int id;
  int carModelId;
  int driverId;
  int vehicleTypeId;
  String regNo;
  String color;
  int isActive;

  String photo;
  CarModel carModel;
  VehicleType vehicleType;
  bool verified;

  factory Vehicle.fromJson(Map<String, dynamic> json) {
    return Vehicle(
      id: json["id"],
      carModelId: json["car_model_id"],
      driverId: json["driver_id"],
      vehicleTypeId: json["vehicle_type_id"],
      regNo: json["reg_no"],
      color: json["color"],
      isActive: json["is_active"] == null
          ? 0
          : (json["is_active"] is bool)
              ? json["is_active"]
                  ? 1
                  : 0
              : json["is_active"] ?? 0,
      photo: json["photo"],
      carModel: CarModel.fromJson(json["car_model"]),
      vehicleType: VehicleType.fromJson(json["vehicle_type"]),
      verified: json["verified"] ?? false,
    );
  }

  Map<String, dynamic> toJson() => {
        "id": id,
        "car_model_id": carModelId,
        "driver_id": driverId,
        "vehicle_type_id": vehicleTypeId,
        "reg_no": regNo,
        "color": color,
        "is_active": isActive,
        "photo": photo,
        "car_model": carModel.toJson(),
        "vehicle_type": vehicleType.toJson(),
        "verified": verified,
      };
}

class CarModel {
  CarModel({
    required this.id,
    required this.name,
    required this.carMakeId,
    required this.carMake,
  });

  int id;
  String name;
  int carMakeId;
  CarMake? carMake;

  factory CarModel.fromJson(Map<String, dynamic> json) => CarModel(
        id: json["id"],
        name: json["name"],
        carMakeId: json["car_make_id"],
        carMake: json["car_make"] != null
            ? CarMake.fromJson(json["car_make"])
            : null,
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "car_make_id": carMakeId,
        "car_make": carMake?.toJson(),
      };
}

class CarMake {
  CarMake({
    required this.id,
    required this.name,
  });

  int id;
  String name;

  factory CarMake.fromJson(Map<String, dynamic> json) => CarMake(
        id: json["id"],
        name: json["name"],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
      };
}

class VehicleType {
  VehicleType({
    required this.id,
    required this.name,
    required this.slug,
    required this.baseFare,
    required this.distanceFare,
    required this.timeFare,
    required this.minFare,
    required this.isActive,
    required this.formattedDate,
    required this.photo,
  });

  int id;
  String name;
  String slug;
  double baseFare;
  double distanceFare;
  double timeFare;
  double minFare;
  int isActive;
  String formattedDate;
  String photo;

  factory VehicleType.fromJson(Map<String, dynamic> json) => VehicleType(
        id: json["id"],
        name: json["name"],
        slug: json["slug"],
        baseFare: json["base_fare"].toString().toDouble()!,
        distanceFare: json["distance_fare"].toString().toDouble()!,
        timeFare: json["time_fare"].toString().toDouble()!,
        minFare: json["min_fare"].toString().toDouble()!,
        isActive: json["is_active"],
        formattedDate: json["formatted_date"],
        photo: json["photo"],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "slug": slug,
        "base_fare": baseFare,
        "distance_fare": distanceFare,
        "time_fare": timeFare,
        "min_fare": minFare,
        "is_active": isActive,
        "formatted_date": formattedDate,
        "photo": photo,
      };
}
