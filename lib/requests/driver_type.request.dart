import 'package:fuodz/constants/api.dart';
import 'package:fuodz/models/api_response.dart';
import 'package:fuodz/services/http.service.dart';

class DriverTypeRequest extends HttpService {
  //
  Future<ApiResponse> switchType(Map payload) async {
    final apiResult = await post(Api.driverTypeSwitch, payload);
    return ApiResponse.fromResponse(apiResult);
  }
}
