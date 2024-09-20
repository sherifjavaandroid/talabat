import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/constants/app_text_styles.dart';
import 'package:fuodz/widgets/busy_indicator.dart';

class CustomTextButton extends StatelessWidget {
  final Function? onPressed;
  final String title;
  final Color? titleColor;
  final EdgeInsets? padding;
  final bool loading;

  //
  const CustomTextButton({
    required this.title,
    this.onPressed,
    this.titleColor,
    this.padding,
    this.loading = false,
    Key? key,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return TextButton(
      onPressed: this.onPressed != null ? () => this.onPressed!() : null,
      style: TextButton.styleFrom(
        padding: this.padding,
      ),
      child: loading
          ? Center(
              child: BusyIndicator(),
            )
          : Text(
              this.title,
              style: AppTextStyle.h4TitleTextStyle(
                color: titleColor ?? AppColor.primaryColor,
              ),
            ),
    );
  }
}
