import 'package:flutter/material.dart';
import 'package:fuodz/models/vendor_type.dart';
import 'package:fuodz/view_models/vendor/popular_services.vm.dart';
import 'package:fuodz/widgets/buttons/custom_text_button.dart';
import 'package:fuodz/widgets/list_items/service.gridview_item.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class PopularServicesView extends StatefulWidget {
  const PopularServicesView(this.vendorType, {Key? key}) : super(key: key);

  final VendorType vendorType;

  @override
  _PopularServicesViewState createState() => _PopularServicesViewState();
}

class _PopularServicesViewState extends State<PopularServicesView> {
  bool showGrid = true;

  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<PopularServicesViewModel>.reactive(
      viewModelBuilder: () => PopularServicesViewModel(
        context,
        widget.vendorType,
      ),
      onViewModelReady: (vm) => vm.initialise(),
      builder: (context, vm, child) {
        //
        if (!vm.isBusy && vm.services.isEmpty) {
          return SizedBox.shrink();
        }
        //
        return VStack(
          [
            //
            HStack(
              [
                ("Popular".tr() + " ${widget.vendorType.name}")
                    .text
                    .xl
                    .bold
                    .make()
                    .expand(),
                //view more
                CustomTextButton(
                  title: "See all".tr(),
                  onPressed: vm.openSearch,
                ),
              ],
              spacing: 20,
            ).px12(),
            //
            Builder(
              builder: (context) {
                double spacing = 20;
                double eachWidth = (context.screenWidth - (spacing * 2)) / 2;
                List<Widget> children = vm.services.map((service) {
                  return ServiceGridViewItem(
                    service: service,
                    onPressed: vm.serviceSelected,
                  ).w(eachWidth);
                }).toList();
                //append 12px
                children.insert(0, 0.widthBox);
                children.add(0.widthBox);
                //
                return Scrollbar(
                  thumbVisibility: false,
                  trackVisibility: false,
                  interactive: true,
                  child: HStack(
                    children,
                    spacing: spacing,
                    axisSize: MainAxisSize.min,
                    alignment: MainAxisAlignment.start,
                    crossAlignment: CrossAxisAlignment.start,
                  ).scrollHorizontal(
                    physics: BouncingScrollPhysics(),
                  ),
                );
              },
            ),

            // CustomListView(
            //   scrollDirection: Axis.horizontal,
            //   isLoading: vm.isBusy,
            //   dataSet: vm.services,
            //   itemBuilder: (context, index) {
            //     final service = vm.services[index];
            //     return ServiceGridViewItem(
            //       service: service,
            //       onPressed: vm.serviceSelected,
            //     ).h(220).w(300);
            //   },
            // ).p12(),
          ],
        );
      },
    );
  }
}
