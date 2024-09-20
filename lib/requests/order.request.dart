import 'package:fuodz/constants/api.dart';
import 'package:fuodz/models/api_response.dart';
import 'package:fuodz/models/order.dart';
import 'package:fuodz/services/http.service.dart';

class OrderRequest extends HttpService {
  //
  Future<List<Order>> getOrders(
      {int page = 1, Map<String, dynamic>? params}) async {
    final apiResult = await get(
      Api.orders,
      queryParameters: {
        "page": page,
        ...(params != null ? params : {}),
      },
    );

    //
    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      List<Order> orders = [];
      List<dynamic> jsonArray =
          (apiResponse.body is List) ? apiResponse.body : apiResponse.data;
      for (var jsonObject in jsonArray) {
        try {
          orders.add(Order.fromJson(jsonObject));
        } catch (e) {
          print(e);
        }
      }

      return orders;
    } else {
      throw apiResponse.message!;
    }
  }

  //
  Future<Order> getOrderDetails({required int id}) async {
    final apiResult = await get(Api.orders + "/$id");
    //
    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return Order.fromJson(apiResponse.body);
    } else {
      throw apiResponse.message!;
    }
  }

  //
  Future<String> updateOrder({int? id, String? status, String? reason}) async {
    final apiResult = await patch(Api.orders + "/$id", {
      "status": status,
      "reason": reason,
    });
    //
    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return apiResponse.message!;
    } else {
      throw apiResponse.message!;
    }
  }

  //
  Future<Order> trackOrder(String code, {int? vendorTypeId}) async {
    //
    final apiResult = await post(
      Api.trackOrder,
      {
        "code": code,
        "vendor_type_id": vendorTypeId,
      },
    );
    //
    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return Order.fromJson(apiResponse.body);
    } else {
      throw apiResponse.message!;
    }
  }

  Future<ApiResponse> updateOrderPaymentMethod({
    int? id,
    int? paymentMethodId,
    String? status,
  }) async {
    //
    final apiResult = await patch(
      Api.orders + "/$id",
      {
        "payment_method_id": paymentMethodId,
        "payment_status": status,
      },
    );
    //
    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return apiResponse;
    } else {
      throw apiResponse.message!;
    }
  }

  Future<List<String>> orderCancellationReasons({
    Order? order,
  }) async {
    //
    final apiResult = await get(
      Api.cancellationReasons,
      queryParameters: {
        "type": (order?.isTaxi ?? false) ? "taxi" : "order",
      },
    );
    //
    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return (apiResponse.body as List).map((e) {
        return e['reason'].toString();
      }).toList();
    } else {
      throw apiResponse.message!;
    }
  }
}
