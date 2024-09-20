import 'dart:io';

import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/models/order.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/order_photo_verification.vm.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class PhotoVerificationPage extends StatefulWidget {
  PhotoVerificationPage({
    required this.order,
    this.onsubmit,
    Key? key,
  }) : super(key: key);

  //
  final Order order;
  final Function(File)? onsubmit;
  @override
  _PhotoVerificationPageState createState() => _PhotoVerificationPageState();
}

class _PhotoVerificationPageState extends State<PhotoVerificationPage> {
  @override
  Widget build(BuildContext context) {
    return BasePage(
      showAppBar: true,
      showLeadingAction: true,
      title: "Order Verification".tr(),
      body: ViewModelBuilder<OrderPhotoVerificationViewModel>.reactive(
        viewModelBuilder: () => OrderPhotoVerificationViewModel(
          context,
          widget.order,
        ),
        onViewModelReady: (vm) => vm.initialise(),
        builder: (context, vm, child) {
          return VStack(
            [
              "Photo".tr().text.semiBold.xl.make(),
              //
              VStack(
                [
                  //image preview
                  vm.newPhoto != null
                      ? Image.file(
                          vm.newPhoto!,
                          fit: BoxFit.fill,
                        )
                          .wFull(context)
                          .hOneThird(context)
                          .box
                          .topRounded(value: 5)
                          .clip(Clip.antiAlias)
                          .make()
                      : UiSpacer.emptySpace(),
                  //select image button
                  CustomButton(
                    onPressed: vm.takeDeliveryPhoto,
                    color: AppColor.accentColor,
                    elevation: 0,
                    child: HStack(
                      [
                        Icon(
                          FlutterIcons.camera_ant,
                          color: Colors.white,
                        ),
                        UiSpacer.horizontalSpace(space: 10),
                        "Take a shot".tr().text.make(),
                      ],
                      crossAlignment: CrossAxisAlignment.center,
                      alignment: MainAxisAlignment.center,
                    ),
                  ).wFull(context).p12(),
                ],
              ).wFull(context).box.roundedSM.border().make().py12(),

              //
              CustomButton(
                title: "Submit".tr(),
                loading: vm.isBusy,
                onLongPress: (vm.newPhoto == null)
                    ? null
                    : widget.onsubmit != null
                        ? () async {
                            widget.onsubmit!(vm.newPhoto!);
                          }
                        : vm.submitPhotoProof,
              ).wFull(context),
              "Long press to submit".tr().text.lg.makeCentered().py8(),
            ],
          ).p20();
        },
      ),
    );
  }
}
