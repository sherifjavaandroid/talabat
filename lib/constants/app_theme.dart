import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:google_fonts/google_fonts.dart';

class AppTheme {
  //
  ThemeData lightTheme() {
    return ThemeData(
      // fontFamily: GoogleFonts.ibmPlexSerif().fontFamily,
      // fontFamily: GoogleFonts.krub().fontFamily,
      // fontFamily: GoogleFonts.montserrat().fontFamily,
      // fontFamily: GoogleFonts.poppins().fontFamily,
      fontFamily: GoogleFonts.roboto().fontFamily,
      // fontFamily: GoogleFonts.nunito().fontFamily,
      // fontFamily: GoogleFonts.jetBrainsMono().fontFamily,
      // backgroundColor: Colors.white,
      primaryColor: AppColor.primaryColor,
      primaryColorDark: AppColor.primaryColorDark,
      textSelectionTheme: TextSelectionThemeData(
        selectionColor: Colors.grey,
        cursorColor: AppColor.cursorColor,
      ),
      cardColor: Colors.grey[50],
      textTheme: blackTextTheme,
      bottomSheetTheme: BottomSheetThemeData(
        backgroundColor: Colors.white,
      ),
      // brightness: Brightness.light,
      // CUSTOMIZE showDatePicker Colors
      dialogBackgroundColor: Colors.white,
      buttonTheme: ButtonThemeData(textTheme: ButtonTextTheme.primary),
      highlightColor: Colors.grey[400],
      colorScheme: ColorScheme.light(
        primary: AppColor.primaryColor,
        secondary: AppColor.accentColor,
        brightness: Brightness.light,
      ).copyWith(
        primary: AppColor.primaryMaterialColor,
        surface: Colors.white,
      ),
      //
      tabBarTheme: tabBarTheme,
      useMaterial3: true,
    );
  }

  //
  ThemeData darkTheme() {
    return ThemeData(
      // fontFamily: GoogleFonts.iBMPlexSerif().fontFamily,
      // fontFamily: GoogleFonts.krub().fontFamily,
      fontFamily: GoogleFonts.roboto().fontFamily,
      // fontFamily: GoogleFonts.nunito().fontFamily,
      // fontFamily: GoogleFonts.jetBrainsMono().fontFamily,
      // fontFamily: GoogleFonts.montserrat().fontFamily,
      primaryColor: AppColor.primaryColor,
      primaryColorDark: AppColor.primaryColorDark,
      textSelectionTheme: TextSelectionThemeData(
        selectionColor: Colors.grey,
        cursorColor: AppColor.cursorColor,
      ),
      // backgroundColor: Colors.grey[850],
      cardColor: Colors.grey[700],
      textTheme: whiteTextTheme,
      bottomSheetTheme: BottomSheetThemeData(
        backgroundColor: Colors.black,
      ),
      colorScheme: ColorScheme.fromSwatch()
          .copyWith(
            primary: AppColor.primaryColor,
            secondary: AppColor.accentColor,
            brightness: Brightness.dark,
          )
          .copyWith(
            primary: AppColor.primaryMaterialColor,
            surface: Colors.grey[850],
          ),

      //
      tabBarTheme: tabBarTheme,
      useMaterial3: true,
    );
  }

  //MISC
  final TextTheme blackTextTheme = TextTheme(
    displayLarge: TextStyle(color: Colors.black),
    displayMedium: TextStyle(color: Colors.black),
    displaySmall: TextStyle(color: Colors.black),
    headlineLarge: TextStyle(color: Colors.black),
    headlineMedium: TextStyle(color: Colors.black),
    headlineSmall: TextStyle(color: Colors.black),
    titleLarge: TextStyle(color: Colors.black),
    titleMedium: TextStyle(color: Colors.black),
    titleSmall: TextStyle(color: Colors.black),
    bodyLarge: TextStyle(color: Colors.black),
    bodyMedium: TextStyle(color: Colors.black),
    bodySmall: TextStyle(color: Colors.black),
    labelLarge: TextStyle(color: Colors.black),
    labelMedium: TextStyle(color: Colors.black),
    labelSmall: TextStyle(color: Colors.black),
  );

  final TextTheme whiteTextTheme = TextTheme(
    displayLarge: TextStyle(color: Colors.white),
    displayMedium: TextStyle(color: Colors.white),
    displaySmall: TextStyle(color: Colors.white),
    headlineLarge: TextStyle(color: Colors.white),
    headlineMedium: TextStyle(color: Colors.white),
    headlineSmall: TextStyle(color: Colors.white),
    titleLarge: TextStyle(color: Colors.white),
    titleMedium: TextStyle(color: Colors.white),
    titleSmall: TextStyle(color: Colors.white),
    bodyLarge: TextStyle(color: Colors.white),
    bodyMedium: TextStyle(color: Colors.white),
    bodySmall: TextStyle(color: Colors.white),
    labelLarge: TextStyle(color: Colors.white),
    labelMedium: TextStyle(color: Colors.white),
    labelSmall: TextStyle(color: Colors.white),
  );

  //
  TabBarTheme get tabBarTheme {
    return TabBarTheme(
      indicatorSize: TabBarIndicatorSize.tab,
      labelColor: AppColor.primaryColor,
      unselectedLabelColor: Utils.textColorByTheme(),
      // labelColor: Utils.textColorByTheme(),
      indicator: BoxDecoration(
        // color: AppColor.primaryColor,
        border: Border(
          bottom: BorderSide(
            color: Utils.textColorByTheme(),
            width: 3,
          ),
        ),
      ),
      labelStyle: TextStyle(
        fontWeight: FontWeight.w500,
      ),
      unselectedLabelStyle: TextStyle(
        fontWeight: FontWeight.normal,
      ),
      tabAlignment: TabAlignment.start,
    );
  }
}
