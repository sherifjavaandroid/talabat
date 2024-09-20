import 'dart:io';

import 'package:cached_network_image/cached_network_image.dart';
import 'package:eva_icons_flutter/eva_icons_flutter.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/constants/api.dart';
import 'package:fuodz/constants/app_images.dart';
import 'package:fuodz/view_models/profile.vm.dart';
import 'package:fuodz/views/pages/profile/paymet_accounts.page.dart';
import 'package:fuodz/widgets/busy_indicator.dart';
import 'package:fuodz/widgets/menu_item.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:fuodz/extensions/context.dart';

class ProfileCard extends StatelessWidget {
  const ProfileCard(this.model, {Key? key}) : super(key: key);

  final ProfileViewModel model;
  @override
  Widget build(BuildContext context) {
    return VStack(
      [
        //profile card
        (model.isBusy || model.currentUser == null)
            ? BusyIndicator().centered().p20()
            : HStack(
                [
                  //
                  CachedNetworkImage(
                    imageUrl: model.currentUser!.photo,
                    progressIndicatorBuilder: (context, imageUrl, progress) {
                      return BusyIndicator();
                    },
                    errorWidget: (context, imageUrl, progress) {
                      return Image.asset(
                        AppImages.user,
                      );
                    },
                  )
                      .wh(Vx.dp64, Vx.dp64)
                      .box
                      .roundedFull
                      .clip(Clip.antiAlias)
                      .make(),

                  //
                  VStack(
                    [
                      //name
                      model.currentUser!.name.text.xl.semiBold.make(),
                      //email
                      model.currentUser!.email.text.light.make(),
                    ],
                  ).px20().expand(),
                ],
              ).p12(),
        //
        MenuItem(
          title: "Edit Profile".tr(),
          prefix: Icon(
            EvaIcons.personOutline,
          ),
          onPressed: model.openEditProfile,
          topDivider: true,
        ),
        //change password
        MenuItem(
          title: "Change Password".tr(),
          prefix: Icon(
            EvaIcons.keypadOutline,
          ),
          onPressed: model.openChangePassword,
          topDivider: true,
        ),

        Divider(),
        //
        Visibility(
          visible: !Platform.isIOS,
          child: MenuItem(
            title: "Backend".tr(),
            prefix: Icon(
              EvaIcons.browserOutline,
            ),
            onPressed: () async {
              try {
                final url = await Api.redirectAuth(
                  url: Api.backendUrl,
                  route: "dashboard",
                );
                model.openExternalWebpageLink(url);
              } catch (error) {
                model.toastError("$error");
              }
            },
            topDivider: true,
          ),
        ),
        //
        MenuItem(
          title: "Payment Accounts".tr(),
          prefix: Icon(
            EvaIcons.creditCardOutline,
          ),
          onPressed: () {
            context.push((ctx) => PaymentAccountsPage());
          },
          topDivider: true,
        ),
      ],
    )
        .wFull(context)
        .box
        // .border(color: Theme.of(context).cardColor)
        // .color(Theme.of(context).cardColor)
        .color(Theme.of(context).colorScheme.surface)
        .outerShadow
        .withRounded(value: 5)
        .make();
  }
}
