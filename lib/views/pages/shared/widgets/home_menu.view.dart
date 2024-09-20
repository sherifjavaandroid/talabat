import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/constants/app_ui_settings.dart';
import 'package:fuodz/extensions/context.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/profile.vm.dart';
import 'package:fuodz/views/pages/order/orders.page.dart';
import 'package:fuodz/views/pages/profile/finance.page.dart';
import 'package:fuodz/views/pages/profile/legal.page.dart';
import 'package:fuodz/views/pages/profile/support.page.dart';
import 'package:fuodz/views/pages/profile/widget/document_request.view.dart';
import 'package:fuodz/views/pages/profile/widget/driver_type.switch.dart';
import 'package:fuodz/views/pages/vehicle/vehicles.page.dart';
import 'package:fuodz/widgets/cards/profile.card.dart';
import 'package:fuodz/widgets/menu_item.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class HomeMenuView extends StatelessWidget {
  const HomeMenuView({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<ProfileViewModel>.reactive(
      viewModelBuilder: () => ProfileViewModel(context),
      onViewModelReady: (model) => model.initialise(),
      builder: (context, model, child) {
        return Stack(
          children: [
            VStack(
              [
                //profile card
                ProfileCard(model),
                12.heightBox,

                //if driver switch is enabled
                DriverTypeSwitch(),
                //document verification
                DocumentRequestView(),
                Visibility(
                  visible: AppUISettings.enableDriverTypeSwitch ||
                      model.currentUser.isTaxiDriver,
                  child: MenuItem(
                    title: "Vehicle Details".tr(),
                    onPressed: () {
                      context.nextPage(VehiclesPage());
                    },
                    topDivider: true,
                  ),
                ),
                //
                // orders
                MenuItem(
                  title: "Orders".tr(),
                  onPressed: () {
                    context.nextPage(OrdersPage());
                  },
                ),

                MenuItem(
                  title: "Finance".tr(),
                  onPressed: () {
                    context.nextPage(FinancePage());
                  },
                ),

                //menu
                VStack(
                  [
                    //
                    MenuItem(
                      title: "Notifications".tr(),
                      onPressed: model.openNotification,
                    ),

                    //
                    MenuItem(
                      title: "Rate & Review".tr(),
                      onPressed: model.openReviewApp,
                    ),

                    MenuItem(
                      title: "Faqs".tr(),
                      onPressed: model.openFaqs,
                    ),

                    //
                    MenuItem(
                      title: "Legal".tr(),
                      onPressed: () {
                        context.nextPage(LegalPage());
                      },
                    ),
                    MenuItem(
                      title: "Support".tr(),
                      onPressed: () {
                        context.nextPage(SupportPage());
                      },
                    ),

                    //
                    MenuItem(
                      title: "Language".tr(),
                      divider: false,
                      suffix: Icon(
                        FlutterIcons.language_ent,
                      ),
                      onPressed: model.changeLanguage,
                    ),
                  ],
                ),

                //
                MenuItem(
                  child: "Logout".tr().text.red500.make(),
                  onPressed: model.logoutPressed,
                  divider: false,
                  suffix: Icon(
                    FlutterIcons.logout_ant,
                    size: 16,
                  ),
                ),

                UiSpacer.vSpace(15),

                //
                ("Version".tr() + " - ${model.appVersionInfo}")
                    .text
                    .center
                    .sm
                    .makeCentered()
                    .py20(),
              ],
            )
                .p(18)
                .scrollVertical()
                .hFull(context)
                .box
                .color(context.colors.surface)
                .topRounded(value: 20)
                .make()
                .pOnly(top: 20),

            //close
            IconButton(
              icon: Icon(
                FlutterIcons.close_ant,
                color: Colors.white,
              ),
              onPressed: () {
                context.pop();
              },
            ).box.roundedFull.red500.make().positioned(top: 0, right: 20),
          ],
        );
      },
    );
  }
}
