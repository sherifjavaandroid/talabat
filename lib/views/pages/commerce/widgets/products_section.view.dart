import 'dart:io';

import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/enums/product_fetch_data_type.enum.dart';
import 'package:fuodz/models/category.dart';
import 'package:fuodz/models/search.dart';
import 'package:fuodz/models/vendor_type.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/products.vm.dart';
import 'package:fuodz/views/pages/search/search.page.dart';
import 'package:fuodz/widgets/cards/custom.visibility.dart';
import 'package:fuodz/widgets/custom_list_view.dart';
import 'package:fuodz/widgets/custom_masonry_grid_view.dart';
import 'package:fuodz/widgets/list_items/commerce_product.list_item.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:fuodz/extensions/context.dart';

class ProductsSectionView extends StatelessWidget {
  const ProductsSectionView(
    this.title, {
    this.vendorType,
    this.category,
    this.type = ProductFetchDataType.RANDOM,
    this.showGrid = true,
    this.crossAxisCount,
    this.scrollDirection,
    this.itemBottomPadding,
    this.itemHeight,
    this.titleCapitalize = true,
    Key? key,
  }) : super(key: key);

  final String title;
  final VendorType? vendorType;
  final ProductFetchDataType type;
  final Category? category;
  final bool showGrid;
  final int? crossAxisCount;
  final Axis? scrollDirection;
  final double? itemBottomPadding;
  final double? itemHeight;
  final bool titleCapitalize;

  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<ProductsViewModel>.reactive(
      viewModelBuilder: () => ProductsViewModel(
        context,
        vendorType,
        type,
        categoryId: category?.id,
      ),
      onViewModelReady: (model) => model.initialise(),
      builder: (context, model, child) {
        return CustomVisibilty(
          visible: !model.isBusy && model.products.isNotEmpty,
          child: VStack(
            [
              //
              HStack(
                [
                  (!titleCapitalize ? "$title" : "$title".toUpperCase())
                      .text
                      .semiBold
                      .xl
                      .make()
                      .expand(),
                  UiSpacer.horizontalSpace(),
                  HStack(
                    [
                      "See all"
                          .tr()
                          .text
                          .lg
                          .medium
                          .color(AppColor.primaryColor)
                          .make(),
                      // UiSpacer.smHorizontalSpace(),
                      // Icon(
                      //   Utils.isArabic
                      //       ? FlutterIcons.arrow_left_evi
                      //       : FlutterIcons.arrow_right_evi,
                      // ),
                    ],
                  ).onInkTap(() => openSearchPage(context)),
                ],
              )
                  // .box
                  // .p12
                  // .color(context.theme.colorScheme.surface)
                  // .outerShadowSm
                  // .roundedSM
                  // .make()
                  .wFull(context),
              UiSpacer.vSpace(10),
              CustomVisibilty(
                visible: !showGrid,
                child: CustomListView(
                  isLoading: model.isBusy,
                  dataSet: model.products,
                  scrollDirection: scrollDirection ?? Axis.horizontal,
                  itemBuilder: (context, index) {
                    final product = model.products[index];
                    return FittedBox(
                      child: CommerceProductListItem(
                        product,
                        height: 80,
                      )
                          .w(context.percentWidth * 30)
                          .pOnly(bottom: itemBottomPadding ?? 0),
                    );
                  },
                ).h(itemHeight ?? (Platform.isAndroid ? 160 : 190)),
              ),
              CustomVisibilty(
                visible: showGrid,
                child: CustomMasonryGridView(
                  crossAxisSpacing: 15,
                  mainAxisSpacing: 15,
                  crossAxisCount: crossAxisCount ?? 2,
                  isLoading: model.isBusy,
                  items: List.generate(
                    model.products.length,
                    (index) {
                      //
                      final product = model.products[index];
                      return CommerceProductListItem(
                        product,
                        height: 120,
                        boxFit: BoxFit.cover,
                      );
                    },
                  ),
                ),
              ),
            ],
          ).py12(),
        );
      },
    );
  }

  //
  openSearchPage(BuildContext context) {
    //
    final search = Search(
      type: type.name,
      category: category,
      vendorType: vendorType,
      showType: 2,
    );
    //open search
    context.push(
      (context) => SearchPage(
        search: search,
      ),
    );
  }
}
