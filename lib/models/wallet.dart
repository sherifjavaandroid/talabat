// To parse this JSON data, do
//
//     final wallet = walletFromJson(jsonString);

import 'dart:convert';

Wallet walletFromJson(String str) => Wallet.fromJson(json.decode(str));

String walletToJson(Wallet data) => json.encode(data.toJson());

class Wallet {
  Wallet({
    required this.balance,
    this.updatedAt,
  });

  double balance;
  DateTime? updatedAt;

  factory Wallet.fromJson(Map<String, dynamic> json) => Wallet(
        balance: double.tryParse(json["balance"].toString()) ?? 0.00,
        updatedAt: json["updated_at"] == null
            ? null
            : DateTime.parse(json["updated_at"]),
      );

  Map<String, dynamic> toJson() => {
        "balance": balance,
        "updated_at": updatedAt == null ? null : updatedAt?.toIso8601String(),
      };
}
