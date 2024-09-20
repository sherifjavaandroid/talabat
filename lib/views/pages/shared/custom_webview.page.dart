import 'dart:developer';
import 'dart:io';

import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:flutter_inappwebview/flutter_inappwebview.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/services/app.service.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:url_launcher/url_launcher_string.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:fuodz/extensions/context.dart';

class CustomWebviewPage extends StatefulWidget {
  //
  CustomWebviewPage({
    Key? key,
    required this.selectedUrl,
  }) : super(key: key);

  final String selectedUrl;

  @override
  _CustomWebviewPageState createState() => _CustomWebviewPageState();
}

class _CustomWebviewPageState extends State<CustomWebviewPage> {
  //
  String pageTitle = "";
  String selectedUrl = "";
  bool isLoading = false;
  final GlobalKey webViewKey = GlobalKey();
  InAppWebViewController? webViewController;
  InAppWebViewSettings initialSettings = InAppWebViewSettings(
    useShouldOverrideUrlLoading: true,
    mediaPlaybackRequiresUserGesture: false,
    allowFileAccess: true,
    javaScriptEnabled: true,
    allowsInlineMediaPlayback: true,
    clearCache: true,
    cacheEnabled: false,
    clearSessionCache: true,
    useHybridComposition: true,
  );

  PullToRefreshController? pullToRefreshController;
  String url = "";
  double progress = 0;
  final urlController = TextEditingController();
  bool pageClosed = false;

  @override
  void initState() {
    super.initState();
    pageClosed = false;

    //
    pullToRefreshController = PullToRefreshController(
      settings: PullToRefreshSettings(
        color: AppColor.primaryColor,
      ),
      onRefresh: () async {
        if (Platform.isAndroid) {
          webViewController?.reload();
        } else if (Platform.isIOS) {
          webViewController?.loadUrl(
              urlRequest: URLRequest(url: await webViewController?.getUrl()));
        }
      },
    );

    ///
    selectedUrl = widget.selectedUrl.replaceFirst("http://", "https://");
    if (!selectedUrl.contains("?")) {
      selectedUrl = "$selectedUrl?lan=${translator.activeLocale.languageCode}";
    } else {
      selectedUrl = "$selectedUrl&lan=${translator.activeLocale.languageCode}";
    }

    setState(() {
      selectedUrl = selectedUrl;
    });
  }

  //setup listeners
  setupCustomEventListener(
    InAppWebViewController controller,
    BuildContext context,
  ) {
    //close page
    controller.addJavaScriptHandler(
      handlerName: 'handlerClosePage',
      callback: (args) {
        //only call once
        if (pageClosed) {
          return;
        }
        closePage();
      },
    );
    //open link in browser
    controller.addJavaScriptHandler(
      handlerName: 'handlerOpenLink',
      callback: (args) {
        //only call once
        if (pageClosed) {
          return;
        }

        bool closePage = args[1] ?? true;
        String url = args[0];
        if (closePage) {
          this.closePage();
        }
        launchUrlString(
          url,
          mode: LaunchMode.externalApplication,
        );
      },
    );
  }

  //close page
  closePage() {
    //only call once
    if (pageClosed) {
      return;
    }
    AppService().navigatorKey.currentContext?.pop();
    pageClosed = true;
    setState(() {
      pageClosed = true;
    });
  }

  //UI Build
  @override
  Widget build(BuildContext context) {
    //
    return BasePage(
      showAppBar: true,
      showLeadingAction: true,
      title: pageTitle,
      leading: IconButton(
        icon: Icon(
          FlutterIcons.arrow_left_fea,
          color: Colors.white,
        ),
        onPressed: () => Navigator.pop(context),
      ),
      body: VStack(
        [
          //loading
          Visibility(
            visible: isLoading,
            child: LinearProgressIndicator(),
          ),
          //page
          InAppWebView(
            key: webViewKey,
            initialUrlRequest: URLRequest(
              url: WebUri.uri(
                Uri.parse(selectedUrl),
              ),
            ),
            initialSettings: initialSettings,
            pullToRefreshController: pullToRefreshController,
            onWebViewCreated: (controller) {
              webViewController = controller;
              setupCustomEventListener(controller, context);
            },
            onLoadStart: (controller, url) {
              setState(() {
                this.url = url.toString();
                urlController.text = this.url;
              });
            },
            onPermissionRequest: (controller, permissionRequest) async {
              //
              return PermissionResponse(
                resources: permissionRequest.resources,
                action: PermissionResponseAction.GRANT,
              );
            },
            shouldOverrideUrlLoading: (controller, navigationAction) async {
              var uri = navigationAction.request.url;

              if (![
                "http",
                "https",
                "file",
                "chrome",
                "data",
                "javascript",
                "about"
              ].contains(uri?.scheme)) {
                if (await canLaunchUrlString(url)) {
                  // Launch the App
                  await launchUrlString(
                    url,
                    mode: LaunchMode.externalApplication,
                  );
                  // and cancel the request
                  return NavigationActionPolicy.CANCEL;
                }
              }

              return NavigationActionPolicy.ALLOW;
            },
            onLoadStop: (controller, url) async {
              pullToRefreshController?.endRefreshing();
              setState(() {
                this.url = url.toString();
                urlController.text = this.url;
              });
            },
            onReceivedError:
                (inAppWebViewController, webResourceRequest, webResourceError) {
              pullToRefreshController?.endRefreshing();
            },
            onProgressChanged: (controller, progress) {
              if (progress == 100) {
                pullToRefreshController?.endRefreshing();
              }
              setState(() {
                this.progress = progress / 100;
                urlController.text = this.url;
                isLoading = this.progress != 1;
              });
            },
            onUpdateVisitedHistory: (controller, url, androidIsReload) {
              setState(() {
                this.url = url.toString();
                urlController.text = this.url;
              });
            },
            onConsoleMessage: (controller, consoleMessage) {
              if (kDebugMode) {
                log("console log ===> $consoleMessage");
              }
            },
          ).pOnly(bottom: context.mq.viewInsets.bottom).expand(),
        ],
      ),
    );
  }
}
