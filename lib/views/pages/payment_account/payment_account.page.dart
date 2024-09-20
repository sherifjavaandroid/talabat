import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/view_models/payment_account.vm.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/custom_list_view.dart';
import 'package:fuodz/widgets/list_items/payment_account.list_item.dart';
import 'package:fuodz/widgets/states/payment_account.empty.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class PaymentAccountPage extends StatelessWidget {
  const PaymentAccountPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    //
    return ViewModelBuilder<PaymentAccountViewModel>.reactive(
      viewModelBuilder: () => PaymentAccountViewModel(context),
      onViewModelReady: (vm) => vm.initialise(),
      builder: (context, vm, child) {
        return BasePage(
          title: "Payment Accounts".tr(),
          showLeadingAction: true,
          showAppBar: true,
          fab: FloatingActionButton.extended(
            onPressed: vm.openNewPaymentAccount,
            label: "New".tr().text.white.make(),
            icon: Icon(
              FlutterIcons.plus_ant,
              color: Colors.white,
            ),
            backgroundColor: AppColor.primaryColor,
          ),
          body: VStack(
            [
              CustomListView(
                refreshController: vm.refreshController,
                canPullUp: true,
                canRefresh: true,
                isLoading: vm.busy(vm.paymentAccounts),
                onRefresh: vm.getPaymentAccounts,
                onLoading: () => vm.getPaymentAccounts(initialLoading: false),
                dataSet: vm.paymentAccounts,
                itemBuilder: (context, index) {
                  //
                  final paymentAccount = vm.paymentAccounts[index];
                  return PaymentAccountListItem(
                    paymentAccount,
                    onPressed: () => vm.editPaymentAccount(paymentAccount),
                  );
                },
                emptyWidget: EmptyPaymentAccount(),
              ).expand(),
            ],
          ).p20(),
        );
      },
    );
  }
}
