import 'dart:io';

import 'package:dio/dio.dart';
import 'package:fuodz/constants/api.dart';
import 'package:fuodz/models/api_response.dart';
import 'package:fuodz/models/order.dart';
import 'package:fuodz/services/app.service.dart';
import 'package:fuodz/services/auth.service.dart';
import 'package:fuodz/services/http.service.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';

class OrderRequest extends HttpService {
  //
  Future<List<Order>> getOrders({
    int page = 1,
    String? status,
    String? type,
  }) async {
    final apiResult = await get(
      Api.orders,
      queryParameters: {
        "driver_id": (await AuthServices.getCurrentUser()).id,
        "page": page,
        "status": status,
        "type": type,
      },
    );

    //
    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return apiResponse.data.map((jsonObject) {
        return Order.fromJson(jsonObject);
      }).toList();
    } else {
      throw "${apiResponse.message}";
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
      throw "${apiResponse.message}";
    }
  }

  //
  Future<Order> updateOrder({
    required int id,
    String status = "delivered",
    LatLng? location,
  }) async {
    final apiResult = await patch(
      Api.orders + "/$id",
      {
        "status": status,
        "latlng": "${location?.latitude},${location?.longitude}"
      },
    );
    //
    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return Order.fromJson(apiResponse.body["order"]);
    } else {
      throw "${apiResponse.message}";
    }
  }

  Future<ApiResponse> updateOrderWithSignature({
    required int id,
    String status = "delivered",
    File? signature,
    String typeOfProof = "signature",
  }) async {
    //compress signature image

    //
    if (signature != null) {
      signature = await AppService().compressFile(signature);
    }
    //
    final apiResult = await postWithFiles(
      Api.orders + "/$id",
      {
        "_method": "PUT",
        "status": status,
        "proof_type": typeOfProof,
        "signature": signature != null
            ? await MultipartFile.fromFile(
                signature.path,
              )
            : null,
      },
    );

    //
    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return apiResponse;
    } else {
      throw "${apiResponse.message}";
    }
  }

  Future<ApiResponse> verifyOrderStopRequest({
    required int id,
    File? signature,
    String typeOfProof = "signature",
  }) async {
    //compress signature image

    //
    if (signature != null) {
      signature = await AppService().compressFile(signature);
    }
    //
    final apiResult = await postWithFiles(
      Api.orderStopVerification + "/$id",
      {
        "proof_type": typeOfProof,
        "signature": signature != null
            ? await MultipartFile.fromFile(
                signature.path,
              )
            : null,
      },
    );

    //
    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return apiResponse;
    } else {
      throw "${apiResponse.message}";
    }
  }

  //
  Future<Order> acceptNewOrder(int id, {String? status}) async {
    Map<String, dynamic> payload = {
      "driver_id": (await AuthServices.getCurrentUser()).id,
      "order_id": id,
    };

    if (status != null) {
      payload.addAll(
        {
          "status": status,
        },
      );
    }

    final apiResult = await post(
      Api.acceptTaxiBookingAssignment,
      payload,
    );
    //
    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return Order.fromJson(apiResponse.body["order"]);
    } else {
      throw "${apiResponse.message}";
    }
  }
}
