import 'package:flutter/material.dart';
import 'package:flutter/widgets.dart';
import 'package:fuodz/models/category.dart';
import 'package:fuodz/models/service.dart';
import 'package:fuodz/models/vendor_type.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/service_search.vm.dart';
import 'package:fuodz/views/pages/search/widget/search.header.dart';
import 'package:fuodz/views/pages/search/widget/vendor_search_header.view.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/cards/custom.visibility.dart';
import 'package:fuodz/widgets/custom_list_view.dart';
import 'package:fuodz/widgets/custom_masonry_grid_view.dart';
import 'package:fuodz/widgets/list_items/grid_view_service.list_item.dart';
import 'package:fuodz/widgets/list_items/service.gridview_item.dart';
import 'package:fuodz/widgets/list_items/vendor.list_item.dart';
import 'package:fuodz/widgets/states/service_search.empty.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class ServiceSearchPage extends StatelessWidget {
  ServiceSearchPage({
    Key? key,
    this.category,
    this.vendorType,
    this.showCancel = true,
    this.showVendors = true,
    this.showServices = true,
    this.byLocation = true,
  }) : super(key: key) {}

  //
  final bool showCancel;
  final bool showVendors;
  final bool showServices;
  final bool byLocation;

  final Category? category;
  final VendorType? vendorType;
  //
  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<ServiceSearchViewModel>.reactive(
      viewModelBuilder: () => ServiceSearchViewModel(
        context,
        category: category,
        byLocation: byLocation,
        showServices: showServices,
        showVendors: showVendors,
        vendorType: vendorType,
      ),
      onViewModelReady: (model) => model.startSearch(),
      disposeViewModel: false,
      builder: (context, model, child) {
        return BasePage(
          showCartView: showCancel,
          body: SafeArea(
            bottom: false,
            child: VStack(
              [
                //header
                UiSpacer.verticalSpace(),
                SearchHeader(model, showCancel: showCancel),

                //if by location is enabled and results are empty, show a disclaimer
                Visibility(
                  visible: (model.byLocation ||
                          (model.search?.byLocation ?? false)) &&
                      model.searchResults.isEmpty &&
                      !model.isBusy,
                  child:
                      "Results are currently based on your location. You can disable this in the filter section."
                          .tr()
                          .text
                          .center
                          .gray500
                          .makeCentered()
                          .py(10),
                ),

                //tags
                Padding(
                  padding: EdgeInsets.symmetric(vertical: 12),
                  child: Visibility(
                    visible: showServices && showVendors,
                    child: VendorSearchHeaderview(
                      model,
                      showServices: showServices,
                      showProviders: showVendors,
                      padding: 0,
                    ),
                  ),
                ),

                //vendors listview
                CustomVisibilty(
                  visible: showVendors && model.selectTagId == 1,
                  child: CustomListView(
                    refreshController: model.refreshController,
                    canRefresh: true,
                    canPullUp: true,
                    onRefresh: model.startSearch,
                    onLoading: () => model.startSearch(initialLoaoding: false),
                    isLoading: model.isBusy,
                    dataSet: model.searchResults,
                    itemBuilder: (context, index) {
                      //
                      final searchResult = model.searchResults[index];
                      if (searchResult is Service) {
                        return GridViewServiceListItem(
                          service: searchResult,
                          onPressed: model.servicePressed,
                        );
                      } else {
                        return VendorListItem(
                          vendor: searchResult,
                          onPressed: model.vendorSelected,
                        );
                      }
                    },
                    separatorBuilder: (context, index) =>
                        UiSpacer.verticalSpace(space: 10),
                    emptyWidget: EmptyServiceSearch(),
                  ).expand(),
                ),

                //services related view
                CustomVisibilty(
                  visible: showServices && model.selectTagId != 1,
                  child: VStack(
                    [
                      //result listview
                      CustomVisibilty(
                        visible: !model.showGrid,
                        child: CustomListView(
                          refreshController: model.refreshController,
                          canRefresh: true,
                          canPullUp: true,
                          onRefresh: model.startSearch,
                          onLoading: () =>
                              model.startSearch(initialLoaoding: false),
                          isLoading: model.isBusy,
                          dataSet: model.searchResults,
                          itemBuilder: (context, index) {
                            //
                            final searchResult = model.searchResults[index];
                            if (searchResult is Service) {
                              return GridViewServiceListItem(
                                service: searchResult,
                                onPressed: model.servicePressed,
                              );
                            } else {
                              return VendorListItem(
                                vendor: searchResult,
                                onPressed: model.vendorSelected,
                              );
                            }
                          },
                          separatorBuilder: (ctx, no) => 10.heightBox,
                          emptyWidget: EmptyServiceSearch(),
                        ).expand(),
                      ),

                      //result gridview
                      CustomVisibilty(
                        visible: model.showGrid,
                        child: CustomMasonryGridView(
                          // noScrollPhysics: true,
                          refreshController: model.refreshController,
                          canRefresh: true,
                          canPullUp: true,
                          onRefresh: model.startSearch,
                          onLoading: () =>
                              model.startSearch(initialLoaoding: false),
                          isLoading: model.isBusy,
                          // itemCount: model.searchResults.length,
                          crossAxisSpacing: 10,
                          mainAxisSpacing: 10,
                          items: model.searchResults.map(
                            (searchResult) {
                              //
                              if (searchResult is Service) {
                                return ServiceGridViewItem(
                                  service: searchResult,
                                  onPressed: model.servicePressed,
                                );
                              } else {
                                return VendorListItem(
                                  vendor: searchResult,
                                  onPressed: model.vendorSelected,
                                );
                              }
                            },
                          ).toList(),
                          emptyWidget: EmptyServiceSearch(),
                        ).expand(),
                      ),
                    ],
                  ).expand(),
                ),
              ],
            ).pSymmetric(h: 12),
          ),
        );
      },
    );
  }
}
