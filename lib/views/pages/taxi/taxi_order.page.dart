import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/view_models/taxi/taxi.vm.dart';
import 'package:fuodz/views/pages/taxi/widgets/location_permission.view.dart';
import 'package:fuodz/views/pages/taxi/widgets/sos_button.dart';
import 'package:fuodz/views/pages/taxi/widgets/statuses/idle.view.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/busy_indicator.dart';
import 'package:fuodz/widgets/cards/custom.visibility.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class TaxiOrderPage extends StatefulWidget {
  const TaxiOrderPage({Key? key}) : super(key: key);

  @override
  _TaxiOrderPageState createState() => _TaxiOrderPageState();
}

class _TaxiOrderPageState extends State<TaxiOrderPage>
    with AutomaticKeepAliveClientMixin, WidgetsBindingObserver {
  @override
  bool get wantKeepAlive => true;

  //
  TaxiViewModel? taxiViewModel;

  //
  @override
  void initState() {
    super.initState();
    taxiViewModel ??= TaxiViewModel(context);
  }

  @override
  void didChangeAppLifecycleState(AppLifecycleState state) {
    taxiViewModel?.taxiGoogleMapManagerService.setGoogleMapStyle();
  }

  @override
  Widget build(BuildContext context) {
    super.build(context);
    return BasePage(
      body: ViewModelBuilder<TaxiViewModel>.reactive(
        viewModelBuilder: () => taxiViewModel!,
        onViewModelReady: (vm) => vm.initialise(),
        builder: (context, vm, child) {
          return Stack(
            children: [
              //google map
              CustomVisibilty(
                visible: vm.taxiGoogleMapManagerService.canShowMap,
                child: GoogleMap(
                  style: vm.taxiGoogleMapManagerService.mapStyle,
                  initialCameraPosition: CameraPosition(
                    target: LatLng(0.00, 0.00),
                  ),
                  myLocationButtonEnabled: true,
                  onMapCreated: vm.taxiGoogleMapManagerService.onMapReady,
                  onCameraIdle: vm.taxiGoogleMapManagerService.onMapCameraIdle,
                  onCameraMoveStarted:
                      vm.taxiGoogleMapManagerService.onMapCameraMoveStarted,
                  padding: vm.taxiGoogleMapManagerService.googleMapPadding,
                  markers: vm.taxiGoogleMapManagerService.gMapMarkers,
                  polylines: vm.taxiGoogleMapManagerService.gMapPolylines,
                ),
              ),

              //sos button
              SOSButton(),
              //
              StreamBuilder<Widget?>(
                stream: vm.uiStream,
                builder: (ctx, snapshot) {
                  if (!snapshot.hasData || snapshot.data == null) {
                    return IdleTaxiView(vm);
                  }
                  return snapshot.data!;
                },
              ),
              //permission request
              CustomVisibilty(
                visible: !vm.taxiGoogleMapManagerService.canShowMap,
                child: LocationPermissionView(
                  onResult: (request) {
                    if (request) {
                      vm.taxiLocationService
                          .requestLocationPermissionForGoogleMap();
                    }
                  },
                ).centered(),
              ),

              //loading
              Visibility(
                visible: vm.isBusy,
                child: BusyIndicator(
                  color: AppColor.primaryColor,
                )
                    .wh(60, 60)
                    .box
                    .white
                    .rounded
                    .p32
                    .makeCentered()
                    .box
                    .color(Colors.black.withOpacity(0.3))
                    .make()
                    .wFull(context)
                    .hFull(context),
              ),
            ],
          );
        },
      ),
    );
  }
}
