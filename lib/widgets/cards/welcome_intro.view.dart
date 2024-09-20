import 'package:flutter/material.dart';
import 'package:fuodz/models/user.dart';
import 'package:fuodz/services/auth.service.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:fuodz/view_models/welcome.vm.dart';
import 'package:fuodz/views/pages/welcome/widgets/welcome_simple_header.section.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class WelcomeIntroView extends StatelessWidget {
  const WelcomeIntroView(this.vm, {Key? key}) : super(key: key);
  final WelcomeViewModel vm;
  @override
  Widget build(BuildContext context) {
    return VStack(
      [
        //
        new WelcomeSimpleHeaderSection(vm),
        VStack(
          [
            //welcome intro and loggedin account name
            StreamBuilder(
              stream: AuthServices.listenToAuthState(),
              builder: (ctx, snapshot) {
                //
                String introText = "Welcome".tr();
                String fullIntroText = introText;
                //
                if (snapshot.hasData) {
                  return FutureBuilder<User>(
                    future: AuthServices.getCurrentUser(),
                    builder: (ctx, snapshot) {
                      if (snapshot.hasData) {
                        fullIntroText = "$introText ${snapshot.data?.name}";

                        final user = snapshot.data;
                        return HStack(
                          [
                            CustomImage(
                              imageUrl: user!.photo,
                            ).box.roundedFull.shadowSm.make().wh(50, 50),
                            UiSpacer.hSpace(15),
                            //
                            VStack(
                              [
                                //name
                                fullIntroText.text
                                    .color(Utils.textColorByTheme())
                                    .xl
                                    .semiBold
                                    .make(),
                                //email
                                "${user.email}"
                                    .hidePartial(
                                      begin: 3,
                                      end: "${user.email}".length - 8,
                                    )!
                                    .text
                                    .color(Utils.textColorByTheme())
                                    .sm
                                    .thin
                                    .make(),
                              ],
                            ),
                          ],
                        ).pOnly(bottom: 10);
                      } else {
                        //auth but not data received
                        return fullIntroText.text.white.xl3.semiBold.make();
                      }
                    },
                  );
                }
                return fullIntroText.text.white.xl3.semiBold.make();
              },
            ),
            //
            "How can I help you today?".tr().text.white.xl.medium.make(),
            30.heightBox,
          ],
        ).px20(),
      ],
      spacing: 10,
    );
  }
}
