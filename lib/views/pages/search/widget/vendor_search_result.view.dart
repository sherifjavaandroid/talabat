import 'package:flutter/material.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/main_search.vm.dart';
import 'package:fuodz/widgets/custom_list_view.dart';
import 'package:fuodz/widgets/custom_masonry_grid_view.dart';
import 'package:fuodz/widgets/list_items/dynamic_vendor.list_item.dart';
import 'package:fuodz/widgets/states/search.empty.dart';
import 'package:velocity_x/velocity_x.dart';
// import 'package:velocity_x/velocity_x.dart';

class VendorSearchResultView extends StatefulWidget {
  VendorSearchResultView(this.vm, {Key? key}) : super(key: key);

  final MainSearchViewModel vm;
  @override
  State<VendorSearchResultView> createState() => _VendorSearchResultViewState();
}

class _VendorSearchResultViewState extends State<VendorSearchResultView> {
  @override
  Widget build(BuildContext context) {
    final refreshController = widget.vm.refreshControllers[0];
    //
    return (widget.vm.search?.layoutType == null ||
            widget.vm.search?.layoutType == "grid")
        ? CustomMasonryGridView(
            padding: EdgeInsets.symmetric(vertical: 12),
            refreshController: refreshController,
            canPullUp: true,
            canRefresh: true,
            onRefresh: widget.vm.searchVendors,
            onLoading: () => widget.vm.searchVendors(initial: false),
            mainAxisSpacing: 10,
            crossAxisSpacing: 10,
            isLoading: widget.vm.busy(widget.vm.vendors),
            emptyWidget: EmptySearch(type: "vendor"),
            items: [
              ...(widget.vm.vendors.map(
                (vendor) {
                  return DynamicVendorListItem(
                    vendor,
                    onPressed: widget.vm.vendorSelected,
                    width: context.screenWidth,
                  );
                },
              ).toList()),
            ],
            // dataSet: widget.vm.vendors,
            // itemBuilder: (ctx, index) {
            //   final vendor = widget.vm.vendors[index];
            //   return FittedBox(
            //     child: DynamicVendorListItem(
            //       vendor,
            //       onPressed: widget.vm.vendorSelected,
            //       width: context.percentWidth * 48,
            //     ),
            //   );
            // },
          )
        : CustomListView(
            padding: EdgeInsets.symmetric(vertical: 12),
            refreshController: refreshController,
            canPullUp: true,
            canRefresh: true,
            onRefresh: widget.vm.searchVendors,
            onLoading: () => widget.vm.searchVendors(initial: false),
            dataSet: widget.vm.vendors,
            isLoading: widget.vm.busy(widget.vm.vendors),
            emptyWidget: EmptySearch(type: "vendor"),
            itemBuilder: (ctx, index) {
              final vendor = widget.vm.vendors[index];
              return DynamicVendorListItem(
                vendor,
                onPressed: widget.vm.vendorSelected,
                width: double.infinity,
              );
            },
            separatorBuilder: (p0, p1) => UiSpacer.vSpace(10),
          );
  }
}
