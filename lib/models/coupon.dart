import 'dart:convert';

import 'package:fuodz/models/vendor.dart';
import 'package:fuodz/models/product.dart';
import 'package:localize_and_translate/localize_and_translate.dart';

Coupon couponFromJson(String str) => Coupon.fromJson(json.decode(str));

String couponToJson(Coupon data) => json.encode(data.toJson());

class Coupon {
  Coupon({
    required this.id,
    required this.code,
    required this.description,
    required this.discount,
    this.min_order_amount,
    this.max_coupon_amount,
    required this.percentage,
    this.expiresOn,
    this.times,
    required this.useLeft,
    required this.expired,
    required this.isActive,
    required this.formattedExpiresOn,
    this.products = const [],
    this.vendors = const [],
    required this.vendorTypeId,
    //
    required this.color,
    required this.photo,
    this.for_delivery = false,
  });

  int id;
  String code;
  String description;
  double discount;
  double? min_order_amount;
  double? max_coupon_amount;
  int percentage;
  DateTime? expiresOn;
  dynamic times;
  int useLeft;
  bool expired;
  int isActive;
  String formattedExpiresOn;
  List<Product> products;
  List<Vendor> vendors;
  int? vendorTypeId;
  String? color;
  String photo;
  bool for_delivery;

  factory Coupon.fromJson(Map<String, dynamic> json) {
    return Coupon(
      id: json["id"] == null ? null : json["id"],
      vendorTypeId: json["vendor_type_id"],
      code: json["code"] == null ? null : json["code"],
      description: json["description"] == null ? null : json["description"],
      color: json["color"],
      photo: json["photo"],
      discount: json["discount"] == null
          ? 0.00
          : double.parse(json["discount"].toString()),
      min_order_amount: json["min_order_amount"] != null
          ? double.parse(json["min_order_amount"].toString())
          : null,
      max_coupon_amount: json["max_coupon_amount"] != null
          ? double.parse(json["max_coupon_amount"].toString())
          : null,
      percentage: json["percentage"] == null ? null : json["percentage"],
      expiresOn: json["expires_on"] == null
          ? null
          : DateTime.parse(json["expires_on"]),
      times: json["times"],
      expired: json["expired"],
      useLeft: int.parse(json["use_left"].toString()),
      isActive: json["is_active"] == null ? null : json["is_active"],
      formattedExpiresOn: json["formatted_expires_on"] == null
          ? null
          : json["formatted_expires_on"],
      products: json["products"] == null
          ? []
          : List<Product>.from(
              json["products"].map((x) => Product.fromJson(x))),
      vendors: json["vendors"] == null
          ? []
          : List<Vendor>.from(json["vendors"].map((x) => Vendor.fromJson(x))),
      for_delivery: json["for_delivery"] == null ? false : json["for_delivery"],
    );
  }

  Map<String, dynamic> toJson() => {
        "id": id,
        "vendor_type_id": vendorTypeId,
        "color": color,
        "photo": photo,
        "code": code,
        "description": description,
        "discount": discount,
        "min_order_amount": min_order_amount,
        "max_coupon_amount": max_coupon_amount,
        "percentage": percentage,
        "expires_on": expiresOn == null
            ? null
            : "${expiresOn?.year.toString().padLeft(4, '0')}-${expiresOn?.month.toString().padLeft(2, '0')}-${expiresOn?.day.toString().padLeft(2, '0')}",
        "times": times,
        "expired": expired,
        "use_left": useLeft,
        "is_active": isActive,
        "formatted_expires_on": formattedExpiresOn,
        "products": List<dynamic>.from(products.map((x) => x.toJson())),
        "vendors": List<dynamic>.from(vendors.map((x) => x.toJson())),
        "for_delivery": for_delivery,
      };

  //
  double validateDiscount(double amount, double discount) {
    //check if order total is within allowed order mount
    if (this.min_order_amount != null &&
        amount < (this.min_order_amount ?? 0)) {
      throw "Order amount is less than coupon minimum allowed order".tr();
    }

    if (this.max_coupon_amount != null && discount > this.max_coupon_amount!) {
      return this.max_coupon_amount!;
    } else {
      return discount;
    }
  }
}
