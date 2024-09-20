import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:flutter_typeahead/flutter_typeahead.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/extensions/string.dart';
import 'package:fuodz/models/user.dart';
import 'package:fuodz/models/wallet.dart';
import 'package:fuodz/services/validator.service.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:fuodz/view_models/wallet_transfer.vm.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/custom_text_form_field.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

import 'widgets/selected_wallet_user.dart';

class WalletTransferPage extends StatelessWidget {
  const WalletTransferPage(this.wallet, {Key? key}) : super(key: key);
  //
  final Wallet wallet;

  @override
  Widget build(BuildContext context) {
    return BasePage(
      title: "Wallet Transfer".tr(),
      showLeadingAction: true,
      showAppBar: true,
      body: ViewModelBuilder<WalletTransferViewModel>.reactive(
        viewModelBuilder: () => WalletTransferViewModel(context, wallet),
        onViewModelReady: (vm) => vm.initialise(),
        builder: (context, vm, child) {
          return Form(
            key: vm.formKey,
            autovalidateMode: AutovalidateMode.onUserInteraction,
            child: VStack(
              [
                //amount
                CustomTextFormField(
                  labelText: "Amount".tr(),
                  inputFormatters: [FilteringTextInputFormatter.digitsOnly],
                  textEditingController: vm.amountTEC,
                  validator: (value) => FormValidator.validateCustom(
                    value,
                    name: "Amount".tr(),
                    rules: "required|lt:${vm.wallet?.balance}",
                  ),
                ),
                UiSpacer.formVerticalSpace(),
                //receiver email/phone
                "Receiver".tr().text.lg.semiBold.make(),
                UiSpacer.verticalSpace(space: 6),
                //Receiver row data
                Row(
                  children: [
                    //
                    TypeAheadField(
                      hideOnLoading: true,
                      hideSuggestionsOnKeyboardHide: false,
                      minCharsForSuggestions: 2,
                      debounceDuration: const Duration(seconds: 1),
                      textFieldConfiguration: TextFieldConfiguration(
                        autofocus: false,
                        decoration: InputDecoration(
                          border: OutlineInputBorder(
                            borderSide: BorderSide(
                              color: AppColor.primaryColor,
                            ),
                          ),
                          hintText: "Email/Phone".tr(),
                          focusedBorder: OutlineInputBorder(
                            borderSide: BorderSide(
                              color: AppColor.primaryColor,
                            ),
                          ),
                          enabledBorder: OutlineInputBorder(
                            borderSide: BorderSide(
                              color: AppColor.primaryColor,
                            ),
                          ),
                        ),
                      ),
                      suggestionsCallback: vm.searchUsers,
                      itemBuilder: (context, User? suggestion) {
                        if (suggestion == null) {
                          return Divider();
                        }
                        //
                        return VStack(
                          [
                            VStack(
                              [
                                "${suggestion.name}".text.semiBold.lg.make(),
                                UiSpacer.vSpace(5),
                                "${suggestion.code ?? ''} - ${suggestion.phone.isNotBlank ? suggestion.phone.maskString(
                                        start: 3,
                                        end: 8,
                                      ) : ''}"
                                    .text
                                    .sm
                                    .make(),
                              ],
                            ).px12().py(3),
                            Divider(),
                          ],
                        );
                      },
                      onSuggestionSelected: vm.userSelected,
                    ).expand(),
                    UiSpacer.horizontalSpace(),
                    //scan qrcode
                    Icon(
                      FlutterIcons.qrcode_ant,
                      size: 32,
                      color: Utils.textColorByTheme(),
                    )
                        .p12()
                        .box
                        .roundedSM
                        .outerShadowSm
                        .color(AppColor.primaryColor)
                        .make()
                        .onInkTap(vm.scanWalletAddress),
                  ],
                ),
                //selected user view
                if (vm.selectedUser != null)
                  SelectedWalletUser(vm.selectedUser!),

                UiSpacer.formVerticalSpace(),
                //account password
                CustomTextFormField(
                  labelText: "Password".tr(),
                  textEditingController: vm.passwordTEC,
                  obscureText: true,
                  validator: FormValidator.validatePassword,
                ),
                UiSpacer.formVerticalSpace(),
                //send button
                CustomButton(
                  loading: vm.isBusy,
                  title: "Transfer".tr(),
                  onPressed: vm.initiateWalletTransfer,
                ).wFull(context),
                UiSpacer.formVerticalSpace(),
              ],
            ).p20().scrollVertical(),
          );
        },
      ),
    );
  }
}
