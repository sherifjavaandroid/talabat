import 'package:flutter/material.dart';
import 'package:fuodz/view_models/main_search.vm.dart';
import 'package:fuodz/widgets/custom_list_view.dart';
import 'package:fuodz/widgets/custom_masonry_grid_view.dart';
import 'package:fuodz/widgets/list_items/commerce_product.list_item.dart';
import 'package:fuodz/widgets/list_items/dynamic_product.list_item.dart';
import 'package:fuodz/widgets/states/search.empty.dart';
import 'package:velocity_x/velocity_x.dart';

class ProductSearchResultView extends StatefulWidget {
  ProductSearchResultView(this.vm, {Key? key}) : super(key: key);

  final MainSearchViewModel vm;
  @override
  State<ProductSearchResultView> createState() =>
      _ProductSearchResultViewState();
}

class _ProductSearchResultViewState extends State<ProductSearchResultView> {
  @override
  Widget build(BuildContext context) {
    final refreshController = widget.vm.refreshControllers[1];
    //
    return (widget.vm.search?.layoutType == null ||
            widget.vm.search?.layoutType == "grid")
        ? CustomMasonryGridView(
            padding: EdgeInsets.symmetric(vertical: 10),
            refreshController: refreshController,
            canPullUp: true,
            canRefresh: true,
            onRefresh: widget.vm.searchProducts,
            onLoading: () => widget.vm.searchProducts(initial: false),
            // mainAxisSpacing: 0,
            // crossAxisSpacing: 0,
            isLoading: widget.vm.busy(widget.vm.products),
            // childAspectRatio: (context.screenWidth / 2.5) / 100,
            mainAxisSpacing: 10,
            crossAxisSpacing: 10,
            childAspectRatio: (context.screenWidth / 2.5) / 100,
            emptyWidget: EmptySearch(type: "product"),
            items: [
              ...(widget.vm.products.map((product) {
                return CommerceProductListItem(
                  product,
                  height: 100,
                  // product: product,
                  // onPressed: widget.vm.productSelected,
                  // padding: EdgeInsets.zero,
                  // qtyUpdated: (product, value) {},
                  // h: 100,
                );
              }).toList()),
            ],
            // dataSet: widget.vm.products,
            // separatorBuilder: (p0, p1) => UiSpacer.vSpace(0),
            // itemBuilder: (ctx, index) {
            //   final product = widget.vm.products[index];
            //   return DynamicProductListItem(
            //     product,
            //     onPressed: widget.vm.productSelected,
            //     padding: EdgeInsets.zero,
            //     // h: 100,
            //   );
            // },
          )
        : CustomListView(
            padding: EdgeInsets.symmetric(vertical: 10),
            refreshController: refreshController,
            canPullUp: true,
            canRefresh: true,
            onRefresh: widget.vm.searchProducts,
            onLoading: () => widget.vm.searchProducts(initial: false),
            dataSet: widget.vm.products,
            isLoading: widget.vm.busy(widget.vm.products),
            emptyWidget: EmptySearch(type: "product"),
            itemBuilder: (ctx, index) {
              final product = widget.vm.products[index];
              return DynamicProductListItem(
                product,
                onPressed: widget.vm.productSelected,
                padding: EdgeInsets.zero,
              );
            },
            separatorBuilder: (p0, p1) => 12.heightBox,
          );
  }
}
