import 'package:cool_alert/cool_alert.dart';
import 'package:firebase_dynamic_links/firebase_dynamic_links.dart';
import 'package:flutter/material.dart';
import 'package:flutter_dropdown_alert/alert_controller.dart';
import 'package:flutter_dropdown_alert/model/data_alert.dart';
import 'package:flutter_form_builder/flutter_form_builder.dart';
import 'package:fuodz/constants/api.dart';
import 'package:fuodz/constants/app_dynamic_link.dart';
import 'package:fuodz/constants/app_map_settings.dart';
import 'package:fuodz/constants/app_routes.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/models/cart.dart';
import 'package:fuodz/models/delivery_address.dart';
import 'package:fuodz/models/product.dart';
import 'package:fuodz/models/search.dart';
import 'package:fuodz/models/service.dart';
import 'package:fuodz/models/vendor.dart';
import 'package:fuodz/models/vendor_type.dart';
import 'package:fuodz/services/auth.service.dart';
import 'package:fuodz/services/cart.service.dart';
import 'package:fuodz/services/cart_ui.service.dart';
import 'package:fuodz/services/geocoder.service.dart';
import 'package:fuodz/services/location.service.dart';
import 'package:fuodz/services/navigation.service.dart';
import 'package:fuodz/services/update.service.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:fuodz/view_models/payment.view_model.dart';
import 'package:fuodz/views/pages/auth/login.page.dart';
import 'package:fuodz/views/pages/cart/cart.page.dart';
import 'package:fuodz/views/pages/service/service_details.page.dart';
import 'package:fuodz/views/pages/vendor/vendor_reviews.page.dart';
import 'package:fuodz/views/shared/ops_map.page.dart';
import 'package:fuodz/widgets/bottomsheets/delivery_address_picker.bottomsheet.dart';
import 'package:geolocator/geolocator.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:google_maps_place_picker_mb/google_maps_place_picker.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:pull_to_refresh_flutter3/pull_to_refresh_flutter3.dart';
import 'package:share/share.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:fuodz/extensions/context.dart';

class MyBaseViewModel extends BaseViewModel with UpdateService {
  //
  late BuildContext viewContext;
  GlobalKey pageKey = GlobalKey<State>();
  final formKey = GlobalKey<FormState>();
  final formBuilderKey = GlobalKey<FormBuilderState>();
  GlobalKey genKey = GlobalKey();
  final currencySymbol = AppStrings.currencySymbol;
  DeliveryAddress? deliveryaddress = DeliveryAddress();
  String? firebaseVerificationId;
  VendorType? vendorType;
  Vendor? vendor;
  RefreshController refreshController = RefreshController();

  void initialise() {}

  void reloadPage() {
    pageKey = new GlobalKey<State>();
    refreshController = new RefreshController();
    notifyListeners();
    initialise();
  }

  openWebpageLink(String url) async {
    PaymentViewModel paymentViewModel = PaymentViewModel();
    paymentViewModel.viewContext = viewContext;
    await paymentViewModel.openWebpageLink(url);
  }

  //
  //open delivery address picker
  void pickDeliveryAddress({
    bool vendorCheckRequired = true,
    Function? onselected,
  }) {
    //
    showModalBottomSheet(
      context: viewContext,
      backgroundColor: Colors.transparent,
      isScrollControlled: true,
      builder: (context) {
        return DeliveryAddressPicker(
          vendorCheckRequired: vendorCheckRequired,
          allowOnMap: true,
          onSelectDeliveryAddress: (mDeliveryaddress) async {
            print("called here");
            viewContext.pop();
            deliveryaddress = mDeliveryaddress;
            await LocationService.saveSelectedAddressLocally(mDeliveryaddress);
            notifyListeners();

            //
            final address = Address(
              coordinates: Coordinates(
                deliveryaddress?.latitude ?? 0.00,
                deliveryaddress?.longitude ?? 0.00,
              ),
              addressLine: deliveryaddress?.address,
            );
            //
            LocationService.currenctAddress = address;
            //
            LocationService.currenctAddressSubject.sink.add(address);

            //
            if (onselected != null) onselected();
          },
        );
      },
    );
  }

