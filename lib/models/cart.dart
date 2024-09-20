import 'dart:convert';
import 'package:fuodz/models/option.dart';
import 'package:fuodz/models/product.dart';

Cart cartFromJson(String str) => Cart.fromJson(json.decode(str));

String cartToJson(Cart data) => json.encode(data.toJson());

class Cart {
  Cart({
    this.price,
    this.product,
    this.options,
    this.optionsIds,
    this.selectedQty,
    //
    this.optionsPrice,
    this.productPrice,
  });

  //
  int? selectedQty;
  double? price;
  Product? product;
  List<Option>? options;
  List<int>? optionsIds;
  double? optionsPrice;
  double? productPrice;

  //

  factory Cart.fromJson(Map<String, dynamic> json) {
    return Cart(
      selectedQty: json["selected_qty"] == null ? 1 : json["selected_qty"],
      price:
          json["price"] == null ? 0.00 : double.parse(json["price"].toString()),
      optionsPrice: double.tryParse(json["options_price"].toString()) ?? 0.0,
      productPrice: double.tryParse(json["product_price"].toString()) ?? 0.0,
      product: Product.fromJson(json["product"]),
      options: json["options"] == null
          ? null
          : List<Option>.from(
              json["options"].map((x) => Option.fromJson(x)),
            ),
      optionsIds: json["options_ids"] == null
          ? null
          : List<int>.from(json["options_ids"]),
    );
  }

  Map<String, dynamic> toJson() => {
        "selected_qty": selectedQty,
        "price": price,
        "options_price": optionsPrice,
        "product_price": productPrice,
        "product": product?.toJson(),
        "options": options == null
            ? null
            : List<dynamic>.from(options!.map((x) => x.toJson())),
        "options_ids": optionsIds == null ? null : optionsIds,
        "options_flatten": optionsSentence,
      };

  //to toCheckout
  Map toCheckout() {
    return {
      "selected_qty": selectedQty,
      "price": price,
      "product": product?.toCheckout(),
      "options": options == null
          ? null
          : List<dynamic>.from(options!.map((x) => x.toJson())),
      "options_ids": optionsIds == null ? null : optionsIds,
      "options_flatten": optionsSentence,
      //options_price
      "options_price": optionsPrice,
      // product_price
      "product_price": productPrice,
    };
  }

  //
  String get optionsSentence {
    var optionsSelected = "";
    final optionsLength = options?.length ?? 0;
    options?.asMap().forEach((index, option) {
      optionsSelected += option.name;
      if (optionsLength > (index) + 1) {
        optionsSelected += ", ";
      }
    });
    return optionsSelected;
  }
}
