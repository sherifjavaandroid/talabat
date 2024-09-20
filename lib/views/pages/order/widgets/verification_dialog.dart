import 'package:flutter/material.dart';
import 'package:fuodz/models/order.dart';
import 'package:fuodz/services/validator.service.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/buttons/custom_text_button.dart';
import 'package:fuodz/widgets/custom_text_form_field.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class OrderVerificationDialog extends StatefulWidget {
  OrderVerificationDialog({
    required this.order,
    required this.onValidated,
    required this.openQRCodeScanner,
    Key? key,
  }) : super(key: key);

  //
  final Order order;
  final Function onValidated;
  final Function openQRCodeScanner;
  @override
  _OrderVerificationDialogState createState() =>
      _OrderVerificationDialogState();
}

class _OrderVerificationDialogState extends State<OrderVerificationDialog> {
  //
  TextEditingController verificationCodeTEC = TextEditingController();
  GlobalKey<FormState> formKey = GlobalKey<FormState>();

  @override
  Widget build(BuildContext context) {
    return Form(
      key: formKey,
      child: VStack(
        [
          //
          "Verification Code".tr().text.semiBold.xl.make(),
          "Please enter verification code provided by the customer."
              .tr()
              .text
              .make(),
          //
          CustomTextFormField(
            hintText: "Verification Code".tr(),
            textEditingController: verificationCodeTEC,
            validator: (value) => FormValidator.validateVerificationCode(
              value,
              code: widget.order.verificationCode,
              errorTitle: "Verification Code".tr(),
            ),
          ).py12(),
          VStack(
            [
              //
              CustomButton(
                title: "Verify".tr(),
                onPressed: () {
                  if (formKey.currentState!.validate()) {
                    //
                    widget.onValidated();
                  }
                },
              ),
              //
              CustomTextButton(
                title: "Scan Verification Code".tr(),
                onPressed: () {
                  widget.openQRCodeScanner();
                },
              ).centered().py8(),
            ],
          ),
        ],
      ).p20().pOnly(bottom: context.mq.viewInsets.bottom),
    );
  }
}
