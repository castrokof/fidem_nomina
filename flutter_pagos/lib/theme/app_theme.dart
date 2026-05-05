import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class AppColors {
  static const blue    = Color(0xFF007AFF);
  static const green   = Color(0xFF34C759);
  static const red     = Color(0xFFFF3B30);
  static const orange  = Color(0xFFFF9500);
  static const purple  = Color(0xFF5856D6);
  static const gray    = Color(0xFF8E8E93);
  static const gray2   = Color(0xFFAEAEB2);
  static const gray3   = Color(0xFFD1D1D6);
  static const bg      = Color(0xFFF2F2F7);
  static const card    = Color(0xFFFFFFFF);
  static const label   = Color(0xFF3A3A3C);
  static const label2  = Color(0xFF636366);
}

ThemeData buildTheme() {
  return ThemeData(
    useMaterial3: true,
    colorScheme: ColorScheme.fromSeed(
      seedColor: AppColors.blue,
      surface: AppColors.card,
    ).copyWith(surface: AppColors.bg),
    textTheme: GoogleFonts.interTextTheme(),
    scaffoldBackgroundColor: AppColors.bg,
    appBarTheme: AppBarTheme(
      backgroundColor: AppColors.card,
      surfaceTintColor: Colors.transparent,
      elevation: 0,
      scrolledUnderElevation: 0.5,
      shadowColor: Colors.black12,
      titleTextStyle: GoogleFonts.inter(
        fontSize: 17,
        fontWeight: FontWeight.w700,
        color: AppColors.label,
      ),
      iconTheme: const IconThemeData(color: AppColors.blue),
    ),
    cardTheme: CardThemeData(
      color: AppColors.card,
      elevation: 0,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
      margin: EdgeInsets.zero,
    ),
    inputDecorationTheme: InputDecorationTheme(
      filled: true,
      fillColor: AppColors.card,
      border: OutlineInputBorder(
        borderRadius: BorderRadius.circular(10),
        borderSide: const BorderSide(color: AppColors.gray3),
      ),
      enabledBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(10),
        borderSide: const BorderSide(color: AppColors.gray3),
      ),
      focusedBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(10),
        borderSide: const BorderSide(color: AppColors.blue, width: 1.5),
      ),
      contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
      labelStyle: const TextStyle(color: AppColors.gray, fontSize: 13),
    ),
    elevatedButtonTheme: ElevatedButtonThemeData(
      style: ElevatedButton.styleFrom(
        backgroundColor: AppColors.blue,
        foregroundColor: Colors.white,
        elevation: 0,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
        padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 13),
        textStyle: GoogleFonts.inter(fontSize: 15, fontWeight: FontWeight.w600),
      ),
    ),
    bottomNavigationBarTheme: const BottomNavigationBarThemeData(
      backgroundColor: AppColors.card,
      selectedItemColor: AppColors.blue,
      unselectedItemColor: AppColors.gray2,
      type: BottomNavigationBarType.fixed,
      elevation: 0,
    ),
  );
}
