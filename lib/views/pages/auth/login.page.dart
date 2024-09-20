import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_images.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/services/validator.service.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/login.view_model.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/buttons/custom_outline_button.dart';
import 'package:fuodz/widgets/custom_text_form_field.dart';

import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:localize_and_translate/localize_and_translate.dart';

import 'login/scan_login.view.dart';

class LoginPage extends StatefulWidget {
  LoginPage({Key? key}) : super(key: key);

  @override
  _LoginPageState createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<LoginViewModel>.reactive(
      viewModelBuilder: () => LoginViewModel(context),
      onViewModelReady: (model) => model.initialise(),
      builder: (context, model, child) {
        return BasePage(
          isLoading: model.isBusy,
          body: SafeArea(
            top: true,
            bottom: false,
            child: VStack([
              UiSpacer.vSpace(5 * context.percentHeight),
              //
              HStack(
                [
                  VStack(
                    [
                      "Welcome Back".tr().text.xl2.semiBold.make(),
                      "Login to continue".tr().text.light.make(),
                    ],
                  ).expand(),
                  UiSpacer.hSpace(),
                  Image.asset(AppImages.appLogo)
                      .wh(60, 60)
                      .box
                      .roundedSM
                      .clip(Clip.antiAlias)
                      .make()
                      .p12(),
                ],
                crossAlignment: CrossAxisAlignment.center,
                alignment: MainAxisAlignment.center,
              ),

              //form
              Form(
                key: model.formKey,
                child: VStack(
                  [
                    //
                    CustomTextFormField(
                      labelText: "Email".tr(),
                      keyboardType: TextInputType.emailAddress,
                      textEditingController: model.emailTEC,
                      validator: FormValidator.validateEmail,
                    ).py12(),
                    CustomTextFormField(
                      labelText: "Password".tr(),
                      obscureText: true,
                      textEditingController: model.passwordTEC,
                      validator: FormValidator.validatePassword,
                    ).py12(),

                    //
                    "Forgot Password ?".tr().text.underline.make().onInkTap(
                          model.openForgotPassword,
                        ),
                    //
                    CustomButton(
                      title: "Login".tr(),
                      loading: model.isBusy,
                      onPressed: model.processLogin,
                    ).centered().py12(),

                    20.heightBox,
                    ScanLoginView(model),

                    //registration link
                    Visibility(
                      visible: AppStrings.partnersCanRegister,
                      child: CustomOutlineButton(
                        title: "Become a partner".tr(),
                        onPressed: model.openRegistrationlink,
                      ).wFull(context).centered(),
                    ),
                  ],
                  crossAlignment: CrossAxisAlignment.end,
                ),
              ).py20(),
            ])
                .wFull(context)
                .p20()
                .scrollVertical()
                .pOnly(bottom: context.mq.viewInsets.bottom),
          ),
        );
      },
    );
  }
}
