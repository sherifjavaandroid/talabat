import 'package:flutter/material.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:fuodz/view_models/order_details.vm.dart';
import 'package:fuodz/widgets/cards/amount_tile.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:fuodz/widgets/custom_list_view.dart';
import 'package:fuodz/widgets/list_items/order_product.list_item.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:localize_and_translate/localize_and_translate.dart';

class OrderDetailsItemsView extends StatelessWidget {
  const OrderDetailsItemsView(this.vm, {Key? key}) : super(key: key);
  final OrderDetailsViewModel vm;

  @override
  Widget build(BuildContext context) {
    return VStack(
      [
        (vm.order.isPackageDelivery
                ? "Package Details"
                : vm.order.isSerice
                    ? "Service"
                    : "Products")
            .tr()
            .text
            .semiBold
            .xl
            .make()
            .pOnly(bottom: Vx.dp10),
        vm.order.isPackageDelivery
            ? VStack(
                [
                  AmountTile(
                    "Package Type".tr(),
                    vm.order.packageType!.name,
                  ),
                  AmountTile("Width".tr(), "${vm.order.width} cm"),
                  AmountTile("Length".tr(), "${vm.order.length} cm"),
                  AmountTile("Height".tr(), "${vm.order.height} cm"),
                  AmountTile("Weight".tr(), "${vm.order.weight} kg"),
                ],
                crossAlignment: CrossAxisAlignment.end,
              )
            : vm.order.isSerice
                ? VStack(
                    [
                      AmountTile(
                        "Service".tr(),
                        vm.order.orderService!.service!.name,
                      ),
                      if (vm.order.orderService!.service!.category != null)
                        AmountTile(
                          "Category".tr(),
                          vm.order.orderService!.service!.category!.name,
                        ),
                    ],
                    crossAlignment: CrossAxisAlignment.end,
                  )
                : CustomListView(
                    noScrollPhysics: true,
                    dataSet: vm.order.orderProducts ?? [],
                    separatorBuilder: (context, index) =>
                        UiSpacer.divider(height: 10, thickness: 0.6),
                    itemBuilder: (context, index) {
                      //
                      final orderProduct = vm.order.orderProducts![index];
                      return OrderProductListItem(
                        orderProduct: orderProduct,
                      );
                    },
                  ),

        //order photo
        (vm.order.photo != null && !Utils.isDefaultImg(vm.order.photo))
            ? CustomImage(
                imageUrl: vm.order.photo!,
                boxFit: BoxFit.fill,
              ).h(context.percentHeight * 30).wFull(context)
            : UiSpacer.emptySpace(),
      ],
    );
  }
}
