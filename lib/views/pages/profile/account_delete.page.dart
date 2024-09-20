import 'package:flutter/material.dart';
import 'package:flutter_form_builder/flutter_form_builder.dart';
import 'package:fuodz/services/custom_form_builder_validator.service.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:fuodz/view_models/account_delete.vm.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class AccountDeletePage extends StatelessWidget {
  const AccountDeletePage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<AccountDeleteViewModel>.reactive(
      viewModelBuilder: () => AccountDeleteViewModel(context),
      disposeViewModel: false,
      builder: (context, vm, child) {
        return BasePage(
          showAppBar: true,
          showLeadingAction: true,
          elevation: 0,
          title: "Delete Account".tr(),
          appBarItemColor: Utils.textColorByTheme(),
          backgroundColor: context.theme.colorScheme.surface,
          body: FormBuilder(
            key: vm.formBuilderKey,
            autovalidateMode: AutovalidateMode.disabled,
            child: VStack(
              [
                UiSpacer.vSpace(5),
                //description
                "We're sorry to see you go, but we understand that sometimes things change. If you're sure you want to delete your account, we want to make the process as simple and straightforward as possible."
                    .tr()
                    .text
                    .make(),
                //verification section
                "Deleting your account is permanent, and it cannot be undone."
                    .tr()
                    .text
                    .semiBold
                    .make()
                    .py(10),

                "This means all your data, including profile information, preferences, and activity history, will be permanently removed from our system. You won't be able to recover any of this information once the account deletion is complete."
                    .tr()
                    .text
                    .make(),

                UiSpacer.divider().py(15),
                "Enter you account password to confirm account deletion"
                    .tr()
                    .text
                    .make(),
                UiSpacer.vSpace(5),

                //verification coe input
                UiSpacer.vSpace(10),
                FormBuilderTextField(
                  name: "password",
                  obscureText: true,
                  decoration: InputDecoration(
                    hintText: "Password".tr(),
                    border: OutlineInputBorder(),
                  ),
                  validator: CustomFormBuilderValidator.required,
                ),
                //submit btn
                UiSpacer.vSpace(10),
                CustomButton(
                  title: "Submit".tr(),
                  loading: vm.isBusy,
                  onPressed: vm.processAccountDeletion,
                ).wFull(context)
              ],
            ),
          ).scrollVertical(
            padding: EdgeInsets.fromLTRB(
              Vx.dp20,
              Vx.dp20,
              Vx.dp20,
              context.mq.viewInsets.bottom + Vx.dp20,
            ),
          ),
        );
      },
    );
  }
}
