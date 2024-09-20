import 'package:flutter/material.dart';
import 'package:fuodz/models/vehicle.dart';
import 'package:fuodz/requests/vehicle.request.dart';
import 'package:fuodz/services/alert.service.dart';
import 'package:fuodz/services/auth.service.dart';
import 'package:fuodz/views/pages/splash.page.dart';
import 'package:fuodz/views/pages/vehicle/new_vehicle.page.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:pull_to_refresh_flutter3/pull_to_refresh_flutter3.dart';
import 'package:velocity_x/velocity_x.dart';
import 'base.view_model.dart';

class VehiclesViewModel extends MyBaseViewModel {
  List<Vehicle> vehicles = [];
  //
  VehicleRequest vehicleRequest = VehicleRequest();
  RefreshController refreshController = RefreshController();

  VehiclesViewModel(BuildContext context) {
    this.viewContext = context;
  }

  void initialise() {
    fetchVehicles();
  }

  void fetchVehicles() async {
    refreshController.refreshCompleted();
    setBusy(true);
    try {
      vehicles = await vehicleRequest.vehicles();
    } catch (error) {
      toastError("$error");
    }
    setBusy(false);
  }

  newVehicleCreate() async {
    await Navigator.of(viewContext).push(
      MaterialPageRoute(builder: (context) => NewVehiclePage()),
    );
    fetchVehicles();
  }

  makeVehicleCurrent(Vehicle vehicle) async {
    AlertService.showLoading();
    try {
      await vehicleRequest.makeActive(vehicle.id);
      await AuthServices.saveVehicle(vehicle.toJson());
      await AuthServices.getDriverVehicle(force: true);
      AlertService.stopLoading();
      AlertService.showConfirm(
          title: "Vehicle Update".tr(),
          text:
              "Vehicle updated, you will need to reload app for this to take effect"
                  .tr(),
          confirmBtnText: "Reload".tr(),
          onConfirm: () {
            viewContext.nextAndRemoveUntilPage(SplashPage());
          });
    } catch (error) {
      AlertService.stopLoading();
      toastError("$error");
    }
  }
}
