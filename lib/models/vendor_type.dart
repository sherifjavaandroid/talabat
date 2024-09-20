// To parse this JSON data, do
//
//     final vendorType = vendorTypeFromJson(jsonString);

import 'dart:convert';

import 'package:dartx/dartx.dart';

VendorType vendorTypeFromJson(String str) =>
    VendorType.fromJson(json.decode(str));

String vendorTypeToJson(VendorType data) => json.encode(data.toJson());

class VendorType {
  VendorType({
    required this.id,
    this.name,
    this.description,
    required this.slug,
    required this.isActive,
    required this.logo,
  });

  int id;
  String? name;
  String? description;
  String slug;
  int isActive;
  String logo;

  factory VendorType.fromJson(Map<String, dynamic> json) => VendorType(
        id: json["id"].toString().toInt(),
        name: json["name"] == null ? null : json["name"],
        description: json["description"] == null ? null : json["description"],
        slug: json["slug"] == null ? null : json["slug"],
        isActive: json["is_active"] == null ? 0 : json["is_active"],
        logo: json["logo"] == null ? "" : json["logo"],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "description": description,
        "slug": slug,
        "is_active": isActive,
        "logo": logo,
      };
}
