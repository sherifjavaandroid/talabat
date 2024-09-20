import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/enums/product_fetch_data_type.enum.dart';
import 'package:fuodz/models/search.dart';
import 'package:fuodz/models/vendor_type.dart';
import 'package:fuodz/services/navigation.service.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/commerce.vm.dart';
import 'package:fuodz/views/pages/commerce/widgets/commerce_categories_products.view.dart';
import 'package:fuodz/views/pages/commerce/widgets/products_section.view.dart';
import 'package:fuodz/views/pages/flash_sale/widgets/flash_sale.view.dart';
import 'package:fuodz/views/pages/vendor/widgets/banners.view.dart';
import 'package:fuodz/views/pages/vendor/widgets/header.view.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/inputs/search_bar.input.dart';
import 'package:fuodz/widgets/vendor_type_categories.view.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:pull_to_refresh_flutter3/pull_to_refresh_flutter3.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class CommercePage extends StatefulWidget {
  const CommercePage(this.vendorType, {Key? key}) : super(key: key);

  final VendorType vendorType;
  @override
  _CommercePageState createState() => _CommercePageState();
}

class _CommercePageState extends State<CommercePage>
    with AutomaticKeepAliveClientMixin<CommercePage> {
  @override
  bool get wantKeepAlive => true;

  GlobalKey pageKey = GlobalKey<State>();
  @override
  Widget build(BuildContext context) {
    super.build(context);

    String pageTitle = "";
    if (!AppStrings.isSingleVendorMode) {
      pageTitle = "${widget.vendorType.name}";
    }

    return ViewModelBuilder<CommerceViewModel>.reactive(
        viewModelBuilder: () => CommerceViewModel(context, widget.vendorType),
        onViewModelReady: (model) => model.initialise(),
        builder: (context, model, child) {
          return BasePage(
            showAppBar: true,
            showLeadingAction: !AppStrings.isSingleVendorMode,
            elevation: 0,
            title: pageTitle,
            appBarColor: context.theme.colorScheme.surface,
            appBarItemColor: AppColor.primaryColor,
            showCart: true,
            backgroundColor: AppColor.faintBgColor,
            key: model.pageKey,
            body: VStack(
              [
                //location setion
                VendorHeader(
                  model: model,
                  showSearch: false,
                  onrefresh: model.reloadPage,
                  bottomPadding: false,
                ),

                //
                SmartRefresher(
                  enablePullDown: true,
                  enablePullUp: false,
                  controller: model.refreshController,
                  onRefresh: model.reloadPage,
                  child: VStack(
                    [
                      //search bar
                      SearchBarInput(
                        showFilter: false,
                        onTap: () => showSearchPage(context),
                      ).px(20).py(10),

                      VStack(
                        [
                          //intro
                          "Discover".tr().text.xl4.semiBold.make(),
                          "Find anything that you want"
                              .tr()
                              .text
                              .lg
                              .thin
                              .make(),
                          UiSpacer.verticalSpace(),

                          //banners
                          Banners(
                            widget.vendorType,
                            viewportFraction: 1.0,
                            itemRadius: 10,
                          ),
                          //categories
                          VendorTypeCategories(
                            widget.vendorType,
                            // showTitle: false,
                            title: "Categories".tr(),
                            childAspectRatio: 1.4,
                            crossAxisCount: AppStrings.categoryPerRow,
                            headerPadding: EdgeInsets.symmetric(horizontal: 0),
                            listPadding: EdgeInsets.symmetric(horizontal: 0),
                          ),
                        ],
                      ).px20(),
                      //flash sales products
                      FlashSaleView(widget.vendorType),

                      VStack(
                        [
                          //Best sellers
                          ProductsSectionView(
                            "Best Selling".tr(),
                            titleCapitalize: false,
                            vendorType: widget.vendorType,
                            type: ProductFetchDataType.BEST,
                            scrollDirection: Axis.horizontal,
                            showGrid: false,
                            itemBottomPadding: 5,
                          ),
                          10.heightBox,
                          //new arrivals
                          ProductsSectionView(
                            "New Arrivals".tr(),
                            titleCapitalize: false,
                            vendorType: widget.vendorType,
                            type: ProductFetchDataType.NEW,
                          ),
                          10.heightBox,
                          //top 7 categories products
                          CommerceCategoryProducts(
                            widget.vendorType,
                            length: 5,
                          ),
                        ],
                      ).px20(),
                    ],
                    // key: model.pageKey,
                    spacing: 10,
                  ).scrollVertical(),
                ).expand(),
              ],
            ),
          );
        });
  }

  //open search page
  showSearchPage(BuildContext context) {
    final search = Search(
      showType: 4,
      vendorType: widget.vendorType,
    );
    //
    final page = NavigationService().searchPageWidget(search);
    Navigator.of(context).push(
      MaterialPageRoute(builder: (context) => page),
    );
  }
}
