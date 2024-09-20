import 'dart:io';

import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/models/order.dart';
import 'package:fuodz/view_models/order_signature_verification.vm.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:hand_signature/signature.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class SignatureVerificationPage extends StatefulWidget {
  SignatureVerificationPage({
    required this.order,
    this.onsubmit,
    Key? key,
  }) : super(key: key);

  //
  final Order order;
  final Function(File)? onsubmit;
  @override
  _SignatureVerificationPageState createState() =>
      _SignatureVerificationPageState();
}

class _SignatureVerificationPageState extends State<SignatureVerificationPage> {
  @override
  Widget build(BuildContext context) {
    return BasePage(
      showAppBar: true,
      showLeadingAction: true,
      title: "Order Verification".tr(),
      body: ViewModelBuilder<OrderSignatureVerificationViewModel>.reactive(
        viewModelBuilder: () => OrderSignatureVerificationViewModel(
          context,
          widget.order,
        ),
        onViewModelReady: (vm) => vm.initialise(),
        builder: (context, vm, child) {
          return VStack(
            [
              "Customer signature".tr().text.semiBold.xl.make(),
              //
              HandSignature(
                control: vm.handSignatureControl,
                color: AppColor.primaryColor,
                width: 2.0,
                maxWidth: 10.0,
                type: SignatureDrawType.line,
              ).wFull(context).box.roundedSM.border().make().py12().expand(),

              //
              CustomButton(
                title: "Submit".tr(),
                loading: vm.isBusy,
                onLongPress: widget.onsubmit != null
                    ? () async {
                        widget.onsubmit!(await vm.getSignatureImage());
                      }
                    : vm.submitSignature,
              ).wFull(context),
              "Long press to submit".tr().text.lg.makeCentered().py8(),
            ],
          ).p20();
        },
      ),
    );
  }
}
