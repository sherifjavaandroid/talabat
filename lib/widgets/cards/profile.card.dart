import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/constants/app_images.dart';
import 'package:fuodz/view_models/profile.vm.dart';
import 'package:fuodz/views/pages/profile/manage_account.page.dart';
import 'package:fuodz/widgets/busy_indicator.dart';
import 'package:fuodz/widgets/states/custom_loading.state.dart';
import 'package:velocity_x/velocity_x.dart';

class ProfileCard extends StatelessWidget {
  const ProfileCard(this.model, {Key? key}) : super(key: key);

  final ProfileViewModel model;
  @override
  Widget build(BuildContext context) {
    return VStack(
      [
        //profile card
        CustomLoadingStateView(
          loading: model.isBusy,
          child: HStack(
            [
              //
              CachedNetworkImage(
                imageUrl: model.currentUser.photo,
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
                  "${model.currentUser.name}".text.xl.semiBold.make(),
                  "${model.currentUser.email}".text.light.make(),
                ],
              ).px20().expand(),

              //arrow icon
              Icon(
                FlutterIcons.right_ant,
                size: 24,
              ),
            ],
          ).p12().onTap(() {
            context.nextPage(ManageAccountPage());
          }),
        ),
      ],
    ).wFull(context);
  }
}
