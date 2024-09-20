import 'dart:io';
import 'package:fuodz/models/vendor.dart';
import 'package:fuodz/requests/vendor.request.dart';
import 'package:fuodz/services/alert.service.dart';
import 'package:fuodz/services/auth.service.dart';
import 'package:fuodz/view_models/base.view_model.dart';
import 'package:localize_and_translate/localize_and_translate.dart';

class DocumentRequestViewModel extends MyBaseViewModel {
  //
  VendorRequest _vendorRequest = VendorRequest();
  Vendor? currentVendor;
  List<File> selectedDocuments = [];
  //
  void initialise() {
    currentVendor = AuthServices.currentVendor;
    fetchVendorProfile();
  }

  fetchVendorProfile() async {
    setBusy(true);
    try {
      final response = await _vendorRequest.getVendorDetails();
      currentVendor = Vendor.fromJson(response["vendor"]);
    } catch (error) {
      print(error);
    }
    setBusy(false);
  }

  //
  void onDocumentsSelected(List<File> documents) {
    selectedDocuments = documents;
    notifyListeners();
  }

  submitDocuments() async {
    //if no document is selected
    if (selectedDocuments.isEmpty) {
      toastError("Please select a document".tr());
      return;
    }

    setBusyForObject(selectedDocuments, true);

    try {
      //
      final apiResponse = await _vendorRequest.submitDocumentsRequest(
        docs: selectedDocuments,
      );

      if (apiResponse.allGood) {
        await AlertService.success(
          title: "Document Request".tr(),
          text: "${apiResponse.message}",
        );
        //
        fetchVendorProfile();
        //
      } else {
        toastError("${apiResponse.message}");
      }
    } catch (error) {
      toastError("$error");
    }

    setBusyForObject(selectedDocuments, false);
  }
}
