import 'package:fuodz/models/product.dart';
import 'package:fuodz/models/service.dart';
import 'package:fuodz/models/vendor_type.dart';

class Category {
  int id;
  String name;
  String? imageUrl;
  List<Product> products;
  List<Service> services;
  List<Category> subcategories;
  VendorType? vendorType;
  String? color;
  bool hasSubcategories;

  Category({
    required this.id,
    required this.name,
    this.imageUrl,
    this.products = const [],
    this.services = const [],
    this.subcategories = const [],
    this.vendorType,
    this.color = "#eeeeee",
    this.hasSubcategories = false,
  });

  factory Category.fromJson(dynamic jsonObject) {
    final category = Category(
      id: jsonObject["id"],
      name: jsonObject["name"],
    );
    category.id = jsonObject["id"];
    category.name = jsonObject["name"];
    category.imageUrl = jsonObject["photo"];
    category.color = jsonObject["color"];
    category.hasSubcategories = jsonObject["has_subcategories"] != null
        ? (jsonObject["has_subcategories"] as bool)
        : false;
    category.products = jsonObject["products"] == null
        ? []
        : List<Product>.from(
            jsonObject["products"].map(
              (x) => Product.fromJson(x),
            ),
          );
    category.services = jsonObject["services"] == null
        ? []
        : List<Service>.from(
            jsonObject["services"].map(
              (x) => Service.fromJson(x),
            ),
          );
    category.subcategories = jsonObject["sub_categories"] == null
        ? []
        : List<Category>.from(
            jsonObject["sub_categories"].map(
              (x) => Category.fromJson(x),
            ),
          );
    category.vendorType = jsonObject["vendor_type"] == null
        ? null
        : VendorType.fromJson(
            jsonObject["vendor_type"],
          );

    return category;
  }

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "photo": imageUrl,
        "color": color,
        "products": List<dynamic>.from(products.map((x) => x.toJson())),
        "services": List<dynamic>.from(services.map((x) => x.toJson())),
        "subcategories":
            List<dynamic>.from(subcategories.map((x) => x.toJson())),
        "has_subcategories": hasSubcategories,
      };
}
