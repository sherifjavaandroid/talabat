import 'package:flutter/material.dart';
import 'package:fuodz/models/vendor_type.dart';
import 'package:fuodz/view_models/vendor/top_vendors.vm.dart';
import 'package:fuodz/widgets/busy_indicator.dart';
import 'package:fuodz/widgets/list_items/top_service_vendor.hz.list_item.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class TopServiceVendors extends StatelessWidget {
  const TopServiceVendors(
    this.vendorType, {
    Key? key,
  }) : super(key: key);

  final VendorType vendorType;

  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<TopVendorsViewModel>.reactive(
      viewModelBuilder: () => TopVendorsViewModel(
        context,
        vendorType,
        params: {"type": "rated"},
        enableFilter: false,
      ),
      onViewModelReady: (model) => model.initialise(),
      builder: (context, model, child) {
        return VStack(
          [
            Visibility(
              visible: model.isBusy,
              child: BusyIndicator().centered(),
            ),
            //
            Visibility(
              visible: model.vendors.isNotEmpty,
              child: VStack(
                [
                  // UiSpacer.vSpace(),
                  "Top Rated Providers".tr().text.xl.bold.make().px20(),
                  12.heightBox,
                  //vendors list
                  Builder(
                    builder: (context) {
                      double spacing = 20;
                      double eachWidth =
                          (context.screenWidth - (spacing * 2)) / 1.15;
                      List<Widget> children = model.vendors.map(
                        (vendor) {
                          return TopServiceVendorHorizontalListItem(
                            vendor: vendor,
                            onPressed: model.vendorSelected,
                          ).w(eachWidth);
                        },
                      ).toList();
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
                        ).scrollHorizontal(physics: BouncingScrollPhysics()),
                      );
                    },
                  ),
                ],
              ),
            ),
          ],
        );
      },
    );
  }
}
