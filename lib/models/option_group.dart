// To parse this JSON data, do
//
//     final optionGroup = optionGroupFromJson(jsonString);

import 'dart:convert';

import 'package:fuodz/models/option.dart';

OptionGroup optionGroupFromJson(String str) =>
    OptionGroup.fromJson(json.decode(str));

String optionGroupToJson(OptionGroup data) => json.encode(data.toJson());

class OptionGroup {
  OptionGroup({
    required this.id,
    required this.name,
    required this.multiple,
    required this.required,
    required this.isActive,
    required this.photo,
    required this.options,
  });

  int id;
  String name;
  int multiple;
  int required;
  int isActive;
  String photo;
  List<Option> options;

  factory OptionGroup.fromJson(Map<String, dynamic> json) => OptionGroup(
        id: json["id"] == null ? null : json["id"],
        name: json["name"] == null ? null : json["name"],
        multiple: json["multiple"] == null ? 0 : json["multiple"],
        required: json["required"] == null
            ? 0
            : int.parse(json["required"].toString()),
        isActive: json["is_active"] == null ? null : json["is_active"],
        photo: json["photo"] == null ? null : json["photo"],
        options: json["options"] == null
            ? []
            : List<Option>.from(json["options"].map((x) => Option.fromJson(x))),
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "multiple": multiple,
        "is_active": isActive,
        "photo": photo,
        "options": List<dynamic>.from(options.map((x) => x.toJson())),
      };
}
