$(function () {

    var isFlutterInAppWebViewReady = false;
    window.addEventListener("flutterInAppWebViewPlatformReady", function (event) {
        isFlutterInAppWebViewReady = true;
    });

    $('#closePageBtn').on("click touchstart", function () {
        closePage();
    });
    //listen for click events from this page, with url passed to the function
    $('#openUrlBtn').on("click touchstart", function () {
        var url = $(this).data('url');
        openUrl(url);
    });

    //
    function closePage() {
        if (isFlutterInAppWebViewReady) {
            window.flutter_inappwebview.callHandler('handlerClosePage', true);
        } else {
            window.close();
        }
    }

    //
    function openUrl(url) {
        if (isFlutterInAppWebViewReady) {
            window.flutter_inappwebview.callHandler('handlerOpenLink', url);
        } else {
            window.open(url, "_blank");
        }
    }

});
