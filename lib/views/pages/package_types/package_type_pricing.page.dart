import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/view_models/package_type_pricing.vm.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/custom_list_view.dart';
import 'package:fuodz/widgets/list_items/package_type_pricing.list_item.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:localize_and_translate/localize_and_translate.dart';

class PackagePricingPage extends StatelessWidget {
  const PackagePricingPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: ViewModelBuilder<PackageTypePricingViewModel>.reactive(
        viewModelBuilder: () => PackageTypePricingViewModel(context),
        onViewModelReady: (vm) => vm.initialise(),
        builder: (context, vm, child) {
          return BasePage(
            fab: FloatingActionButton.extended(
              backgroundColor: AppColor.primaryColor,
              onPressed: vm.newPackageTypePricing,
              label: "New Pricing".tr().text.white.make(),
              icon: Icon(
                FlutterIcons.plus_fea,
                color: Colors.white,
              ),
            ),
            body: VStack(
              [
                //
                "Package Type Pricing".tr().text.xl2.semiBold.make().p20(),
                //
                CustomListView(
                  refreshController: vm.refreshController,
                  canRefresh: true,
                  onRefresh: vm.fetchMyPricings,
                  isLoading: vm.isBusy,
                  dataSet: vm.packageTypePricings,
                  itemBuilder: (context, index) {
                    final packageTypePricing = vm.packageTypePricings[index];
                    return PackageTypePricingListItem(
                      packageTypePricing,
                      isLoading: vm.busy(packageTypePricing.id),
                      onEditPressed: vm.editPricing,
                      onToggleStatusPressed: vm.changePricingStatus,
                      onDeletePressed: vm.deletePricing,
                    );
                  },
                ).expand(),
              ],
            ).px20(),
          );
        },
      ),
    );
  }
}
