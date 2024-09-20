// To parse this JSON data, do
//
//     final vendor = vendorFromJson(jsonString);

import 'dart:convert';

import 'package:dartx/dartx.dart';
import 'package:fuodz/models/category.dart';
import 'package:fuodz/models/delivery_address.dart';
import 'package:fuodz/models/delivery_slot.dart';
import 'package:fuodz/models/menu.dart';
import 'package:fuodz/models/package_type_pricing.dart';
import 'package:fuodz/models/vendor_type.dart';
import 'package:random_string/random_string.dart';

class Vendor {
  Vendor({
    required this.id,
    required this.vendorType,
    required this.name,
    required this.description,
    required this.baseDeliveryFee,
    required this.deliveryFee,
    required this.deliveryRange,
    required this.tax,
    required this.phone,
    required this.email,
    required this.address,
    required this.latitude,
    required this.longitude,
    required this.comission,
    required this.pickup,
    required this.delivery,
    required this.rating,
    required this.chargePerKm,
    required this.isOpen,
    required this.logo,
    required this.featureImage,
    required this.menus,
    required this.categories,
    required this.packageTypesPricing,
    required this.cities,
    required this.states,
    required this.countries,
    required this.deliverySlots,
    required this.canRate,
    required this.allowScheduleOrder,
    required this.hasSubcategories,
  }) {
    this.heroTag = randomAlphaNumeric(15) + "$id";
  }

  int id;
  VendorType? vendorType;
  String? heroTag;
  String name;
  String description;
  double baseDeliveryFee;
  double deliveryFee;
  double? deliveryRange;
  String tax;
  String phone;
  String email;
  String address;
  String latitude;
  String longitude;
  double? comission;
  int pickup;
  int delivery;
  int? rating;
  int chargePerKm;
  bool isOpen;
  String logo;
  String featureImage;
  List<Menu> menus;
  List<Category> categories;
  List<PackageTypePricing> packageTypesPricing;
  List<DeliverySlot> deliverySlots;
  List<String> cities;
  List<String> states;
  List<String> countries;
  bool canRate;
  bool allowScheduleOrder;
  bool hasSubcategories;

  factory Vendor.fromRawJson(String str) => Vendor.fromJson(json.decode(str));

  String toRawJson() => json.encode(toJson());

  factory Vendor.fromJson(Map<String, dynamic> json) {
    return Vendor(
      id: json["id"] == null ? null : json["id"],
      vendorType: json["vendor_type"] == null
          ? null
          : VendorType.fromJson(json["vendor_type"]),
      name: json["name"] == null ? null : json["name"],
      description: json["description"] == null ? null : json["description"],
      baseDeliveryFee: json["base_delivery_fee"] == null
          ? 0.00
          : double.parse(json["base_delivery_fee"].toString()),
      deliveryFee: json["delivery_fee"] == null
          ? 0.00
          : double.parse(json["delivery_fee"].toString()),
      deliveryRange: json["delivery_range"] == null
          ? null
          : double.parse(json["delivery_range"].toString()),
      tax: json["tax"] == null ? null : json["tax"],
      phone: json["phone"] == null ? null : json["phone"],
      email: json["email"] == null ? null : json["email"],
      address: json["address"] == null ? null : json["address"],
      latitude: json["latitude"] == null ? null : json["latitude"],
      longitude: json["longitude"] == null ? null : json["longitude"],
      comission: json["comission"] == null
          ? null
          : double.parse(json["comission"].toString()),
      pickup: json["pickup"] == null ? 0 : int.parse(json["pickup"].toString()),
      delivery:
          json["delivery"] == null ? 0 : int.parse(json["delivery"].toString()),
      rating:
          json["rating"] == null ? null : int.parse(json["rating"].toString()),
      chargePerKm: int.tryParse(json["charge_per_km"].toString()) ?? 0,
      isOpen: json["is_open"] == null ? true : json["is_open"],

      logo: json["logo"] == null ? null : json["logo"],
      featureImage:
          json["feature_image"] == null ? null : json["feature_image"],
      menus: json["menus"] == null
          ? []
          : List<Menu>.from(json["menus"].map((x) => Menu.fromJson(x))),
      categories: json["categories"] == null
          ? []
          : List<Category>.from(
              json["categories"].map((x) => Category.fromJson(x))),
      packageTypesPricing: json["package_types_pricing"] == null
          ? []
          : List<PackageTypePricing>.from(json["package_types_pricing"]
              .map((x) => PackageTypePricing.fromJson(x))),
      //cities
      cities: json["cities"] == null
          ? []
          : List<String>.from(json["cities"].map((e) => e["name"])),
      states: json["states"] == null
          ? []
          : List<String>.from(json["states"].map((e) => e["name"])),
      countries: json["cities"] == null
          ? []
          : List<String>.from(json["countries"].map((e) => e["name"])),
      //
      deliverySlots: json["slots"] == null
          ? []
          : List<DeliverySlot>.from(
              json["slots"].map((x) => DeliverySlot.fromJson(x))),

      //
      canRate: json["can_rate"] == null ? null : json["can_rate"],
      hasSubcategories: json["has_sub_categories"] == null
          ? false
          : json["has_sub_categories"],
      allowScheduleOrder: json["allow_schedule_order"] == null
          ? false
          : json["allow_schedule_order"],
    );
  }

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "description": description,
        "base_delivery_fee": baseDeliveryFee,
        "delivery_fee": deliveryFee,
        "delivery_range": deliveryRange == null ? null : deliveryRange,
        "tax": tax,
        "phone": phone,
        "email": email,
        "address": address,
        "latitude": latitude,
        "longitude": longitude,
        "comission": comission == null ? null : comission,
        "pickup": pickup,
        "delivery": delivery,
        "rating": rating == null ? null : rating,
        "charge_per_km": chargePerKm,
        "is_open": isOpen,
        "logo": logo,
        "feature_image": featureImage,
        "can_rate": canRate,
        "allow_schedule_order": allowScheduleOrder,
        "menus": List<dynamic>.from(menus.map((x) => x.toJson())),
        "categories": List<dynamic>.from(categories.map((x) => x.toJson())),
        "package_types_pricing":
            List<dynamic>.from(packageTypesPricing.map((x) => x.toJson())),
        "slots": List<dynamic>.from(deliverySlots.map((x) => x.toJson())),
      };

  //
  bool get allowOnlyDelivery => delivery == 1 && pickup == 0;
  bool get allowOnlyPickup => delivery == 0 && pickup == 1;

  //
  bool canServiceLocation(DeliveryAddress deliveryaddress) {
    //cities,states & countries
    final foundCountry = this.countries.firstOrNullWhere(
          (element) =>
              element.toLowerCase() ==
              "${deliveryaddress.country}".toLowerCase(),
        );

    //
    print("Country found");
    if (foundCountry != null) {
      return true;
    }

    //states
    final foundState = this.states.firstOrNullWhere(
          (element) =>
              element.toLowerCase() == "${deliveryaddress.state}".toLowerCase(),
        );

    //
    print("state found");
    if (foundState != null) {
      return true;
    }

    //cities
    final foundCity = this.cities.firstOrNullWhere(
      (element) {
        return element.toLowerCase() == deliveryaddress.city?.toLowerCase();
      },
    );

    //
    print("city found");
    if (foundCity != null) {
      return true;
    }

    //
    return false;
  }
}
