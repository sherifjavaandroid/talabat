// To parse this JSON data, do
//
//     final packageTypePricing = packageTypePricingFromJson(jsonString);

import 'dart:convert';
import 'package:supercharged/supercharged.dart';

PackageTypePricing packageTypePricingFromJson(String str) =>
    PackageTypePricing.fromJson(json.decode(str));

String packageTypePricingToJson(PackageTypePricing data) =>
    json.encode(data.toJson());

class PackageTypePricing {
  PackageTypePricing({
    required this.id,
    required this.vendorId,
    required this.packageTypeId,
    required this.maxBookingDays,
    required this.sizePrice,
    required this.pricePerKg,
    required this.distancePrice,
    required this.pricePerKm,
    required this.fieldRequired,
  });

  int id;
  int vendorId;
  int packageTypeId;
  int maxBookingDays;
  double sizePrice;
  double pricePerKg;
  double distancePrice;
  double pricePerKm;
  bool fieldRequired;

  factory PackageTypePricing.fromJson(Map<String, dynamic> json) {
    return PackageTypePricing(
      id: json["id"] == null ? null : json["id"],
      vendorId: json["vendor_id"].toString().toInt()!,
      packageTypeId: json["package_type_id"].toString().toInt()!,
      maxBookingDays: json["max_booking_days"] == null
          ? 7
          : json["max_booking_days"].toString().toInt()!,
      sizePrice: double.parse(json["size_price"].toString()),
      pricePerKg: double.parse(json["price_per_kg"].toString()),
      distancePrice: double.parse(json["distance_price"].toString()),
      pricePerKm: double.parse(json["price_per_km"].toString()),
      fieldRequired:
          json["field_required"] == null ? true : json["field_required"],
    );
  }
  Map<String, dynamic> toJson() => {
        "id": id,
        "vendor_id": vendorId,
        "package_type_id": packageTypeId,
        "max_booking_days": maxBookingDays,
        "size_price": sizePrice,
        "price_per_kg": pricePerKg,
        "distance_price": distancePrice,
        "price_per_km": pricePerKm,
        "field_required": fieldRequired,
      };
}
