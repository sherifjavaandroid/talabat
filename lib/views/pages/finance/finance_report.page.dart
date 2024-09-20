import 'package:contained_tab_bar_view/contained_tab_bar_view.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/view_models/finance_report.vm.dart';
import 'package:fuodz/views/pages/finance/widgets/earnings_report.view.dart';
import 'package:fuodz/views/pages/finance/widgets/payouts_report.view.dart';
import 'package:fuodz/widgets/busy_indicator.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class FinanceReportPage extends StatelessWidget {
  const FinanceReportPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<FinanceReportViewModel>.reactive(
      viewModelBuilder: () => FinanceReportViewModel(),
      onViewModelReady: (vm) => vm.initialise(),
      builder: (context, vm, child) {
        return SafeArea(
          child: VStack(
            [
              //
              20.heightBox,
              HStack(
                [
                  "Report".tr().text.xl2.semiBold.make().expand(),
                  //export button
                  IconButton(
                    onPressed: () {
                      vm.exportReport(context);
                    },
                    icon: vm.busy(vm.exportReport)
                        ? BusyIndicator().wh(16, 16)
                        : Icon(
                            Icons.file_download,
                            color: context.textTheme.bodyMedium!.color,
                          ),
                  ),
                  //filter button
                  IconButton(
                    onPressed: () {
                      vm.showDateFilter(context);
                    },
                    icon: Icon(
                      Icons.filter_list,
                      color: context.textTheme.bodyMedium!.color,
                    ),
                  ),
                ],
              ).px20(),
              15.heightBox,
              //contained_tab_bar_view
              ContainedTabBarView(
                onChange: (index) {
                  vm.activeTabIndex = index;
                },
                tabBarProperties: TabBarProperties(
                  indicatorColor: AppColor.primaryColor,
                  labelColor: AppColor.accentColor,
                  unselectedLabelColor: context.textTheme.bodyMedium!.color,
                  labelStyle: context.bodyLarge,
                  unselectedLabelStyle: context.bodyMedium,
                ),
                tabs: [
                  //sales
                  Tab(text: "Payouts".tr()),
                  //earning
                  Tab(text: "Earnings".tr()),
                ],
                views: [
                  PayoutReportView(vm: vm),
                  EarningsReportView(vm: vm),
                ],
              ).expand(),
            ],
          ),
        );
      },
    );
  }
}
