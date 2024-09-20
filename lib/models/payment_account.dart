// To parse this JSON data, do
//
//     final paymentAccount = paymentAccountFromJson(jsonString);

import 'dart:convert';
import 'package:supercharged/supercharged.dart';

PaymentAccount paymentAccountFromJson(String str) =>
    PaymentAccount.fromJson(json.decode(str));

String paymentAccountToJson(PaymentAccount data) => json.encode(data.toJson());

class PaymentAccount {
  PaymentAccount({
    required this.name,
    required this.number,
    required this.instructions,
    required this.isActive,
    required this.updatedAt,
    required this.createdAt,
    required this.id,
    required this.formattedDate,
    required this.formattedUpdatedDate,
  });

  String name;
  String number;
  String instructions;
  bool isActive;
  DateTime updatedAt;
  DateTime createdAt;
  int id;
  String formattedDate;
  String formattedUpdatedDate;

  factory PaymentAccount.fromJson(Map<String, dynamic> json) => PaymentAccount(
        name: json["name"],
        number: json["number"],
        instructions: json["instructions"] == null ? '' : json["instructions"],
        isActive: json["is_active"].toString().toInt() == 1,
        updatedAt: DateTime.parse(json["updated_at"]),
        createdAt: DateTime.parse(json["created_at"]),
        id: json["id"],
        formattedDate: json["formatted_date"],
        formattedUpdatedDate: json["formatted_updated_date"],
      );

  Map<String, dynamic> toJson() => {
        "name": name,
        "number": number,
        "is_active": isActive ? "1" : "0",
        "instructions": instructions,
        "updated_at": updatedAt.toIso8601String(),
        "created_at": createdAt.toIso8601String(),
        "id": id,
        "formatted_date": formattedDate,
        "formatted_updated_date": formattedUpdatedDate,
      };
}
