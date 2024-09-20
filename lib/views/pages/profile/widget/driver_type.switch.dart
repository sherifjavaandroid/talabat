import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/constants/app_ui_settings.dart';
import 'package:fuodz/models/user.dart';
import 'package:fuodz/requests/driver_type.request.dart';
import 'package:fuodz/services/alert.service.dart';
import 'package:fuodz/services/auth.service.dart';
import 'package:fuodz/services/local_storage.service.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:fuodz/views/pages/splash.page.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:swipe_button_widget/swipe_button_widget.dart';
import 'package:velocity_x/velocity_x.dart';

class DriverTypeSwitch extends StatefulWidget {
  const DriverTypeSwitch({Key? key}) : super(key: key);

  @override
  State<DriverTypeSwitch> createState() => _DriverTypeSwitchState();
}

class _DriverTypeSwitchState extends State<DriverTypeSwitch> {
  @override
  Widget build(BuildContext context) {
    return FutureBuilder<User>(
      future: AuthServices.getCurrentUser(force: true),
      builder: (context, snapshot) {
        if (!snapshot.hasData ||
            snapshot.connectionState != ConnectionState.done) {
          return UiSpacer.emptySpace();
        }

        //
        return Visibility(
          visible: AppUISettings.enableDriverTypeSwitch,
          child: VStack(
            [
              //note indicating the button below is a swipable button
              "Swipe below to switch".tr().text.sm.make().centered().py(2),
              //
              SwipeButtonWidget(
                  acceptPoitTransition: 0.6,
                  margin: const EdgeInsets.all(0),
                  padding: const EdgeInsets.all(0),
                  boxShadow: [],
                  borderRadius: BorderRadius.circular(10),
                  colorBeforeSwipe: AppColor.primaryColor,
                  colorAfterSwiped: AppColor.primaryColor,
                  height: 50,
                  childBeforeSwipe: Container(
                    decoration: BoxDecoration(
                      borderRadius: BorderRadius.circular(10),
                      color: AppColor.primaryColor,
                    ),
                    width: 50,
                    height: double.infinity,
                    child: const Center(
                      child: Icon(
                        FlutterIcons.chevrons_right_fea,
                        color: Colors.white,
                        size: 24,
                      ),
                    ),
                  ),
                  childAfterSwiped: Container(
                    decoration: BoxDecoration(
                      borderRadius: BorderRadius.circular(0),
                      color: AppColor.primaryColor,
                    ),
                    width: 70,
                    height: double.infinity,
                    child: const Center(
                      child: Icon(
                        FlutterIcons.check_ant,
                        color: Colors.white,
                      ),
                    ),
                  ),
                  leftChildren: [
                    Align(
                      alignment: Alignment(0.9, 0),
                      child:
                          (snapshot.data != null && !snapshot.data!.isTaxiDriver
                                  ? "Switch To Taxi Driver"
                                  : "Switch To Regular Driver")
                              .tr()
                              .text
                              .lg
                              .color(Utils.textColorByTheme())
                              .white
                              .make(),
                    )
                  ],
                  onHorizontalDragUpdate: (e) {},
                  onHorizontalDragRight: (e) => _processDriverTypeSwitch(
                        snapshot.data!,
                        context,
                      ),
                  onHorizontalDragleft: (e) async {
                    return false;
                  }).h(50),
              UiSpacer.vSpace(),
            ],
          ),
        );
      },
    );
  }

  Future<bool> _processDriverTypeSwitch(User user, BuildContext context) async {
    bool result = false;
    try {
      AlertService.showLoading();

      final payload = {
        "driver_id": user.id,
        "is_taxi": !user.isTaxiDriver,
      };
      final apiResponse = await DriverTypeRequest().switchType(payload);

      if (apiResponse.allGood) {
        //
        final vehicleJson = apiResponse.body['data']["vehicle"] ?? null;
        final driverJson = apiResponse.body['data']["driver"] ?? null;
        final newUserModel = await AuthServices.saveUser(driverJson);
        if (newUserModel.isTaxiDriver && vehicleJson != null) {
          await AuthServices.saveVehicle(vehicleJson);
        } else {
          await LocalStorageService.prefs!.remove(AppStrings.driverVehicleKey);
        }

        await AuthServices.getCurrentUser(force: true);
        AlertService.stopLoading();
        //reload app from splash screen
        context.nextAndRemoveUntilPage(SplashPage());
      } else {
        throw "${apiResponse.message}";
      }
      //
      result = true;
    } catch (error) {
      AlertService.stopLoading();
      AlertService.error(text: "$error");
    }
    return result;
  }
}
