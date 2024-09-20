import 'dart:convert';

import 'package:fuodz/models/vendor.dart';
import 'package:fuodz/models/product.dart';

Coupon couponFromJson(String str) => Coupon.fromJson(json.decode(str));

String couponToJson(Coupon data) => json.encode(data.toJson());

class Coupon {
  Coupon({
    required this.id,
    required this.code,
    required this.description,
    required this.discount,
    required this.percentage,
    required this.expiresOn,
    required this.times,
    this.products = const [],
    this.vendors = const [],
  });

  int id;
  String code;
  String description;
  double discount;
  int percentage;
  DateTime? expiresOn;
  dynamic times;
  List<Product> products;
  List<Vendor> vendors;

  factory Coupon.fromJson(Map<String, dynamic> json) => Coupon(
        id: json["id"] == null ? null : json["id"],
        code: json["code"] == null ? null : json["code"],
        description: json["description"] == null ? null : json["description"],
        discount: double.tryParse(json["discount"].toString()) ?? 0,
        percentage: json["percentage"] == null ? null : json["percentage"],
        expiresOn: json["expires_on"] == null
            ? null
            : DateTime.parse(json["expires_on"]),
        times: json["times"],
        products: json["products"] == null
            ? []
            : List<Product>.from(
                json["products"].map((x) => Product.fromJson(x))),
        vendors: json["vendors"] == null
            ? []
            : List<Vendor>.from(json["vendors"].map((x) => Vendor.fromJson(x))),
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "code": code,
        "description": description,
        "discount": discount,
        "percentage": percentage,
        "expires_on": expiresOn == null
            ? null
            : "${expiresOn!.year.toString().padLeft(4, '0')}-${expiresOn!.month.toString().padLeft(2, '0')}-${expiresOn!.day.toString().padLeft(2, '0')}",
        "times": times,
        "products": List<dynamic>.from(products.map((x) => x.toJson())),
        "vendors": List<dynamic>.from(vendors.map((x) => x.toJson())),
      };
}
