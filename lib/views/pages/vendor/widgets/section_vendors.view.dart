import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/models/search.dart';
import 'package:fuodz/models/vendor.dart';
import 'package:fuodz/models/vendor_type.dart';
import 'package:fuodz/view_models/vendor/section_vendors.vm.dart';
import 'package:fuodz/widgets/cards/custom.visibility.dart';
import 'package:fuodz/widgets/custom_list_view.dart';
import 'package:fuodz/widgets/list_items/food_vendor.list_item.dart';
import 'package:fuodz/widgets/list_items/horizontal_vendor.list_item.dart';
import 'package:fuodz/widgets/list_items/vendor.list_item.dart';
import 'package:fuodz/widgets/section.title.dart';
import 'package:fuodz/widgets/states/vendor.empty.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class SectionVendorsView extends StatefulWidget {
  const SectionVendorsView(
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
    this.hideEmpty = false,
    this.onSeeAllPressed,
    //provide your own itembuilder
    this.itemBuilder,
    this.spacer,
    Key? key,
  }) : super(key: key);

  final VendorType? vendorType;
  final Axis scrollDirection;
  final SearchFilterType type;
  final String title;
  final double? itemWidth;
  final dynamic viewType;
  final Widget? separator;
  final bool byLocation;
  final EdgeInsets? itemsPadding;
  final EdgeInsets? titlePadding;
  final bool hideEmpty;
  final Function? onSeeAllPressed;
  final Widget Function(BuildContext, int, Vendor)? itemBuilder;
  final double? spacer;

  @override
  State<SectionVendorsView> createState() => _SectionVendorsViewState();
}

class _SectionVendorsViewState extends State<SectionVendorsView> {
  @override
  Widget build(BuildContext context) {
    return CustomVisibilty(
      visible: !AppStrings.enableSingleVendor,
      child: ViewModelBuilder<SectionVendorsViewModel>.reactive(
        viewModelBuilder: () => SectionVendorsViewModel(
          context,
          widget.vendorType,
          type: widget.type,
          byLocation: widget.byLocation,
        ),
        onViewModelReady: (model) => model.initialise(),
        builder: (context, model, child) {
          //
          Widget listView = CustomListView(
            scrollDirection: widget.scrollDirection,
            padding:
                widget.itemsPadding ?? EdgeInsets.symmetric(horizontal: 10),
            dataSet: model.vendors,
            isLoading: model.isBusy,
            noScrollPhysics: widget.scrollDirection != Axis.horizontal,
            itemBuilder: widget.itemBuilder != null
                ? (ctx, index) {
                    return widget.itemBuilder!(
                        ctx, index, model.vendors[index]);
                  }
                : (context, index) {
                    //
                    final vendor = model.vendors[index];
                    //
                    if (widget.viewType != null &&
                        widget.viewType == HorizontalVendorListItem) {
                      return HorizontalVendorListItem(
                        vendor,
                        onPressed: model.vendorSelected,
                      );
                    } else if (vendor.vendorType.isFood) {
                      return FittedBox(
                          child: FoodVendorListItem(
                        vendor: vendor,
                        onPressed: model.vendorSelected,
                      ).w(widget.itemWidth ?? (context.percentWidth * 50)));
                    } else {
                      //
                      return VendorListItem(
                        vendor: vendor,
                        onPressed: model.vendorSelected,
                      ).w(widget.itemWidth ?? (context.percentWidth * 50));
                    }
                  },
            emptyWidget: EmptyVendor(),
            separatorBuilder: widget.separator != null
                ? (ctx, index) => widget.separator!
                : null,
          );

          //
          return Visibility(
            visible: !widget.hideEmpty || (model.vendors.isNotEmpty),
            child: VStack(
              [
                //
                Visibility(
                  visible: widget.title.isNotBlank,
                  child: Padding(
                    padding: widget.titlePadding ?? EdgeInsets.all(12),
                    child: HStack(
                      [
                        SectionTitle("${widget.title}").expand(),
                        //see all button
                        if (widget.onSeeAllPressed != null)
                          "See more".tr().text.sm.make().onInkTap(
                            () {
                              widget.onSeeAllPressed!();
                            },
                          ),
                      ],
                      spacing: 10,
                    ).wFull(context),
                  ),
                ),

                //vendors list
                if (model.vendors.isEmpty)
                  listView.h(model.vendors.isEmpty ? 240 : 195).wFull(context)
                else if (widget.scrollDirection == Axis.horizontal)
                  listView.h(195).wFull(context)
                else
                  listView.wFull(context)
              ],
              spacing: widget.spacer ?? 0,
            ),
          );
        },
      ),
    );
  }
}
