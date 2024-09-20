import 'package:flutter/material.dart';
import 'package:flutter_widget_from_html_core/flutter_widget_from_html_core.dart';
import 'package:url_launcher/url_launcher_string.dart';
import 'package:velocity_x/velocity_x.dart';

class HtmlTextView extends StatelessWidget {
  const HtmlTextView(
    this.htmlContent, {
    this.padding,
    Key? key,
  }) : super(key: key);

  final String? htmlContent;
  final double? padding;

  @override
  Widget build(BuildContext context) {
    return HtmlWidget(
      htmlContent ?? "",
      onTapImage: (ImageMetadata imageMetadata) async {
        final url = imageMetadata.sources.first.url;
        await launchUrlString(url);
      },
      onTapUrl: (url) {
        return launchUrlString(url);
      },
    ).px(padding ?? 20);
  }
}
