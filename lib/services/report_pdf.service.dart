import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/extensions/dynamic.dart';
import 'package:fuodz/models/report/sale_report.dart';
import 'package:fuodz/models/vendor.dart';
import 'package:jiffy/jiffy.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:pdf/pdf.dart';
import 'package:pdf/widgets.dart' as pw;
import 'package:velocity_x/velocity_x.dart';

class ReportPdfExportService {
  //
  static Future<pw.Document> getSalesReportPDF(
    List<SaleReport> sales, {
    required DateTime startDate,
    required DateTime endDate,
    required Vendor vendor,
  }) async {
    final pdf = pw.Document();
    final currencySymbol = AppStrings.currencySymbol;
    pdf.addPage(
      pw.Page(
        pageFormat: PdfPageFormat.a4,
        build: (pw.Context context) {
          pw.Table table = pw.TableHelper.fromTextArray(
            context: context,
            headerAlignment: pw.Alignment.centerLeft,
            data: <List<String>>[
              <String>[
                "S/N",
                'Name'.tr(),
                'Total Unit'.tr(),
                'Total Amount'.tr() + " ($currencySymbol)",
              ],
              ...sales.mapIndexed(
                (e, index) => [
                  (index + 1).toString(),
                  e.name,
                  e.totalUnit.toString(),
                  e.totalAmount.toString(),
                ],
              ),
            ],
          );
          return pw.Column(
            children: [
              pw.Text(
                'Sales Report'.tr(),
                //boold and bigger
                style: pw.TextStyle(
                  fontWeight: pw.FontWeight.bold,
                  fontSize: 20,
                ),
              ),
              //add vendor name
              pw.Text(
                "[${vendor.name}]",
                //boold and bigger
                style: pw.TextStyle(
                  fontWeight: pw.FontWeight.bold,
                  fontSize: 14,
                ),
              ),
              pw.SizedBox(height: 5),
              pw.Text(
                "From".tr() + ": ${Jiffy.parseFromDateTime(startDate).yMMMMd}",
                style: pw.TextStyle(
                  fontWeight: pw.FontWeight.bold,
                  fontSize: 12,
                ),
              ),
              pw.Text(
                "To".tr() + ": ${Jiffy.parseFromDateTime(endDate).yMMMMd}",
                style: pw.TextStyle(
                  fontWeight: pw.FontWeight.bold,
                  fontSize: 12,
                ),
              ),
              pw.SizedBox(height: 20),
              table,
            ],
          );
        },
      ),
    );

    return pdf;
  }

  //
  static Future<pw.Document> getEarningReportPDF(
    List<Map<String, dynamic>> dataSet, {
    required DateTime startDate,
    required DateTime endDate,
    required Vendor vendor,
  }) async {
    final pdf = pw.Document();
    final currencySymbol = AppStrings.currencySymbol;
    //
    pdf.addPage(
      pw.Page(
        pageFormat: PdfPageFormat.a4,
        build: (pw.Context context) {
          pw.Table table = pw.TableHelper.fromTextArray(
            context: context,
            headerAlignment: pw.Alignment.centerLeft,
            data: <List<String>>[
              <String>[
                "S/N",
                'Date'.tr(),
                'Earned'.tr() + " ($currencySymbol)",
                'Commission'.tr() + " ($currencySymbol)",
                'Balance'.tr() + " ($currencySymbol)",
              ],
              ...dataSet.mapIndexed(
                (e, index) => [
                  (index + 1).toString(),
                  "${e["date"]}",
                  "${e["total_earning"]}".currencyValueFormat(),
                  "${e["total_commission"]}".currencyValueFormat(),
                  "${e["total_balance"]}".currencyValueFormat(),
                ],
              ),
            ],
          );
          return pw.Column(
            children: [
              pw.Text(
                'Earnings Report'.tr(),
                //boold and bigger
                style: pw.TextStyle(
                  fontWeight: pw.FontWeight.bold,
                  fontSize: 20,
                ),
              ),
              //add vendor name
              pw.Text(
                "[${vendor.name}]",
                //boold and bigger
                style: pw.TextStyle(
                  fontWeight: pw.FontWeight.bold,
                  fontSize: 14,
                ),
              ),
              pw.SizedBox(height: 5),
              pw.Text(
                "From".tr() + ": ${Jiffy.parseFromDateTime(startDate).yMMMMd}",
                style: pw.TextStyle(
                  fontWeight: pw.FontWeight.bold,
                  fontSize: 12,
                ),
              ),
              pw.Text(
                "To".tr() + ": ${Jiffy.parseFromDateTime(endDate).yMMMMd}",
                style: pw.TextStyle(
                  fontWeight: pw.FontWeight.bold,
                  fontSize: 12,
                ),
              ),
              pw.SizedBox(height: 20),
              table,
            ],
          );
        },
      ),
    );

    return pdf;
  }

  //
}
