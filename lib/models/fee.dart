import 'dart:convert';

Fee feeFromJson(String str) => Fee.fromJson(json.decode(str));

String feeToJson(Fee data) => json.encode(data.toJson());

class Fee {
  Fee({
    this.id,
    this.name,
    required this.value,
    required this.amount,
    this.percentage,
  });

  int? id;
  String? name;
  double value;
  double amount;
  int? percentage;

  factory Fee.fromJson(Map<String, dynamic> json) => Fee(
        id: json["id"],
        name: json["name"],
        value:
            json["value"] == null ? 0 : double.parse(json["value"].toString()),
        amount: json["amount"] == null
            ? 0
            : double.parse(json["amount"].toString()),
        percentage: json["percentage"],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "value": value,
        "amount": amount,
        "percentage": percentage,
      };

  double getRate(double subTotal) {
    if (!isPercentage) {
      return value;
    }
    return ((value) / 100) * subTotal;
  }

  bool get isPercentage {
    return percentage == 1;
  }
}
