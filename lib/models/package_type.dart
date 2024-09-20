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
    required this.createdAt,
    required this.updatedAt,
    required this.formattedDate,
    required this.photo,
    this.package_type_pricings_count = 0,
    this.driverVerifyStops = false,
  });

  int id;
  String name;
  String description;
  bool driverVerifyStops;
  int isActive;
  DateTime createdAt;
  DateTime updatedAt;
  String formattedDate;
  String photo;
  int package_type_pricings_count;

  factory PackageType.fromJson(dynamic json) => PackageType(
      id: json["id"] == null ? null : json["id"],
      name: json["name"] == null ? null : json["name"],
      description: json["description"] == null ? "" : json["description"],
      isActive: int.tryParse(json["is_active"].toString()) ?? 0,
      driverVerifyStops: json["driver_verify_stops"] == null
          ? false
          : (int.parse(json["driver_verify_stops"].toString()) == 1),
      createdAt: DateTime.parse(json["created_at"]),
      updatedAt: DateTime.parse(json["updated_at"]),
      formattedDate:
          json["formatted_date"] == null ? null : json["formatted_date"],
      photo: json["photo"] == null ? null : json["photo"],
      package_type_pricings_count: json['package_type_pricings_count'] ?? 0);

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "description": description,
        "driver_verify_stops": driverVerifyStops ? 1 : 0,
        "is_active": isActive,
        "created_at": createdAt.toIso8601String(),
        "updated_at": updatedAt.toIso8601String(),
        "formatted_date": formattedDate,
        "photo": photo,
        "package_type_pricings_count": package_type_pricings_count,
      };
}
