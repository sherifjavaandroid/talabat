// To parse this JSON data, do
//
//     final OrderFee = feeFromJson(jsonString);

import 'dart:convert';

OrderFee feeFromJson(String str) => OrderFee.fromJson(json.decode(str));

String feeToJson(OrderFee data) => json.encode(data.toJson());

class OrderFee {
  OrderFee({
    required this.id,
    required this.name,
    required this.amount,
  });

  int? id;
  String name;
  double amount;

  factory OrderFee.fromJson(Map<String, dynamic> json) => OrderFee(
        id: json["id"],
        name: json["name"],
        amount: double.parse(json["amount"].toString()),
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "amount": amount,
      };
}
