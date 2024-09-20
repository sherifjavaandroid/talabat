import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_images.dart';
import 'package:fuodz/view_models/vehicles.vm.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/custom_list_view.dart';
import 'package:fuodz/widgets/list_items/vehicle.list_item.dart';
import 'package:fuodz/widgets/states/empty.state.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class VehiclesPage extends StatelessWidget {
  const VehiclesPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<VehiclesViewModel>.reactive(
      viewModelBuilder: () => VehiclesViewModel(context),
      onViewModelReady: (vm) => vm.initialise(),
      builder: (context, vm, child) {
        return BasePage(
          showAppBar: true,
          showLeadingAction: true,
          title: "Vehicles".tr(),
          body: CustomListView(
            refreshController: vm.refreshController,
            canRefresh: true,
            onRefresh: vm.fetchVehicles,
            isLoading: vm.isBusy,
            dataSet: vm.vehicles,
            padding: EdgeInsets.all(20),
            itemBuilder: (ctx, index) {
              final vehicle = vm.vehicles[index];
              //
              return VehicleListItem(
                vehicle: vehicle,
                onLongpress: () => vm.makeVehicleCurrent(vehicle),
              );
            },
            emptyWidget: EmptyState(
              title: "\n" + "No Vehicles".tr(),
              description: "You have not added any vehicles yet".tr(),
              imageUrl: AppImages.noVehicle,
            ).centered().p20(),
          ),
          bottomSheet: VStack(
            [
              CustomButton(
                title: "New Vehicle".tr(),
                onPressed: vm.newVehicleCreate,
              ),
            ],
          )
              .p12()
              .py(20)
              .box
              .shadow
              .color(context.theme.colorScheme.surface)
              .make(),
        );
      },
    );
  }
}
