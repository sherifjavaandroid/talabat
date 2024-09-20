import 'dart:convert';
import 'package:fuodz/services/local_storage.service.dart';
import 'package:supercharged/supercharged.dart';

class AppStrings {
  //
  static String get appName => env('app_name');
  static String get companyName => env('company_name');
  static String get googleMapApiKey => env('google_maps_key');
  static String get fcmApiKey => env('fcm_key');
  static String get currencySymbol => env('currency');
  static String get countryCode => env('country_code');

  //
  static bool get enableOtp => env('enble_otp') == "1";
  static bool get enableOTPLogin => env('enableOTPLogin') == "1";
  static bool get enableEmailLogin => env('enableEmailLogin');
  static bool get enableProfileUpdate => env('enableProfileUpdate');
  //

  static bool get enableProofOfDelivery => env('enableProofOfDelivery') == "1";
  static bool get signatureVerify =>
      env('orderVerificationType') == "signature";
  static bool get verifyOrderByPhoto => env('orderVerificationType') == "photo";
  static bool get enableDriverWallet => env('enableDriverWallet') == "1";
  static bool get enableChat => env('enableChat') == "1";
  static bool get partnersCanRegister =>
      ["1", 1].contains(env('partnersCanRegister'));
  static double get driverSearchRadius =>
      double.parse((env('driverSearchRadius') ?? 10).toString());
  static int get maxDriverOrderAtOnce =>
      int.parse((env('maxDriverOrderAtOnce') ?? 1).toString());

  static double get distanceCoverLocationUpdate =>
      double.parse((env('distanceCoverLocationUpdate') ?? 10).toString());
  static int get timePassLocationUpdate =>
      int.parse((env('timePassLocationUpdate') ?? 10).toString());
  //
  static int get alertDuration {
    final duration = env('alertDuration').toString().toInt();
    if (duration == null || duration < 10) {
      return 10;
    }
    return duration;
  }

  static bool get driverMatchingNewSystem =>
      ((env('autoassignmentsystem') ?? 0) == 1);

  //
  static String get otpGateway => env('otpGateway') ?? "none";
  static bool get isFirebaseOtp => otpGateway.toLowerCase() == "firebase";
  static bool get isCustomOtp =>
      !["none", "firebase"].contains(otpGateway.toLowerCase());
  static String get emergencyContact => env('emergencyContact') ?? "911";

  //UI Configures
  static dynamic get uiConfig {
    return env('ui') ?? null;
  }

  static bool get qrcodeLogin => env('auth')['qrcodeLogin'];

  //DONT'T TOUCH
  static const String notificationChannel = "high_importance_channel";

  //START DON'T TOUNCH
  //for app usage
  static String firstTimeOnApp = "first_time";
  static String authenticated = "authenticated";
  static String userAuthToken = "auth_token";
  static String userKey = "user";
  static String driverVehicleKey = "driver_vehicle";
  static String appLocale = "locale";
  static String notificationsKey = "notifications";
  static String appCurrency = "currency";
  static String appColors = "colors";
  static String appRemoteSettings = "appRemoteSettings";
  static String onlineOnApp = "online";
  //END DON'T TOUNCH

  //
  //Change to your app store id
  static String appStoreId = "";

  //
  //saving
  static Future<bool> saveAppSettingsToLocalStorage(String colorsMap) async {
    return await LocalStorageService.prefs!
        .setString(AppStrings.appRemoteSettings, colorsMap);
  }

  static dynamic appSettingsObject;
  static Future<void> getAppSettingsFromLocalStorage() async {
    appSettingsObject =
        LocalStorageService.prefs!.getString(AppStrings.appRemoteSettings);
    if (appSettingsObject != null) {
      appSettingsObject = jsonDecode(appSettingsObject);
    }
  }

  static dynamic env(String ref) {
    //
    getAppSettingsFromLocalStorage();
    //
    return appSettingsObject != null ? appSettingsObject[ref] : "";
  }
}
