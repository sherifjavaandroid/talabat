import 'package:flutter/material.dart';
import 'package:velocity_x/velocity_x.dart';

class MyMasonryGrid extends StatelessWidget {
  const MyMasonryGrid({
    super.key,
    required this.items,
    this.column = 2,
    this.crossAxisSpacing = 8,
    this.mainAxisSpacing = 8,
  });

  final List<Widget> items;
  final int column;
  final double crossAxisSpacing;
  final double mainAxisSpacing;

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      child: Column(
        children: [
          getColumns(column),
        ],
      ),
    );
  }

  Widget getColumns(int numberOfColumns) {
    // Create a list of empty sublists
    List<List<Widget>> sublists = List.generate(numberOfColumns, (_) => []);

    // Divide the original list into sublists
    for (int i = 0; i < items.length; i++) {
      int columnIndex = i % numberOfColumns;
      sublists[columnIndex].add(items[i]);
    }
    List<Widget> children = [];
    for (int i = 0; i < sublists.length; i++) {
      Widget indexColumn = VStack(
        sublists[i],
        crossAlignment: CrossAxisAlignment.start,
        alignment: MainAxisAlignment.start,
        spacing: mainAxisSpacing,
      ).expand();
      children.add(indexColumn);
    }

    return HStack(
      children,
      crossAlignment: CrossAxisAlignment.start,
      alignment: MainAxisAlignment.start,
      spacing: crossAxisSpacing,
    );
  }
  //
}
