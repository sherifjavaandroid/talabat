import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/taxi.vm.dart';
import 'package:fuodz/view_models/taxi_new_order_location_entry.vm.dart';
import 'package:fuodz/widgets/busy_indicator.dart';
import 'package:fuodz/widgets/cards/custom.visibility.dart';
import 'package:fuodz/widgets/custom_list_view.dart';
import 'package:fuodz/widgets/list_items/taxi_order_location_history.list_item.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:measure_size/measure_size.dart';
import 'package:velocity_x/velocity_x.dart';

class NewTaxiOrderEntryCollapsed extends StatelessWidget {
  const NewTaxiOrderEntryCollapsed(this.taxiNewOrderViewModel, {Key? key})
      : super(key: key);

  final NewTaxiOrderLocationEntryViewModel taxiNewOrderViewModel;

  @override
  Widget build(BuildContext context) {
    final TaxiViewModel vm = taxiNewOrderViewModel.taxiViewModel;
    //
    return MeasureSize(
      onChange: (size) {
        vm.updateGoogleMapPadding(
            height: taxiNewOrderViewModel.customViewHeight + 30);
      },
      child: VxBox(
        child: vm.isBusy
            ? BusyIndicator().centered().p20()
            : VStack(
                [
                  UiSpacer.swipeIndicator(),
                  UiSpacer.vSpace(),
                  HStack(
                    [
                      Icon(
                        FlutterIcons.search1_ant,
                        size: 24,
                        color: AppColor.primaryColor,
                      ),
                      "Where to?".tr().text.semiBold.lg.make().px12().expand(),
                      CustomVisibilty(
                        visible: AppStrings.canScheduleTaxiOrder,
                        child: Icon(
                          FlutterIcons.calendar_ant,
                          size: 18,
                          color: AppColor.primaryColor,
                        )
                            .onInkTap(
                              taxiNewOrderViewModel.onScheduleOrderPressed,
                            )
                            .p2(),
                      ),
                    ],
                  )
                      .px12()
                      .py8()
                      .box
                      .color(context.theme.colorScheme.surface)
                      .shadowXs
                      .withRounded(value: 5)
                      .border(color: AppColor.primaryColor)
                      .make()
                      .onTap(
                        taxiNewOrderViewModel.onDestinationPressed,
                      ),
                  //previous history
                  Padding(
                    padding: (taxiNewOrderViewModel
                            .shortPreviousAddressesList.isEmpty)
                        ? EdgeInsets.all(5)
                        : EdgeInsets.symmetric(vertical: 5),
                    child: CustomListView(
                      isLoading: taxiNewOrderViewModel.busy(
                        taxiNewOrderViewModel.previousAddresses,
                      ),
                      dataSet: taxiNewOrderViewModel.shortPreviousAddressesList,
                      padding: EdgeInsets.zero,
                      itemBuilder: (ctx, index) {
                        final orderAddressHistory = taxiNewOrderViewModel
                            .shortPreviousAddressesList[index];
                        return TaxiOrderHistoryListItem(
                          orderAddressHistory,
                          onPressed:
                              taxiNewOrderViewModel.onDestinationSelected,
                        );
                      },
                      separatorBuilder: (ctx, index) => UiSpacer.divider(),
                    ),
                  ),
                ],
              ),
      )
          .p20
          .color(context.theme.colorScheme.surface)
          .topRounded(value: 25)
          .outerShadow2Xl
          .make(),
    );
  }
}
