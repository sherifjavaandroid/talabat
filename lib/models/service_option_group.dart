// To parse this JSON data, do
//
//     final serviceOptionGroup = serviceOptionGroupFromJson(jsonString);

import 'dart:convert';

import 'service_option.dart';

ServiceOptionGroup serviceOptionGroupFromJson(String str) =>
    ServiceOptionGroup.fromJson(json.decode(str));

String serviceOptionGroupToJson(ServiceOptionGroup data) =>
    json.encode(data.toJson());

class ServiceOptionGroup {
  final int id;
  final String name;
  final int vendorId;
  final int multiple;
  final int required;
  final int? maxOptions;
  final String photo;
  final List<ServiceOption> options;

  ServiceOptionGroup({
    required this.id,
    required this.name,
    required this.vendorId,
    required this.multiple,
    required this.required,
    required this.photo,
    required this.options,
    this.maxOptions,
  });

  factory ServiceOptionGroup.fromJson(Map<String, dynamic> json) =>
      ServiceOptionGroup(
        id: json["id"],
        name: json["name"],
        vendorId: json["vendor_id"],
        multiple: json["multiple"],
        required: json["required"],
        maxOptions: json["max_options"] ?? null,
        photo: json["photo"],
        options: json["options"] == null
            ? []
            : List<ServiceOption>.from(
                json["options"].map(
                  (x) => ServiceOption.fromJson(x),
                ),
              ),
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "vendor_id": vendorId,
        "multiple": multiple,
        "required": required,
        "max_options": maxOptions,
        "photo": photo,
        "options": List<dynamic>.from(options.map((x) => x.toJson())),
      };
}
