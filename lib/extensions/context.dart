import 'package:flutter/material.dart';

extension NavigationExtensions on BuildContext {
  void pop([dynamic result]) {
    Navigator.of(this).pop(result);
  }

  Future<T?> push<T>(WidgetBuilder builder) {
    return Navigator.of(this).push(
      MaterialPageRoute(builder: builder),
    );
  }
}
