import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/buttons/custom_leading.dart';
import 'package:fuodz/widgets/buttons/custom_outline_button.dart';
import 'package:fuodz/widgets/buttons/custom_text_button.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:pin_code_fields/pin_code_fields.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:fuodz/extensions/context.dart';

class AccountVerificationEntry extends StatefulWidget {
  const AccountVerificationEntry({
    required this.onSubmit,
    this.onResendCode,
    this.vm,
    Key? key,
    this.phone = "",
  }) : super(key: key);

  final Function(String) onSubmit;
  final Function? onResendCode;
  final dynamic vm;
  final String phone;

  @override
  _AccountVerificationEntryState createState() =>
      _AccountVerificationEntryState();
}

class _AccountVerificationEntryState extends State<AccountVerificationEntry> {
  TextEditingController pinTEC = new TextEditingController();
  String? smsCode;
  int resendSecs = 20;
  int resendSecsIncreamental = 5;
  int maxResendSeconds = 30;
  bool loading = false;

  @override
  void initState() {
    super.initState();
    //
    startCountDown();
  }

  @override
  Widget build(BuildContext context) {
    //
    //
    final pinWidth = context.percentWidth * 70;
//
    return BasePage(
      showAppBar: true,
      showLeadingAction: true,
      appBarColor: AppColor.primaryColor,
      title: "Verification Code".tr(),
      elevation: 0,
      leading: CustomLeading().onInkTap(() {
        context.pop();
      }),
      body: VStack(
        [
          //
          // Image.asset(
          //   AppImages.otpImage,
          //   width: 200,
          //   height: 200,
          // ).centered(),
          //
          "Enter Verification Code".tr().text.bold.xl.make(),
          ("Enter the 6-digit verification code sent to".tr() +
                  " ${widget.phone}")
              .text
              .gray600
              .sm
              .make()
              .py2(),
          //pin code
          PinCodeTextField(
            appContext: context,
            length: 6,
            obscureText: false,
            keyboardType: TextInputType.number,
            animationType: AnimationType.fade,
            textStyle: context.textTheme.bodyLarge!.copyWith(fontSize: 16),
            controller: pinTEC,
            pinTheme: PinTheme(
              shape: PinCodeFieldShape.underline,
              fieldHeight: 50,
              fieldWidth: pinWidth / 7,
              activeFillColor: AppColor.primaryColor,
              selectedColor: AppColor.primaryColor,
              inactiveColor: AppColor.accentColor,
            ),
            animationDuration: Duration(milliseconds: 300),
            backgroundColor: Colors.transparent,
            enableActiveFill: false,
            onCompleted: (pin) {
              print("Completed");
              print("Pin ==> $pin");
              smsCode = pin;
            },
            onChanged: (value) {
              smsCode = value;
            },
          ).w(pinWidth).centered().p12(),
          // TextFormField(
          //   controller: pinTEC,
          //   textAlignVertical: TextAlignVertical.center,
          //   textAlign: TextAlign.center,
          //   style: context.textTheme.bodyLarge.copyWith(
          //     fontSize: 20,
          //     fontWeight: FontWeight.bold,
          //     letterSpacing: 10,
          //   ),
          //   decoration: InputDecoration(
          //     border: UnderlineInputBorder(),
          //     hintText: "------",
          //     // hintStyle: context.textTheme.bodyLarge.copyWith(
          //     //   fontSize: 12,
          //     //   fontWeight: FontWeight.bold,
          //     //   letterSpacing: 10,
          //     // ),
          //     counter: UiSpacer.emptySpace(),
          //   ),
          //   // keyboardType: TextInputType.number,
          //   inputFormatters: <TextInputFormatter>[
          //     FilteringTextInputFormatter.digitsOnly
          //   ],
          //   maxLength: 6,
          //   maxLengthEnforcement: MaxLengthEnforcement.enforced,
          //   onChanged: (pin) {
          //     smsCode = pin;
          //   },
          // ).w40(context).centered().p12(),

          //submit
          HStack(
            [
              //resend btn
              CustomOutlineButton(
                loading: loading,
                title: resendSecs > 0
                    ? ("Resend in".tr() + " $resendSecs " + "sec".tr())
                    : ("Resend".tr()),
                titleStyle: context.textTheme.bodyLarge!.copyWith(
                  color: resendSecs <= 0
                      ? Utils.textColorByTheme()
                      : Colors.grey.shade500,
                  fontWeight: FontWeight.w400,
                  fontSize: 11,
                ),
                onPressed: resendSecs <= 0
                    ? () async {
                        setState(() {
                          loading = true;
                        });
                        //custom otp
                        if (widget.onResendCode != null) {
                          await widget.onResendCode!();
                        }
                        //firebase opt
                        else {
                          await widget.vm
                              .processFirebaseOTPVerification(initial: false);
                        }

                        setState(() {
                          loading = false;
                          resendSecs = maxResendSeconds;
                          maxResendSeconds += resendSecsIncreamental;
                        });
                        //
                        startCountDown();
                      }
                    : null,
              ).expand(),
              UiSpacer.hSpace(10),
              //
              CustomOutlineButton(
                title: "Edit Phone No.".tr(),
                titleStyle: context.textTheme.bodyLarge!.copyWith(
                  // fontSize: 16,
                  fontWeight: FontWeight.bold,
                  color: Utils.textColorByTheme(),
                ),
                onPressed: () {
                  context.pop();
                },
              ).expand(),
            ],
          ),

          CustomButton(
            title: "Verify".tr(),
            loading: loading ||
                widget.vm.busy(widget.vm.otpLogin) ||
                widget.vm.isBusy,
            onPressed: () async {
              //
              if (smsCode == null || smsCode!.length != 6) {
                widget.vm.toastError("Verification code required".tr());
              } else {
                setState(() {
                  loading = true;
                });
                await widget.onSubmit(smsCode!);
                setState(() {
                  loading = false;
                });
              }
            },
          ).wFull(context).py12(),

          //
          Visibility(
            visible: widget.onResendCode != null,
            child: HStack(
              [
                "Didn't receive the code?".tr().text.make(),
                Visibility(
                  visible: resendSecs > 0,
                  child: "($resendSecs)"
                      .text
                      .bold
                      .color(AppColor.primaryColorDark)
                      .make()
                      .px4(),
                ),
                Visibility(
                  visible: resendSecs == 0,
                  child: CustomTextButton(
                    loading: loading,
                    title: "Resend".tr(),
                    onPressed: () async {
                      setState(() {
                        loading = true;
                      });
                      await widget.onResendCode!();
                      setState(() {
                        loading = false;
                        resendSecs = maxResendSeconds;
                      });
                      //
                      startCountDown();
                    },
                  ),
                ),
              ],
              crossAlignment: CrossAxisAlignment.center,
              alignment: MainAxisAlignment.center,
            ).py12(),
          ),
        ],
      ).p20().hFull(context),
    );
  }

  //
  void startCountDown() async {
    //
    if (resendSecs > 0) {
      setState(() {
        resendSecs -= 1;
      });

      //
      await Future.delayed(1.seconds);
      startCountDown();
    }
  }
}
