import 'package:fuodz/models/user.dart';
import 'package:fuodz/requests/user.request.dart';
import 'package:fuodz/view_models/base.view_model.dart';

class AssignOrderViewModel extends MyBaseViewModel {
  //
  UserRequest userRequest = UserRequest();
  List<User> drivers = [];
  List<User> unfilteredDrivers = [];
  int? selectedDriverId;

  void initialise() {
    fetchDrivers();
  }

  //
  fetchDrivers() async {
    setBusy(true);
    try {
      drivers = await userRequest.getUsers(
        role: "driver",
      );

      unfilteredDrivers = drivers;
      clearErrors();
    } catch (error) {
      print("Users Error ==> $error");
      setError(error);
    }

    setBusy(false);
  }

  filterDrivers(String keyword) {
    drivers = unfilteredDrivers.where((e) {
      return e.name.toLowerCase().contains(keyword.toLowerCase());
    }).toList();
    notifyListeners();
  }

  //
  changeSelectedDriver(int? driverId) {
    selectedDriverId = driverId;
    notifyListeners();
  }
}
