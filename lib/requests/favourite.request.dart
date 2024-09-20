import 'package:fuodz/constants/api.dart';
import 'package:fuodz/models/api_response.dart';
import 'package:fuodz/models/product.dart';
import 'package:fuodz/services/http.service.dart';

class FavouriteRequest extends HttpService {
  //
  Future<List<Product>> favourites() async {
    final apiResult = await get(Api.favourites);
    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      List<Product> products = [];
      (apiResponse.body as List).forEach(
        (jsonObject) {
          try {
            products.add(Product.fromJson(jsonObject["product"]));
          } catch (error) {
            print("error: $error");
          }
        },
      );
      return products;
    }

    throw apiResponse.message!;
  }

  //
  Future<ApiResponse> makeFavourite(int id) async {
    final apiResult = await post(
      Api.favourites,
      {
        "product_id": id,
      },
    );

    return ApiResponse.fromResponse(apiResult);
  }

  //
  Future<ApiResponse> removeFavourite(int productId) async {
    final apiResult = await delete(Api.favourites + "/$productId");
    return ApiResponse.fromResponse(apiResult);
  }
}
