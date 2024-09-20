import 'package:fuodz/models/product.dart';

class OrderProduct {
  OrderProduct({
    required this.id,
    required this.quantity,
    required this.price,
    this.options,
    required this.orderId,
    required this.productId,
    required this.createdAt,
    required this.updatedAt,
    required this.formattedDate,
    required this.product,
    required this.reviewed,
  });

  int id;
  int quantity;
  double price;
  String? options;
  int orderId;
  int productId;
  DateTime createdAt;
  DateTime updatedAt;
  String formattedDate;
  Product? product;
  bool reviewed;

  factory OrderProduct.fromJson(Map<String, dynamic> json) => OrderProduct(
        id: json["id"] == null ? null : json["id"],
        reviewed: json["reviewed"] ?? false,
        quantity: json["quantity"] == null
            ? 1
            : int.parse(json["quantity"].toString()),
        price: double.parse(json["price"].toString()),
        options: json["options"] == null ? null : json["options"],
        orderId: int.parse(json["order_id"].toString()),
        productId: int.parse(json["product_id"].toString()),
        createdAt: json["created_at"] == null
            ? DateTime.now()
            : DateTime.parse(json["created_at"]),
        updatedAt: json["updated_at"] == null
            ? DateTime.now()
            : DateTime.parse(json["updated_at"]),
        formattedDate:
            json["formatted_date"] == null ? null : json["formatted_date"],
        product:
            json["product"] == null ? null : Product.fromJson(json["product"]),
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "reviewed": reviewed,
        "quantity": quantity,
        "price": price,
        "options": options,
        "order_id": orderId,
        "product_id": productId,
        "created_at": createdAt.toIso8601String(),
        "updated_at": updatedAt.toIso8601String(),
        "formatted_date": formattedDate,
        "product": product == null ? null : product?.toJson(),
      };
}
