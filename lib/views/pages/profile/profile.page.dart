import 'package:adaptive_theme/adaptive_theme.dart';
import 'package:eva_icons_flutter/eva_icons_flutter.dart';
import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/profile.vm.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/cards/profile.card.dart';
import 'package:fuodz/widgets/menu_item.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

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
        disposeViewModel: false,
        builder: (context, model, child) {
          return BasePage(
            body: VStack(
              [
                //
                "Settings".tr().text.xl2.semiBold.make(),
                "Profile & App Settings".tr().text.lg.light.make(),

                //profile card
                ProfileCard(model).py12(),

                //menu
                VStack(
                  [
                    //
                    MenuItem(
                      title: "Language".tr(),
                      divider: false,
                      prefix: Icon(FlutterIcons.language_ent),
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

                    20.heightBox,
                    //
                    MenuItem(
                      title: "Notifications".tr(),
                      prefix: Icon(EvaIcons.bellOutline),
                      onPressed: model.openNotification,
                    ),

                    //
                    MenuItem(
                      title: "Rate & Review".tr(),
                      onPressed: model.openReviewApp,
                      prefix: Icon(EvaIcons.starOutline),
                    ),

                    //
                    MenuItem(
                      title: "Faqs".tr(),
                      onPressed: model.openFaqs,
                      prefix: Icon(EvaIcons.questionMarkCircleOutline),
                    ),
                    //
                    MenuItem(
                      title: "Privacy Policy".tr(),
                      onPressed: model.openPrivacyPolicy,
                      prefix: Icon(EvaIcons.bookOutline),
                    ),
                    //
                    MenuItem(
                      title: "Terms & Conditions".tr(),
                      onPressed: model.openTerms,
                      prefix: Icon(EvaIcons.shieldOutline),
                    ),
                    //START NEW LINKS
                    MenuItem(
                      title: "Refund Policy".tr(),
                      onPressed: model.openRefundPolicy,
                      prefix: Icon(EvaIcons.refreshOutline),
                    ),
                    MenuItem(
                      title: "Cancellation Policy".tr(),
                      onPressed: model.openCancellationPolicy,
                      prefix: Icon(EvaIcons.closeCircleOutline),
                    ),
                    MenuItem(
                      title: "Delivery/Shipping Policy".tr(),
                      onPressed: model.openShippingPolicy,
                      prefix: Icon(EvaIcons.shoppingBagOutline),
                    ),
                    //END NEW LINKS
                    //
                    MenuItem(
                      title: "Contact Us".tr(),
                      onPressed: model.openContactUs,
                      prefix: Icon(EvaIcons.emailOutline),
                    ),
                    //
                    MenuItem(
                      title: "Live Support".tr(),
                      onPressed: model.openLivesupport,
                      prefix: Icon(EvaIcons.messageSquareOutline),
                    ),
                  ],
                ),
                model.appVersionInfo.text.sm.medium.gray400
                    .makeCentered()
                    .py20(),
                //
                UiSpacer.verticalSpace(space: context.percentHeight * 10),
              ],
            ).p20().scrollVertical(),
          );
        },
      ),
    );
  }

  @override
  bool get wantKeepAlive => true;
}
