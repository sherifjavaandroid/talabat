import 'dart:io';
import 'package:double_back_to_close/double_back_to_close.dart';
import 'package:eva_icons_flutter/eva_icons_flutter.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_upgrade_settings.dart';
import 'package:fuodz/services/auth.service.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:fuodz/views/pages/finance/vendor_finance_report.page.dart';
import 'package:fuodz/views/pages/profile/profile.page.dart';
import 'package:fuodz/view_models/home.vm.dart';
import 'package:fuodz/views/pages/vendor/vendor_details.page.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:google_nav_bar/google_nav_bar.dart';
import 'package:stacked/stacked.dart';
import 'package:upgrader/upgrader.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:localize_and_translate/localize_and_translate.dart';

import 'order/orders.page.dart';

class HomePage extends StatefulWidget {
  HomePage({
    Key? key,
  }) : super(key: key);

  @override
  _HomePageState createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  @override
  Widget build(BuildContext context) {
    //
    bool canViewReport = AuthServices.currentUser?.allPermissions
            .contains("view_report_on_app") ??
        true;
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
                  OrdersPage(),
                  //
                  Utils.vendorSectionPage(model.currentVendor),
                  VendorDetailsPage(),
                  //
                  if (canViewReport)
                    //show report page
                    VendorFinanceReportPage(),

                  //
                  ProfilePage(),
                ],
              ),
            ),
            bottomNavigationBar: VxBox(
              child: SafeArea(
                child: GNav(
                  gap: 2,
                  activeColor: Theme.of(context).primaryColor,
                  color: Theme.of(context).textTheme.bodyLarge!.color,
                  iconSize: 22,
                  padding: EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                  duration: Duration(seconds: 2),
                  curve: Curves.bounceInOut,
                  tabBackgroundColor: Colors.transparent,
                  style: GnavStyle.oldSchool,
                  textSize: 13,
                  tabBorderRadius: 0,
                  mainAxisAlignment: MainAxisAlignment.spaceAround,
                  tabs: [
                    GButton(
                      icon: EvaIcons.inboxOutline,
                      text: 'Orders'.tr(),
                    ),
                    GButton(
                      icon: Utils.vendorIconIndicator(model.currentVendor),
                      text: Utils.vendorTypeIndicator(model.currentVendor).tr(),
                    ),
                    GButton(
                      icon: EvaIcons.shoppingBagOutline,
                      text: 'Vendor'.tr(),
                    ),

                    //show report page
                    if (canViewReport)
                      GButton(
                        icon: EvaIcons.pieChartOutline,
                        text: 'Report'.tr(),
                      ),

                    //
                    GButton(
                      icon: EvaIcons.menu,
                      text: 'More'.tr(),
                    ),
                  ],
                  selectedIndex: model.currentIndex,
                  onTabChange: model.onTabChange,
                ),
              ),
            )
                // .p16
                .p12
                .shadow
                .color(Theme.of(context).bottomSheetTheme.backgroundColor!)
                .make(),
          );
        },
      ),
    );
  }
}
