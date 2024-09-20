import 'package:fuodz/constants/api.dart';
import 'package:fuodz/models/api_response.dart';
import 'package:fuodz/services/http.service.dart';

class ReportRequest extends HttpService {
//
  Future<List<Map<String, dynamic>>> getPayoutsReport({
    String? sDate,
    String? eDate,
    int page = 1,
  }) async {
    final apiResult = await get(
      Api.payoutsReport,
      queryParameters: {
        "page": page,
        "start_date": sDate,
        "end_date": eDate,
      },
    );
    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return (apiResponse.body as List)
          .map((e) => e as Map<String, dynamic>)
          .toList();
    } else {
      throw apiResponse.message!;
    }
  }

  Future<List<Map<String, dynamic>>> getEarningsReport({
    String? sDate,
    String? eDate,
    int page = 1,
  }) async {
    //
    final apiResult = await get(
      Api.earningsReport,
      queryParameters: {
        "page": page,
        "start_date": sDate,
        "end_date": eDate,
      },
    );
    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return (apiResponse.body as List)
          .map((e) => e as Map<String, dynamic>)
          .toList();
    } else {
      throw apiResponse.message!;
    }
  }
}
