import 'package:flutter/material.dart';
import 'package:fuodz/view_models/taxi.vm.dart';
import 'package:fuodz/widgets/list_items/new_horizontal_vehicle_type.list_item.dart';
import 'package:fuodz/widgets/states/loading_indicator.dart';
import 'package:velocity_x/velocity_x.dart';

class NewTaxiVehicleTypeListView extends StatelessWidget {
  NewTaxiVehicleTypeListView({
    Key? key,
    this.min = false,
    required this.vm,
    this.axis = Axis.vertical,
  }) : super(key: key);

  final TaxiViewModel vm;
  final bool min;
  final Axis axis;

  @override
  Widget build(BuildContext context) {
    //vertical axis
    return LoadingIndicator(
      loading: vm.busy(vm.vehicleTypes),
      child: Container(
        constraints: BoxConstraints(
          maxHeight: context.percentHeight * 30,
        ),
        child: Scrollbar(
          thumbVisibility: true,
          trackVisibility: true,
          child: ListView.separated(
            padding: EdgeInsets.all(12),
            physics: PageScrollPhysics(),
            itemCount: vm.vehicleTypes.length,
            itemBuilder: (context, index) {
              final vehicleType = vm.vehicleTypes[index];
              return NewHorizontalVehicleTypeListItem(vm, vehicleType);
            },
            separatorBuilder: (ctx, index) => 10.heightBox,
          ),
        ),
      ),
    );
  }
}
