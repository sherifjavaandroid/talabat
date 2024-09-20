import 'dart:async';
import 'dart:developer';

import 'package:cool_alert/cool_alert.dart';
import 'package:dartx/dartx.dart' hide IterableForEachIndexed;
import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_routes.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/models/coupon.dart';
import 'package:fuodz/models/delivery_address.dart';
import 'package:fuodz/models/order_stop.dart';
import 'package:fuodz/models/package_checkout.dart';
import 'package:fuodz/models/package_type.dart';
import 'package:fuodz/models/payment_method.dart';
import 'package:fuodz/models/vendor.dart';
import 'package:fuodz/models/vendor_type.dart';
import 'package:fuodz/requests/cart.request.dart';
import 'package:fuodz/requests/checkout.request.dart';
import 'package:fuodz/requests/delivery_address.request.dart';
import 'package:fuodz/requests/package.request.dart';
import 'package:fuodz/requests/payment_method.request.dart';
import 'package:fuodz/requests/vendor.request.dart';
import 'package:fuodz/services/alert.service.dart';
import 'package:fuodz/services/app.service.dart';
import 'package:fuodz/services/auth.service.dart';
import 'package:fuodz/services/cart.service.dart';
import 'package:fuodz/services/geocoder.service.dart';
import 'package:fuodz/services/location.service.dart';
import 'package:fuodz/services/validator.service.dart';
import 'package:fuodz/view_models/payment.view_model.dart';
import 'package:fuodz/widgets/bottomsheets/parcel_location_picker_option.bottomsheet.dart';
import 'package:google_maps_place_picker_mb/google_maps_place_picker.dart';
import 'package:jiffy/jiffy.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:fuodz/extensions/context.dart';

class NewParcelViewModel extends PaymentViewModel {
  //
  PackageRequest packageRequest = PackageRequest();
  CartRequest cartRequest = CartRequest();
  VendorRequest vendorRequest = VendorRequest();
  PaymentMethodRequest paymentOptionRequest = PaymentMethodRequest();
  CheckoutRequest checkoutRequest = CheckoutRequest();
  Function? onFinish;
  VendorType? vendorType;

  //Step 1
  List<PackageType> packageTypes = [];
  PackageType? selectedPackgeType;

  //Step 2
  List<Vendor> vendors = [];
  Vendor? selectedVendor;
  bool requireParcelInfo = true;

  //Step 3
  DeliveryAddress? pickupLocation;
  DeliveryAddress? dropoffLocation;
  DateTime? selectedPickupDate;
  String? pickupDate;
  TimeOfDay? selectedPickupTime;
  String? pickupTime;

  final deliveryInfoFormKey = GlobalKey<FormState>();
  TextEditingController fromTEC = TextEditingController();
  TextEditingController toTEC = TextEditingController();
  List<TextEditingController> toTECs = [];
  TextEditingController dateTEC = TextEditingController();
  TextEditingController timeTEC = TextEditingController();
  bool isScheduled = false;
  List<String> availableTimeSlots = [];

  //step 4
  //receipents
  int openedRecipientFormIndex = 0;
  final recipientInfoFormKey = GlobalKey<FormState>();
  List<TextEditingController> recipientNamesTEC = [TextEditingController()];
  List<TextEditingController> recipientPhonesTEC = [TextEditingController()];
  List<TextEditingController> recipientNotesTEC = [TextEditingController()];

  //step 5
  final packageInfoFormKey = GlobalKey<FormState>();
  TextEditingController packageWeightTEC = TextEditingController();
  TextEditingController packageHeightTEC = TextEditingController();
  TextEditingController packageWidthTEC = TextEditingController();
  TextEditingController packageLengthTEC = TextEditingController();
  TextEditingController noteTEC = TextEditingController();

  //packageCheckout
  PackageCheckout packageCheckout = PackageCheckout();
  List<PaymentMethod> paymentMethods = [];
  PaymentMethod? selectedPaymentMethod;
  //
  bool canApplyCoupon = false;
  Coupon? coupon;
  TextEditingController couponTEC = TextEditingController();

