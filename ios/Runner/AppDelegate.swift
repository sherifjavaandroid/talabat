import UIKit
import Flutter
import GoogleMaps

@UIApplicationMain
@objc class AppDelegate: FlutterAppDelegate {
  override func application(
    _ application: UIApplication,
    didFinishLaunchingWithOptions launchOptions: [UIApplication.LaunchOptionsKey: Any]?
  ) -> Bool {
    var flutter_native_splash = 1
    UIApplication.shared.isStatusBarHidden = false
    GMSServices.provideAPIKey("AIzaSyD3OKxgOAcHixt-EswOYdpGAVDd_qJWleQ")
    GeneratedPluginRegistrant.register(with: self)
    return super.application(application, didFinishLaunchingWithOptions: launchOptions)
  }
}