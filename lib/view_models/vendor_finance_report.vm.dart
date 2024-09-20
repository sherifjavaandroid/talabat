import 'dart:io';

import 'package:flutter/material.dart';
import 'package:fuodz/models/report/earning_data_source.dart';
import 'package:fuodz/models/report/sale_data_source.dart';
import 'package:fuodz/models/report/sale_report.dart';
import 'package:fuodz/services/alert.service.dart';
import 'package:fuodz/services/auth.service.dart';
import 'package:fuodz/view_models/vendor_details.view_model.dart';
import 'package:fuodz/services/report_pdf.service.dart';
import 'package:jiffy/jiffy.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:pull_to_refresh_flutter3/pull_to_refresh_flutter3.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:syncfusion_flutter_datagrid/datagrid.dart';
import 'package:path_provider/path_provider.dart' as path_provider;
import 'package:open_file/open_file.dart' as open_file;

class VendorFinanceReportViewModel extends VendorDetailsViewModel {
  VendorFinanceReportViewModel(super.context) {
    this.startDate = DateTime.now().subtract(7.days);
    this.endDate = DateTime.now();
  }
  //
  int activeTabIndex = 0;
  int salesPage = 1;
  int earningsPage = 1;
  SaleReportDataSource saleReportDataSource =
      SaleReportDataSource(saleReports: []);
  EarningReportDataSource earningReportDataSource =
      EarningReportDataSource(dataSet: []);
  DateTime? startDate;
  DateTime? endDate;
  List<Map<String, dynamic>> earningsReportDataset = [];
  List<SaleReport> salesReportDataset = [];

  //
  RefreshController salesReportRefreshController = RefreshController();
  RefreshController earningReportRefreshController = RefreshController();
  final GlobalKey<SfDataGridState> sReportKey = GlobalKey<SfDataGridState>();
  final GlobalKey<SfDataGridState> eReportKey = GlobalKey<SfDataGridState>();

//
  initialise() {
    super.initialise();
    fetchSalesReport();
    fetchEarningsReport();
  }

  //
  fetchSalesReport([bool loadMore = false]) async {
    if (loadMore) {
      salesPage++;
    } else {
      salesPage = 1;
      salesReportRefreshController.refreshCompleted();
      //clear previous data
      saleReportDataSource = SaleReportDataSource(saleReports: []);
      setBusyForObject(saleReportDataSource, true);
    }

    try {
      final salesReport = await vendorRequest.getSalesReport(
        page: salesPage,
        sDate: startDate?.toString(),
        eDate: endDate?.toString(),
      );
      if (loadMore) {
        salesReportDataset.addAll(salesReport);
      } else {
        salesReportDataset = salesReport;
      }

      saleReportDataSource = SaleReportDataSource(
        saleReports: salesReportDataset,
      );
    } catch (error) {
      toastError("$error");
    }

    if (loadMore) {
      salesReportRefreshController.loadComplete();
    }
    setBusyForObject(saleReportDataSource, false);
  }

  fetchEarningsReport([bool loadMore = false]) async {
    if (loadMore) {
      earningsPage++;
    } else {
      earningsPage = 1;
      earningReportRefreshController.refreshCompleted();
      //clear previous data
      earningReportDataSource = EarningReportDataSource(dataSet: []);
      setBusyForObject(earningReportDataSource, true);
    }

    try {
      final earningsReport = await vendorRequest.getEarningsReport(
        page: earningsPage,
        sDate: startDate?.toString(),
        eDate: endDate?.toString(),
      );

      if (loadMore) {
        earningsReportDataset.addAll(earningsReport);
      } else {
        earningsReportDataset = earningsReport;
      }

      earningReportDataSource = EarningReportDataSource(
        dataSet: earningsReportDataset,
      );
    } catch (error) {
      toastError("$error");
    }

    if (loadMore) {
      earningReportRefreshController.loadComplete();
    }
    setBusyForObject(earningReportDataSource, false);
  }

  void showDateFilter(BuildContext context) {
    //date range picker
    showDateRangePicker(
      context: context,
      firstDate: DateTime.now().subtract(365.days),
      lastDate: DateTime.now(),
      initialDateRange: DateTimeRange(
        start: startDate ?? DateTime.now().subtract(7.days),
        end: endDate ?? DateTime.now(),
      ),
    ).then(
      (value) {
        if (value != null) {
          startDate = value.start;
          endDate = value.end;
          fetchSalesReport();
          fetchEarningsReport();
        }
      },
    );
  }

  exportSalesReport(BuildContext context) async {
    //
    setBusyForObject(exportSalesReport, true);
    try {
      List<int> bytes = [];
      String? path;
      String fileLocation = "";
      if (Platform.isAndroid) {
        final Directory? directory =
            await path_provider.getDownloadsDirectory() ??
                await path_provider.getExternalStorageDirectory();
        if (directory != null) {
          path = directory.path;
        }
      } else {
        final Directory directory =
            await path_provider.getApplicationSupportDirectory();
        path = directory.path;
      }

      //
      if (activeTabIndex == 0) {
        if (saleReportDataSource.rows.isEmpty) {
          toastError("No data to export".tr());
          return;
        }
        final document = await ReportPdfExportService.getSalesReportPDF(
          salesReportDataset,
          startDate: startDate!,
          endDate: endDate!,
          vendor: AuthServices.currentVendor!,
        );
        bytes = await document.save();
        String cTime = Jiffy.now().format(pattern: 'dd MMM yyyy hh:mm a');
        String fileName = "Sales-Report ($cTime).pdf";
        fileLocation = '$path/$fileName';
      } else {
        if (earningReportDataSource.rows.isEmpty) {
          toastError("No data to export".tr());
          return;
        }
        final document = await ReportPdfExportService.getEarningReportPDF(
          earningsReportDataset,
          startDate: startDate!,
          endDate: endDate!,
          vendor: AuthServices.currentVendor!,
        );
        bytes = await document.save();
        String cTime = Jiffy.now().format(pattern: 'dd MMM yyyy hh:mm a');
        String fileName = "Earning-Report ($cTime}).pdf";
        fileLocation = '$path/$fileName';
      }

      final File file = File(fileLocation);
      await file.writeAsBytes(bytes, flush: true);
      //
      bool confirmed = await AlertService.success(
        text: "Report exported successfully".tr(),
        confirmBtnText: "Open".tr(),
      );
      if (confirmed) {
        await open_file.OpenFile.open(fileLocation);
      }
    } catch (error) {
      toastError("$error");
    }

    setBusyForObject(exportSalesReport, false);
  }
}
