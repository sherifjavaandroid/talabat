import 'package:fuodz/constants/api.dart';
import 'package:fuodz/models/api_response.dart';
import 'package:fuodz/models/payment_account.dart';
import 'package:fuodz/services/http.service.dart';

class PaymentAccountRequest extends HttpService {
  //
  Future<ApiResponse> newPaymentAccount(Map<String, dynamic> payload) async {
    final apiResult = await post(Api.paymentAccount, payload);
    return ApiResponse.fromResponse(apiResult);
  }

  Future<ApiResponse> updatePaymentAccount(
      int id, Map<String, dynamic> payload) async {
    final apiResult = await patch(Api.paymentAccount + "/$id", payload);
    return ApiResponse.fromResponse(apiResult);
  }

  Future<List<PaymentAccount>> paymentAccounts({int page = 1}) async {
    final apiResult = await get(
      Api.paymentAccount,
      queryParameters: {"page": page},
    );

    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return (page == 0 ? apiResponse.body["data"] as List : apiResponse.data)
          .map((e) => PaymentAccount.fromJson(e))
          .toList();
    }

    throw "${apiResponse.message}";
  }

  //
  Future<ApiResponse> requestPayout(Map<String, dynamic> payload) async {
    final apiResult = await post(Api.payoutRequest, payload);
    return ApiResponse.fromResponse(apiResult);
  }
}
