import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/profile.vm.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/menu_item.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class FinancePage extends StatelessWidget {
  const FinancePage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return BasePage(
      showAppBar: true,
      showLeadingAction: true,
      title: "Finance".tr(),
      body: ViewModelBuilder<ProfileViewModel>.reactive(
        viewModelBuilder: () => ProfileViewModel(context),
        onViewModelReady: (vm) => vm.initialise(),
        builder: (context, model, child) {
          return VStack(
            [
              //Earning
              MenuItem(
                title: "Earning".tr(),
                onPressed: model.showEarning,
                divider: true,
              ),
              //
              AppStrings.enableDriverWallet
                  ? MenuItem(
                      title: "Wallet".tr(),
                      onPressed: model.openWallet,
                    )
                  : UiSpacer.emptySpace(),
              //
              //
              MenuItem(
                title: "Payment Accounts".tr(),
                onPressed: model.openPaymentAccounts,
              ),
            ],
          ).p20().scrollVertical();
        },
      ),
    );
  }
}
