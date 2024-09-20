import 'package:fuodz/models/product.dart';

class Category {
  int id;
  String name;
  String imageUrl;
  List<Product>? products;
  List<Category>? subcategories;

  Category({
    required this.id,
    required this.name,
    required this.imageUrl,
    this.products = const [],
  });

  factory Category.fromJson(dynamic jsonObject) {
    final category = Category(
      id: jsonObject["id"],
      name: jsonObject["name"],
      imageUrl: jsonObject["photo"],
    );
    //
    category.products = jsonObject["products"] == null
        ? null
        : List<Product>.from(
            jsonObject["products"].map(
              (x) => Product.fromJson(x),
            ),
          );
    category.subcategories = jsonObject["sub_categories"] == null
        ? []
        : List<Category>.from(
            jsonObject["sub_categories"].map(
              (x) => Category.fromJson(x),
            ),
          );
    return category;
  }

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "photo": imageUrl,
        "products": List<dynamic>.from((products ?? []).map((x) => x.toJson())),
        "subcategories":
            List<dynamic>.from((subcategories ?? []).map((x) => x.toJson())),
      };
}