  //
  int activeStep = 0;
  PageController pageController = PageController();
  StreamSubscription? currentLocationChangeStream;

  //
  NewParcelViewModel(BuildContext context, this.onFinish, this.vendorType) {
    this.viewContext = context;
  }

  void initialise() async {
    //clear cart
    await CartServices.clearCart();
    //listen to user location change
    currentLocationChangeStream =
        LocationService.currenctAddressSubject.stream.listen(
      (location) async {
        //
        deliveryaddress ??= DeliveryAddress();
        deliveryaddress?.address = location.addressLine;
        deliveryaddress?.latitude = location.coordinates?.latitude;
        deliveryaddress?.longitude = location.coordinates?.longitude;
        //get city, state & country
        deliveryaddress = await getLocationCityName(deliveryaddress!);
        notifyListeners();
      },
    );
    //
    if (AppStrings.enableParcelMultipleStops) {
      packageCheckout.stopsLocation = [];
      addNewStop();
    }
    await fetchParcelTypes();
    await fetchPaymentOptions();
  }

  //
  dispose() {
    super.dispose();
    currentLocationChangeStream?.cancel();
  }

  //
  fetchParcelTypes() async {
    //
    setBusyForObject(packageTypes, true);
    try {
      packageTypes = await packageRequest.fetchPackageTypes();
      clearErrors();
    } catch (error) {
      setErrorForObject(packageTypes, error);
    }
    setBusyForObject(packageTypes, false);
  }

  fetchParcelVendors() async {
    //
    vendors = [];
    selectedVendor = null;
    setBusyForObject(vendors, true);
    try {
      //
      List<OrderStop> allStops = getAllStops();
      vendors = await vendorRequest.fetchParcelVendors(
        vendorTypeId: vendorType?.id,
        packageTypeId: selectedPackgeType!.id,
        stops: allStops,
      );

      //
      if (AppStrings.enableSingleVendor && vendors.length > 0) {
        changeSelectedVendor(vendors.first);
      }
      clearErrors();
    } catch (error) {
      print("error >> $error");
      setErrorForObject(vendors, error);
    }
    setBusyForObject(vendors, false);
  }

  //
  fetchPaymentOptions() async {
    setBusyForObject(paymentMethods, true);
    try {
      paymentMethods = await paymentOptionRequest.getPaymentOptions();
      clearErrors();
    } catch (error) {
      print("Error getting payment methods ==> $error");
    }
    setBusyForObject(paymentMethods, false);
  }

  ///FORM MANIPULATION
  nextForm(int index) {
    activeStep = index;
    pageController.jumpToPage(index);
    notifyListeners();
  }

  //
  void changeSelectedPackageType(PackageType packgeType) async {
    selectedPackgeType = packgeType;
    packageCheckout.packageType = selectedPackgeType;
    notifyListeners();
  }

  void showNoVendorSelectedError() {
    toastError("No vendor for the selected package type.".tr());
    if (kDebugMode) {
      toastError(
        "DEBUG: Ensure you have at least one vendor under the package type. Also if you are using single mode, make sure the package types are attached to the active vendor."
            .tr(),
      );
    }
  }

  changeSelectedVendor(Vendor vendor) {
    selectedVendor = vendor;
    packageCheckout.vendor = selectedVendor;
    final vendorPackagePricing =
        selectedVendor?.packageTypesPricing.firstOrNullWhere(
      (e) => e.packageTypeId == selectedPackgeType?.id,
    );

    requireParcelInfo = vendorPackagePricing?.fieldRequired ?? true;
    notifyListeners();
  }

  //
  changePickupAddress() async {
    //check that user is logged in to countinue else go to login page
    if (!AuthServices.authenticated()) {
      final result =
          await Navigator.of(viewContext).pushNamed(AppRoutes.loginRoute);
      paymentOptionRequest = PaymentMethodRequest();
      if (result == null || (result is bool && !result)) {
        return;
      }
    }

    final result = await showDeliveryAddressPicker();
    pickupLocation = result;
    fromTEC.text = pickupLocation?.address ?? "";
    //
    packageCheckout.pickupLocation = pickupLocation;
    notifyListeners();
  }

