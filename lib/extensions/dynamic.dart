import 'package:currency_formatter/currency_formatter.dart';
import 'package:fuodz/constants/app_strings.dart';

extension DynamicParsing on dynamic {
  //if not sumbol was adde
  String formatCurSym() {
    String value = this.toString().replaceAll(AppStrings.currencySymbol, "");
    value = value.trim();
    value = "${AppStrings.currencySymbol} $value";
    return value.currencyFormat();
  }

  //
  String currencyFormat() {
    final uiConfig = AppStrings.uiConfig;
    if (uiConfig != null && uiConfig["currency"] != null) {
      //
      final thousandSeparator = uiConfig["currency"]["format"] ?? ",";
      final decimalSeparator = uiConfig["currency"]["decimal_format"] ?? ".";
      final decimals = uiConfig["currency"]["decimals"];
      final currencylOCATION = uiConfig["currency"]["location"] ?? 'left';
      final decimalsValue =
          "".padLeft(int.tryParse(decimals.toString()) ?? 0, "0");

      //
      //
      final values =
          this.toString().split(" ").join("").split(AppStrings.currencySymbol);

      //
      CurrencyFormat currencySettings = CurrencyFormat(
        symbol: AppStrings.currencySymbol,
        symbolSide: currencylOCATION.toLowerCase() == "left"
            ? SymbolSide.left
            : SymbolSide.right,
        thousandSeparator: thousandSeparator,
        decimalSeparator: decimalSeparator,
      );

      return CurrencyFormatter.format(
        values[1],
        currencySettings,
        decimal: decimalsValue.length,
        enforceDecimals: true,
      );
    } else {
      return this.toString();
    }
  }

  //
  String currencyValueFormat() {
    final uiConfig = AppStrings.uiConfig;
    if (uiConfig != null && uiConfig["currency"] != null) {
      final thousandSeparator = uiConfig["currency"]["format"] ?? ",";
      final decimalSeparator = uiConfig["currency"]["decimal_format"] ?? ".";
      final decimals = uiConfig["currency"]["decimals"];
      final decimalsValue =
          "".padLeft(int.tryParse(decimals.toString()) ?? 0, "0");
      final values = this.toString().split(" ").join("");

      //
      CurrencyFormat currencySettings = CurrencyFormat(
        symbol: "",
        symbolSide: SymbolSide.right,
        thousandSeparator: thousandSeparator,
        decimalSeparator: decimalSeparator,
      );

      return CurrencyFormatter.format(
        values,
        currencySettings,
        decimal: decimalsValue.length,
        enforceDecimals: true,
      );
    } else {
      return this.toString();
    }
  }

  //
  String fill(List values) {
    //
    String data = this.toString();
    for (var value in values) {
      data = data.replaceFirst("%s", value.toString());
    }
    return data;
  }
}
