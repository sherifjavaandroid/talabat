import 'dart:io';

import 'package:firestore_chat/firestore_chat.dart';
import 'package:flutter/material.dart';
import 'package:flutter_dropdown_alert/alert_controller.dart';
import 'package:flutter_dropdown_alert/model/data_alert.dart';
import 'package:flutter_form_builder/flutter_form_builder.dart';
import 'package:flutter_inappwebview/flutter_inappwebview.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/models/delivery_address.dart';
import 'package:fuodz/services/location.service.dart';
import 'package:fuodz/services/toast.service.dart';
import 'package:fuodz/views/pages/payment/custom_webview.page.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:url_launcher/url_launcher_string.dart';
import 'package:fuodz/extensions/context.dart';

class MyChromeSafariBrowser extends ChromeSafariBrowser {
  @override
  void onOpened() {
    print("ChromeSafari browser opened");
  }

  @override
  void onCompletedInitialLoad(bool? value) {
    print("ChromeSafari browser initial load completed");
  }

  @override
  void onClosed() {
    print("ChromeSafari browser closed");
  }
}

class MyBaseViewModel extends BaseViewModel {
  //
  late BuildContext viewContext;
  GlobalKey<FormState> formKey = GlobalKey<FormState>();
  final formBuilderKey = GlobalKey<FormBuilderState>();
  final currencySymbol = AppStrings.currencySymbol;
  DeliveryAddress deliveryaddress = DeliveryAddress();
  String? firebaseVerificationId;
  ChatEntity? chatEntity;

  //

  void initialise() {
    // FirestoreRepository();
  }

  newFormKey() {
    formKey = GlobalKey<FormState>();
    notifyListeners();
  }

  //
  void startNewOrderBackgroundService() {
    WidgetsFlutterBinding.ensureInitialized();

    //
    //try sending location to fcm
    print("Resending fcm location");
    if (LocationService().currentLocationData == null) {
      return;
    }
    //
    LocationService().syncLocationWithFirebase(
      LocationService().currentLocationData!,
    );
  }

  //
  // openWebpageLink(String url) async {
  //   //
  //   ChromeSafariBrowser browser = new MyChromeSafariBrowser();
  //   await browser.open(
  //     url: Uri.parse(url),
  //     options: ChromeSafariBrowserClassOptions(
  //       android: AndroidChromeCustomTabsOptions(
  //         addDefaultShareMenuItem: false,
  //         enableUrlBarHiding: true,
  //         toolbarBackgroundColor: AppColor.primaryColor,
  //       ),
  //       ios: IOSSafariOptions(
  //         barCollapsingEnabled: true,
  //         preferredBarTintColor: AppColor.primaryColor,
  //       ),
  //     ),
  //   );

  // }
  openWebpageLink(String url, {bool external = false}) async {
    if (Platform.isIOS || external) {
      await launchUrlString(url);
      return;
    }
    await viewContext.push(
      (context) => CustomWebviewPage(
        selectedUrl: url,
      ),
    );
  }

  Future<dynamic> openExternalWebpageLink(String url) async {
    try {
      await launchUrlString(
        url,
        mode: LaunchMode.externalApplication,
      );
      return;
    } catch (error) {
      ToastService.toastError("$error");
    }
  }

  //show toast
  toastSuccessful(String msg, {String? title}) {
    AlertController.show(
      title ?? "Successful".tr(),
      msg,
      TypeAlert.success,
    );
  }

  toastError(String msg, {String? title}) {
    AlertController.show(
      title ?? "Error".tr(),
      msg,
      TypeAlert.error,
    );
  }
}
