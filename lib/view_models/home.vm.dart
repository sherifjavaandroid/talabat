import 'dart:async';

import 'package:firebase_dynamic_links/firebase_dynamic_links.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/constants/api.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/models/product.dart';
import 'package:fuodz/models/service.dart';
import 'package:fuodz/models/vendor.dart';
import 'package:fuodz/models/vendor_type.dart';
import 'package:fuodz/requests/product.request.dart';
import 'package:fuodz/requests/service.request.dart';
import 'package:fuodz/requests/vendor.request.dart';
import 'package:fuodz/services/alert.service.dart';
import 'package:fuodz/services/app.service.dart';
import 'package:fuodz/services/auth.service.dart';
import 'package:fuodz/services/cart.service.dart';
import 'package:fuodz/services/local_storage.service.dart';
import 'package:fuodz/services/navigation.service.dart';
import 'package:fuodz/view_models/base.view_model.dart';
import 'package:fuodz/views/pages/auth/login.page.dart';
import 'package:fuodz/views/pages/product/amazon_styled_commerce_product_details.page.dart';
import 'package:fuodz/views/pages/product/product_details.page.dart';
import 'package:fuodz/views/pages/service/service_details.page.dart';
import 'package:fuodz/views/pages/vendor_details/vendor_details.page.dart';
import 'package:fuodz/views/pages/welcome/welcome.page.dart';
import 'package:rx_shared_preferences/rx_shared_preferences.dart';
import 'package:fuodz/extensions/context.dart';

class HomeViewModel extends MyBaseViewModel {
  //
  HomeViewModel(BuildContext context) {
    this.viewContext = context;
  }

  //
  int currentIndex = 0;
  PageController pageViewController = PageController(initialPage: 0);
  int totalCartItems = 0;
  StreamSubscription? homePageChangeStream;
  Widget homeView = WelcomePage();

  @override
  void initialise() async {
    //
    handleAppLink();

    //determine if homeview should be multiple vendor types or single vendor page
    if (AppStrings.isSingleVendorMode) {
      VendorType vendorType = VendorType.fromJson(AppStrings.enabledVendorType);
      homeView = NavigationService.vendorTypePage(
        vendorType,
        context: viewContext,
      );
      //require login
      if (vendorType.authRequired && !AuthServices.authenticated()) {
        await viewContext.push(
          (context) => LoginPage(
            required: true,
          ),
        );
      }
      notifyListeners();
    }

    //start listening to changes to items in cart
    LocalStorageService.rxPrefs?.getIntStream(CartServices.totalItemKey).listen(
      (total) {
        if (total != null) {
          totalCartItems = total;
          notifyListeners();
        }
      },
    );

    //
    homePageChangeStream = AppService().homePageIndex.stream.listen(
      (index) {
        //
        onTabChange(index);
      },
    );
  }

  //
  // dispose() {
  //   super.dispose();
  //   homePageChangeStream.cancel();
  // }

  //
  onPageChanged(int index) {
    currentIndex = index;
    notifyListeners();
  }

  //
  onTabChange(int index) {
    try {
      currentIndex = index;
      pageViewController.animateToPage(
        currentIndex,
        duration: Duration(microseconds: 5),
        curve: Curves.bounceInOut,
      );
    } catch (error) {
      print("error ==> $error");
    }
    notifyListeners();
  }

  //
  handleAppLink() async {
    // Get any initial links
    final PendingDynamicLinkData? initialLink =
        await FirebaseDynamicLinks.instance.getInitialLink();

    //
    if (initialLink != null) {
      final Uri deepLink = initialLink.link;
      openPageByLink(deepLink);
    }

    //
    FirebaseDynamicLinks.instance.onLink.listen(
      (dynamicLinkData) {
        //
        openPageByLink(dynamicLinkData.link);
      },
    ).onError(
      (error) {
        // Handle errors
        print("error opening link ==> $error");
      },
    );
  }

  //
  openPageByLink(Uri deepLink) async {
    final cleanLink = Uri.decodeComponent(deepLink.toString());
    if (cleanLink.contains(Api.appShareLink)) {
      //
      try {
        final isProductLink = cleanLink.contains("/product");
        final isVendorLink = cleanLink.contains("/vendor");
        final isServiceLink = cleanLink.contains("/service");
        final pathFragments = cleanLink.split("/");
        final dataId = pathFragments.last;

        if (isProductLink) {
          AlertService.showLoading();
          try {
            ProductRequest _productRequest = ProductRequest();
            Product product =
                await _productRequest.productDetails(int.parse(dataId));
            AlertService.stopLoading();
            if (!product.vendor.vendorType.slug.contains("commerce")) {
              viewContext.push(
                (context) => ProductDetailsPage(
                  product: product,
                ),
              );
            } else {
              viewContext.push(
                (context) => AmazonStyledCommerceProductDetailsPage(
                  product: product,
                ),
              );
            }
          } catch (error) {
            print("error ==> $error");
            AlertService.stopLoading();
          }
        } else if (isVendorLink) {
          AlertService.showLoading();
          try {
            VendorRequest _vendorRequest = VendorRequest();
            Vendor vendor = await _vendorRequest.vendorDetails(
              int.parse(dataId),
              params: {'type': 'small'},
            );
            AlertService.stopLoading();
            viewContext.push(
              (context) => VendorDetailsPage(
                vendor: vendor,
              ),
            );
          } catch (error) {
            print("error ==> $error");
            AlertService.stopLoading();
          }
        } else if (isServiceLink) {
          AlertService.showLoading();
          try {
            ServiceRequest _serviceRequest = ServiceRequest();
            Service service =
                await _serviceRequest.serviceDetails(int.parse(dataId));
            AlertService.stopLoading();
            viewContext.push(
              (context) => ServiceDetailsPage(service),
            );
          } catch (error) {
            print("error ==> $error");
            AlertService.stopLoading();
          }
        }
      } catch (error) {
        toastError("$error");
      }
    }
    print("Url Link ==> $cleanLink");
  }
}
