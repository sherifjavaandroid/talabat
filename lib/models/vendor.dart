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
    required this.isActive,
    required this.logo,
    required this.featureImage,
    this.menus = const [],
    this.categories = const [],
    this.packageTypesPricing = const [],
    this.cities = const [],
    this.states = const [],
    this.countries = const [],
    this.deliverySlots = const [],
    required this.canRate,
    required this.allowScheduleOrder,
    required this.hasSubcategories,
    required this.useSubscription,
    required this.hasSubscription,
    //
    this.documentRequested = false,
    this.pendingDocumentApproval = false,
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
  String? address;
  String? latitude;
  String? longitude;
  double? comission;
  int pickup;
  int delivery;
  int rating;
  int chargePerKm;
  bool isOpen;
  int isActive;
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
  bool useSubscription;
  bool hasSubscription;
  //
  bool documentRequested;
  bool pendingDocumentApproval;

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
      rating: int.tryParse(json["rating"].toString()) ?? 0,
      chargePerKm: json["charge_per_km"] == null
          ? 0
          : int.parse(json["charge_per_km"].toString()),
      isOpen: json["is_open"] == null ? true : json["is_open"],
      isActive: json["is_active"] == null
          ? 0
          : int.parse(json["is_active"].toString()),

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
      hasSubscription:
          json["has_subscription"] == null ? false : json["has_subscription"],
      useSubscription:
          json["use_subscription"] == null ? false : json["use_subscription"],
      //
      documentRequested: json["document_requested"] == null
          ? false
          : json["document_requested"],
      pendingDocumentApproval: json["pending_document_approval"] == null
          ? false
          : json["pending_document_approval"],
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
        "rating": rating,
        "charge_per_km": chargePerKm,
        "is_open": isOpen,
        "is_active": isActive,
        "logo": logo,
        "feature_image": featureImage,
        "can_rate": canRate,
        "allow_schedule_order": allowScheduleOrder,
        "vendor_type": vendorType == null ? null : vendorType?.toJson(),
        "menus": List<dynamic>.from(menus.map((x) => x.toJson())),
        "categories": List<dynamic>.from(categories.map((x) => x.toJson())),
        "package_types_pricing":
            List<dynamic>.from(packageTypesPricing.map((x) => x.toJson())),
        "slots": List<dynamic>.from(deliverySlots.map((x) => x.toJson())),
        'has_subscription': hasSubscription,
        'use_subscription': useSubscription,
        "document_requested": documentRequested,
        "pending_document_approval": pendingDocumentApproval,
      };

  //
  bool get allowOnlyDelivery => delivery == 1 && pickup == 0;
  bool get allowOnlyPickup => delivery == 0 && pickup == 1;
  bool get isPackageType {
    return (vendorType != null && vendorType?.slug == "parcel");
  }

  bool get isServiceType {
    return (vendorType != null && vendorType?.slug == "service");
  }

  //
  bool canServiceLocation(DeliveryAddress deliveryaddress) {
    //cities,states & countries

    final foundCountry = this.countries.firstOrNullWhere(
          (element) =>
              element.toLowerCase() ==
              "${deliveryaddress.country}".toLowerCase(),
        );

    //
    if (foundCountry != null) {
      print("Country found");
      return true;
    }

    //states
    final foundState = this.states.firstOrNullWhere(
          (element) =>
              element.toLowerCase() == "${deliveryaddress.state}".toLowerCase(),
        );

    //
    if (foundState != null) {
      print("state found");
      return true;
    }

    //cities
    final foundCity = this.cities.firstOrNullWhere(
      (element) {
        return element.toLowerCase() == deliveryaddress.city.toLowerCase();
      },
    );

    //
    if (foundCity != null) {
      print("city found");
      return true;
    }

    return false;
  }
}