  //
  changeDropOffAddress() async {
    //check that user is logged in to countinue else go to login page
    if (!AuthServices.authenticated()) {
      final result =
          await Navigator.of(viewContext).pushNamed(AppRoutes.loginRoute);
      paymentOptionRequest = PaymentMethodRequest();
      if (result == null || (result is bool && !result)) {
        return;
      }
    }

    final result = await showDeliveryAddressPicker();
    dropoffLocation = result;
    toTEC.text = dropoffLocation?.address ?? "";
    //
    packageCheckout.dropoffLocation = dropoffLocation;
    notifyListeners();
  }

  //
  changeStopDeliveryAddress(int index) async {
    //check that user is logged in to countinue else go to login page
    if (!AuthServices.authenticated()) {
      final result =
          await Navigator.of(viewContext).pushNamed(AppRoutes.loginRoute);
      paymentOptionRequest = PaymentMethodRequest();
      if (result == null || (result is bool && !result)) {
        return;
      }
    }

    final result = await showDeliveryAddressPicker();
    dropoffLocation = result;
    toTECs[index].text = dropoffLocation?.address ?? "";
    //
    packageCheckout.stopsLocation?[index] = new OrderStop();
    packageCheckout.stopsLocation?[index].deliveryAddress = dropoffLocation;
    notifyListeners();
  }

  manualChangeStopDeliveryAddress(
    int index,
    DeliveryAddress deliveryAddress,
  ) async {
    //check that user is logged in to countinue else go to login page
    if (!AuthServices.authenticated()) {
      final result =
          await Navigator.of(viewContext).pushNamed(AppRoutes.loginRoute);
      paymentOptionRequest = PaymentMethodRequest();
      if (result == null || (result is bool && !result)) {
        return;
      }
    }

    dropoffLocation = deliveryAddress;
    toTECs[index].text = dropoffLocation?.address ?? "";
    //
    packageCheckout.stopsLocation?[index] = new OrderStop();
    packageCheckout.stopsLocation?[index].deliveryAddress = dropoffLocation;
    notifyListeners();
  }

  ///
  handlePickupStop() async {
    final result = await showLocationPickerOptionBottomsheet();
    if (result is bool) {
      changePickupAddress();
    } else if (result is DeliveryAddress) {
      pickupLocation = result;
      pickupLocation?.name = pickupLocation?.address;
      fromTEC.text = pickupLocation?.address ?? "";
      //
      packageCheckout.pickupLocation = pickupLocation;
      notifyListeners();
    }
  }

  handleDropoffStop() async {
    if (recipientNamesTEC.length < 2) {
      recipientNamesTEC.add(TextEditingController());
      recipientPhonesTEC.add(TextEditingController());
      recipientNotesTEC.add(TextEditingController());
    }

    final result = await showLocationPickerOptionBottomsheet();
    if (result is bool) {
      changeDropOffAddress();
    } else if (result is DeliveryAddress) {
      dropoffLocation = result;
      toTEC.text = dropoffLocation?.address ?? "";
      //
      packageCheckout.dropoffLocation = dropoffLocation;
      notifyListeners();
    }
  }

  handleOtherStop(int index) async {
    final result = await showLocationPickerOptionBottomsheet();
    if (result is bool) {
      changeStopDeliveryAddress(index);
    } else if (result is DeliveryAddress) {
      manualChangeStopDeliveryAddress(index, result);
    }
  }

  ///

  //location/delivery address picker options
  Future<dynamic> showLocationPickerOptionBottomsheet() async {
    final result = await showModalBottomSheet(
      context: viewContext,
      builder: (ctx) {
        return ParcelLocationPickerOptionBottomSheet();
      },
    );

    //
    if (result != null && (result is int)) {
      //map address picker
      if (result == 1) {
        return await pickFromMap();
      } else {
        return true;
      }
    }
    return false;
  }

