import 'package:fuodz/constants/app_strings.dart';

class AppFileLimit extends AppStrings {
  //prescription file limit
  static int get prescriptionFileLimit {
    if (AppStrings.env('file_limit') != null &&
        AppStrings.env('file_limit')['prescription'] != null &&
        AppStrings.env('file_limit')['prescription']["file_limit"] != null) {
      try {
        return int.parse(AppStrings.env('file_limit')['prescription']
                ["file_limit"]
            .toString());
      } catch (error) {
        return 2;
      }
    }
    return 2;
  }

  //prescription file size limit
  static int get prescriptionFileSizeLimit {
    if (AppStrings.env('file_limit') != null &&
        AppStrings.env('file_limit')['prescription'] != null &&
        AppStrings.env('file_limit')['prescription']["file_size_limit"] !=
            null) {
      try {
        return int.parse(AppStrings.env('file_limit')['prescription']
                ["file_size_limit"]
            .toString());
      } catch (error) {
        return 1024;
      }
    }
    //return 1mb in kb
    return 1024;
  }
}
