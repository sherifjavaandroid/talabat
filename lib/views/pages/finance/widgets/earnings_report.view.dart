import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/view_models/vendor_finance_report.vm.dart';
import 'package:fuodz/widgets/busy_indicator.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:pull_to_refresh_flutter3/pull_to_refresh_flutter3.dart';
import 'package:syncfusion_flutter_datagrid/datagrid.dart';
import 'package:velocity_x/velocity_x.dart';

class EarningsReportView extends StatelessWidget {
  const EarningsReportView({required this.vm, super.key});
  final VendorFinanceReportViewModel vm;
  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Visibility(
          visible: vm.busy(vm.earningReportDataSource),
          child: BusyIndicator().p20().centered(),
        ),
        SmartRefresher(
          controller: vm.earningReportRefreshController,
          enablePullDown: true,
          enablePullUp: true,
          onRefresh: () {
            vm.fetchEarningsReport();
          },
          onLoading: () {
            vm.fetchEarningsReport(true);
          },
          child: Container(
            child: SfDataGrid(
              key: vm.eReportKey,
              source: vm.earningReportDataSource,
              columnWidthMode: ColumnWidthMode.fitByCellValue,
              allowColumnsResizing: true,
              isScrollbarAlwaysShown: true,
              columns: <GridColumn>[
                GridColumn(
                  columnName: 'sn',
                  label: Container(
                    padding: EdgeInsets.all(8),
                    alignment: Alignment.centerLeft,
                    child: Text('S/N'.tr()),
                  ),
                  width: 55,
                ),
                GridColumn(
                  columnName: 'date',
                  label: Container(
                    padding: EdgeInsets.all(8),
                    alignment: Alignment.centerLeft,
                    child: Text(
                      'Date'.tr(),
                    ),
                  ),
                ),
                GridColumn(
                  columnName: 'total_earning',
                  label: Container(
                    padding: EdgeInsets.all(8),
                    alignment: Alignment.centerLeft,
                    child: Text(
                      'Earned'.tr() + " (${AppStrings.currencySymbol})",
                    ),
                  ),
                ),
                GridColumn(
                  columnName: 'total_commission',
                  width: 120,
                  label: Container(
                    padding: EdgeInsets.all(8),
                    alignment: Alignment.centerLeft,
                    child: Text(
                      'Commission'.tr() + " (${AppStrings.currencySymbol})",
                    ),
                  ),
                ),
                GridColumn(
                  columnName: 'total_balance',
                  label: Container(
                    padding: EdgeInsets.all(8),
                    alignment: Alignment.centerLeft,
                    child: Text(
                      'Balance'.tr() + " (${AppStrings.currencySymbol})",
                    ),
                  ),
                ),
              ],
            ),
          ),
        ).expand(),
      ],
    );
  }
}
