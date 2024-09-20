import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_images.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class EmptyState extends StatelessWidget {
  const EmptyState({
    Key? key,
    this.imageUrl,
    this.title = "",
    this.actionText = "Action",
    this.description = "",
    this.showAction = false,
    this.showImage = true,
    this.actionPressed,
    this.auth = false,
  }) : super(key: key);

  final String title;
  final String actionText;
  final String description;
  final String? imageUrl;
  final Function? actionPressed;
  final bool showAction;
  final bool showImage;
  final bool auth;

  @override
  Widget build(BuildContext context) {
    return FittedBox(
      child: VStack(
        [
          //
          (imageUrl != null && imageUrl.isNotEmptyAndNotNull)
              ? Image.asset(imageUrl!)
                  .wh(
                    context.percentWidth * 30,
                    context.percentWidth * 30,
                  )
                  .box
                  .makeCentered()
                  .wFull(context)
              : UiSpacer.emptySpace(),
          UiSpacer.vSpace(5),

          //
          (title.isNotEmpty)
              ? title.text.lg.semiBold.center.makeCentered()
              : SizedBox.shrink(),

          //
          (auth && showImage)
              ? Image.asset(
                  AppImages.auth,
                  width: context.percentWidth * 25,
                  height: context.percentWidth * 25,
                ).box.makeCentered().py12().wFull(context)
              : SizedBox.shrink(),
          //
          auth
              ? "You have to login to access profile and history"
                  .tr()
                  .text
                  .center
                  .lg
                  .medium
                  .makeCentered()
                  .py12()
              : description.isNotEmpty
                  ? description.text.sm.light.center.makeCentered()
                  : SizedBox.shrink(),

          //
          auth
              ? CustomButton(
                  title: "Login / Register".tr(),
                  onPressed: actionPressed,
                  elevation: 4,
                ).wTwoThird(context).centered()
              : showAction
                  ? CustomButton(
                      title: actionText.tr(),
                      onPressed: actionPressed,
                    ).centered().py12()
                  : SizedBox.shrink(),
        ],
        crossAlignment: CrossAxisAlignment.center,
        alignment: MainAxisAlignment.center,
      ).wFull(context),
    );
  }
}
