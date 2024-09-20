import 'dart:io';

import 'package:country_picker/country_picker.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/extensions/string.dart';
import 'package:fuodz/models/vendor_type.dart';
import 'package:fuodz/requests/auth.request.dart';
import 'package:fuodz/requests/vendor_type.request.dart';
import 'package:fuodz/services/alert.service.dart';
import 'package:fuodz/services/geocoder.service.dart';
import 'package:fuodz/traits/qrcode_scanner.trait.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'base.view_model.dart';

class RegisterViewModel extends MyBaseViewModel with QrcodeScannerTrait {
  //the textediting controllers
  TextEditingController nameTEC = new TextEditingController();
  TextEditingController bEmailTEC = new TextEditingController();
  TextEditingController bPhoneTEC = new TextEditingController();
  TextEditingController passwordTEC = new TextEditingController();
  TextEditingController addressTEC = new TextEditingController();
  List<VendorType> vendorTypes = [];
  List<File> selectedDocuments = [];
  bool hidePassword = true;
  Country? selectedVendorCountry;
  Country? selectedCountry;
  int? selectedVendorTypeId;
  //
  String? address;
  String? latitude;
  String? longitude;

  //
  AuthRequest _authRequest = AuthRequest();
  VendorTypeRequest _vendorTypeRequest = VendorTypeRequest();

  RegisterViewModel(BuildContext context) {
    this.viewContext = context;
    this.selectedVendorCountry = Country.parse("us");
    this.selectedCountry = Country.parse("us");
  }

  void initialise() async {
    fetchVendorTypes();
    String countryCode = await Utils.getCurrentCountryCode();
    this.selectedCountry = Country.parse(countryCode);
    this.selectedVendorCountry = Country.parse(countryCode);
    notifyListeners();
  }

  Future<List<Address>> searchAddress(String keyword) async {
    List<Address> addresses = [];
    try {
      addresses = await GeocoderService().findAddressesFromQuery(keyword);
    } catch (error) {
      toastError("$error");
    }

    //
    return addresses;
  }

  onAddressSelected(Address address) {
    this.address = address.addressLine;
    this.latitude = address.coordinates?.latitude.toString();
    this.longitude = address.coordinates?.longitude.toString();
    this.addressTEC.text = "${address.addressLine}";
    notifyListeners();
  }

  fetchVendorTypes() async {
    setBusyForObject(vendorTypes, true);
    try {
      vendorTypes = await _vendorTypeRequest.index();
      vendorTypes = vendorTypes
          .where(
            (e) => !e.slug.toLowerCase().contains("taxi"),
          )
          .toList();
    } catch (error) {
      toastError("$error");
    }
    setBusyForObject(vendorTypes, false);
  }

  showCountryDialPicker([bool vendorPhone = false]) {
    showCountryPicker(
      context: viewContext,
      showPhoneCode: true,
      onSelect: (value) => countryCodeSelected(value, vendorPhone),
    );
  }

  countryCodeSelected(Country country, bool vendorPhone) {
    if (vendorPhone) {
      selectedVendorCountry = country;
    } else {
      selectedCountry = country;
    }
    notifyListeners();
  }

  changeSelectedVendorType(int? vendorTypeId) {
    selectedVendorTypeId = vendorTypeId;
    notifyListeners();
  }

  void onDocumentsSelected(List<File> documents) {
    selectedDocuments = documents;
    notifyListeners();
  }

  void processLogin() async {
    // Validate returns true if the form is valid, otherwise false.
    if (formBuilderKey.currentState!.saveAndValidate()) {
      //

      Map<String, dynamic> params =
          Map.from(formBuilderKey.currentState!.value);
      String phone = params['phone'].toString().telFormat();
      params["phone"] = "+${selectedCountry?.phoneCode}${phone}";
      String vPhone = params['vendor_phone'].toString().telFormat();
      params["vendor_phone"] = "+${selectedVendorCountry?.phoneCode}${vPhone}";
      //add address and coordinates
      params["address"] = address;
      params["latitude"] = latitude;
      params["longitude"] = longitude;

      //
      setBusy(true);

      try {
        final apiResponse = await _authRequest.registerRequest(
          vals: params,
          docs: selectedDocuments,
        );

        if (apiResponse.allGood) {
          await AlertService.success(
            title: "Become a partner".tr(),
            text: "${apiResponse.message}",
          );
          //
        } else {
          toastError("${apiResponse.message}");
        }
      } catch (error) {
        toastError("$error");
      }

      setBusy(false);
    }
  }
}
