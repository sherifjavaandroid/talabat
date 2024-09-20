// To parse this JSON data, do
//
//     final packageTypePricing = packageTypePricingFromJson(jsonString);

import 'dart:convert';

import 'package:fuodz/models/package_type.dart';

PackageTypePricing packageTypePricingFromJson(String str) =>
    PackageTypePricing.fromJson(json.decode(str));

String packageTypePricingToJson(PackageTypePricing data) =>
    json.encode(data.toJson());

class PackageTypePricing {
  PackageTypePricing({
    required this.id,
    required this.isActive,
    required this.vendorId,
    required this.packageTypeId,
    required this.maxBookingDays,
    required this.sizePrice,
    required this.pricePerKg,
    required this.distancePrice,
    required this.basePrice,
    required this.pricePerKm,
    required this.packageType,
    required this.autoReady,
    required this.extraFields,
  });

  int id;
  int isActive;
  int vendorId;
  int packageTypeId;
  int maxBookingDays;
  double sizePrice;
  double pricePerKg;
  double distancePrice;
  double basePrice;
  double pricePerKm;
  PackageType packageType;
  bool autoReady;
  bool extraFields;

  factory PackageTypePricing.fromJson(Map<String, dynamic> json) {
    return PackageTypePricing(
      id: json["id"] == null ? null : json["id"],
      vendorId: json["vendor_id"] == null ? null : json["vendor_id"],
      autoReady:
          (json["auto_assignment"] == null || json["auto_assignment"] == 0)
              ? false
              : true,
      extraFields:
          json["field_required"] == null ? false : json["field_required"],
      isActive: json["is_active"],
      packageTypeId:
          json["package_type_id"] == null ? null : json["package_type_id"],
      maxBookingDays:
          json["max_booking_days"] == null ? null : json["max_booking_days"],
      sizePrice: double.tryParse(json["size_price"].toString()) ?? 0,
      pricePerKg: double.tryParse(json["price_per_kg"].toString()) ?? 0,
      distancePrice: double.tryParse(json["distance_price"].toString()) ?? 0,
      pricePerKm: double.tryParse(json["price_per_km"].toString()) ?? 0,
      basePrice: double.tryParse(json["base_price"].toString()) ?? 0.00,
      packageType: PackageType.fromJson(json["package_type"]),
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
        "base_price": basePrice,
        "is_active": isActive,
        "price_per_km": pricePerKm,
        "package_type": packageType,
      };
}
