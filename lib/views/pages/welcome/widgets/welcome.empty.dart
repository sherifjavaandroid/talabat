import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:flutter_staggered_animations/flutter_staggered_animations.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/constants/home_screen.config.dart';
import 'package:fuodz/constants/sizes.dart';
import 'package:fuodz/models/search.dart';
import 'package:fuodz/services/navigation.service.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/welcome.vm.dart';
import 'package:fuodz/views/pages/vendor/widgets/banners.view.dart';
import 'package:fuodz/views/pages/vendor/widgets/section_vendors.view.dart';
import 'package:fuodz/widgets/cards/custom.visibility.dart';
import 'package:fuodz/widgets/cards/welcome_intro.view.dart';
import 'package:fuodz/widgets/custom_list_view.dart';
import 'package:fuodz/widgets/finance/wallet_management.view.dart';
import 'package:fuodz/widgets/list_items/vendor_type.list_item.dart';
import 'package:fuodz/widgets/list_items/vendor_type.vertical_list_item.dart';
import 'package:fuodz/widgets/states/loading.shimmer.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:masonry_grid/masonry_grid.dart';
import 'package:velocity_x/velocity_x.dart';

class EmptyWelcome extends StatelessWidget {
  const EmptyWelcome({
    required this.vm,
    Key? key,
  }) : super(key: key);

  final WelcomeViewModel vm;
  @override
  Widget build(BuildContext context) {
    return VStack(
      [
        //
        SafeArea(
          child: WelcomeIntroView(vm),
        ),

        //
        VStack(
          [
            //finance section
            CustomVisibilty(
              visible: HomeScreenConfig.showWalletOnHomeScreen,
              child: WalletManagementView(),
            ),
            //
            //top banner
            CustomVisibilty(
              visible: HomeScreenConfig.showBannerOnHomeScreen &&
                  HomeScreenConfig.isBannerPositionTop,
              child: VStack(
                [
                  Banners(
                    null,
                    featured: true,
                    padding: 0,
                    // ).py12(),
                  ).py(0),
                ],
              ),
            ),
            //
            VStack(
              [
                HStack(
                  [
                    "I want to:".tr().text.xl.medium.make().expand(),
                    CustomVisibilty(
                      visible: HomeScreenConfig.isVendorTypeListingBoth,
                      child: Icon(
                        vm.showGrid
                            ? FlutterIcons.grid_fea
                            : FlutterIcons.list_fea,
                      ).p2().onInkTap(
                        () {
                          vm.showGrid = !vm.showGrid;
                          vm.notifyListeners();
                        },
                      ),
                    ),
                  ],
                  crossAlignment: CrossAxisAlignment.center,
                ),
                UiSpacer.vSpace(12),
                //list view
                CustomVisibilty(
                  visible: (HomeScreenConfig.isVendorTypeListingBoth &&
                          !vm.showGrid) ||
                      (!HomeScreenConfig.isVendorTypeListingBoth &&
                          HomeScreenConfig.isVendorTypeListingListView),
                  child: CustomListView(
                    noScrollPhysics: true,
                    dataSet: vm.vendorTypes,
                    isLoading: vm.isBusy,
                    loadingWidget: LoadingShimmer().px20(),
                    padding: EdgeInsets.zero,
                    itemBuilder: (context, index) {
                      final vendorType = vm.vendorTypes[index];
                      return VendorTypeListItem(
                        vendorType,
                        onPressed: () {
                          NavigationService.pageSelected(vendorType,
                              context: context);
                        },
                      );
                    },
                    separatorBuilder: (context, index) => UiSpacer.emptySpace(),
                  ),
                ),
                //gridview
                CustomVisibilty(
                  visible: HomeScreenConfig.isVendorTypeListingGridView &&
                      vm.showGrid &&
                      vm.isBusy,
                  child: LoadingShimmer().px20().centered(),
                ),
                CustomVisibilty(
                  visible: HomeScreenConfig.isVendorTypeListingGridView &&
                      vm.showGrid &&
                      !vm.isBusy,
                  child: AnimationLimiter(
                    child: MasonryGrid(
                      column: HomeScreenConfig.vendorTypePerRow,
                      crossAxisSpacing: 10,
                      mainAxisSpacing: 10,
                      children: List.generate(
                        vm.vendorTypes.length,
                        (index) {
                          final vendorType = vm.vendorTypes[index];
                          return VendorTypeVerticalListItem(
                            vendorType,
                            onPressed: () {
                              NavigationService.pageSelected(vendorType,
                                  context: context);
                            },
                          );
                        },
                      ),
                    ),
                  ),
                ),
              ],
            ).px20(),

            //botton banner
            CustomVisibilty(
              visible: HomeScreenConfig.showBannerOnHomeScreen &&
                  !HomeScreenConfig.isBannerPositionTop,
              child: Banners(
                null,
                featured: true,
                // ).py(),
              ).py(0),
            ),

            //featured vendors
            SectionVendorsView(
              null,
              title: "Featured Vendors".tr(),
              titlePadding: EdgeInsets.symmetric(horizontal: 20, vertical: 12),
              itemsPadding: EdgeInsets.symmetric(horizontal: 20),
              scrollDirection: Axis.horizontal,
              type: SearchFilterType.featured,
              itemWidth: context.percentWidth * 48,
              byLocation: AppStrings.enableFatchByLocation,
              hideEmpty: true,
            ),
            //spacing
            40.heightBox,
          ],
          spacing: 6,
        )
            .box
            .color(context.theme.colorScheme.surface)
            .topRounded(value: Sizes.radiusDefault)
            .make(),
      ],
      spacing: 15,
    ).box.color(AppColor.primaryColor).make().scrollVertical();
  }
}
