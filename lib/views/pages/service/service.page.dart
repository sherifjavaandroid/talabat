import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/models/vendor_type.dart';
import 'package:fuodz/view_models/service.vm.dart';
import 'package:fuodz/views/pages/service/widgets/categories_services.view.dart';
import 'package:fuodz/views/pages/service/widgets/popular_services.view.dart';
import 'package:fuodz/views/pages/vendor/widgets/complex_header.view.dart';
import 'package:fuodz/views/pages/vendor/widgets/simple_styled_banners.view.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/vendor_type_categories.view.dart';
import 'package:pull_to_refresh_flutter3/pull_to_refresh_flutter3.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

import 'widgets/top_service_vendors.view.dart';

class ServicePage extends StatefulWidget {
  const ServicePage(this.vendorType, {Key? key}) : super(key: key);

  final VendorType vendorType;
  @override
  _ServicePageState createState() => _ServicePageState();
}

class _ServicePageState extends State<ServicePage>
    with AutomaticKeepAliveClientMixin<ServicePage> {
  GlobalKey pageKey = GlobalKey<State>();
  @override
  Widget build(BuildContext context) {
    super.build(context);
    //

    return ViewModelBuilder<ServiceViewModel>.reactive(
      viewModelBuilder: () => ServiceViewModel(context, widget.vendorType),
      onViewModelReady: (model) => model.initialise(),
      builder: (context, model, child) {
        return BasePage(
          showAppBar: true,
          showLeadingAction: !AppStrings.isSingleVendorMode,
          elevation: 0,
          title: "${widget.vendorType.name}",
          // appBarColor: widget.vendorType.hasBanners
          //     ? Colors.transparent
          //     : context.theme.colorScheme.surface,
          // appBarItemColor: AppColor.primaryColor,
          showCart: false,
          key: model.pageKey,
          body: SmartRefresher(
            controller: model.refreshController,
            onRefresh: model.reloadPage,
            child: VStack(
              [
                //
                10.heightBox,
                ComplexVendorHeader(
                  model: model,
                  searchShowType: 5,
                  onrefresh: model.reloadPage,
                  onSearchPressed: model.openSearch,
                )
                    .box
                    .color(context.theme.colorScheme.surface)
                    .roundedSM
                    .outerShadowSm
                    .make()
                    .px(10),
                VStack(
                  [
                    //
                    SimpleStyledBanners(
                      widget.vendorType,
                      height: AppStrings.bannerHeight,
                      withPadding: false,
                      viewportFraction: 0.92,
                      hideEmpty: true,
                    ),

                    //categories
                    VendorTypeCategories(
                      widget.vendorType,
                      title: "Categories",
                      childAspectRatio: 1.4,
                      crossAxisCount: 4,
                    ),

                    //top services
                    PopularServicesView(widget.vendorType),
                    //
                    //top vendors
                    TopServiceVendors(widget.vendorType),

                    //services by top ~5 categories
                    CategoriesServicesView(
                      widget.vendorType,
                      showTitle: true,
                      maxCategories: 5,
                    ),
                    //
                    20.heightBox,
                  ],
                  spacing: 12,
                ).scrollVertical().expand(),
              ],
            ),
          ),
        );
      },
    );
  }

  @override
  bool get wantKeepAlive => true;
}
