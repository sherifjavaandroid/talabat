import 'package:rx_shared_preferences/rx_shared_preferences.dart';

class LocalStorageService {
  static SharedPreferences? prefs;
  static RxSharedPreferences? rxPrefs;

  static Future<SharedPreferences> getPrefs() async {
    try {
      if (prefs == null) {
        prefs = await SharedPreferences.getInstance();
        rxPrefs = RxSharedPreferences(prefs!, null);
      }
    } catch (error) {
      print("Error Getting SharedPreference => $error");
    }
    // prefs.clear();
    return prefs!;
  }
}
