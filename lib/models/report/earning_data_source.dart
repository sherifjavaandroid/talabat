import 'package:flutter/material.dart';
import 'package:fuodz/extensions/dynamic.dart';
import 'package:syncfusion_flutter_datagrid/datagrid.dart';
import 'package:velocity_x/velocity_x.dart';

class EarningReportDataSource extends DataGridSource {
  EarningReportDataSource({List<Map<String, dynamic>> dataSet = const []}) {
    _dataRows = dataSet
        .mapIndexed<DataGridRow>(
          (e, index) => DataGridRow(
            cells: [
              DataGridCell<int>(
                columnName: 'sn',
                value: index + 1,
              ),
              DataGridCell<String>(
                columnName: 'date',
                value: e['date'],
              ),
              DataGridCell<String>(
                columnName: 'total_earning',
                value: "${e["total_earning"]}".currencyValueFormat().toString(),
              ),
              DataGridCell<String>(
                columnName: 'total_commission',
                value:
                    "${e["total_commission"]}".currencyValueFormat().toString(),
              ),
              DataGridCell<String>(
                columnName: 'total_balance',
                value: "${e["total_balance"]}".currencyValueFormat().toString(),
              ),
            ],
          ),
        )
        .toList();
  }

  List<DataGridRow> _dataRows = [];

  @override
  List<DataGridRow> get rows => _dataRows;

  @override
  DataGridRowAdapter? buildRow(DataGridRow row) {
    return DataGridRowAdapter(
      cells: row.getCells().map<Widget>(
        (dataGridCell) {
          return Container(
            alignment: Alignment.centerLeft,
            padding: EdgeInsets.symmetric(horizontal: 8, vertical: 2),
            child: Text(
              dataGridCell.value.toString(),
            ),
          );
        },
      ).toList(),
    );
  }
}
