import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/utils/utils.dart';

import 'package:velocity_x/velocity_x.dart';

class BasePage extends StatefulWidget {
  final bool showAppBar;
  final bool showLeadingAction;
  final bool showCart;
  final Function? onBackPressed;
  final String? title;
  final Widget body;
  final Widget? bottomSheet;
  final Widget? fab;
  final bool isLoading;
  final bool extendBodyBehindAppBar;
  final double? elevation;
  final Color? appBarItemColor;
  final Color? backgroundColor;
  final Color? appBarColor;
  final Widget? leading;
  final Widget? bottomNavigationBar;

  BasePage({
    this.showAppBar = false,
    this.showLeadingAction = false,
    this.leading,
    this.showCart = false,
    this.onBackPressed,
    this.title = "",
    required this.body,
    this.bottomSheet,
    this.fab,
    this.isLoading = false,
    this.appBarColor,
    this.elevation,
    this.extendBodyBehindAppBar = false,
    this.appBarItemColor,
    this.backgroundColor,
    this.bottomNavigationBar,
    Key? key,
  }) : super(key: key);

  @override
  _BasePageState createState() => _BasePageState();
}

class _BasePageState extends State<BasePage> {
  @override
  Widget build(BuildContext context) {
    return Directionality(
      textDirection: Utils.textDirection,
      child: Scaffold(
        resizeToAvoidBottomInset: false,
        backgroundColor:
            widget.backgroundColor ?? Theme.of(context).colorScheme.surface,
        extendBodyBehindAppBar: widget.extendBodyBehindAppBar,
        appBar: widget.showAppBar
            ? AppBar(
                backgroundColor: widget.appBarColor ?? context.primaryColor,
                automaticallyImplyLeading: widget.showLeadingAction,
                elevation: widget.elevation,
                leading: widget.showLeadingAction
                    ? widget.leading == null
                        ? IconButton(
                            icon: Icon(
                              !Utils.isArabic
                                  ? FlutterIcons.arrow_left_fea
                                  : FlutterIcons.arrow_right_fea,
                            ),
                            onPressed: (widget.onBackPressed != null)
                                ? () => widget.onBackPressed!()
                                : () => Navigator.pop(context),
                          )
                        : widget.leading
                    : null,
                title: Text(
                  "${widget.title}",
                ),
              )
            : null,
        body: VStack(
          [
            //
            widget.isLoading
                ? LinearProgressIndicator()
                : UiSpacer.emptySpace(),

            //
            widget.body.expand(),
          ],
        ),
        bottomSheet: widget.bottomSheet,
        floatingActionButton: widget.fab,
        // floatingActionButtonLocation: FloatingActionButtonLocation.centerDocked,
        bottomNavigationBar: widget.bottomNavigationBar,
      ),
    );
  }
}
