import 'package:flutter/material.dart';
import 'package:fuodz/extensions/dynamic.dart';
import 'package:fuodz/models/report/sale_report.dart';
import 'package:syncfusion_flutter_datagrid/datagrid.dart';
import 'package:velocity_x/velocity_x.dart';

class SaleReportDataSource extends DataGridSource {
  SaleReportDataSource({List<SaleReport> saleReports = const []}) {
    _saleReports = saleReports
        .mapIndexed<DataGridRow>(
          (e, index) => DataGridRow(
            cells: [
              DataGridCell<int>(
                columnName: 'sn',
                value: index + 1,
              ),
              DataGridCell<String>(
                columnName: 'name',
                value: e.name,
              ),
              DataGridCell<int>(
                columnName: 'unit',
                value: e.totalUnit,
              ),
              DataGridCell<String>(
                columnName: 'price',
                value: "${e.totalAmount}".currencyValueFormat(),
              ),
              DataGridCell<String>(
                columnName: 'date',
                value: "${e.date}",
              ),
            ],
          ),
        )
        .toList();
  }

  List<DataGridRow> _saleReports = [];

  @override
  List<DataGridRow> get rows => _saleReports;

  @override
  DataGridRowAdapter? buildRow(DataGridRow row) {
    return DataGridRowAdapter(
      cells: row.getCells().map<Widget>(
        (dataGridCell) {
          return Container(
            alignment: Alignment.centerLeft,
            padding: EdgeInsets.symmetric(horizontal: 8, vertical: 0),
            child: Text(dataGridCell.value.toString()),
          );
        },
      ).toList(),
    );
  }
}
