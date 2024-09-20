import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:fuodz/views/shared/go_to_cart.view.dart';
import 'package:fuodz/widgets/cart_page_action.dart';
import 'package:keyboard_dismisser/keyboard_dismisser.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:measure_size/measure_size.dart';
import 'package:velocity_x/velocity_x.dart';

class BasePage extends StatefulWidget {
  final bool showAppBar;
  final bool showLeadingAction;
  final bool? extendBodyBehindAppBar;
  final Function? onBackPressed;
  final bool showCart;
  final dynamic title;
  final List<Widget>? actions;
  final Widget? leading;
  final Widget body;
  final Widget? bottomSheet;
  final Widget? bottomNavigationBar;
  final Widget? fab;
  final FloatingActionButtonLocation? fabLocation;
  final bool isLoading;
  final Color? appBarColor;
  final double? elevation;
  final Color? appBarItemColor;
  final Color? backgroundColor;
  final bool showCartView;
  final PreferredSize? customAppbar;

  BasePage({
    this.showAppBar = false,
    this.leading,
    this.showLeadingAction = false,
    this.onBackPressed,
    this.showCart = false,
    this.title = "",
    this.actions,
    required this.body,
    this.bottomSheet,
    this.bottomNavigationBar,
    this.fab,
    this.fabLocation,
    this.isLoading = false,
    this.appBarColor,
    this.appBarItemColor,
    this.backgroundColor,
    this.elevation,
    this.extendBodyBehindAppBar,
    this.showCartView = false,
    this.customAppbar,
    Key? key,
  }) : super(key: key);

  @override
  _BasePageState createState() => _BasePageState();
}

class _BasePageState extends State<BasePage> {
  //
  double bottomPaddingSize = 0;

  //
  @override
  Widget build(BuildContext context) {
    return Directionality(
      textDirection: translator.activeLocale.languageCode == "ar"
          ? TextDirection.rtl
          : TextDirection.ltr,
      child: KeyboardDismisser(
        child: Scaffold(
          resizeToAvoidBottomInset: false,
          backgroundColor: widget.backgroundColor ?? AppColor.faintBgColor,
          extendBodyBehindAppBar: widget.extendBodyBehindAppBar ?? false,
          appBar: widget.customAppbar != null
              ? widget.customAppbar
              : widget.showAppBar
                  ? AppBar(
                      backgroundColor:
                          widget.appBarColor ?? context.primaryColor,
                      elevation: widget.elevation,
                      automaticallyImplyLeading: widget.showLeadingAction,
                      leading: widget.showLeadingAction
                          ? widget.leading == null
                              ? IconButton(
                                  icon: Icon(
                                    !Utils.isArabic
                                        ? FlutterIcons.arrow_left_fea
                                        : FlutterIcons.arrow_right_fea,
                                    color: widget.appBarItemColor == null
                                        ? Colors.white
                                        : widget.appBarItemColor !=
                                                Colors.transparent
                                            ? widget.appBarItemColor
                                            : AppColor.primaryColor,
                                  ),
                                  onPressed: widget.onBackPressed != null
                                      ? () => widget.onBackPressed!()
                                      : () => Navigator.pop(context),
                                )
                              : widget.leading
                          : null,
                      title: widget.title is Widget
                          ? widget.title
                          : "${widget.title}"
                              .text
                              .maxLines(1)
                              .overflow(TextOverflow.ellipsis)
                              .color(widget.appBarItemColor ?? Colors.white)
                              .make(),
                      actions: widget.actions ??
                          [
                            widget.showCart
                                ? PageCartAction()
                                : UiSpacer.emptySpace(),
                          ],
                    )
                  : null,
          body: Stack(
            children: [
              //body
              VStack(
                [
                  //
                  widget.isLoading
                      ? LinearProgressIndicator()
                      : UiSpacer.emptySpace(),

                  //
                  widget.body.pOnly(bottom: bottomPaddingSize).expand(),
                ],
              ),

              //cart view
              Positioned(
                bottom: 0,
                left: 0,
                right: 0,
                child: Visibility(
                  visible: widget.showCartView,
                  child: MeasureSize(
                    onChange: (size) {
                      setState(() {
                        bottomPaddingSize = size.height;
                      });
                    },
                    child: GoToCartView(),
                  ),
                ),
              ),
            ],
          ),
          bottomNavigationBar: widget.bottomNavigationBar,
          bottomSheet: widget.bottomSheet,
          floatingActionButton: widget.fab,
          floatingActionButtonLocation: widget.fabLocation,
        ),
      ),
    );
  }
}