  Future<DeliveryAddress?> pickFromMap() async {
    //
    dynamic result = await newPlacePicker();

    if (result is PickResult) {
      PickResult locationResult = result;
      DeliveryAddress deliveryAddress = DeliveryAddress();
      deliveryAddress.name = locationResult.formattedAddress;
      deliveryAddress.address = locationResult.formattedAddress;
      deliveryAddress.latitude = locationResult.geometry?.location.lat;
      deliveryAddress.longitude = locationResult.geometry?.location.lng;
      setBusy(true);
      deliveryaddress = await getLocationCityName(deliveryAddress);
      setBusy(false);
      return deliveryAddress;
    } else if (result is Address) {
      Address locationResult = result;
      DeliveryAddress deliveryAddress = DeliveryAddress();
      deliveryAddress.name = locationResult.addressLine;
      deliveryAddress.address = locationResult.addressLine;
      deliveryAddress.latitude = locationResult.coordinates?.latitude;
      deliveryAddress.longitude = locationResult.coordinates?.longitude;
      deliveryAddress.city = locationResult.locality;
      deliveryAddress.state = locationResult.adminArea;
      deliveryAddress.country = locationResult.countryName;
      //
      setBusy(true);
      deliveryaddress = await getLocationCityName(deliveryAddress);
      setBusy(false);
      return deliveryAddress;
    }

    return null;
  }

  //

  //
  toggleScheduledOrder(bool? value) {
    isScheduled = value ?? false;
    packageCheckout.isScheduled = isScheduled;
    //remove delivery address if pickup
    packageCheckout.date = null;
    packageCheckout.deliverySlotDate = null;
    packageCheckout.time = null;
    packageCheckout.deliverySlotTime = null;
    notifyListeners();
  }

  //start of schedule related
  changeSelectedDeliveryDate(String string, int index) {
    packageCheckout.deliverySlotDate = string;
    packageCheckout.date = string;
    pickupDate = string;
    availableTimeSlots = selectedVendor?.deliverySlots[index].times ?? [];
    notifyListeners();
  }

  changeSelectedDeliveryTime(String time) {
    packageCheckout.deliverySlotTime = time;
    packageCheckout.time = time;
    pickupTime = time;
    notifyListeners();
  }

  //
  changeDropOffDate() async {
    final result = await showDatePicker(
      context: viewContext,
      firstDate: DateTime.now(),
      lastDate: DateTime.now().add(
        Duration(
          days: selectedVendor?.packageTypesPricing.first.maxBookingDays ?? 7,
        ),
      ),
      initialDate: selectedPickupDate ?? DateTime.now(),
    );

    //
    if (result != null) {
      selectedPickupDate = result;
      pickupDate =
          Jiffy.unixFromMillisecondsSinceEpoch(result.millisecondsSinceEpoch)
              .format("yyyy-MM-dd");
      dateTEC.text =
          Jiffy.unixFromMillisecondsSinceEpoch(result.millisecondsSinceEpoch)
              .yMMMMd;
      packageCheckout.date = pickupDate;
      notifyListeners();
    }
  }

  changeDropOffTime() async {
    final result = await showTimePicker(
      context: viewContext,
      initialTime: selectedPickupTime ?? TimeOfDay.now(),
    );

    //
    if (result != null) {
      selectedPickupTime = result;
      pickupTime = result.format(viewContext);
      timeTEC.text = pickupTime ?? "";

      try {
        packageCheckout.time = "${result.hour}:${result.minute}";
      } catch (error) {
        packageCheckout.time = "$pickupTime";
      }
      notifyListeners();
    }
  }

  changeSelectedPaymentMethod(PaymentMethod paymentMethod) {
    selectedPaymentMethod = paymentMethod;
    packageCheckout.paymentMethod = paymentMethod;
    notifyListeners();
  }

  //Form validationns
  validateDeliveryInfo() async {
    if (deliveryInfoFormKey.currentState!.validate()) {
      //
      //
      if (AppStrings.enableSingleVendor) {
        setBusyForObject(selectedVendor, true);
        await fetchParcelVendors();
        setBusyForObject(selectedVendor, false);
        //
        if (AppStrings.enableSingleVendor && selectedVendor == null) {
          showNoVendorSelectedError();
        } else {
          nextForm(2);
        }
      } else {
        nextForm(2);
        fetchParcelVendors();
      }
    }
  }

