import 'dart:convert';

PaymentMethod paymentMethodFromJson(String str) =>
    PaymentMethod.fromJson(json.decode(str));

String paymentMethodToJson(PaymentMethod data) => json.encode(data.toJson());

class PaymentMethod {
  PaymentMethod({
    required this.id,
    required this.name,
    required this.slug,
    required this.instruction,
    required this.isActive,
    required this.isCash,
    required this.createdAt,
    required this.updatedAt,
    required this.formattedDate,
    required this.photo,
  });

  int id;
  String name;
  String slug;
  String? instruction;
  int isActive;
  int isCash;
  DateTime createdAt;
  DateTime updatedAt;
  String formattedDate;
  String photo;

  factory PaymentMethod.fromJson(Map<String, dynamic> json) {
    return PaymentMethod(
      id: json["id"],
      name: json["name"],
      slug: json["slug"],
      instruction: json["instruction"],
      isActive: int.parse(json["is_active"].toString()),
      isCash: int.parse(json["is_cash"].toString()),
      createdAt: DateTime.parse(json["created_at"]),
      updatedAt: DateTime.parse(json["updated_at"]),
      formattedDate: json["formatted_date"],
      photo: json["photo"],
    );
  }

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "slug": slug,
        "instruction": instruction,
        "is_active": isActive,
        "is_cash": isCash,
        "created_at": createdAt.toIso8601String(),
        "updated_at": updatedAt.toIso8601String(),
        "formatted_date": formattedDate,
        "photo": photo,
      };
}
