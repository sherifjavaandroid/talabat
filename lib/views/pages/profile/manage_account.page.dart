import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/profile.vm.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/menu_item.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class ManageAccountPage extends StatelessWidget {
  const ManageAccountPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return BasePage(
      showAppBar: true,
      showLeadingAction: true,
      title: "Manage Account".tr(),
      body: ViewModelBuilder<ProfileViewModel>.reactive(
        viewModelBuilder: () => ProfileViewModel(context),
        onViewModelReady: (vm) => vm.initialise(),
        builder: (context, model, child) {
          return VStack(
            [
              //
              MenuItem(
                title: "Edit Profile".tr(),
                onPressed: model.openEditProfile,
                topDivider: false,
                divider: false,
              ),

              //change password
              MenuItem(
                title: "Change Password".tr(),
                onPressed: model.openChangePassword,
                topDivider: false,
                divider: false,
              ).py12(),

              //delete account
              MenuItem(
                title: "Delete Account".tr(),
                child: HStack(
                  [
                    Icon(
                      FlutterIcons.delete_ant,
                      size: 16,
                      color: Vx.red400,
                    ),
                    UiSpacer.hSpace(10),
                    "Delete Account".tr().text.make(),
                  ],
                ),
                onPressed: model.deleteAccount,
                topDivider: false,
                divider: false,
              ),

              //
              UiSpacer.vSpace(15),
            ],
          ).p20().scrollVertical();
        },
      ),
    );
  }
}
