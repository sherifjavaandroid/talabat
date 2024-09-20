import 'coordinates.dart';

class Address {
  /// The geographic coordinates.
  final Coordinates? coordinates;

  /// The formatted address with all lines.
  final String? addressLine;

  /// The localized country name of the address.
  final String? countryName;

  /// The country code of the address.
  final String? countryCode;

  /// The feature name of the address.
  final String? featureName;

  /// The postal code.
  final String? postalCode;

  /// The administrative area name of the address
  final String? adminArea;

  /// The sub-administrative area name of the address
  final String? subAdminArea;

  /// The locality of the address
  final String? locality;

  /// The sub-locality of the address
  final String? subLocality;

  String? gMapPlaceId;

  Address({
    this.coordinates,
    this.addressLine,
    this.countryName,
    this.countryCode,
    this.featureName,
    this.postalCode,
    this.adminArea,
    this.subAdminArea,
    this.locality,
    this.subLocality,
  });

/*
  [
    geometry -> location -> [lat,lng],
    formatted_address,
    country,
    country_code,
    postal_code,
    locality, 
    sublocality
    administrative_area_level_1
    administrative_area_level_2
    thorough_fare
    sub_thorough_fare
  ]
  */
  /// Creates an address from a map containing its properties.
  Address fromMap(Map map) {
    String featureName;
    try {
      featureName = map['formatted_address'];
    } catch (e) {
      featureName = "";
    }
    if (map.containsKey("structured_formatting") &&
        map["structured_formatting"] != null) {
      Map<String, dynamic> structuredFormatting = map["structured_formatting"];
      if (structuredFormatting.containsKey("main_text")) {
        featureName = structuredFormatting["main_text"];
      }
    }

    return new Address(
      coordinates: map["geometry"] != null
          ? new Coordinates.fromMap(map["geometry"]["location"])
          : null,
      addressLine:
          map['formatted_address'] ?? map["addressLine"] ?? map['description'],
      countryName: getTypeFromAddressComponents("country", map),
      countryCode: getTypeFromAddressComponents(
        "country",
        map,
        nameTye: "short_name",
      ),
      featureName: featureName,
      postalCode: getTypeFromAddressComponents("postal_code", map),
      locality: getTypeFromAddressComponents("locality", map),
      subLocality: getTypeFromAddressComponents("sublocality", map),
      adminArea:
          getTypeFromAddressComponents("administrative_area_level_1", map),
      subAdminArea:
          getTypeFromAddressComponents("administrative_area_level_2", map),
    );
  }

  Address fromServerMap(Map map) {
    return new Address(
      coordinates: new Coordinates.fromMap(map["geometry"]["location"]),
      addressLine: map['formatted_address'],
      countryName: map['country'],
      countryCode: map['country_code'],
      featureName:
          map['name'] ?? map['feature_name'] ?? map['formatted_address'] ?? "",
      postalCode: map["postal_code"],
      locality: map["locality"],
      subLocality: map["sublocality"],
      adminArea: map["administrative_area_level_1"],
      subAdminArea: map["administrative_area_level_2"],
    );
  }

  Address fromPlaceDetailsMap(Map map) {
    return new Address(
      coordinates: map["geometry"] != null
          ? new Coordinates.fromMap(map["geometry"]["location"])
          : null,
      addressLine: map['formatted_address'],
      countryName: getTypeFromAddressComponents("country", map),
      countryCode: getTypeFromAddressComponents(
        "country",
        map,
        nameTye: "short_name",
      ),
      featureName: map["name"],
      postalCode: getTypeFromAddressComponents("postal_code", map),
      locality: getTypeFromAddressComponents("locality", map),
      subLocality: getTypeFromAddressComponents("sublocality", map),
      adminArea:
          getTypeFromAddressComponents("administrative_area_level_1", map),
      subAdminArea:
          getTypeFromAddressComponents("administrative_area_level_2", map),
    );
  }

  /// Creates a map from the address properties.
  Map toMap() => {
        "coordinates": this.coordinates?.toMap(),
        "addressLine": this.addressLine,
        "countryName": this.countryName,
        "countryCode": this.countryCode,
        "featureName": this.featureName,
        "postalCode": this.postalCode,
        "locality": this.locality,
        "subLocality": this.subLocality,
        "adminArea": this.adminArea,
        "subAdminArea": this.subAdminArea,
      };

  //
  String getTypeFromAddressComponents(
    String type,
    Map searchResult, {
    String nameTye = "long_name",
  }) {
    //
    String result = "";
    //
    if (searchResult["address_components"] != null) {
      for (var componenet in (searchResult["address_components"] as List)) {
        final found = (componenet["types"] as List).contains(type);
        if (found) {
          //
          result = componenet[nameTye];
          break;
        }
      }
    }
    return result;
  }
}
