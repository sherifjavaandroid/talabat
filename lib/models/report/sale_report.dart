class SaleReport {
  final int id;
  final String name;
  final double totalAmount;
  final int totalUnit;
  final String date;

  SaleReport({
    required this.id,
    required this.name,
    required this.totalAmount,
    required this.totalUnit,
    required this.date,
  });

  factory SaleReport.fromJson(Map<String, dynamic> json) => SaleReport(
        id: json["id"],
        name: json["name"],
        totalAmount: double.parse(json["total_amount"].toString()),
        totalUnit: json["total_unit"],
        date: json["date"].toString(),
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "total_amount": totalAmount,
        "total_unit": totalUnit,
        "date": date,
      };
}