  //
  bool isAuthenticated() {
    return AuthServices.authenticated();
  }

  //
  void openLogin() async {
    viewContext.nextPage(LoginPage());
    notifyListeners();
  }

  openTerms() {
    final url = Api.terms;
    openWebpageLink(url);
  }

  openPaymentTerms() {
    final url = Api.paymentTerms;
    openWebpageLink(url);
  }

  //
  //
  Future<DeliveryAddress?> showDeliveryAddressPicker() async {
    //
    DeliveryAddress? selectedDeliveryAddress;

    //
    await showModalBottomSheet(
      context: viewContext,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) {
        return DeliveryAddressPicker(
          onSelectDeliveryAddress: (deliveryAddress) {
            viewContext.pop();
            selectedDeliveryAddress = deliveryAddress;
          },
        );
      },
    );

    return selectedDeliveryAddress;
  }

  //
  Future<DeliveryAddress> getLocationCityName(
      DeliveryAddress deliveryAddress) async {
    final coordinates = new Coordinates(
      deliveryAddress.latitude ?? 0.00,
      deliveryAddress.longitude ?? 0.00,
    );
    final addresses = await GeocoderService().findAddressesFromCoordinates(
      coordinates,
    );
    //loop through the addresses and get data
    for (var address in addresses) {
      //address
      deliveryAddress.address ??= address.addressLine;
      //name
      deliveryAddress.name ??= address.featureName;
      if (deliveryAddress.name == null || deliveryAddress.name!.isEmpty) {
        deliveryAddress.name = address.addressLine;
      }
      //city
      deliveryAddress.city ??= address.locality;
      if (deliveryAddress.city == null || deliveryAddress.city!.isEmpty) {
        deliveryAddress.city = address.subLocality;
      }
      //state
      deliveryAddress.state ??= address.adminArea;
      if (deliveryAddress.state == null || deliveryAddress.state!.isEmpty) {
        deliveryAddress.state = address.subAdminArea;
      }
      //country
      deliveryAddress.country ??= address.countryName;

      //break if all data is set
      if (deliveryAddress.address != null &&
          deliveryAddress.city != null &&
          deliveryAddress.state != null &&
          deliveryAddress.country != null) {
        break;
      }
    }
    //
    // deliveryAddress.city = addresses.first.locality;
    // deliveryAddress.state = addresses.first.adminArea;
    // deliveryAddress.country = addresses.first.countryName;
    return deliveryAddress;
  }

  addToCartDirectly(Product product, int qty, {bool force = false}) async {
    //
    if (qty <= 0) {
      //
      final mProductsInCart = CartServices.productsInCart;
      final previousProductIndex = mProductsInCart.indexWhere(
        (e) => e.product?.id == product.id,
      );
      //
      if (previousProductIndex >= 0) {
        mProductsInCart.removeAt(previousProductIndex);
        await CartServices.saveCartItems(mProductsInCart);
      }
      return;
    }
    //
    final cart = Cart();
    cart.price = (product.showDiscount ? product.discountPrice : product.price);
    product.selectedQty = qty;
    cart.product = product;
    cart.selectedQty = product.selectedQty;
    cart.options = product.selectedOptions ?? [];
    cart.optionsIds = product.selectedOptions!.map((e) => e.id).toList();

    //

    try {
      //
      bool canAddToCart = await CartUIServices.handleCartEntry(
        viewContext,
        cart,
        product,
      );

      if (canAddToCart || force) {
        //
        final mProductsInCart = CartServices.productsInCart;
        final previousProductIndex = mProductsInCart.indexWhere(
          (e) => e.product?.id == product.id,
        );
        //
        if (previousProductIndex >= 0) {
          mProductsInCart.removeAt(previousProductIndex);
          mProductsInCart.insert(previousProductIndex, cart);
          await CartServices.saveCartItems(mProductsInCart);
        } else {
          await CartServices.addToCart(cart);
        }
      } else if (product.isDigital) {
        //
        CoolAlert.show(
          context: viewContext,
          title: "Digital Product".tr(),
          text:
              "You can only buy/purchase digital products together with other digital products. Do you want to clear cart and add this product?"
                  .tr(),
          type: CoolAlertType.confirm,
          onConfirmBtnTap: () async {
            //
            viewContext.pop();
            await CartServices.clearCart();
            addToCartDirectly(product, qty, force: true);
          },
        );
      } else {
        //
        CoolAlert.show(
          context: viewContext,
          title: "Different Vendor".tr(),
          text:
              "Are you sure you'd like to change vendors? Your current items in cart will be lost."
                  .tr(),
          type: CoolAlertType.confirm,
          onConfirmBtnTap: () async {
            //
            viewContext.pop();
            await CartServices.clearCart();
            addToCartDirectly(product, qty, force: true);
          },
        );
      }
    } catch (error) {
      print("Cart Error => $error");
      setError(error);
    }
  }

  //switch to use current location instead of selected delivery address
  void useUserLocation() {
    LocationService.geocodeCurrentLocation();
  }

  //
  openSearch({int showType = 4}) async {
    final search = Search(
      vendorType: vendorType,
      showType: showType,
    );
    final page = NavigationService().searchPageWidget(search);
    viewContext.nextPage(page);
  }

  openCart() async {
    viewContext.nextPage(CartPage());
  }

  //
  //
  productSelected(Product product) async {
    Navigator.pushNamed(
      viewContext,
      AppRoutes.product,
      arguments: product,
    );
  }

  servicePressed(Service service) async {
    viewContext.push(
      (context) => ServiceDetailsPage(service),
    );
  }

  openVendorReviews() {
    viewContext.push(
      (context) => VendorReviewsPage(vendor!),
    );
  }

  //show toast
  toastSuccessful(String msg, {String? title}) {
    AlertController.show(
      title ?? "Successful".tr(),
      msg,
      TypeAlert.success,
    );
  }

  toastError(String msg, {String? title}) {
    AlertController.show(
      title ?? "Error".tr(),
      msg,
      TypeAlert.error,
    );
  }

  Future<void> fetchCurrentLocation() async {
    //
    Position currentLocation = await Geolocator.getCurrentPosition();
    //
    final address = await LocationService.addressFromCoordinates(
      lat: currentLocation.latitude,
      lng: currentLocation.longitude,
    );
    //
    LocationService.currenctAddress = address;
    if (address != null) {
      LocationService.currenctAddressSubject.sink.add(address);
    }
    deliveryaddress ??= DeliveryAddress();
    deliveryaddress!.address = address?.addressLine;
    deliveryaddress!.latitude = address?.coordinates?.latitude;
    deliveryaddress!.longitude = address?.coordinates?.longitude;
    deliveryaddress!.name = "Current Location".tr();
    LocationService.deliveryaddress = deliveryaddress;
    LocationService.currenctDeliveryAddressSubject.add(deliveryaddress!);
  }

  //handle fetch delivery address
  preloadDeliveryLocation() async {
    try {
      //fetch saved location from local storage
      deliveryaddress = LocationService.deliveryaddress;
      if (deliveryaddress == null) {
        deliveryaddress = await LocationService.getLocallySaveAddress();
      }
      notifyListeners();
    } catch (error) {
      print("Error getting delivery address => $error");
    }
  }

  // NEW LOCATION PICKER
  Future<dynamic> newPlacePicker() async {
    //
    LatLng initialPosition = LatLng(0.00, 0.00);
    double initialZoom = 0;
    if (LocationService.currenctAddress != null) {
      initialPosition = LatLng(
        LocationService.currenctAddress?.coordinates?.latitude ?? 0.00,
        LocationService.currenctAddress?.coordinates?.longitude ?? 0.00,
      );
      initialZoom = 15;
    }
    String? mapRegion;
    try {
      mapRegion = await Utils.getCurrentCountryCode();
    } catch (error) {
      print("Error getting sim country code => $error");
    }
    mapRegion ??= AppStrings.countryCode.trim().split(",").firstWhere(
      (e) => !e.toLowerCase().contains("auto"),
      orElse: () {
        return "";
      },
    );

    //
    if (!AppMapSettings.useGoogleOnApp) {
      return await viewContext.push(
        (context) => OPSMapPage(
          region: mapRegion,
          initialPosition: initialPosition,
          useCurrentLocation: true,
          initialZoom: initialZoom,
        ),
      );
    }
    //google maps
    return await Navigator.push(
      viewContext,
      MaterialPageRoute(
        builder: (context) => PlacePicker(
          apiKey: AppStrings.googleMapApiKey,
          autocompleteLanguage: translator.activeLocale.languageCode,
          region: mapRegion,
          onPlacePicked: (result) {
            Navigator.of(context).pop(result);
          },
          initialPosition: initialPosition,
        ),
      ),
    );
  }

  //share
  shareProduct(Product product) async {
    //
    setBusyForObject(shareProduct, true);
    String link = "${Api.appShareLink}/product/${product.id}";
    final dynamicLinkParams = DynamicLinkParameters(
      link: Uri.parse(link),
      uriPrefix: AppDynamicLink.dynamicLinkPrefix,
      androidParameters: AndroidParameters(
        packageName: await AppDynamicLink.androidDynamicLinkId,
      ),
      iosParameters: IOSParameters(
        bundleId: await AppDynamicLink.iOSDynamicLinkId,
      ),
    );
    final dynamicLink = await FirebaseDynamicLinks.instance.buildLink(
      dynamicLinkParams,
    );

    String shareLink = Uri.decodeFull(
      Uri.decodeComponent(dynamicLink.toString()),
    );
    await Share.share(shareLink);
    setBusyForObject(shareProduct, false);
  }

  shareVendor(Vendor vendor) async {
    //
    setBusyForObject(shareVendor, true);
    String link = "${Api.appShareLink}/vendor/${vendor.id}";
    final dynamicLinkParams = DynamicLinkParameters(
      link: Uri.parse(link),
      uriPrefix: AppDynamicLink.dynamicLinkPrefix,
      androidParameters: AndroidParameters(
        packageName: await AppDynamicLink.androidDynamicLinkId,
      ),
      iosParameters: IOSParameters(
        bundleId: await AppDynamicLink.iOSDynamicLinkId,
      ),
    );
    final dynamicLink = await FirebaseDynamicLinks.instance.buildLink(
      dynamicLinkParams,
    );

    String shareLink = Uri.decodeFull(
      Uri.decodeComponent(dynamicLink.toString()),
    );
    await Share.share(shareLink);
    setBusyForObject(shareVendor, false);
  }

  shareService(Service service) async {
    //
    setBusyForObject(shareService, true);
    String link = "${Api.appShareLink}/service/${service.id}";
    final dynamicLinkParams = DynamicLinkParameters(
      link: Uri.parse(link),
      uriPrefix: AppDynamicLink.dynamicLinkPrefix,
      androidParameters: AndroidParameters(
        packageName: await AppDynamicLink.androidDynamicLinkId,
      ),
      iosParameters: IOSParameters(
        bundleId: await AppDynamicLink.iOSDynamicLinkId,
      ),
    );
    final dynamicLink = await FirebaseDynamicLinks.instance.buildLink(
      dynamicLinkParams,
    );

    String shareLink = Uri.decodeFull(
      Uri.decodeComponent(dynamicLink.toString()),
    );
    await Share.share(shareLink);
    setBusyForObject(shareService, false);
  }
}
