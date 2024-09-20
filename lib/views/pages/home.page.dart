import 'dart:io';

import 'package:animated_bottom_navigation_bar/animated_bottom_navigation_bar.dart';
import 'package:double_back_to_close/double_back_to_close.dart';
import 'package:eva_icons_flutter/eva_icons_flutter.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/constants/app_ui_settings.dart';
import 'package:fuodz/constants/app_upgrade_settings.dart';
import 'package:fuodz/services/location.service.dart';
import 'package:fuodz/views/pages/profile/profile.page.dart';
import 'package:fuodz/view_models/home.vm.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:upgrader/upgrader.dart';
import 'package:velocity_x/velocity_x.dart';

import 'order/orders.page.dart';
import 'search/main_search.page.dart';
import 'welcome/widgets/cart.fab.dart';

class HomePage extends StatefulWidget {
  HomePage({
    Key? key,
  }) : super(key: key);

  @override
  _HomePageState createState() => _HomePageState();
}

class _HomePageState extends State<HomePage>
    with AutomaticKeepAliveClientMixin<HomePage> {
  @override
  bool get wantKeepAlive => true;
  late HomeViewModel vm;
  @override
  void initState() {
    super.initState();
    vm = HomeViewModel(context);
    WidgetsBinding.instance.addPostFrameCallback(
      (_) {
        LocationService.prepareLocationListener(true);
        vm.initialise();
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    super.build(context);
    return DoubleBack(
      message: "Press back again to close".tr(),
      child: ViewModelBuilder<HomeViewModel>.reactive(
        viewModelBuilder: () => vm,
        builder: (context, model, child) {
          return BasePage(
            extendBodyBehindAppBar: true,
            body: UpgradeAlert(
              upgrader: Upgrader(
                showIgnore: !AppUpgradeSettings.forceUpgrade(),
                shouldPopScope: () => !AppUpgradeSettings.forceUpgrade(),
                dialogStyle: Platform.isIOS
                    ? UpgradeDialogStyle.cupertino
                    : UpgradeDialogStyle.material,
              ),
              child: PageView(
                controller: model.pageViewController,
                onPageChanged: model.onPageChanged,
                //disable swipe
                physics: NeverScrollableScrollPhysics(),
                children: [
                  model.homeView,
                  OrdersPage(),
                  MainSearchPage(),
                  ProfilePage(),
                ],
              ),
            ),
            fab: AppUISettings.showCart ? CartHomeFab(model) : null,
            fabLocation: AppUISettings.showCart
                ? FloatingActionButtonLocation.centerDocked
                : null,
            bottomNavigationBar: AnimatedBottomNavigationBar.builder(
              itemCount: 4,
              backgroundColor: Theme.of(context).colorScheme.surface,
              blurEffect: true,
              shadow: BoxShadow(
                color: Theme.of(context).colorScheme.primary.withOpacity(0.3),
                blurRadius: 10,
              ),
              activeIndex: model.currentIndex,
              onTap: model.onTabChange,
              gapLocation: GapLocation.center,
              notchSmoothness: NotchSmoothness.defaultEdge,
              leftCornerRadius: 0,
              rightCornerRadius: 0,
              splashSpeedInMilliseconds: 10,
              tabBuilder: (int index, bool isActive) {
                final color = isActive
                    ? AppColor.primaryColor
                    : Theme.of(context).textTheme.bodyLarge?.color;
                List<String> titles = [
                  "Home".tr(),
                  "Orders".tr(),
                  "Search".tr(),
                  "Menu".tr(),
                ];
                List<IconData> icons = [
                  EvaIcons.homeOutline,
                  EvaIcons.inboxOutline,
                  EvaIcons.searchOutline,
                  EvaIcons.menu2Outline,
                ];
                //filled icons
                List<IconData> filledIcons = [
                  EvaIcons.home,
                  EvaIcons.inbox,
                  EvaIcons.search,
                  EvaIcons.menu2,
                ];

                Widget tab = Column(
                  mainAxisSize: MainAxisSize.min,
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(
                      isActive ? filledIcons[index] : icons[index],
                      size: 22,
                      color: color,
                    ),
                    Padding(
                      padding: const EdgeInsets.all(0.5),
                      child: titles[index]
                          .text
                          .scale(0.89)
                          .fontWeight(
                            isActive ? FontWeight.bold : FontWeight.normal,
                          )
                          .color(color)
                          .make(),
                    ),
                  ],
                );

                //
                return tab;
              },
            ),
            // child: SafeArea(
            //   child: GNav(
            //     gap: 8,
            //     activeColor: Colors.white,
            //     color: Theme.of(context).textTheme.bodyLarge?.color,
            //     iconSize: 20,
            //     padding: EdgeInsets.symmetric(horizontal: 10, vertical: 5),
            //     duration: Duration(milliseconds: 250),
            //     tabBackgroundColor: Theme.of(context).colorScheme.secondary,
            //     tabs: [
            //       GButton(
            //         icon: FlutterIcons.home_ant,
            //         text: 'Home'.tr(),
            //       ),
            //       GButton(
            //         icon: FlutterIcons.inbox_ant,
            //         text: 'Orders'.tr(),
            //       ),
            //       GButton(
            //         icon: FlutterIcons.search_fea,
            //         text: 'Search'.tr(),
            //       ),
            //       GButton(
            //         icon: FlutterIcons.menu_fea,
            //         text: 'More'.tr(),
            //       ),
            //     ],
            //     selectedIndex: model.currentIndex,
            //     onTabChange: model.onTabChange,
            //   ),
            // ),
          );
        },
      ),
    );
  }
}
