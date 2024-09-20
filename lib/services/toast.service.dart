import 'package:flutter_dropdown_alert/alert_controller.dart';
import 'package:flutter_dropdown_alert/model/data_alert.dart';
import 'package:localize_and_translate/localize_and_translate.dart';

class ToastService {
  //
  //show toast
  static toastSuccessful(String msg, {String? title}) {
    AlertController.show(
      title ?? "Successful".tr(),
      msg,
      TypeAlert.success,
    );
  }

  static toastError(String msg, {String? title}) {
    AlertController.show(
      title ?? "Error".tr(),
      msg,
      TypeAlert.error,
    );
  }
}
