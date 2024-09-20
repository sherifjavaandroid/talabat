// To parse this JSON data, do
//
//     final menu = menuFromJson(jsonString);

import 'dart:convert';

import 'package:fuodz/models/product.dart';

Menu menuFromJson(String str) => Menu.fromJson(json.decode(str));

String menuToJson(Menu data) => json.encode(data.toJson());

class Menu {
  Menu({
    required this.id,
    required this.name,
    required this.vendorId,
    required this.photo,
    required this.products,
  });

  int id;
  String name;
  int? vendorId;
  String? photo;
  List<Product> products;

  factory Menu.fromJson(Map<String, dynamic> json) => Menu(
        id: json["id"],
        name: json["name"],
        vendorId: json["vendor_id"] != null
            ? int.parse(json["vendor_id"].toString())
            : null,
        photo: json["photo"] == null ? null : json["photo"],
        products: json["products"] == null
            ? []
            : List<Product>.from(
                json["products"].map((x) => Product.fromJson(x))),
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "vendor_id": vendorId,
        "photo": photo,
        "products": List<dynamic>.from(products.map((x) => x.toJson())),
      };
}
