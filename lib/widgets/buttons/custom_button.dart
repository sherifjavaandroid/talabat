import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/constants/app_text_styles.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:fuodz/widgets/busy_indicator.dart';

import 'package:velocity_x/velocity_x.dart';

class CustomButton extends StatelessWidget {
  final String? title;
  final IconData? icon;
  final double? iconSize;
  final Widget? child;
  final TextStyle? titleStyle;
  final Function? onPressed;
  final Function? onLongPress;
  final OutlinedBorder? shape;
  final bool isFixedHeight;
  final double? height;
  final bool loading;
  final double shapeRadius;
  final Color? color;
  final Color? iconColor;
  final double? elevation;

  const CustomButton({
    this.title,
    this.icon,
    this.iconSize,
    this.iconColor,
    this.child,
    this.onPressed,
    this.onLongPress,
    this.shape,
    this.isFixedHeight = false,
    this.height,
    this.loading = false,
    this.shapeRadius = Vx.dp4,
    this.color,
    this.titleStyle,
    this.elevation,
    Key? key,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return ButtonTheme(
      padding: EdgeInsets.all(0),
      child: ElevatedButton(
        style: ElevatedButton.styleFrom(
          tapTargetSize: MaterialTapTargetSize.shrinkWrap,
          backgroundColor: this.color ?? AppColor.primaryColor,
          disabledBackgroundColor: this.loading ? AppColor.primaryColor : null,
          elevation: this.elevation,
          shape: this.shape ??
              RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(this.shapeRadius),
              ),
        ),
        onPressed: (this.loading || this.onPressed == null)
            ? null
            : () {
                //change focus to new focus node
                FocusScope.of(context).requestFocus(new FocusNode());
                this.onPressed!();
              },
        onLongPress: (this.loading || this.onLongPress == null)
            ? null
            : () {
                //change focus to new focus node
                FocusScope.of(context).requestFocus(new FocusNode());
                this.onLongPress!();
              },
        child: this.loading
            ? BusyIndicator(color: Colors.white)
            : Container(
                width: null, //double.infinity,
                height: this.isFixedHeight ? Vx.dp48 : (this.height ?? Vx.dp48),
                child: this.child ??
                    Row(
                      crossAxisAlignment: CrossAxisAlignment.center,
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        this.icon != null
                            ? Icon(this.icon,
                                    color: this.iconColor ?? Colors.white,
                                    size: this.iconSize ?? 20,
                                    textDirection: Utils.isArabic
                                        ? TextDirection.rtl
                                        : TextDirection.ltr)
                                .pOnly(
                                right: Utils.isArabic ? Vx.dp0 : Vx.dp5,
                                left: Utils.isArabic ? Vx.dp0 : Vx.dp5,
                              )
                            : UiSpacer.emptySpace(),
                        (this.title != null && this.title!.isNotBlank)
                            ? Text(
                                "${this.title}",
                                textAlign: TextAlign.center,
                                style: this.titleStyle ??
                                    AppTextStyle.h3TitleTextStyle(
                                      color: Colors.white,
                                    ),
                              ).centered()
                            : UiSpacer.emptySpace(),
                      ],
                    ),
              ),
      ),
    );
  }
}
