import 'package:eva_icons_flutter/eva_icons_flutter.dart';
import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/models/notification.dart';
import 'package:fuodz/services/app.service.dart';
import 'package:fuodz/services/local_storage.service.dart';
import 'package:fuodz/services/notification.service.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:fuodz/view_models/home.vm.dart';
import 'package:fuodz/views/pages/notification/notifications.page.dart';
import 'package:fuodz/widgets/busy_indicator.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:rolling_switch/rolling_switch.dart';
import 'package:rx_shared_preferences/rx_shared_preferences.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class OnlineOfflineFab extends StatelessWidget {
  const OnlineOfflineFab({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    //
    return ViewModelBuilder<HomeViewModel>.reactive(
      viewModelBuilder: () => HomeViewModel(context),
      onViewModelReady: (homeVm) => homeVm.initialise(),
      builder: (context, homeVm, child) {
        //
        return HStack(
          [
            "Active Orders".tr().text.xl2.semiBold.make().expand(),
            //
            homeVm.isBusy
                ? BusyIndicator(color: AppColor.primaryColor).wh(35, 35)
                : RollingSwitch.icon(
                    enableDrag: false,
                    width: context.screenWidth * 0.40,
                    initialState: AppService().driverIsOnline,
                    onChanged: (bool state) {
                      print('turned ${(state) ? 'on' : 'off'}');
                      homeVm.toggleOnlineStatus();
                    },
                    rollingInfoRight: RollingIconInfo(
                      icon: FlutterIcons.location_on_mdi,
                      text: Text(
                        'Online'.tr(),
                        style: context.textTheme.bodyLarge!.copyWith(
                          color: Colors.white,
                          fontSize: 26,
                        ),
                      ),
                      backgroundColor: Colors.green,
                      iconColor: AppColor.primaryColor,
                    ),
                    rollingInfoLeft: RollingIconInfo(
                      icon: FlutterIcons.location_off_mdi,
                      backgroundColor: Colors.red,
                      text: Text(
                        'Offline'.tr(),
                        style: context.textTheme.bodyLarge!.copyWith(
                          color: Colors.white,
                          fontSize: 26,
                        ),
                      ),
                    ),
                  ).fittedBox().h(35),

            // notification icon
            StreamBuilder<String?>(
              stream: LocalStorageService.rxPrefs!.getStringStream(
                AppStrings.notificationsKey,
              ),
              builder: (context, snapshot) {
                Widget icon = Icon(
                  EvaIcons.bellOutline,
                  size: 26,
                );

                //get notifications count of unread notifications
                if (snapshot.hasData) {
                  return FutureBuilder<List<NotificationModel>?>(
                    future: NotificationService.getNotifications(),
                    builder: (context, dataSet) {
                      //
                      final totalUnread = dataSet.data!
                          .where((element) => !element.read)
                          .toList()
                          .length;
                      //
                      if (totalUnread > 0) {
                        icon = icon.badge(
                          color: AppColor.primaryColor,
                          count: totalUnread,
                          textStyle: TextStyle(
                            color: Utils.textColorByTheme(),
                            fontSize: 12,
                          ),
                        );
                      }

                      //
                      return icon.p(4).onTap(
                        () {
                          Navigator.of(context).push(
                            MaterialPageRoute(
                              builder: (context) => NotificationsPage(),
                            ),
                          );
                        },
                      );
                    },
                  );
                } else {
                  return icon.p(4).onTap(
                    () {
                      Navigator.of(context).push(
                        MaterialPageRoute(
                          builder: (context) => NotificationsPage(),
                        ),
                      );
                    },
                  );
                }
              },
            ),
          ],
          spacing: 8,
        );
      },
    ).p(12).card.p0.elevation(1.5).roundedNone.make().wFull(context);
  }
}
