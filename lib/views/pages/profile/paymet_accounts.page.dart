import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/view_models/payment_accounts.vm.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/custom_list_view.dart';
import 'package:fuodz/widgets/list_items/payment_account.list_item.dart';
import 'package:fuodz/widgets/states/payment_account.empty.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';

class PaymentAccountsPage extends StatelessWidget {
  const PaymentAccountsPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<PaymentAccountsViewModel>.reactive(
      viewModelBuilder: () => PaymentAccountsViewModel(context),
      onViewModelReady: (vm) => vm.initialise(),
      builder: (context, vm, child) {
        return BasePage(
          showAppBar: true,
          title: "Payment Accounts".tr(),
          showLeadingAction: true,
          body: CustomListView(
            //todo: add empty state
            refreshController: vm.refreshController,
            canPullUp: true,
            canRefresh: true,
            onLoading: () => vm.fetchPaymentAccounts(false),
            onRefresh: vm.fetchPaymentAccounts,
            isLoading: vm.busy(vm.paymentAccounts),
            dataSet: vm.paymentAccounts,
            padding: EdgeInsets.symmetric(vertical: 20),
            itemBuilder: (context, index) {
              final paymentAccount = vm.paymentAccounts[index];
              return PaymentAccountListItem(
                paymentAccount,
                onEditPressed: () => vm.editPaymentAccount(paymentAccount),
                onStatusPressed: () =>
                    vm.togglePaymentAccountStatus(paymentAccount),
              );
            },
            emptyWidget: EmptyPaymentAccount(),
          ),
          fab: FloatingActionButton(
            child: Icon(
              FlutterIcons.plus_ant,
              color: Colors.white,
            ),
            onPressed: vm.newPaymentAccount,
          ),
        );
      },
    );
  }
}
