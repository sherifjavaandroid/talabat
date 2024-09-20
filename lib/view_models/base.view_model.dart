import 'dart:io';

import 'package:flutter/material.dart';
import 'package:flutter_dropdown_alert/alert_controller.dart';
import 'package:flutter_dropdown_alert/model/data_alert.dart';
import 'package:flutter_form_builder/flutter_form_builder.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/models/delivery_address.dart';
import 'package:fuodz/services/toast.service.dart';
import 'package:fuodz/services/update.service.dart';
import 'package:fuodz/views/pages/shared/custom_webview.page.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:url_launcher/url_launcher_string.dart';
import 'package:fuodz/extensions/context.dart';

class MyBaseViewModel extends BaseViewModel with UpdateService {
  //
  late BuildContext viewContext;
  final formKey = GlobalKey<FormState>();
  final formBuilderKey = GlobalKey<FormBuilderState>();
  final currencySymbol = AppStrings.currencySymbol;
  GlobalKey pageKey = GlobalKey<FormState>();
  DeliveryAddress? deliveryaddress;
  String? firebaseVerificationId;

  void initialise() {
    //FirestoreRepository();
  }

  newPageKey() {
    pageKey = GlobalKey<FormState>();
    notifyListeners();
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
      print("Error ==> $error");
      ToastService.toastError("$error");
    }
  }
}
