import 'package:flutter/material.dart';
import 'package:fuodz/extensions/dynamic.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:syncfusion_flutter_datagrid/datagrid.dart';
import 'package:velocity_x/velocity_x.dart';

class PayoutReportDataSource extends DataGridSource {
  PayoutReportDataSource({List<Map<String, dynamic>> dataSet = const []}) {
    _payoutReports = dataSet
        .mapIndexed<DataGridRow>(
          (e, index) => DataGridRow(
            cells: [
              DataGridCell<int>(
                columnName: 'sn',
                value: index + 1,
              ),
              DataGridCell<String>(
                columnName: 'date',
                value: e["date"],
              ),
              DataGridCell<String>(
                columnName: 'amount',
                value: "${e['amount']}".currencyValueFormat(),
              ),
              DataGridCell<String>(
                columnName: 'status',
                value: e["status"].toString().tr(),
              ),
              DataGridCell<String>(
                columnName: 'amount',
                value: (e["payment_account"] != null)
                    ? "${e["payment_account"]["name"]} - [${e["payment_account"]["number"]}]"
                    : "",
              ),
            ],
          ),
        )
        .toList();
  }

  List<DataGridRow> _payoutReports = [];

  @override
  List<DataGridRow> get rows => _payoutReports;

  @override
  DataGridRowAdapter? buildRow(DataGridRow row) {
    return DataGridRowAdapter(
      color: Colors.red,
      cells: row.getCells().map<Widget>(
        (dataGridCell) {
          return Container(
            alignment: Alignment.centerLeft,
            padding: EdgeInsets.symmetric(horizontal: 8, vertical: 0),
            child: Text(
              dataGridCell.value.toString(),
            ),
          );
        },
      ).toList(),
    );
  }
}