  // Recipient
  validateRecipientInfo() {
    //
    recipientInfoFormKey.currentState?.validate();
    bool dataRequired = false;
    //loop throug the recipents
    recipientNamesTEC.forEachIndexed((index, element) {
      if (element.text.isEmpty) {
        dataRequired = true;
        return;
      }
    });

    recipientPhonesTEC.forEachIndexed((index, element) {
      if (element.text.isEmpty ||
          FormValidator.validatePhone(element.text) != null) {
        dataRequired = true;
        return;
      }
    });

    if (dataRequired) {
      CoolAlert.show(
        context: viewContext,
        type: CoolAlertType.warning,
        title: "Fill Contact Info".tr(),
        text:
            "Please ensure you fill in contact info for all added stops. Thank you"
                .tr(),
        onConfirmBtnTap: () {
          //hide keyboard
          FocusScope.of(viewContext).requestFocus(FocusNode());
          viewContext.pop();
        },
      );

      return;
    }

    //
    if (recipientInfoFormKey.currentState!.validate()) {
      //loop through recipients
      // recipientNamesTEC

      // packageCheckout.recipientName = recipientNameTEC.text;
      // packageCheckout.recipientPhone = recipientPhoneTEC.text;
      nextForm(!requireParcelInfo ? 5 : 4);
    }
  }

  validateDeliveryParcelInfo() {
    if (packageInfoFormKey.currentState!.validate()) {
      //
      packageCheckout.weight = packageWeightTEC.text;
      packageCheckout.width = packageWidthTEC.text;
      packageCheckout.length = packageLengthTEC.text;
      packageCheckout.height = packageHeightTEC.text;
      //hide keyboard
      FocusScope.of(viewContext).unfocus();
      nextForm(5);
    }
  }

  validateSelectedVendor() {
    print("Date: ${packageCheckout.deliverySlotDate}");
    print("Time: ${packageCheckout.deliverySlotTime}");
    //
    if (!selectedVendor!.isOpen &&
        (packageCheckout.deliverySlotDate == null ||
            packageCheckout.deliverySlotTime == null ||
            packageCheckout.deliverySlotDate.isEmptyOrNull ||
            packageCheckout.deliverySlotTime.isEmptyOrNull)) {
      if (selectedVendor!.allowScheduleOrder) {
        AlertService.error(
            text: "Vendor is not open. Please schedule order".tr());
      } else {
        AlertService.error(text: "Vendor is not open".tr());
      }
    } else {
      FocusScope.of(viewContext).unfocus();
      nextForm(3);
    }
  }

  //Submit form
  prepareOrderSummary() async {
    //
    nextForm(6);
    clearErrors();
    setBusyForObject(packageCheckout, true);
    try {
      List<OrderStop> allStops = getAllStops();

      //loop through deleivery addresses and create the onces that were selected from map directly
      for (var i = 0; i < allStops.length; i++) {
        final stop = allStops[i];
        //
        if (stop.deliveryAddress?.id == null) {
          DeliveryAddressRequest dARequest = DeliveryAddressRequest();
          final apiResposne =
              await dARequest.saveDeliveryAddress(stop.deliveryAddress!);
          //
          if (apiResposne.allGood) {
            allStops[i].deliveryAddress = DeliveryAddress.fromJson(
              (apiResposne.body as Map)["data"],
            );
          } else {
            toastError("${apiResposne.message}");
          }
        }
      }

      //
      recipientNamesTEC.forEachIndexed((index, element) {
        allStops[index].stopId = allStops[index].deliveryAddress?.id;
        allStops[index].name = element.text;
        allStops[index].phone = recipientPhonesTEC[index].text;
        allStops[index].note = recipientNotesTEC[index].text;
      });

      //
      packageCheckout.allStops = allStops;

      //
      final mPackageCheckout = await packageRequest.parcelSummary(
        vendorId: selectedVendor?.id,
        packageTypeId: selectedPackgeType?.id,
        stops: allStops,
        packageWeight: packageWeightTEC.text,
        couponCode: couponTEC.text,
      );

      //
      packageCheckout.copyWith(packageCheckout: mPackageCheckout);
      //
    } catch (error) {
      print("Package error ==> $error");
      AlertService.error(
        title: "Checkout".tr(),
        text: "$error",
      );
    }
    setBusyForObject(packageCheckout, false);
  }

