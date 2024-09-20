import 'dart:convert';
// import 'dart:developer';

import 'package:fuodz/models/menu.dart';
import 'package:fuodz/models/product_category.dart';
import 'package:fuodz/models/product_tag.dart';
import 'package:fuodz/models/vendor.dart';
import 'package:fuodz/models/option_group.dart';
import 'package:random_string/random_string.dart';

Product productFromJson(String str) => Product.fromJson(json.decode(str));

String productToJson(Product data) => json.encode(data.toJson());

class Product {
  Product({
    required this.id,
    required this.name,
    required this.description,
    required this.price,
    required this.discountPrice,
    required this.capacity,
    required this.unit,
    required this.packageCount,
    required this.featured,
    required this.isFavourite,
    required this.deliverable,
    required this.isActive,
    required this.vendorId,
    required this.categoryId,
    required this.formattedDate,
    required this.photo,
    required this.vendor,
    this.optionGroups = const [],
    this.availableQty,
    this.selectedQty = 0,
    this.tags = const [],
    this.menus = const [],
    this.categories = const [],
    this.subCategories = const [],
    this.photos = const [],
  }) {
    this.heroTag = randomAlphaNumeric(15) + "$id";
  }

  int id;
  String? heroTag;
  String name;
  String? description;
  double price;
  double discountPrice;
  String? capacity;
  String? unit;
  String? packageCount;
  int featured;
  bool isFavourite;
  int deliverable;
  int isActive;
  int vendorId;
  int? categoryId;
  String formattedDate;
  String photo;
  Vendor? vendor;
  List<Menu> menus;
  List<ProductCategory> categories;
  List<ProductCategory> subCategories;
  List<OptionGroup> optionGroups;
  List<String> photos;
  List<ProductTag> tags;

  //
  int? availableQty;
  int selectedQty;

  factory Product.fromJson(Map<String, dynamic> json) {
    // log("Products ==> ${jsonEncode(json)}");
    return Product(
      id: json["id"] == null ? null : json["id"],
      name: json["name"] == null ? null : json["name"],
      description: json["description"] == null ? "" : json["description"],
      price: double.parse(json["price"].toString()),
      discountPrice: json["discount_price"] == null
          ? 0
          : double.parse(json["discount_price"].toString()),
      capacity: json["capacity"] == null ? null : json["capacity"].toString(),
      unit: json["unit"] == null ? null : json["unit"],
      packageCount: json["package_count"] == null
          ? null
          : json["package_count"].toString(),
      featured:
          json["featured"] == null ? 0 : int.parse(json["featured"].toString()),
      isFavourite: json["is_favourite"] == null ? null : json["is_favourite"],
      deliverable: json["deliverable"] == null
          ? 0
          : int.parse(json["deliverable"].toString()),
      isActive: json["is_active"] == null
          ? 0
          : int.parse(json["is_active"].toString()),
      vendorId: int.parse(json["vendor_id"].toString()),
      categoryId: json["category_id"] == null ? null : json["category_id"],

      formattedDate:
          json["formatted_date"] == null ? null : json["formatted_date"],
      photo: json["photo"] == null ? null : json["photo"],
      vendor: json["vendor"] == null ? null : Vendor.fromJson(json["vendor"]),
      optionGroups: json["option_groups"] == null
          ? []
          : List<OptionGroup>.from(
              json["option_groups"].map((x) => OptionGroup.fromJson(x)),
            ),

      categories: json["categories"] == null
          ? []
          : List<ProductCategory>.from(
              json["categories"].map((x) => ProductCategory.fromJson(x)),
            ),
      subCategories: json["sub_categories"] == null
          ? []
          : List<ProductCategory>.from(
              json["sub_categories"].map((x) => ProductCategory.fromJson(x)),
            ),
      menus: json["menus"] == null
          ? []
          : List<Menu>.from(
              json["menus"].map((x) => Menu.fromJson(x)),
            ),
      tags: json["tags"] == null
          ? []
          : List<ProductTag>.from(
              json["tags"].map((x) => ProductTag.fromJson(x))),
      // photos
      photos: json["photos"] == null
          ? []
          : List<String>.from(
              json["photos"].map((x) => x),
            ),
      //
      availableQty: json["available_qty"] == null
          ? null
          : int.parse(json["available_qty"].toString()),
      selectedQty: json["selected_qty"] == null
          ? 0
          : int.tryParse(json["selected_qty"].toString()) ?? 0,
    );
  }

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "description": description,
        "price": price,
        "discount_price": discountPrice,
        "capacity": capacity,
        "unit": unit,
        "package_count": packageCount,
        "featured": featured,
        "is_favourite": isFavourite,
        "deliverable": deliverable,
        "is_active": isActive,
        "vendor_id": vendorId,
        "category_id": categoryId,
        "formatted_date": formattedDate,
        "photo": photo,
        "vendor": vendor == null ? null : vendor?.toJson(),
        "option_groups":
            List<dynamic>.from(optionGroups.map((x) => x.toJson())),
        "categories": List<dynamic>.from(categories.map((x) => x.toJson())),
        "sub_categories":
            List<dynamic>.from(subCategories.map((x) => x.toJson())),
        "menus": List<dynamic>.from(menus.map((x) => x.toJson())),
        "tags": List<dynamic>.from(tags.map((x) => x.toJson())),

        //
        "photos": photos,
        "available_qty": availableQty == null ? null : availableQty,
        "selected_qty": selectedQty,
      };

  //getters
  get showDiscount => discountPrice > 0.00;
  get canBeDelivered => deliverable == 1;
  double get sellPrice {
    if (showDiscount) {
      return discountPrice;
    }
    return price;
  }
}
