import 'dart:convert';

import 'package:adaptive_theme/adaptive_theme.dart';
import 'package:cool_alert/cool_alert.dart';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/constants/app_routes.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/constants/app_theme.dart';
import 'package:fuodz/requests/settings.request.dart';
import 'package:fuodz/services/auth.service.dart';
import 'package:fuodz/services/firebase.service.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:fuodz/widgets/cards/language_selector.view.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:package_info_plus/package_info_plus.dart';
import 'base.view_model.dart';
import 'package:fuodz/extensions/context.dart';

class SplashViewModel extends MyBaseViewModel {
  SplashViewModel(BuildContext context) {
    this.viewContext = context;
  }

  //
  SettingsRequest settingsRequest = SettingsRequest();

  //
  initialise() async {
    super.initialise();
    await loadAppSettings();
    if (AuthServices.authenticated()) {
      await AuthServices.getCurrentUser(force: true);
    }
  }

  //

  //
  loadAppSettings() async {
    setBusy(true);
    try {
      final appSettingsObject = await settingsRequest.appSettings();
      Map<String, dynamic> appGenSettings = appSettingsObject.body["strings"];
      //set the app name ffrom package to the app settings
      PackageInfo packageInfo = await PackageInfo.fromPlatform();
      String appName = packageInfo.appName;
      appGenSettings["app_name"] = appName;
      //app settings
      await updateAppVariables(appGenSettings);
      //colors
      await updateAppTheme(appSettingsObject.body["colors"]);
      loadNextPage();
    } catch (error) {
      setError(error);
      print("Error loading app settings ==> $error");
      //show a dialog
      CoolAlert.show(
        context: viewContext,
        barrierDismissible: false,
        type: CoolAlertType.error,
        title: "An error occurred".tr(),
        text: "$error",
        confirmBtnText: "Retry".tr(),
        onConfirmBtnTap: () {
          viewContext.pop();
          initialise();
        },
      );
    }
    setBusy(false);
  }

  //
  updateAppVariables(dynamic json) async {
    //
    await AppStrings.saveAppSettingsToLocalStorage(jsonEncode(json));
  }

  //theme change
  updateAppTheme(dynamic colorJson) async {
    //
    await AppColor.saveColorsToLocalStorage(jsonEncode(colorJson));
    //change theme
    // await AdaptiveTheme.of(viewContext).reset();
    AdaptiveTheme.of(viewContext).setTheme(
      light: AppTheme().lightTheme(),
      dark: AppTheme().darkTheme(),
      notify: true,
    );
    await AdaptiveTheme.of(viewContext).persist();
  }

  //
  loadNextPage() async {
    //
    await Utils.setJiffyLocale();
    //
    if (AuthServices.firstTimeOnApp()) {
      //choose language
      await Navigator.of(viewContext).push(
        MaterialPageRoute(
          builder: (ctx) => AppLanguageSelector(),
        ),
      );
      // await showModalBottomSheet(
      //   context: viewContext,
      //   isScrollControlled: true,
      //   builder: (context) {
      //     return AppLanguageSelector();
      //   },
      // );
    }
    //
    if (AuthServices.firstTimeOnApp()) {
      Navigator.of(viewContext).pushNamedAndRemoveUntil(
        AppRoutes.welcomeRoute,
        (Route<dynamic> route) => false,
      );
    } else {
      Navigator.of(viewContext).pushNamedAndRemoveUntil(
        AppRoutes.homeRoute,
        (Route<dynamic> route) => false,
      );
    }

    //
    RemoteMessage? initialMessage =
        await FirebaseService().firebaseMessaging.getInitialMessage();
    if (initialMessage != null) {
      //
      FirebaseService().saveNewNotification(initialMessage);
      FirebaseService().notificationPayloadData = initialMessage.data;
      FirebaseService().selectNotification("");
    }
  }
}
