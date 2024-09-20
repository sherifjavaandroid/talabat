import 'package:flutter/material.dart';
import 'package:fuodz/models/flash_sale.dart';
import 'package:fuodz/models/product.dart';
import 'package:fuodz/models/vendor_type.dart';
import 'package:fuodz/requests/flash_sale.request.dart';
import 'package:fuodz/view_models/base.view_model.dart';
import 'package:pull_to_refresh_flutter3/pull_to_refresh_flutter3.dart';

class FlashSaleViewModel extends MyBaseViewModel {
  FlashSaleViewModel(
    BuildContext context, {
    this.vendorType,
    this.flashSale,
  }) {
    this.viewContext = context;
  }

  //
  FlashSaleRequest _flashSaleRequest = FlashSaleRequest();
  RefreshController refreshController = RefreshController();

  //
  List<FlashSale> flashSales = [];
  List<Product> flashSaleItems = [];
  final VendorType? vendorType;
  final FlashSale? flashSale;
  int queryPage = 1;

  //
  initialise() async {
    setBusy(true);
    try {
      flashSales = await _flashSaleRequest.getFlashSales(
        queryParams: {
          "vendor_type_id": vendorType?.id,
        },
      );
      setBusy(false);
      //fetch items for each flash sale
      fetchFlashSaleItems();
      clearErrors();
    } catch (error) {
      setError(error);
      toastError("$error");
    }
    setBusy(false);
  }

  fetchFlashSaleItems() async {
    for (var i = 0; i < flashSales.length; i++) {
      final flashSale = flashSales[i];
      setBusyForObject(flashSale.id, true);
      try {
        final flashSaleItems = await _flashSaleRequest.getProdcuts(
          queryParams: {
            "flash_sale_id": flashSale.id,
          },
        );

        //set data
        flashSales[i].items = flashSaleItems;
        clearErrors();
      } catch (error) {
        setError(error);
        toastError("$error");
      }
      setBusyForObject(flashSale.id, false);
    }
  }

  getFlashSaleItems([bool initial = true]) async {
    if (initial) {
      setBusy(true);
      queryPage = 1;
    } else {
      queryPage += 1;
      refreshController.refreshCompleted();
      setBusyForObject(flashSale?.id, true);
    }
    try {
      final mFlashSaleItems = await _flashSaleRequest.getProdcuts(
        queryParams: {
          "flash_sale_id": flashSale?.id,
        },
        page: queryPage,
      );

      if (!initial) {
        flashSaleItems.addAll(mFlashSaleItems);
        refreshController.loadComplete();
      } else {
        flashSaleItems = mFlashSaleItems;
        refreshController.refreshCompleted();
      }

      clearErrors();
    } catch (error) {
      setError(error);
    }
    setBusy(false);
  }
}
