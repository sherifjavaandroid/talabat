import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/services/auth.service.dart';
import 'package:fuodz/view_models/vendor_finance_report.vm.dart';
import 'package:fuodz/widgets/busy_indicator.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:pull_to_refresh_flutter3/pull_to_refresh_flutter3.dart';
import 'package:syncfusion_flutter_datagrid/datagrid.dart';
import 'package:velocity_x/velocity_x.dart';

class SalesReportView extends StatelessWidget {
  const SalesReportView({required this.vm, super.key});

  final VendorFinanceReportViewModel vm;

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Visibility(
          visible: vm.busy(vm.saleReportDataSource),
          child: BusyIndicator().p20().centered(),
        ),

        //
        SmartRefresher(
          controller: vm.salesReportRefreshController,
          enablePullDown: true,
          enablePullUp: true,
          onRefresh: () {
            vm.fetchSalesReport();
          },
          onLoading: () {
            vm.fetchSalesReport(true);
          },
          child: Container(
            child: SfDataGrid(
              key: vm.sReportKey,
              source: vm.saleReportDataSource,
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
                  columnName: 'name',
                  label: Container(
                    width: double.infinity,
                    padding: EdgeInsets.all(8),
                    alignment: Alignment.centerLeft,
                    child: Text(
                      AuthServices.currentVendor!.isPackageType
                          ? 'Package Type'.tr()
                          : AuthServices.currentVendor!.isServiceType
                              ? 'Service'.tr()
                              : 'Product'.tr(),
                    ),
                  ),
                ),
                GridColumn(
                  columnName: 'unit',
                  label: Container(
                    padding: EdgeInsets.all(8),
                    alignment: Alignment.centerLeft,
                    child: Text('Total Unit'.tr()),
                  ),
                  width: context.percentWidth * 22,
                ),
                GridColumn(
                  columnName: 'price',
                  label: Container(
                    padding: EdgeInsets.all(8),
                    alignment: Alignment.centerLeft,
                    child: Text(
                      'Total Amount'.tr() + " (${AppStrings.currencySymbol})",
                    ),
                  ),
                  width: context.percentWidth * 30,
                ),
                GridColumn(
                  columnName: 'date',
                  label: Container(
                    padding: EdgeInsets.all(8),
                    alignment: Alignment.centerLeft,
                    child: Text('Date'.tr()),
                  ),
                  width: context.percentWidth * 22,
                ),
              ],
            ),
          ),
        ).expand(),
      ],
    );
  }
}
