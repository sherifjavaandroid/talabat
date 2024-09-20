import 'dart:convert';

PackageType packageTypeFromJson(String str) =>
    PackageType.fromJson(json.decode(str));

String packageTypeToJson(PackageType data) => json.encode(data.toJson());

class PackageType {
  PackageType({
    required this.id,
    required this.name,
    required this.description,
    required this.isActive,
    required this.formattedDate,
    required this.photo,
  });

  int id;
  String name;
  String description;
  int isActive;
  String formattedDate;
  String photo;

  factory PackageType.fromJson(dynamic json) => PackageType(
        id: json["id"] == null ? null : json["id"],
        name: json["name"] ?? "",
        description: json["description"] ?? "",
        isActive: json["is_active"] == null
            ? 0
            : int.parse(json["is_active"].toString()),
        formattedDate:
            json["formatted_date"] == null ? null : json["formatted_date"],
        photo: json["photo"] == null ? null : json["photo"],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "description": description,
        "is_active": isActive,
        "formatted_date": formattedDate,
        "photo": photo,
      };
}
