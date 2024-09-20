import 'dart:convert';
import 'dart:developer';
import 'dart:io';
import 'package:dio/dio.dart';
import 'package:flutter/foundation.dart';
import 'package:fuodz/constants/api.dart';
import 'package:fuodz/constants/app_file_limit.dart';
import 'package:fuodz/models/api_response.dart';
import 'package:fuodz/models/checkout.dart';
import 'package:fuodz/models/package_checkout.dart';
import 'package:fuodz/models/payment_method.dart';
import 'package:fuodz/models/service.dart';
import 'package:fuodz/models/vendor.dart';
import 'package:fuodz/services/http.service.dart';
import 'package:fuodz/utils/utils.dart';

class CheckoutRequest extends HttpService {
  //
  Future<List<PaymentMethod>> getPaymentOptions({
    int? deliveryAddressId,
    int? vendorId,
  }) async {
    final apiResult = await get(Api.paymentMethods);

    //
    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return apiResponse.data.map((jsonObject) {
        return PaymentMethod.fromJson(jsonObject);
      }).toList();
    } else {
      throw apiResponse.message!;
    }
  }

  Future<ApiResponse> newOrder(
    CheckOut checkout, {
    String note = "",
    String tip = "",
  }) async {
    final payload = {
      "tip": tip,
      "note": note,
      "coupon_code": checkout.coupon?.code ?? "",
      "pickup_date": checkout.deliverySlotDate,
      "pickup_time": checkout.deliverySlotTime,
      "products": checkout.cartItems?.map((e) => e.toCheckout()).toList(),
      "vendor_id": checkout.cartItems?.first.product?.vendorId,
      "delivery_address_id": checkout.deliveryAddress?.id,
      "payment_method_id": checkout.paymentMethod?.id,
      "sub_total": checkout.subTotal,
      "discount": checkout.discount,
      "delivery_fee": checkout.deliveryFee,
      "tax": checkout.tax,
      "fees": checkout.fees.map((e) => e.toJson()).toList(),
      "total": checkout.total,
      "token": checkout.token,
    };
    //
    print("Order Payload: $payload");
    //
    final apiResult = await post(
      Api.orders,
      payload,
    );
    //
    return ApiResponse.fromResponse(apiResult);
  }

  Future<ApiResponse> newMultipleVendorOrder(
    CheckOut checkout, {
    String note = "",
    String tip = "",
    required Map payload,
  }) async {
    Map<String, dynamic> orderPayload = {
      ...payload,
      "tip": tip,
      "note": note,
      "coupon_code": checkout.coupon?.code ?? "",
      "pickup_date": checkout.deliverySlotDate,
      "pickup_time": checkout.deliverySlotTime,
      "delivery_address_id": checkout.deliveryAddress?.id,
      "payment_method_id": checkout.paymentMethod?.id,
      "sub_total": checkout.subTotal,
      "discount": checkout.discount,
      "delivery_fee": checkout.deliveryFee,
      "tax": checkout.tax,
      "total": checkout.total,
    };

    log("Multiple Vendor Order Payload: $orderPayload");

    final apiResult = await post(
      Api.orders,
      orderPayload,
    );
    //
    return ApiResponse.fromResponse(apiResult);
  }

  //
  Future<ApiResponse> newPackageOrder(
    PackageCheckout packageCheckout, {
    String? note,
  }) async {
    //fees
    List<Map> feesObjects = [];
    for (var fee in packageCheckout.vendor?.fees ?? []) {
      double calFee = 0;
      String feeName = fee.name;
      if (fee.isPercentage) {
        calFee = fee.getRate(packageCheckout.subTotal);
        feeName = "$feeName (${fee.value}%)";
      } else {
        calFee = fee.value;
      }

      //
      feesObjects.add({
        "id": fee.id,
        "name": feeName,
        "amount": calFee,
      });
      //
    }

    Map<String, dynamic> payload = {
      "type": "package",
      "note": note,
      "coupon_code": packageCheckout.coupon?.code ?? "",
      "package_type_id": packageCheckout.packageType?.id,
      "vendor_id": packageCheckout.vendor?.id,
      "pickup_date": packageCheckout.date,
      "pickup_time": packageCheckout.time,
      "stops": packageCheckout.allStops?.map((e) {
        return e?.toJson();
      }).toList(),
      "recipient_name": packageCheckout.recipientName,
      "recipient_phone": packageCheckout.recipientPhone,
      "weight": packageCheckout.weight,
      "width": packageCheckout.width,
      "length": packageCheckout.length,
      "height": packageCheckout.height,
      "payment_method_id": packageCheckout.paymentMethod?.id,
      "sub_total": (packageCheckout.subTotal! - (packageCheckout.deliveryFee)),
      "discount": packageCheckout.discount,
      "delivery_fee": packageCheckout.deliveryFee,
      "tax": packageCheckout.tax,
      "tax_rate": packageCheckout.taxRate,
      "token": packageCheckout.token,
      "payer": packageCheckout.payer,
      "fees": feesObjects,
      "total": packageCheckout.total,
    };

    if (kDebugMode) {
      log("Package Order Payload: ${jsonEncode(payload)}");
    }

    final apiResult = await post(
      Api.orders,
      payload,
    );
    //
    return ApiResponse.fromResponse(apiResult);
  }

  //
  Future<ApiResponse> newServiceOrder(
    CheckOut checkout, {
    List<Map>? fees,
    required Service service,
    double? service_amount,
    String? note,
  }) async {
    //
    final params = {
      "type": "service",
      "note": note,
      "service_id": service.id,
      "vendor_id": service.vendor.id,
      "delivery_address_id": checkout.deliveryAddress?.id,
      "pickup_date": checkout.deliverySlotDate,
      "pickup_time": checkout.deliverySlotTime,
      "hours": service.selectedQty,
      "service_price": service_amount != null
          ? service_amount
          : service.showDiscount
              ? service.discountPrice
              : service.price,
      "payment_method_id": checkout.paymentMethod?.id,
      "sub_total": checkout.subTotal,
      "discount": checkout.discount,
      "delivery_fee": checkout.deliveryFee,
      "tax": checkout.tax,
      "total": checkout.total,
      "coupon_code": checkout.coupon?.code ?? "",
      "fees": fees,
      "token": checkout.token,
    };

    //if there is selected options
    if (service.selectedOptions.isNotEmpty) {
      String optionFlatten = "";
      List<int> optionIds = [];
      for (var option in service.selectedOptions) {
        optionFlatten += "${option.name}";
        //add , if its not the last option
        if (service.selectedOptions.last.id != option.id) {
          optionFlatten += ", ";
        }

        optionIds.add(option.id);
      }

      //
      params.addAll({
        "options_flatten": optionFlatten,
        "options_ids": optionIds,
      });
    }
    //
    final apiResult = await post(
      Api.orders,
      params,
    );
    //
    return ApiResponse.fromResponse(apiResult);
  }

  Future<ApiResponse> newPrescriptionOrder(
    CheckOut checkout,
    Vendor vendor, {
    List<File>? photos,
    String note = "",
  }) async {
    //
    Map<String, dynamic> postBody = {
      "type": "prescription",
      "note": note,
      "pickup_date": checkout.deliverySlotDate,
      "pickup_time": checkout.deliverySlotTime,
      "vendor_id": vendor.id,
      "delivery_address_id": checkout.deliveryAddress?.id,
      "sub_total": 0.00,
      "discount": 0.00,
      "delivery_fee": 0.00,
      "tax": 0.00,
      "total": 0.00,
    };
    FormData formData = FormData.fromMap(postBody);
    if (photos != null && photos.isNotEmpty) {
      for (File? file in photos) {
        //if the file size is bigger than the AppFileLimit.prescriptionFileSizeLimit then compress it
        //file size in kb
        final fileSize = file!.lengthSync() / 1024;
        if (fileSize > AppFileLimit.prescriptionFileSizeLimit) {
          file = await Utils.compressFile(
            file: file,
            quality: 60,
          );
        }
        //
        formData.files.add(
          MapEntry("photos[]", await MultipartFile.fromFile(file!.path)),
        );
      }
    }

    //make api request
    final apiResult = await postWithFiles(
      Api.orders,
      formData,
    );
    //
    return ApiResponse.fromResponse(apiResult);
  }

  Future<PackageCheckout> orderDeliveryFeeSummary({
    required int deliveryAddressId,
    required int vendorId,
  }) async {
    final params = {
      "vendor_id": "${vendorId}",
      "delivery_address_id": "${deliveryAddressId}",
    };

    //
    final apiResult = await get(
      Api.generalOrderDeliveryFeeSummary,
      queryParameters: params,
    );

    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return PackageCheckout.fromJson(apiResponse.body);
    }

    throw apiResponse.message!;
  }

  Future<CheckOut> orderSummary(Map payload) async {
    //
    final apiResult = await post(
      Api.generalOrderSummary,
      payload,
    );

    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return CheckOut.fromJson(apiResponse.body);
    }

    throw apiResponse.message!;
  }

  Future<CheckOut> serviceOrderSummary(Map payload) async {
    //
    final apiResult = await post(
      Api.serviceOrderSummary,
      payload,
    );

    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return CheckOut.fromJson(apiResponse.body);
    }

    throw apiResponse.message!;
  }
}
