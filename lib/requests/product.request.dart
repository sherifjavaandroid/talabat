import 'dart:io';

import 'package:dio/dio.dart';
import 'package:fuodz/constants/api.dart';
import 'package:fuodz/models/api_response.dart';
import 'package:fuodz/models/product.dart';
import 'package:fuodz/models/product_category.dart';
import 'package:fuodz/models/product_tag.dart';
import 'package:fuodz/services/auth.service.dart';
import 'package:fuodz/services/http.service.dart';
import 'package:fuodz/utils/utils.dart';

class ProductRequest extends HttpService {
  //
  Future<List<Product>> getProducts({
    String? keyword,
    int page = 1,
  }) async {
    final apiResult = await get(
      Api.products,
      queryParameters: {
        "keyword": keyword,
        "type": "vendor",
        "page": page,
      },
    );
    //
    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      List<Product> products = [];
      apiResponse.data.forEach((jsonObject) {
        try {
          final mProduct = Product.fromJson(jsonObject);
          products.add(mProduct);
        } catch (error) {
          print("Error ==> $error");
        }
      });
      return products;
    } else {
      throw apiResponse.message;
    }
  }

  //
  Future<Product> getProductDetails(int productId) async {
    final apiResult = await get(
      Api.products + "/$productId",
    );
    //
    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return Product.fromJson(apiResponse.body);
    } else {
      throw apiResponse.message;
    }
  }

  Future<List<ProductCategory>> getProductCategories({
    bool subCat = false,
    int? vendorTypeId,
  }) async {
    final apiResult = await get(
      Api.productCategories,
      queryParameters: {
        "type": subCat ? "sub" : "",
        "vendor_type_id": vendorTypeId
      },
    );
    //
    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return apiResponse.data.map((jsonObject) {
        return ProductCategory.fromJson(jsonObject);
      }).toList();
    } else {
      throw apiResponse.message;
    }
  }

  Future<ApiResponse> newProduct(
    Map<String, dynamic> value, {
    List<File>? photos,
  }) async {
    //
    final postBody = {
      ...value,
      "vendor_id": AuthServices.currentVendor?.id,
    };

    FormData formData = FormData.fromMap(postBody);
    if (photos != null && photos.isNotEmpty) {
      for (File file in photos) {
        File? mFile = await Utils.compressFile(
          file: file,
          quality: 60,
        );
        if (mFile != null) {
          formData.files.addAll([
            MapEntry("photos[]", await MultipartFile.fromFile(mFile.path)),
          ]);
        }
      }
    }

    final apiResult = await postWithFiles(
      Api.products,
      null,
      formData: formData,
    );
    //
    return ApiResponse.fromResponse(apiResult);
  }

  Future<ApiResponse> deleteProduct(
    Product product,
  ) async {
    final apiResult = await delete(
      Api.products + "/${product.id}",
    );
    //
    return ApiResponse.fromResponse(apiResult);
  }

  Future<ApiResponse> updateDetails(
    Product product, {
    Map<String, dynamic>? data,
    List<File>? photos,
  }) async {
    //
    final postBody = {
      "_method": "PUT",
      ...(data == null ? product.toJson() : data),
      "vendor_id": AuthServices.currentVendor?.id,
    };
    FormData formData = FormData.fromMap(
      postBody,
      ListFormat.multiCompatible,
    );

    if (photos != null && photos.isNotEmpty) {
      for (File file in photos) {
        File? mFile = await Utils.compressFile(
          file: file,
          quality: 60,
        );
        if (mFile != null) {
          formData.files.addAll([
            MapEntry("photos[]", await MultipartFile.fromFile(mFile.path)),
          ]);
        }
      }
    }

    final apiResult = await postWithFiles(
      Api.products + "/${product.id}",
      null,
      formData: formData,
    );
    //
    return ApiResponse.fromResponse(apiResult);
  }

  Future<List<ProductCategory>> fetchSubCategories({
    required dynamic categoryId,
  }) async {
    final apiResult = await get(
      Api.productCategories,
      queryParameters: {
        "category_id": categoryId,
        "type": "sub",
      },
    );
    //
    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return apiResponse.data.map((jsonObject) {
        return ProductCategory.fromJson(jsonObject);
      }).toList();
    } else {
      throw apiResponse.message;
    }
  }

  Future<List<ProductTag>> getProductTags({int? vendorTypeId}) async {
    final apiResult = await get(
      Api.productTags,
      queryParameters: {
        "vendor_type_id": vendorTypeId,
      },
    );
    //
    final apiResponse = ApiResponse.fromResponse(apiResult);
    if (apiResponse.allGood) {
      return (apiResponse.body as List).map((jsonObject) {
        return ProductTag.fromJson(jsonObject);
      }).toList();
    } else {
      throw apiResponse.message;
    }
  }
}
