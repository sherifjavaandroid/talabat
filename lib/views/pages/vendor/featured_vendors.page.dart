import 'package:flutter/material.dart';
import 'package:fuodz/view_models/vendor/featured_vendors.vm.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/custom_list_view.dart';
import 'package:fuodz/widgets/list_items/featured_vendor.list_item.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class FeaturedVendorsPage extends StatelessWidget {
  const FeaturedVendorsPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<FeaturedVendorsPageViewModel>.reactive(
      viewModelBuilder: () => FeaturedVendorsPageViewModel(context),
      onViewModelReady: (vm) => vm.initialise(),
      builder: (context, vm, child) {
        return BasePage(
          showAppBar: true,
          title: "Featured Vendors".tr(),
          showLeadingAction: true,
          body: CustomListView(
            isLoading: vm.isBusy,
            refreshController: vm.refreshController,
            canPullUp: true,
            canRefresh: true,
            onRefresh: vm.fetchFeaturedVendors,
            onLoading: () => vm.fetchFeaturedVendors(false),
            padding: EdgeInsets.all(12),
            dataSet: vm.vendors,
            itemBuilder: (context, index) {
              final vendor = vm.vendors[index];
              return FeaturedVendorListItem(
                vendor: vendor,
                onPressed: vm.vendorSelected,
              );
            },
            separatorBuilder: (p0, p1) => 6.heightBox,
          ),
        );
      },
    );
  }
}
