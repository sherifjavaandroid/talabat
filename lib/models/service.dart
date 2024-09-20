// To parse this JSON data, do
//
//     final Service = ServiceFromJson(jsonString);

import 'dart:convert';

import 'package:fuodz/models/category.dart';
import 'package:fuodz/models/vendor.dart';
import 'package:random_string/random_string.dart';

Service ServiceFromJson(String str) => Service.fromJson(json.decode(str));

String ServiceToJson(Service data) => json.encode(data.toJson());

class Service {
  Service({
    required this.id,
    required this.name,
    required this.description,
    required this.price,
    required this.discountPrice,
    required this.duration,
    required this.location,
    required this.isActive,
    required this.vendorId,
    required this.categoryId,
    required this.subcategoryId,
    required this.formattedDate,
    required this.vendor,
    required this.category,
    required this.photos,
    this.selectedQty,
  }) {
    this.heroTag = randomAlphaNumeric(15) + "$id";
  }

  int id;
  String? heroTag;
  String name;
  String? description;
  double price;
  double discountPrice;
  String duration;
  int location;
  int isActive;
  int? vendorId;
  int? categoryId;
  int? subcategoryId;
  String formattedDate;
  Vendor? vendor;
  Category? category;
  List<String> photos;
  int? selectedQty;

  factory Service.fromJson(Map<String, dynamic> json) {
    return Service(
      id: json["id"] == null ? null : json["id"],
      name: json["name"] == null ? null : json["name"],
      description: json["description"] == null ? "" : json["description"],
      price: double.tryParse(json["price"].toString()) ?? 0,
      discountPrice: json["discount_price"] == null
          ? 0
          : double.parse(json["discount_price"].toString()),
      duration: json["duration"],
      location: json["location"] == null
          ? 0
          : (json["location"] is bool)
              ? (json["location"] ? 1 : 0)
              : int.tryParse(json["location"].toString()) ?? 0,
      isActive: int.tryParse(json["is_active"].toString()) ?? 0,
      vendorId: json["vendor_id"] == null
          ? null
          : int.parse(json["vendor_id"].toString()),
      categoryId: json["category_id"] == null ? null : json["category_id"],
      subcategoryId:
          json["subcategory_id"] == null ? null : json["subcategory_id"],

      formattedDate:
          json["formatted_date"] == null ? null : json["formatted_date"],
      vendor: json["vendor"] == null ? null : Vendor.fromJson(json["vendor"]),
      category:
          json["category"] == null ? null : Category.fromJson(json["category"]),

      // photos
      photos: json["photos"] == null
          ? []
          : List<String>.from(
              json["photos"].map((x) => x),
            ),
    );
  }

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "description": description,
        "price": price,
        "discount_price": discountPrice,
        "duration": duration,
        "location": location,
        "is_active": isActive,
        "vendor_id": vendorId == null ? null : vendorId,
        "category_id": categoryId,
        "subcategory_id": subcategoryId,
        "formatted_date": formattedDate,
        "vendor": vendor == null ? null : vendor?.toJson(),
        "category": category?.toJson(),
      };

  //getters
  bool get showDiscount => discountPrice > 0.00;
  bool get isPerHour => duration == "hour";
}
