// To parse this JSON data, do
//
//     final walletTransaction = walletTransactionFromJson(jsonString);

import 'dart:convert';

import 'package:dartx/dartx.dart';

WalletTransaction walletTransactionFromJson(String str) =>
    WalletTransaction.fromJson(json.decode(str));

String walletTransactionToJson(WalletTransaction data) =>
    json.encode(data.toJson());

class WalletTransaction {
  WalletTransaction({
    required this.id,
    required this.amount,
    required this.reason,
    required this.ref,
    required this.walletId,
    required this.paymentMethodId,
    required this.status,
    required this.isCredit,
    required this.createdAt,
    required this.updatedAt,
    required this.formattedDate,
    required this.formattedUpdatedDate,
    required this.photo,
  });

  int id;
  double amount;
  String reason;
  String ref;
  int walletId;
  String? paymentMethodId;
  String status;
  int isCredit;
  DateTime createdAt;
  DateTime? updatedAt;
  String? formattedDate;
  String? formattedUpdatedDate;
  String? photo;

  factory WalletTransaction.fromJson(Map<String, dynamic> json) =>
      WalletTransaction(
        id: json["id"] == null ? null : json["id"],
        amount: json["amount"].toString().toDouble(),
        reason: json["reason"] == null ? "" : json["reason"],
        ref: json["ref"] == null ? null : json["ref"],
        walletId: json["wallet_id"] == null ? null : json["wallet_id"],
        paymentMethodId: json["payment_method_id"] == null
            ? null
            : json["payment_method_id"],
        status: json["status"] == null ? null : json["status"],
        isCredit: json["is_credit"] == null ? null : json["is_credit"],
        createdAt: json["created_at"] == null
            ? DateTime.now()
            : DateTime.parse(json["created_at"]),
        updatedAt: json["updated_at"] == null
            ? null
            : DateTime.parse(json["updated_at"]),
        formattedDate:
            json["formatted_date"] == null ? null : json["formatted_date"],
        formattedUpdatedDate: json["formatted_updated_date"] == null
            ? null
            : json["formatted_updated_date"],
        photo: json["photo"] == null ? null : json["photo"],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "amount": amount,
        "reason": reason,
        "ref": ref,
        "wallet_id": walletId,
        "payment_method_id": paymentMethodId,
        "status": status,
        "is_credit": isCredit,
        "created_at": createdAt.toIso8601String(),
        "updated_at": updatedAt?.toIso8601String(),
        "formatted_date": formattedDate,
        "formatted_updated_date": formattedUpdatedDate,
        "photo": photo,
      };
}
