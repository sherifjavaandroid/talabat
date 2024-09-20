// To parse this JSON data, do
//
//     final product = productFromJson(jsonString);

import 'dart:convert';

import 'package:fuodz/models/vendor.dart';
import 'package:fuodz/models/option_group.dart';
import 'package:random_string/random_string.dart';
import 'package:supercharged/supercharged.dart';

Product productFromJson(String str) => Product.fromJson(json.decode(str));

String productToJson(Product data) => json.encode(data.toJson());

class Product {
  Product({
    required this.id,
    required this.name,
    required this.description,
    required this.price,
    required this.discountPrice,
    this.capacity,
    this.unit,
    this.packageCount,
    required this.featured,
    required this.isFavourite,
    required this.deliverable,
    required this.isActive,
    required this.vendorId,
    this.categoryId,
    required this.createdAt,
    required this.updatedAt,
    required this.formattedDate,
    required this.photo,
    this.vendor,
    this.optionGroups = const [],
    this.availableQty,
    this.selectedQty = 0,
  }) {
    this.heroTag = randomAlphaNumeric(15) + "$id";
  }

  int id;
  String? heroTag;
  String name;
  String description;
  double price;
  double discountPrice;
  String? capacity;
  String? unit;
  String? packageCount;
  int featured;
  bool isFavourite;
  int deliverable;
  int isActive;
  int? vendorId;
  int? categoryId;
  DateTime createdAt;
  DateTime updatedAt;
  String formattedDate;
  String photo;
  Vendor? vendor;
  List<OptionGroup> optionGroups;

  //
  int? availableQty;
  int selectedQty;

  factory Product.fromJson(Map<String, dynamic> json) {
    return Product(
      id: json["id"].toString().toInt()!,
      name: json["name"] == null ? null : json["name"],
      description: json["description"] == null ? "" : json["description"],
      price: double.tryParse(json["price"].toString()) ?? 0.00,
      discountPrice: double.tryParse(json["discount_price"].toString()) ?? 0.00,
      capacity: json["capacity"] == null ? null : json["capacity"].toString(),
      unit: json["unit"] == null ? null : json["unit"],
      packageCount: json["package_count"] == null
          ? null
          : json["package_count"].toString(),
      featured: int.tryParse(json["featured"].toString()) ?? 0,
      isFavourite: json["is_favourite"] == null ? null : json["is_favourite"],
      deliverable: int.tryParse(json["deliverable"].toString()) ?? 0,
      isActive: int.tryParse(json["is_active"].toString()) ?? 0,
      vendorId: json["vendor_id"] == null
          ? null
          : int.parse(json["vendor_id"].toString()),
      categoryId: json["category_id"] == null ? null : json["category_id"],
      createdAt: DateTime.parse(json["created_at"]),
      updatedAt: DateTime.parse(json["updated_at"]),
      formattedDate:
          json["formatted_date"] == null ? null : json["formatted_date"],
      photo: json["photo"] == null ? null : json["photo"],
      vendor: json["vendor"] == null ? null : Vendor.fromJson(json["vendor"]),
      optionGroups: json["option_groups"] == null
          ? []
          : List<OptionGroup>.from(
              json["option_groups"].map((x) => OptionGroup.fromJson(x)),
            ),
      //
      availableQty: json["available_qty"] == null
          ? null
          : int.parse(json["available_qty"].toString()),
      selectedQty: json["selected_qty"] == null
          ? 0
          : int.parse(json["selected_qty"].toString()),
    );
  }

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "description": description,
        "price": price,
        "discount_price": discountPrice,
        "capacity": capacity == null ? null : capacity,
        "unit": unit == null ? null : unit,
        "package_count": packageCount == null ? null : packageCount,
        "featured": featured,
        "is_favourite": isFavourite,
        "deliverable": deliverable,
        "is_active": isActive,
        "vendor_id": vendorId == null ? null : vendorId,
        "category_id": categoryId == null ? null : categoryId,
        "created_at": createdAt.toIso8601String(),
        "updated_at": updatedAt.toIso8601String(),
        "formatted_date": formattedDate,
        "photo": photo,
        "vendor": vendor == null ? null : vendor?.toJson(),
        "option_groups":
            List<dynamic>.from(optionGroups.map((x) => x.toJson())),

        //
        "available_qty": availableQty == null ? null : availableQty,
        "selected_qty": selectedQty,
      };

  //getters
  get showDiscount => discountPrice > 0.00;
  get canBeDelivered => deliverable == 1;
  double get sellPrice {
    return showDiscount ? discountPrice : price;
  }
}
