import 'package:flutter/material.dart';
import 'package:fuodz/constants/printer_values.dart';
import 'package:fuodz/services/local_storage.service.dart';
import 'package:fuodz/view_models/base.view_model.dart';
import 'package:localize_and_translate/localize_and_translate.dart';

class PrinterSettingsPageViewModel extends MyBaseViewModel {
  String? paperSize;
  String? printerType;
  bool autoPrint = false;

  PrinterSettingsPageViewModel(BuildContext context) {
    //
    paperSize = LocalStorageService.prefs!.getString(
      PrinterSettingValues.paperSizeKey,
    );
    paperSize ??= PrinterSettingValues.paperSizes.first;
    //printer type
    printerType = LocalStorageService.prefs!.getString(
      PrinterSettingValues.printerTypeKey,
    );
    printerType ??= PrinterSettingValues.printerTypes.first;
    //auto print
    autoPrint = LocalStorageService.prefs!.getBool(
          PrinterSettingValues.autoPrintKey,
        ) ??
        false;
  }

  void setPaperSize(String? value) {
    paperSize = value;
    notifyListeners();
  }

  void setPrinterType(String? value) {
    printerType = value;
    notifyListeners();
  }

  void setAutoPrint(bool value) {
    autoPrint = value;
    notifyListeners();
  }

  save() async {
    try {
      await LocalStorageService.prefs!.setString(
        PrinterSettingValues.paperSizeKey,
        paperSize!,
      );
      await LocalStorageService.prefs!.setString(
        PrinterSettingValues.printerTypeKey,
        printerType!,
      );
      await LocalStorageService.prefs!.setBool(
        PrinterSettingValues.autoPrintKey,
        autoPrint,
      );

      //
      toastSuccessful("Settings saved successfully".tr());
    } catch (e) {
      toastError("An error occurred. Please try again".tr());
    }
  }

  //
}
