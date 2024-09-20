import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/enums/product_fetch_data_type.enum.dart';
import 'package:fuodz/models/vendor_type.dart';
import 'package:fuodz/view_models/grocery.vm.dart';
import 'package:fuodz/views/pages/flash_sale/widgets/flash_sale.view.dart';
import 'package:fuodz/views/pages/grocery/widgets/grocery_categories.view.dart';
import 'package:fuodz/views/pages/grocery/widgets/grocery_categories_products.view.dart';
import 'package:fuodz/views/pages/grocery/widgets/grocery_picks.view.dart';
import 'package:fuodz/views/pages/vendor/widgets/banners.view.dart';
import 'package:fuodz/views/pages/vendor/widgets/header.view.dart';
import 'package:fuodz/views/pages/vendor/widgets/nearby_vendors.view.dart';
import 'package:fuodz/views/shared/widgets/section_coupons.view.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/cards/view_all_vendors.view.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:pull_to_refresh_flutter3/pull_to_refresh_flutter3.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class GroceryPage extends StatefulWidget {
  const GroceryPage(this.vendorType, {Key? key}) : super(key: key);

  final VendorType vendorType;
  @override
  _GroceryPageState createState() => _GroceryPageState();
}

class _GroceryPageState extends State<GroceryPage>
    with AutomaticKeepAliveClientMixin<GroceryPage> {
  GlobalKey pageKey = GlobalKey<State>();
  @override
  Widget build(BuildContext context) {
    super.build(context);
    return ViewModelBuilder<GroceryViewModel>.reactive(
      viewModelBuilder: () => GroceryViewModel(context, widget.vendorType),
      onViewModelReady: (model) => model.initialise(),
      builder: (context, model, child) {
        return BasePage(
          showAppBar: true,
          showLeadingAction: !AppStrings.isSingleVendorMode,
          elevation: 0,
          title: "${widget.vendorType.name}",
          appBarColor: context.theme.colorScheme.surface,
          appBarItemColor: AppColor.primaryColor,
          showCart: true,
          key: model.pageKey,
          body: VStack(
            [
              //
              VendorHeader(
                model: model,
                onrefresh: model.reloadPage,
              ),

              SmartRefresher(
                enablePullDown: true,
                enablePullUp: false,
                controller: model.refreshController,
                onRefresh: model.reloadPage,
                child: VStack(
                  [
                    //banners
                    Banners(
                      widget.vendorType,
                      viewportFraction: 0.98,
                    ).px20(),
                    //categories
                    GroceryCategories(widget.vendorType),

                    //coupons
                    SectionCouponsView(
                      widget.vendorType,
                      title: "Coupons".tr(),
                      scrollDirection: Axis.horizontal,
                      itemWidth: context.percentWidth * 70,
                      height: 90,
                      itemsPadding: EdgeInsets.fromLTRB(10, 0, 10, 10),
                    ),

                    //flash sales products
                    FlashSaleView(widget.vendorType),

                    //today picks
                    GroceryProductsSectionView(
                      "Today Picks".tr() + " 🔥",
                      model.vendorType!,
                      showGrid: true,
                      type: ProductFetchDataType.RANDOM,
                    ),
                    //

                    //nearby
                    NearByVendors(widget.vendorType),

                    //top 5 categories products
                    GroceryCategoryProducts(widget.vendorType, length: 6),

                    //view cart and all vendors
                    ViewAllVendorsView(vendorType: widget.vendorType),
                  ],
                  spacing: 12,
                ).scrollVertical(),
              ).expand(),
            ],
            // key: model.pageKey,
          ),
        );
      },
    );
  }

  @override
  bool get wantKeepAlive => true;
}
