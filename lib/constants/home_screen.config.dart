import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/views/pages/welcome/widgets/modern.welcome.empty.dart';
import 'package:fuodz/views/pages/welcome/widgets/plain.welcome.empty.dart';
import 'package:fuodz/views/pages/welcome/widgets/welcome.empty.dart';
import 'package:supercharged/supercharged.dart';

class HomeScreenConfig {
  //
  static bool get showBannerOnHomeScreen {
    dynamic uiEnv = AppStrings.env("ui");
    if (uiEnv == null || uiEnv["home"] == null) {
      return false;
    }

    return uiEnv['home']["showBannerOnHomeScreen"] ?? false;
  }

  static bool get showWalletOnHomeScreen {
    dynamic uiEnv = AppStrings.env("ui");
    if (uiEnv == null || uiEnv["home"] == null) {
      return true;
    }

    return uiEnv['home']["showWalletOnHomeScreen"] ?? true;
  }

  static bool get isBannerPositionTop {
    try {
      dynamic uiEnv = AppStrings.env("ui");
      if (uiEnv == null ||
          uiEnv["home"] == null ||
          uiEnv['home']["bannerPosition"] == null) {
        return true;
      }
      return (uiEnv['home']["bannerPosition"].toString().toLowerCase() ==
          "top");
    } catch (e) {
      print(e);
      return true;
    }
  }

  static bool get isVendorTypeListingBoth {
    try {
      dynamic uiEnv = AppStrings.env("ui");
      if (uiEnv == null ||
          uiEnv["home"] == null ||
          uiEnv['home']["vendortypeListStyle"] == null) {
        return true;
      }
      return [
        "both"
      ].contains(uiEnv['home']["vendortypeListStyle"].toString().toLowerCase());
    } catch (e) {
      print(e);
      return false;
    }
  }

  static bool get isVendorTypeListingGridView {
    try {
      dynamic uiEnv = AppStrings.env("ui");
      if (uiEnv == null ||
          uiEnv["home"] == null ||
          uiEnv['home']["vendortypeListStyle"] == null) {
        print("styles ==> ${uiEnv['home']}");
        return true;
      }
      return [
        "gridview",
        "both"
      ].contains(uiEnv['home']["vendortypeListStyle"].toString().toLowerCase());
    } catch (e) {
      return false;
    }
  }

  static bool get isVendorTypeListingListView {
    try {
      dynamic uiEnv = AppStrings.env("ui");
      if (uiEnv == null ||
          uiEnv["home"] == null ||
          uiEnv['home']["vendortypeListStyle"] == null) {
        return true;
      }
      return [
        "listview",
        "both"
      ].contains(uiEnv['home']["vendortypeListStyle"].toString().toLowerCase());
    } catch (e) {
      return false;
    }
  }

  static int get vendorTypePerRow {
    dynamic uiEnv = AppStrings.env("ui");
    if (uiEnv == null ||
        uiEnv["home"] == null ||
        uiEnv['home']["vendortypePerRow"] == null) {
      return 2;
    }
    return (uiEnv['home']["vendortypePerRow"].toString().toInt()) ?? 2;
  }

  static bool get allowWalletTransfer {
    dynamic financeEnv = AppStrings.env("finance");
    if (financeEnv == null || financeEnv["allowWalletTransfer"] == null) {
      return false;
    }
    return financeEnv['allowWalletTransfer'].toString() == "1";
  }

  static Widget homeScreen(vm, key) {
    dynamic style = 1;
    dynamic uiEnv = AppStrings.env("ui");
    if (uiEnv == null ||
        uiEnv["home"] == null ||
        uiEnv['home']["homeViewStyle"] == null) {
      style = 1;
    }
    style = (uiEnv['home']["homeViewStyle"].toString().toInt()) ?? 1;
    Widget ui;
    switch (style) {
      case 1:
        ui = EmptyWelcome(vm: vm, key: key);
        break;
      case 2:
        ui = ModernEmptyWelcome(vm: vm, key: key);
        break;
      case 3:
        ui = PlainEmptyWelcome(vm: vm, key: key);
        break;
      default:
        ui = ModernEmptyWelcome(vm: vm, key: key);
        break;
    }
    return ui;
  }
}
