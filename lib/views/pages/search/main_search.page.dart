import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/main_search.vm.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/states/loading_indicator.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:contained_tab_bar_view/contained_tab_bar_view.dart';

import 'widget/product_search_result.view.dart';
import 'widget/search.header.dart';
import 'widget/service_search_result.view.dart';
import 'widget/vendor_search_result.view.dart';

class MainSearchPage extends StatelessWidget {
  const MainSearchPage({
    Key? key,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<MainSearchViewModel>.reactive(
      viewModelBuilder: () => MainSearchViewModel(context),
      onViewModelReady: (vm) => vm.initialise(),
      disposeViewModel: false,
      builder: (context, vm, child) {
        return BasePage(
          body: SafeArea(
            bottom: false,
            child: VStack(
              [
                //header
                UiSpacer.verticalSpace(),
                SearchHeader(vm, showCancel: false),
                //if by location is enabled and results are empty, show a disclaimer
                Visibility(
                  visible: (vm.search?.byLocation ?? true) &&
                      vm.searchResults.isEmpty &&
                      !vm.isBusy,
                  child:
                      "Results are currently based on your location. You can disable this in the filter section."
                          .tr()
                          .text
                          .center
                          .gray500
                          .makeCentered()
                          .py(10),
                ),
                //tab-
                LoadingIndicator(
                  loading: vm.isBusy,
                  child: ContainedTabBarView(
                    callOnChangeWhileIndexIsChanging: true,
                    tabBarProperties: TabBarProperties(
                      alignment: TabBarAlignment.start,
                      isScrollable: true,
                      labelPadding: EdgeInsets.symmetric(
                        horizontal: 10,
                        vertical: 0,
                      ),

                      //
                      // padding: EdgeInsets.all(0),
                      labelColor: AppColor.primaryColor,
                      unselectedLabelColor: AppColor.primaryColor,
                      labelStyle: context.textTheme.bodyLarge!.copyWith(
                        fontWeight: FontWeight.bold,
                      ),
                      // unselectedLabelStyle:
                      //     context.textTheme.bodyLarge!.copyWith(),
                    ),
                    tabs: [
                      //
                      if (vm.showVendors)
                        Tab(child: "Vendors".tr().text.make()),
                      //
                      if (vm.showProducts)
                        Tab(child: "Products".tr().text.make()),
                      //
                      if (vm.showServices ?? false)
                        Tab(child: "Services".tr().text.make()),
                    ],
                    views: [
                      if (vm.showVendors) VendorSearchResultView(vm),
                      //
                      if (vm.showProducts) ProductSearchResultView(vm),
                      //
                      if (vm.showServices ?? false) ServiceSearchResultView(vm),
                    ],
                    onChange: vm.onTabChange,
                  ).expand(),
                ),
              ],
            ).px(16),
          ),
        );
      },
    );
  }
}
