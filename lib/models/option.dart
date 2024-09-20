import 'dart:convert';

Option optionFromJson(String str) => Option.fromJson(json.decode(str));

String optionToJson(Option data) => json.encode(data.toJson());

class Option {
  Option({
    required this.id,
    required this.name,
    required this.description,
    required this.price,
    this.productId,
    required this.optionGroupId,
    required this.photo,
  });

  int id;
  String name;
  String? description;
  double price;
  int? productId;
  int? optionGroupId;
  String photo;

  factory Option.fromJson(Map<String, dynamic> json) => Option(
        id: json["id"] == null ? null : json["id"],
        name: json["name"] == null ? null : json["name"],
        description: json["description"],
        price: json["price"] == null ? null : json["price"].toDouble(),
        productId: json["product_id"] == null ? null : json["product_id"],
        optionGroupId:
            json["option_group_id"] == null ? null : json["option_group_id"],
        photo: json["photo"] == null ? null : json["photo"],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "description": description,
        "price": price,
        "product_id": productId == null ? null : productId,
        "option_group_id": optionGroupId == null ? null : optionGroupId,
        "photo": photo,
      };
}
