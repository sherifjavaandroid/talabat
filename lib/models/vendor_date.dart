import 'package:jiffy/jiffy.dart';

class VendorDay {
  final int id;
  final String name;
  final String open;
  final String close;

  VendorDay({
    required this.id,
    required this.name,
    required this.open,
    required this.close,
  });

  factory VendorDay.fromJson(Map<String, dynamic> json) {
    return VendorDay(
      id: json["id"],
      name: json["name"],
      open: json["pivot"] != null ? json["pivot"]["open"] ?? "" : json["open"],
      close:
          json["pivot"] != null ? json["pivot"]["close"] ?? "" : json["close"],
    );
  }
  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "open": open,
        "close": close,
      };

  // getters
  String get openTime {
    return Jiffy(open, "HH:mm:ss").format("hh:mm a");
  }

  String get closeTime {
    return Jiffy(close, "HH:mm:ss").format("hh:mm a");
  }
}
