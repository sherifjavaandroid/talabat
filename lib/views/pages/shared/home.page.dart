import 'dart:io';
import 'package:double_back_to_close/double_back_to_close.dart';
import 'package:eva_icons_flutter/eva_icons_flutter.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_upgrade_settings.dart';
import 'package:fuodz/services/auth.service.dart';
import 'package:fuodz/utils/color_utils.dart';
import 'package:fuodz/views/pages/finance/finance_report.page.dart';
import 'package:fuodz/views/pages/order/assigned_orders.page.dart';
import 'package:fuodz/view_models/home.vm.dart';
import 'package:fuodz/views/pages/order/orders.page.dart';
import 'package:fuodz/views/pages/profile/profile.page.dart';
import 'package:fuodz/views/pages/taxi/taxi_order.page.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:upgrader/upgrader.dart';
import 'package:velocity_x/velocity_x.dart';

class HomePage extends StatelessWidget {
  HomePage({
    Key? key,
  }) : super(key: key);

  //
  @override
  Widget build(BuildContext context) {
    //
    bool isTaxiDriver = AuthServices.currentUser!.isTaxiDriver;
    //
    return DoubleBack(
      message: "Press back again to close".tr(),
      child: ViewModelBuilder<HomeViewModel>.reactive(
        viewModelBuilder: () => HomeViewModel(context),
        onViewModelReady: (model) => model.initialise(),
        builder: (context, model, child) {
          return BasePage(
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
                physics: NeverScrollableScrollPhysics(),
                children: [
                  //
                  if (AuthServices.currentUser!.isTaxiDriver)
                    //taxi driver view
                    TaxiOrderPage(),
                  if (!AuthServices.currentUser!.isTaxiDriver)
                    //regular driver view
                    AssignedOrdersPage(),

                  //
                  OrdersPage(),
                  //show report page
                  FinanceReportPage(),
                  //
                  ProfilePage(),
                ],
              ),
            ),
            bottomNavigationBar: Theme(
              data: ThemeData(
                //
                canvasColor: ColorUtils.shuffleColorByMode(
                  context,
                  lightMode: Colors.white,
                  darkMode: Colors.grey.shade800,
                ),
              ),
              child: BottomNavigationBar(
                elevation: 5,
                selectedItemColor: context.primaryColor,
                unselectedItemColor: ColorUtils.shuffleColorByMode(
                  context,
                  lightMode: Colors.black,
                  darkMode: Colors.grey.shade500,
                ),
                showSelectedLabels: true,
                showUnselectedLabels: true,
                currentIndex: model.currentIndex,
                onTap: model.onTabChange,
                items: [
                  BottomNavigationBarItem(
                    icon: Icon(
                      EvaIcons.homeOutline,
                    ),
                    activeIcon: Icon(
                      EvaIcons.home,
                    ),
                    label: 'Home'.tr(),
                  ),
                  BottomNavigationBarItem(
                    icon: Icon(
                      isTaxiDriver
                          ? EvaIcons.pinOutline
                          : EvaIcons.archiveOutline,
                    ),
                    activeIcon: Icon(
                      isTaxiDriver ? EvaIcons.pin : EvaIcons.archive,
                    ),
                    label: isTaxiDriver ? 'Rides'.tr() : "Orders".tr(),
                  ),
                  BottomNavigationBarItem(
                    icon: Icon(
                      EvaIcons.barChart2Outline,
                    ),
                    activeIcon: Icon(
                      EvaIcons.barChart2,
                    ),
                    label: 'Report'.tr(),
                  ),
                  BottomNavigationBarItem(
                    icon: Icon(
                      EvaIcons.menu2Outline,
                    ),
                    activeIcon: Icon(
                      EvaIcons.menu2,
                    ),
                    label: 'More'.tr(),
                  ),
                ],
              ),
            ),
          );
        },
      ),
    );
  }
}
