import 'package:fuodz/constants/api.dart';
import 'package:fuodz/models/api_response.dart';
import 'package:fuodz/models/coupon.dart';
import 'package:fuodz/services/http.service.dart';
import 'package:fuodz/services/location.service.dart';

class CouponRequest extends HttpService {
  //
  Future<List<Coupon>> fetchCoupons({
    int page = 1,
    bool byLocation = false,
    Map? params,
  }) async {
    Map<String, dynamic> queryParameters = {
      ...(params != null ? params : {}),
      "page": "$page",
      "latitude": byLocation ? LocationService.getFetchByLocationLat() : null,
      "longitude": byLocation ? LocationService.getFetchByLocationLng() : null,
    };

    //
    final apiResult = await get(
      Api.coupons,
      queryParameters: queryParameters,
    );

    print("queryParameters ==> $queryParameters");

    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return apiResponse.data
          .map((jsonObject) => Coupon.fromJson(jsonObject))
          .toList();
    }

    throw apiResponse.message!;
  }

  Future<Coupon> fetchCoupon(int id) async {
    final apiResult = await get("${Api.coupons}/details/${id}");
    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return Coupon.fromJson(apiResponse.body);
    }

    throw apiResponse.message!;
  }
}
