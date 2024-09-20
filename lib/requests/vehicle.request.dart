import 'dart:io';

import 'package:dio/dio.dart';
import 'package:fuodz/constants/api.dart';
import 'package:fuodz/models/api_response.dart';
import 'package:fuodz/models/vehicle.dart';
import 'package:fuodz/services/http.service.dart';

class VehicleRequest extends HttpService {
  //
  Future<List<Vehicle>> vehicles() async {
    final apiResult = await get(Api.vehicles);
    final apiResponse = ApiResponse.fromResponse(apiResult);
    return (apiResponse.body as List).map((e) => Vehicle.fromJson(e)).toList();
  }

  Future<ApiResponse> newVehicleRequest({
    required Map<String, dynamic> vals,
    List<File>? docs,
  }) async {
    final postBody = {
      ...vals,
    };

    FormData formData = FormData.fromMap(postBody);
    if ((docs ?? []).isNotEmpty) {
      for (File file in docs!) {
        formData.files.addAll([
          MapEntry("documents[]", await MultipartFile.fromFile(file.path)),
        ]);
      }
    }

    final apiResult = await postCustomFiles(
      Api.driverVehicleRegister,
      null,
      formData: formData,
    );
    //
    return ApiResponse.fromResponse(apiResult);
  }

  Future<ApiResponse> makeActive(int id) async {
    final apiResult = await post(
      Api.activateVehicle.replaceAll("{id}", "$id"),
      {},
    );
    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return apiResponse;
    }
    throw "${apiResponse.message}";
  }
}
