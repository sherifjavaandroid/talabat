import 'package:flutter/material.dart';
import 'package:fuodz/models/search.dart';
import 'package:fuodz/models/vendor_type.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/coupons.vm.dart';
import 'package:fuodz/widgets/custom_list_view.dart';
import 'package:fuodz/widgets/list_items/coupon.list_item.dart';
import 'package:fuodz/widgets/section.title.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class SectionCouponsView extends StatelessWidget {
  const SectionCouponsView(
    this.vendorType, {
    this.title = "",
    this.scrollDirection = Axis.vertical,
    this.type = SearchFilterType.sales,
    this.itemWidth,
    this.viewType,
    this.separator,
    this.byLocation = false,
    this.itemsPadding,
    this.titlePadding,
    this.height,
    this.bPadding = 0,
    Key? key,
  }) : super(key: key);

  final VendorType? vendorType;
  final Axis scrollDirection;
  final SearchFilterType type;
  final String title;
  final double? height;
  final double? itemWidth;
  final dynamic viewType;
  final Widget? separator;
  final bool byLocation;
  final EdgeInsets? itemsPadding;
  final EdgeInsets? titlePadding;
  final double bPadding;

  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<CouponsViewModel>.reactive(
      viewModelBuilder: () => CouponsViewModel(
        context,
        vendorType,
      ),
      onViewModelReady: (model) => model.initialise(),
      builder: (context, model, child) {
        //
        Widget listView = CustomListView(
          scrollDirection: scrollDirection,
          padding: itemsPadding ?? EdgeInsets.symmetric(horizontal: 10),
          dataSet: model.coupons,
          isLoading: model.isBusy,
          noScrollPhysics: scrollDirection != Axis.horizontal,
          itemBuilder: (context, index) {
            //
            final coupon = model.coupons[index];
            //
            return CouponListItem(
              coupon,
              onPressed: model.couponSelected,
            ).w(itemWidth ?? (context.percentWidth * 50));
          },
          separatorBuilder:
              separator != null ? (ctx, index) => separator! : null,
        );

        //
        return (model.coupons.isEmpty)
            ? UiSpacer.emptySpace()
            : VStack(
                [
                  //
                  Visibility(
                    visible: title.isNotBlank,
                    child: Padding(
                      padding: titlePadding ?? EdgeInsets.all(12),
                      child: SectionTitle("$title"),
                    ),
                  ),

                  //vendors list
                  if (scrollDirection == Axis.horizontal)
                    listView.h(height ?? 195)
                  else
                    listView,
                  //
                  UiSpacer.vSpace(bPadding),
                ],
              );
      },
    );
  }
}