  couponCodeChange(String code) {
    canApplyCoupon = code.isNotEmpty;
    notifyListeners();
  }

  //
  applyCoupon() async {
    //
    setBusyForObject("coupon", true);
    try {
      await prepareOrderSummary();
    } catch (error) {
      print("error ==> $error");
      toastError("$error");
    }
    setBusyForObject("coupon", false);
  }

  clearCoupon() {
    coupon = null;
    couponTEC.text = "";
    notifyListeners();
    applyCoupon();
  }

  //Submit form
  initiateOrderPayment() async {
    //show loading dialog
    CoolAlert.show(
      context: viewContext,
      type: CoolAlertType.loading,
      barrierDismissible: false,
      title: "Checkout".tr(),
      text: "Processing order. Please wait...".tr(),
    );

    try {
      //
      final apiResponse = await checkoutRequest.newPackageOrder(
        packageCheckout,
        note: noteTEC.text,
      );

      //close loading dialog
      viewContext.pop();

      //not error
      if (apiResponse.allGood) {
        //cash payment

        final paymentLink = apiResponse.body["link"].toString();
        if (!paymentLink.isEmpty) {
          showOrdersTab();
          openWebpageLink(paymentLink);
        }
        //cash payment
        else {
          CoolAlert.show(
              context: viewContext,
              type: CoolAlertType.success,
              title: "Checkout".tr(),
              text: apiResponse.message,
              barrierDismissible: false,
              onConfirmBtnTap: () {
                viewContext.pop();
                showOrdersTab();
              });
        }
      } else {
        CoolAlert.show(
          context: viewContext,
          type: CoolAlertType.error,
          title: "Checkout".tr(),
          text: apiResponse.message,
        );
      }
    } catch (error) {
      log("Error ==> $error");
      viewContext.pop();
      CoolAlert.show(
        context: viewContext,
        type: CoolAlertType.error,
        title: "Checkout".tr(),
        text: "$error",
      );
    }
  }

  //
  showOrdersTab() {
    //
    viewContext.pop();
    //switch tab to orders
    AppService().changeHomePageIndex(index: 1);
  }

  addNewStop() {
    if (AppStrings.maxParcelStops > (toTECs.length - 1)) {
      final toTEC = TextEditingController();
      toTECs.add(toTEC);
      //
      recipientNamesTEC.add(TextEditingController());
      recipientPhonesTEC.add(TextEditingController());
      recipientNotesTEC.add(TextEditingController());
      //
      packageCheckout.stopsLocation?.add(
        OrderStop(),
      );
      notifyListeners();
    }
  }

  removeStop(int index) {
    toTECs.removeAt(index);
    recipientNamesTEC.removeAt(index);
    recipientPhonesTEC.removeAt(index);
    recipientNotesTEC.removeAt(index);
    packageCheckout.stopsLocation?.removeAt(index);
    notifyListeners();
  }

  List<OrderStop> getAllStops() {
    List<OrderStop> allStops = [];
    if (packageCheckout.pickupLocation != null) {
      allStops.add(OrderStop(deliveryAddress: packageCheckout.pickupLocation));
    }

    if (packageCheckout.stopsLocation != null &&
        packageCheckout.stopsLocation!.isNotEmpty) {
      allStops.addAll(
        packageCheckout.stopsLocation!,
      );
    }
    if (packageCheckout.dropoffLocation != null) {
      allStops.add(OrderStop(deliveryAddress: packageCheckout.dropoffLocation));
    }

    return allStops;
  }

  //
  void setupReceiverPaymentMethod() {
    //get the cash payment method, from the list of payment methods
    PaymentMethod? cashPaymentMethod = paymentMethods.firstOrNullWhere(
      (element) => element.isCash == 1,
    );

    //
    if (cashPaymentMethod != null) {
      changeSelectedPaymentMethod(cashPaymentMethod);
    }
  }
}
