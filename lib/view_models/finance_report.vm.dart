import 'dart:io';

import 'package:flutter/material.dart';
import 'package:fuodz/models/report/earning_data_source.dart';
import 'package:fuodz/models/report/payout_data_source.dart';
import 'package:fuodz/requests/report.request.dart';
import 'package:fuodz/services/alert.service.dart';
import 'package:fuodz/services/auth.service.dart';
import 'package:fuodz/view_models/base.view_model.dart';
import 'package:fuodz/services/report_pdf.service.dart';
import 'package:jiffy/jiffy.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:pull_to_refresh_flutter3/pull_to_refresh_flutter3.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:syncfusion_flutter_datagrid/datagrid.dart';
import 'package:path_provider/path_provider.dart' as path_provider;
import 'package:open_file/open_file.dart' as open_file;

class FinanceReportViewModel extends MyBaseViewModel {
  FinanceReportViewModel() {
    this.startDate = DateTime.now().subtract(7.days);
    this.endDate = DateTime.now();
  }
  //
  int activeTabIndex = 0;
  int payoutsPage = 1;
  int earningsPage = 1;
  PayoutReportDataSource payoutReportDataSource =
      PayoutReportDataSource(dataSet: []);
  EarningReportDataSource earningReportDataSource =
      EarningReportDataSource(dataSet: []);
  DateTime? startDate;
  DateTime? endDate;
  List<Map<String, dynamic>> earningsReportDataset = [];
  List<Map<String, dynamic>> payoutsReportDataset = [];

  //
  RefreshController payoutsReportRefreshController = RefreshController();
  RefreshController earningReportRefreshController = RefreshController();
  final GlobalKey<SfDataGridState> pReportKey = GlobalKey<SfDataGridState>();
  final GlobalKey<SfDataGridState> eReportKey = GlobalKey<SfDataGridState>();
  ReportRequest reportRequest = ReportRequest();

//
  initialise() {
    super.initialise();
    fetchPayoutReport();
    fetchEarningsReport();
  }

  //
  fetchPayoutReport([bool loadMore = false]) async {
    if (loadMore) {
      payoutsPage++;
    } else {
      payoutsPage = 1;
      payoutsReportRefreshController.refreshCompleted();
      //clear previous data
      payoutReportDataSource = PayoutReportDataSource(dataSet: []);
      setBusyForObject(payoutReportDataSource, true);
    }

    try {
      final payoutsReport = await reportRequest.getPayoutsReport(
        page: payoutsPage,
        sDate: startDate?.toString(),
        eDate: endDate?.toString(),
      );
      if (loadMore) {
        payoutsReportDataset.addAll(payoutsReport);
      } else {
        payoutsReportDataset = payoutsReport;
      }

      payoutReportDataSource = PayoutReportDataSource(
        dataSet: payoutsReportDataset,
      );
    } catch (error) {
      toastError("$error");
    }

    if (loadMore) {
      payoutsReportRefreshController.loadComplete();
    }
    setBusyForObject(payoutReportDataSource, false);
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
      final earningsReport = await reportRequest.getEarningsReport(
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
          fetchPayoutReport();
          fetchEarningsReport();
        }
      },
    );
  }

  exportReport(BuildContext context) async {
    //
    setBusyForObject(exportReport, true);
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
        if (payoutReportDataSource.rows.isEmpty) {
          toastError("No data to export".tr());
          return;
        }
        final document = await ReportPdfExportService.getPayoutReportPDF(
          payoutsReportDataset,
          startDate: startDate!,
          endDate: endDate!,
          user: AuthServices.currentUser!,
        );
        bytes = await document.save();
        String cTime = Jiffy.now().format(pattern: 'dd MMM yyyy hh:mm a');
        String fileName = "Payout-Report ($cTime).pdf";
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
          user: AuthServices.currentUser!,
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

    setBusyForObject(exportReport, false);
  }
}
