// To parse this JSON data, do
//
//     final serviceOption = serviceOptionFromJson(jsonString);

import 'dart:convert';

import 'package:supercharged/supercharged.dart';

ServiceOption serviceOptionFromJson(String str) =>
    ServiceOption.fromJson(json.decode(str));

String serviceOptionToJson(ServiceOption data) => json.encode(data.toJson());

class ServiceOption {
  final int id;
  final String name;
  final String description;
  final double price;
  final int serviceOptionGroupId;
  final int vendorId;
  final String formattedDate;
  final String formattedUpdatedDate;
  final String photo;

  ServiceOption({
    required this.id,
    required this.name,
    required this.description,
    required this.price,
    required this.serviceOptionGroupId,
    required this.vendorId,
    required this.formattedDate,
    required this.formattedUpdatedDate,
    required this.photo,
  });

  factory ServiceOption.fromJson(Map<String, dynamic> json) => ServiceOption(
        id: json["id"],
        name: json["name"],
        description: json["description"] ?? "",
        price: json["price"] == null ? 0 : json["price"].toString().toDouble()!,
        serviceOptionGroupId: json["service_option_group_id"],
        vendorId: json["vendor_id"],
        formattedDate: json["formatted_date"],
        formattedUpdatedDate: json["formatted_updated_date"],
        photo: json["photo"],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "description": description,
        "price": price,
        "service_option_group_id": serviceOptionGroupId,
        "vendor_id": vendorId,
        "formatted_date": formattedDate,
        "formatted_updated_date": formattedUpdatedDate,
        "photo": photo,
      };
}
