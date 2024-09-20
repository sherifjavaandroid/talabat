import 'dart:io';
import 'package:dio/dio.dart';
import 'package:fuodz/constants/api.dart';
import 'package:fuodz/models/api_response.dart';
import 'package:fuodz/models/report/sale_report.dart';
import 'package:fuodz/models/vendor.dart';
import 'package:fuodz/services/auth.service.dart';
import 'package:fuodz/services/http.service.dart';

class VendorRequest extends HttpService {
  //
  Future<dynamic> getVendorDetails() async {
    final vendorId = (await AuthServices.getCurrentUser(force: true)).vendor_id;
    final apiResult = await get(
      Api.vendorDetails.replaceFirst("id", vendorId.toString()),
    );

    //
    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return apiResponse.body;
    } else {
      throw apiResponse.message;
    }
  }

  Future<ApiResponse> toggleVendorAvailablity(Vendor vendor) async {
    final apiResult = await post(
      Api.vendorAvailability.replaceFirst(
        "id",
        vendor.id.toString(),
      ),
      {
        "is_open": !vendor.isOpen,
      },
    );
    return ApiResponse.fromResponse(apiResult);
  }

  Future<List<Vendor>> myVendors() async {
    final apiResult = await get(Api.myVendors);
    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return (apiResponse.body as List)
          .map(
            (e) => Vendor.fromJson(e),
          )
          .toList();
    } else {
      throw apiResponse.message;
    }
  }

  switchVendor(Vendor vendor) async {
    final apiResult = await post(
      Api.switchVendor,
      {
        "vendor_id": vendor.id,
      },
    );
    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return;
    } else {
      throw apiResponse.message;
    }
  }

  Future<ApiResponse> submitDocumentsRequest({required List<File> docs}) async {
    FormData formData = FormData.fromMap({});
    for (File file in docs) {
      formData.files.addAll([
        MapEntry("documents[]", await MultipartFile.fromFile(file.path)),
      ]);
    }

    final apiResult = await postCustomFiles(
      Api.documentSubmission,
      null,
      formData: formData,
    );
    return ApiResponse.fromResponse(apiResult);
  }

  Future<List<SaleReport>> getSalesReport({
    String? sDate,
    String? eDate,
    int page = 1,
  }) async {
    final apiResult = await get(
      Api.salesReport,
      queryParameters: {
        "page": page,
        "start_date": sDate,
        "end_date": eDate,
      },
    );
    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return (apiResponse.body as List).map((e) {
        return SaleReport.fromJson(e);
      }).toList();
    } else {
      throw apiResponse.message;
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
      throw apiResponse.message;
    }
  }
}
