// import 'package:velocity_x/velocity_x.dart';

import 'package:fuodz/services/auth.service.dart';

class Api {
  static String get baseUrl {
    return "https://fuodz.edentech.online/api";
    // return "http://192.168.8.145:8000/api";
  }

  static const appSettings = "/app/settings";
  static const appOnboardings = "/app/onboarding?type=vendor";
  static const faqs = "/app/faqs?type=vendor";

  static const accountDelete = "/account/delete";
  static const login = "/login";
  static const newAccount = "/vendor/register";
  static const qrlogin = "/login/qrcode";
  static const logout = "/logout";
  static const forgotPassword = "/password/reset/init";
  static const verifyPhoneAccount = "/verify/phone";
  static const updateProfile = "/profile/update";
  static const updatePassword = "/profile/password/update";
  //
  static const sendOtp = "/otp/send";
  static const verifyOtp = "/otp/verify";

  static const orders = "/orders";
  static const chat = "/chat/notification";
  static const users = "/users";
  static const products = "/products";
  static const productCategories = "/categories";
  static const productTags = "/tags";
  static const packagePricing = "/vendor/package/pricing";
  static const packageTypes = "/package/types";
  static const services = "/my/services";
  static const serviceDurations = "/service/durations";
  static const vendorTypes = "/vendor/types";

  //Payment accounts
  static const paymentAccount = "/payment/accounts";
  static const payoutRequest = "/payouts/request";

  //
  static const vendorDetails = "/vendor/id/details";
  static const vendorAvailability = "/availability/vendor/id";
  static const documentSubmission = "/my/vendor/document/request/submission";

  //manage vendors
  static const myVendors = "/my/vendors";
  static const switchVendor = "/switch/vendor";
  static const salesReport = "/my/vendor/sales/report";
  static const earningsReport = "/my/vendor/earnings/report";

  //map
  static const geocoderForward = "/geocoder/forward";
  static const geocoderReserve = "/geocoder/2/reserve";
  static const geocoderPlaceDetails = "/geocoder/place/details";

  //misc
  static const externalRedirect = "/external/redirect";

  static String get webUrl {
    return baseUrl.replaceAll('/api', '');
  }

  //
  static String get subscription {
    return "$webUrl/subscription/my/subscribe";
  }

  // Other pages
  static String get privacyPolicy {
    return "$webUrl/privacy/policy";
  }

  static String get terms {
    return "$webUrl/pages/terms";
  }

  //
  static String get register {
    return "$webUrl/register#vendor";
  }

  static String get contactUs {
    return "$webUrl/pages/contact";
  }

  static String get inappSupport {
    return "$webUrl/support/chat";
  }

  static String get backendUrl {
    return "$webUrl/";
  }

  static Future<String> redirectAuth({String? url, String? route}) async {
    final userToken = await AuthServices.getAuthBearerToken();
    final webUrl = "$baseUrl/external/web/redirect";
    return "$webUrl?token=$userToken&route=$route&url=$url";
  }
}
