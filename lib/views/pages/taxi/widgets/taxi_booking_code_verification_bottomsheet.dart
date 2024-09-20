import 'package:flutter/material.dart';
import 'package:flutter_form_builder/flutter_form_builder.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:fuodz/extensions/context.dart';

class TaxiBookingCodeVerificationBottomSheet extends StatefulWidget {
  TaxiBookingCodeVerificationBottomSheet(this.verificationCode, {Key? key})
      : super(key: key);

  final String verificationCode;
  @override
  State<TaxiBookingCodeVerificationBottomSheet> createState() =>
      _TaxiBookingCodeVerificationBottomSheetState();
}

class _TaxiBookingCodeVerificationBottomSheetState
    extends State<TaxiBookingCodeVerificationBottomSheet> {
  //
  final formBuilderKey = GlobalKey<FormBuilderState>();
  //
  @override
  Widget build(BuildContext context) {
    return VStack(
      [
        "Taxi Booking verification code".tr().text.semiBold.xl2.make(),
        UiSpacer.vSpace(5),
        "Please enter customer trip booking code below to continue with trip"
            .tr()
            .text
            .make(),
        FormBuilder(
          key: formBuilderKey,
          child: VStack(
            [
              FormBuilderTextField(
                name: "code",
                decoration: InputDecoration(
                  hintText: "Booking Code".tr(),
                ),
                validator: (value) {
                  //
                  if (value != widget.verificationCode) {
                    return "Taxi Booking verification code is invalid".tr();
                  }
                  return null;
                },
              ),
              CustomButton(
                title: "Verify".tr(),
                onPressed: () {
                  if (formBuilderKey.currentState!.saveAndValidate()) {
                    context.pop(true);
                  }
                },
              ).py12(),
            ],
          ),
        ),
      ],
    )
        .p20()
        .box
        .topRounded(value: 10)
        .color(context.theme.colorScheme.surface)
        .make()
        .h(context.percentHeight * 70)
        .pOnly(bottom: context.mq.viewPadding.bottom);
  }
}
