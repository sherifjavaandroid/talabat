import 'package:fuodz/services/local_storage.service.dart';
import 'package:rx_shared_preferences/rx_shared_preferences.dart';

class SearchService {
  static Future<List<String>> getSearchHistory() async {
    final searchHistory =
        await LocalStorageService.rxPrefs!.getStringList("search_history");
    return searchHistory ?? [];
  }

  static Future<void> saveSearchHistory(String keyword) async {
    final searchHistory = await getSearchHistory();

    //prevent duplicate
    if (searchHistory.contains(keyword)) {
      searchHistory.removeWhere((element) => element == keyword);
    }

    searchHistory.add(keyword);
    await LocalStorageService.rxPrefs!
        .setStringList("search_history", searchHistory);
  }
}
