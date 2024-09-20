import 'package:flutter_esc_pos_utils/flutter_esc_pos_utils.dart';
import 'package:fuodz/services/local_storage.service.dart';

class PrinterSettingValues {
  static List<String> printerTypes = [
    "In-built",
    "External",
  ];

  //
  static List<String> get paperSizes => [
        "80mm",
        "58mm",
      ];

  //Key values
  static const String paperSizeKey = "printer.paperSize";
  static const String printerTypeKey = "printer.type";
  static const String autoPrintKey = "printer.autoPrint";

  //default values
  static bool get isAutoPrintEnabled {
    return LocalStorageService.prefs!.getBool(autoPrintKey) ?? false;
  }

  static bool get useExternalPrinter {
    return LocalStorageService.prefs!.getString(printerTypeKey) == "External";
  }

  static PaperSize paperSizeToWidth() {
    String size = LocalStorageService.prefs!.getString(
          paperSizeKey,
        ) ??
        paperSizes.first;
    size = size.replaceAll("mm", "");
    int paperSizeInMM = int.parse(size);
    if (paperSizeInMM == 58) {
      return PaperSize.mm58;
    } else {
      return PaperSize.mm80;
    }
  }

  static PaperSize paperSize() {
    String size = LocalStorageService.prefs!.getString(
          paperSizeKey,
        ) ??
        paperSizes.first;
    size = size.replaceAll("mm", "");
    int paperSizeInMM = int.parse(size);
    if (paperSizeInMM == 58) {
      return PaperSize.mm58;
    } else {
      return PaperSize.mm80;
    }
  }

  static int maxCharPerLineSize(int size) {
    if (size == 58) {
      return PaperSize.mm58.value;
    } else {
      return PaperSize.mm58.value;
    }
  }
}
