import 'package:fuodz/constants/api.dart';
import 'package:fuodz/models/api_response.dart';
import 'package:fuodz/models/vehicle.dart';
import 'package:fuodz/services/http.service.dart';

class GeneralRequest extends HttpService {
  //
  Future<List<VehicleType>> getVehicleTypes() async {
    final apiResult = await get(
      Api.vehicleTypes,
    );

    //
    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return (apiResponse.body as List).map((jsonObject) {
        return VehicleType.fromJson(jsonObject);
      }).toList();
    } else {
      throw "${apiResponse.message}";
    }
  }

  Future<List<CarMake>> getCarMakes() async {
    final apiResult = await get(
      Api.carMakes,
    );

    //
    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return (apiResponse.body as List).map((jsonObject) {
        return CarMake.fromJson(jsonObject);
      }).toList();
    } else {
      throw "${apiResponse.message}";
    }
  }

  Future<List<CarModel>> getCarModels({
    int? carMakeId,
  }) async {
    final apiResult = await get(
      Api.carModels,
      queryParameters: {
        "car_make_id": carMakeId,
      },
    );

    //
    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return (apiResponse.body as List).map((jsonObject) {
        return CarModel.fromJson(jsonObject);
      }).toList();
    } else {
      throw "${apiResponse.message}";
    }
  }
}
