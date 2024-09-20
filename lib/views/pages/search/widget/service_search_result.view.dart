import 'package:flutter/material.dart';
import 'package:fuodz/view_models/main_search.vm.dart';
import 'package:fuodz/widgets/custom_list_view.dart';
import 'package:fuodz/widgets/custom_masonry_grid_view.dart';
import 'package:fuodz/widgets/list_items/service.gridview_item.dart';
import 'package:fuodz/widgets/list_items/service.list_item.dart';
import 'package:fuodz/widgets/states/search.empty.dart';
import 'package:velocity_x/velocity_x.dart';

class ServiceSearchResultView extends StatelessWidget {
  ServiceSearchResultView(this.vm, {Key? key}) : super(key: key);

  final MainSearchViewModel vm;
  @override
  Widget build(BuildContext context) {
    final refreshController = vm.refreshControllers.last;
    bool isGrid =
        (vm.search?.layoutType == null || vm.search?.layoutType == "grid");
    //
    if (isGrid) {
      //gridview
      return CustomMasonryGridView(
        padding: EdgeInsets.symmetric(vertical: 10),
        refreshController: refreshController,
        canPullUp: true,
        canRefresh: true,
        onRefresh: vm.searchServices,
        onLoading: () => vm.searchServices(initial: false),
        // dataSet: widget.vm.services,
        mainAxisSpacing: 10,
        crossAxisSpacing: 10,
        isLoading: vm.busy(vm.services),
        childAspectRatio: (context.screenWidth / 2.5) / 80,
        emptyWidget: EmptySearch(type: "service"),
        items: [
          ...(vm.services.map((service) {
            return ServiceGridViewItem(
              service: service,
              onPressed: vm.servicePressed,
              // height: 80,
              // imgW: 60,
            );
          }).toList()),
        ],
        // separatorBuilder: (p0, p1) => UiSpacer.vSpace(0),
        // itemBuilder: (ctx, index) {
        //   final service = widget.vm.services[index];
        //   return ServiceGridViewItem(
        //     service: service,
        //     onPressed: widget.vm.servicePressed,
        //     // height: 80,
        //     // imgW: 60,
        //   );
        // },
      );
    }

    //listview
    return CustomListView(
      padding: EdgeInsets.symmetric(vertical: 10),
      refreshController: refreshController,
      canPullUp: true,
      canRefresh: true,
      onRefresh: vm.searchProducts,
      onLoading: () => vm.searchProducts(initial: false),
      dataSet: vm.services,
      isLoading: vm.busy(vm.services),
      emptyWidget: EmptySearch(type: "service"),
      itemBuilder: (ctx, index) {
        final service = vm.services[index];
        return ServiceListItem(
          service: service,
          onPressed: vm.servicePressed,
          height: 80,
          imgW: 60,
        );
      },
      separatorBuilder: (p0, p1) => 10.heightBox,
    );
  }
}
