import 'package:adaptive_theme/adaptive_theme.dart';
import 'package:eva_icons_flutter/eva_icons_flutter.dart';
import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/view_models/profile.vm.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/cards/profile.card.dart';
import 'package:fuodz/widgets/menu_item.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:localize_and_translate/localize_and_translate.dart';

class ProfilePage extends StatefulWidget {
  const ProfilePage({Key? key}) : super(key: key);

  @override
  _ProfilePageState createState() => _ProfilePageState();
}

class _ProfilePageState extends State<ProfilePage>
    with AutomaticKeepAliveClientMixin<ProfilePage> {
  @override
  Widget build(BuildContext context) {
    super.build(context);
    return SafeArea(
      child: ViewModelBuilder<ProfileViewModel>.reactive(
        viewModelBuilder: () => ProfileViewModel(context),
        onViewModelReady: (model) => model.initialise(),
        builder: (context, model, child) {
          return BasePage(
            body: VStack(
              [
                //
                "Settings".tr().text.xl2.semiBold.make(),
                "Profile & App Settings".tr().text.lg.light.make(),

                //profile card
                ProfileCard(model).py12(),
                // 10.heightBox,
                VxBox(
                  child: VStack(
                    [
                      //printer settings
                      MenuItem(
                        title: "Printing Settings".tr(),
                        onPressed: model.openPrinterSettings,
                        prefix: Icon(
                          FlutterIcons.printer_ant,
                        ),
                      ),
                      //
                      MenuItem(
                        title: "Language".tr(),
                        prefix: Icon(
                          FlutterIcons.language_ent,
                        ),
                        onPressed: model.changeLanguage,
                      ),
                      MenuItem(
                        title: "Theme".tr(),
                        suffix: Text(
                          AdaptiveTheme.of(context).mode.name.tr().capitalized,
                        ),
                        prefix: Icon(
                          EvaIcons.syncOutline,
                        ),
                        onPressed: () {
                          AdaptiveTheme.of(context).toggleThemeMode();
                        },
                      ),
                    ],
                    spacing: 10,
                  ),
                )
                    .color(Theme.of(context).colorScheme.surface)
                    .outerShadow
                    .withRounded(value: 5)
                    .make(),
                20.heightBox,

                //menu
                VxBox(
                  child: VStack(
                    [
                      //
                      MenuItem(
                        title: "Notifications".tr(),
                        prefix: Icon(
                          EvaIcons.bellOutline,
                        ),
                        onPressed: model.openNotification,
                      ),

                      //
                      MenuItem(
                        title: "Rate & Review".tr(),
                        prefix: Icon(
                          EvaIcons.starOutline,
                        ),
                        onPressed: model.openReviewApp,
                      ),
                      MenuItem(
                        title: "Faqs".tr(),
                        prefix: Icon(
                          EvaIcons.questionMarkCircleOutline,
                        ),
                        onPressed: model.openFaqs,
                      ),

                      //
                      MenuItem(
                        title: "Privacy Policy".tr(),
                        prefix: Icon(
                          EvaIcons.shieldOutline,
                        ),
                        onPressed: model.openPrivacyPolicy,
                      ),
                      //
                      MenuItem(
                        title: "Contact Us".tr(),
                        prefix: Icon(
                          EvaIcons.emailOutline,
                        ),
                        onPressed: model.openContactUs,
                      ),
                      MenuItem(
                        title: "Live support".tr(),
                        divider: false,
                        prefix: Icon(
                          EvaIcons.messageSquareOutline,
                        ),
                        onPressed: model.openLivesupport,
                      ),
                    ],
                  ),
                )
                    .color(Theme.of(context).colorScheme.surface)
                    .outerShadow
                    .withRounded(value: 5)
                    .make(),
                20.heightBox,
                VxBox(
                  child: VStack(
                    [
                      //
                      MenuItem(
                        child: "Logout".tr().text.bold.lg.make(),
                        onPressed: model.logoutPressed,
                        suffix: Icon(
                          FlutterIcons.logout_ant,
                          size: 16,
                        ),
                      ),
                      MenuItem(
                        child: "Delete Account".tr().text.red500.lg.make(),
                        onPressed: model.deleteAccount,
                        divider: false,
                        suffix: Icon(
                          FlutterIcons.x_circle_fea,
                          size: 16,
                          color: Vx.red600,
                        ),
                      ),
                    ],
                  ),
                )
                    .color(Theme.of(context).colorScheme.surface)
                    .outerShadow
                    .withRounded(value: 5)
                    .make(),

                //version
                model.appVersionInfo.text.sm.gray400.makeCentered().py20(),

                // //
                // "Copyright ©%s %s all right reserved"
                //     .tr()
                //     .fill([
                //       "${DateTime.now().year}",
                //       AppStrings.appName,
                //     ])
                //     .text
                //     .center
                //     .sm
                //     .makeCentered()
                //     .py20(),
              ],
              spacing: 10,
            ).p20().scrollVertical(),
          );
        },
      ),
    );
  }

  @override
  bool get wantKeepAlive => true;
}
