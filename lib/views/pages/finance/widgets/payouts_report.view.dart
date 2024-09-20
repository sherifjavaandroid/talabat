import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/view_models/finance_report.vm.dart';
import 'package:fuodz/widgets/busy_indicator.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:pull_to_refresh_flutter3/pull_to_refresh_flutter3.dart';
import 'package:syncfusion_flutter_datagrid/datagrid.dart';
import 'package:velocity_x/velocity_x.dart';

class PayoutReportView extends StatelessWidget {
  const PayoutReportView({required this.vm, super.key});

  final FinanceReportViewModel vm;

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Visibility(
          visible: vm.busy(vm.payoutReportDataSource),
          child: BusyIndicator().p20().centered(),
        ),

        //
        SmartRefresher(
          controller: vm.payoutsReportRefreshController,
          enablePullDown: true,
          enablePullUp: true,
          onRefresh: () {
            vm.fetchPayoutReport();
          },
          onLoading: () {
            vm.fetchPayoutReport(true);
          },
          child: Container(
            width: double.infinity,
            child: SfDataGrid(
              key: vm.pReportKey,
              source: vm.payoutReportDataSource,
              columnWidthMode: context.isMobileTypeTablet
                  ? ColumnWidthMode.fill
                  : ColumnWidthMode.auto,
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
                    child: Text('Date'.tr()),
                  ),
                ),
                GridColumn(
                  columnName: 'amount',
                  label: Container(
                    width: double.infinity,
                    padding: EdgeInsets.all(8),
                    alignment: Alignment.centerLeft,
                    child: Text(
                      'Amount'.tr() + " (${AppStrings.currencySymbol})",
                    ),
                  ),
                ),
                GridColumn(
                  columnName: 'status',
                  label: Container(
                    padding: EdgeInsets.all(8),
                    alignment: Alignment.centerLeft,
                    child: Text(
                      'Status'.tr(),
                    ),
                  ),
                ),
                GridColumn(
                  columnName: 'payment_account',
                  label: Container(
                    width: double.infinity,
                    padding: EdgeInsets.all(8),
                    alignment: Alignment.centerLeft,
                    child: Text(
                      'Payment Account'.tr(),
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
