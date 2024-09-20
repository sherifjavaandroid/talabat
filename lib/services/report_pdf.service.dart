import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/extensions/dynamic.dart';
import 'package:fuodz/models/user.dart';
import 'package:jiffy/jiffy.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:pdf/pdf.dart';
import 'package:pdf/widgets.dart' as pw;
import 'package:velocity_x/velocity_x.dart';

class ReportPdfExportService {
  //
  static Future<pw.Document> getPayoutReportPDF(
    List<Map<String, dynamic>> payouts, {
    required DateTime startDate,
    required DateTime endDate,
    required User user,
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
                'Date'.tr(),
                'Amount'.tr() + " ($currencySymbol)",
                'Payment Account'.tr(),
                'Status'.tr(),
              ],
              ...payouts.mapIndexed(
                (e, index) => [
                  (index + 1).toString(),
                  e["date"].toString(),
                  e["amount"].toString(),
                  //get payment account name
                  (e["payment_account"] != null)
                      ? "${e["payment_account"]["name"]} - [${e["payment_account"]["number"]}]"
                      : "",
                  e["status"].toString(),
                ],
              ),
            ],
          );
          return pw.Column(
            children: [
              pw.Text(
                'Payout Report'.tr(),
                //boold and bigger
                style: pw.TextStyle(
                  fontWeight: pw.FontWeight.bold,
                  fontSize: 20,
                ),
              ),
              //add user name
              pw.Text(
                "[${user.name}]",
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
    required User user,
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
              //add user name
              pw.Text(
                "[${user.name}]",
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
