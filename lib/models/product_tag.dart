class ProductTag {
  final int id;
  final String name;
  final int vendorTypeId;

  ProductTag({
    required this.id,
    required this.name,
    required this.vendorTypeId,
  });

  factory ProductTag.fromJson(Map<String, dynamic> json) => ProductTag(
        id: json["id"],
        name: json["name"],
        vendorTypeId: json["vendor_type_id"],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "vendor_type_id": vendorTypeId,
      };
}
