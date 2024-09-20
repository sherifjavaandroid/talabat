import 'package:flutter/material.dart';

class RoundedContainer extends StatelessWidget {
  final Widget child;
  final double radius;
  final List<BoxShadow>? boxShadow;

  RoundedContainer({
    required this.child,
    this.radius = 8.0,
    this.boxShadow,
  });

  @override
  Widget build(BuildContext context) {
    return ClipRRect(
      borderRadius: BorderRadius.circular(radius),
      child: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          boxShadow: boxShadow,
        ),
        child: child,
      ),
    );
  }
}
