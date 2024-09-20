import 'package:flutter/material.dart';
import 'package:flutter_staggered_animations/flutter_staggered_animations.dart';
import 'package:fuodz/constants/home_screen.config.dart';
import 'package:fuodz/services/navigation.service.dart';
import 'package:fuodz/view_models/welcome.vm.dart';
import 'package:fuodz/views/pages/vendor/widgets/banners.view.dart';
import 'package:fuodz/views/pages/vendor/widgets/featured_vendors.view.dart';
import 'package:fuodz/views/pages/welcome/widgets/welcome_header.section.dart';
import 'package:fuodz/widgets/cards/custom.visibility.dart';
import 'package:fuodz/widgets/finance/wallet_management.view.dart';
import 'package:fuodz/widgets/list_items/modern_vendor_type.vertical_list_item.dart';
import 'package:fuodz/widgets/states/loading.shimmer.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:masonry_grid/masonry_grid.dart';
import 'package:velocity_x/velocity_x.dart';

class ModernEmptyWelcome extends StatelessWidget {
  const ModernEmptyWelcome({
    required this.vm,
    Key? key,
  }) : super(key: key);

  final WelcomeViewModel vm;
  @override
  Widget build(BuildContext context) {
    return VStack(
      [
        //
        WelcomeHeaderSection(vm),
        VStack(
          [
            //finance section
            CustomVisibilty(
              visible: HomeScreenConfig.showWalletOnHomeScreen &&
                  vm.isAuthenticated(),
              child: WalletManagementView(),
            ),
            //top banner
            CustomVisibilty(
              visible: (HomeScreenConfig.showBannerOnHomeScreen &&
                  HomeScreenConfig.isBannerPositionTop),
              child: Banners(
                null,
                featured: true,
                padding: 0,
              ),
            ),
            //
            VStack(
              [
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
                      crossAxisSpacing: 15,
                      mainAxisSpacing: 15,
                      children: List.generate(
                        vm.vendorTypes.length,
                        (index) {
                          final vendorType = vm.vendorTypes[index];
                          return ModernVendorTypeVerticalListItem(
                            vendorType,
                            onPressed: () {
                              NavigationService.pageSelected(
                                vendorType,
                                context: context,
                              );
                            },
                          );
                        },
                      ),
                    ),
                  ),
                ),
              ],
            ).p20(),

            //botton banner
            CustomVisibilty(
              visible: HomeScreenConfig.showBannerOnHomeScreen &&
                  !HomeScreenConfig.isBannerPositionTop,
              child: Banners(
                null,
                featured: true,
              ).py12(),
            ),
            //featured vendors
            FeaturedVendorsView(
              title: "Featured Vendors".tr(),
              scrollDirection: Axis.horizontal,
              itemWidth: context.percentWidth * 48,
              listViewPadding: Vx.mSymmetric(h: 20),
              titlePadding: Vx.mSymmetric(h: 20, v: 6),
              onSeeAllPressed: () {
                vm.openFeaturedVendors();
              },
              onVendorSelected: (vendor) {
                NavigationService.openVendorDetailsPage(
                  vendor,
                  context: context,
                );
              },
            ),
            //spacing
            100.heightBox,
          ],
        )
            .scrollVertical()
            .box
            .color(context.theme.colorScheme.surface)
            .make()
            .expand(),
      ],
    );
  }
}
