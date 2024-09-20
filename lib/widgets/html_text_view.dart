import 'package:flutter/material.dart';
import 'package:flutter_widget_from_html_core/flutter_widget_from_html_core.dart';
import 'package:url_launcher/url_launcher_string.dart';

class HtmlTextView extends StatelessWidget {
  const HtmlTextView(
    this.htmlContent, {
    this.padding = const EdgeInsets.symmetric(horizontal: 20),
    Key? key,
  }) : super(key: key);

  final String htmlContent;
  final EdgeInsetsGeometry padding;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: padding,
      child: HtmlWidget(
        htmlContent,
        onTapImage: (ImageMetadata imageMetadata) {
          try {
            launchUrlString(imageMetadata.sources.first.url);
          } catch (e) {
            print(e);
          }
        },
        onTapUrl: (url) {
          return launchUrlString(url);
        },
      ),
    );
  }
}
