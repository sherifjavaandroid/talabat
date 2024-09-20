import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/extensions/string.dart';
import 'package:fuodz/view_models/product_details.vm.dart';
import 'package:fuodz/widgets/busy_indicator.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/buttons/qty_stepper.dart';
import 'package:fuodz/widgets/currency_hstack.dart';
import 'package:fuodz/widgets/states/loading_indicator.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class ProductDetailsCartBottomSheet extends StatelessWidget {
  const ProductDetailsCartBottomSheet({
    required this.model,
    Key? key,
  }) : super(key: key);

  final ProductDetailsViewModel model;
  @override
  Widget build(BuildContext context) {
    return LoadingIndicator(
      loading: model.busy(model.product),
      loadingWidget: BusyIndicator().centered().box.make().wh(40, 40),
      child: VStack(
        [
          //
          Visibility(
            visible: model.product.hasStock,
            child: HStack(
              [
                //
                "Quantity".tr().text.xl.medium.make().expand(),
                //
                QtyStepper(
                  defaultValue: model.product.selectedQty,
                  min: 1,
                  max: (model.product.availableQty != null &&
                          model.product.availableQty! > 0)
                      ? model.product.availableQty!
                      : 20,
                  disableInput: true,
                  onChange: model.updatedSelectedQty,
                ),
              ],
            ),
          ),

          //
          Visibility(
            visible: model.product.hasStock,
            child: HStack(
              [
                //
                CustomButton(
                  loading: model.isBusy,
                  child: Icon(
                    FlutterIcons.heart_fea,
                    color: Colors.white,
                  ).centered(),
                  onPressed: !model.isAuthenticated()
                      ? model.openLogin
                      : !model.product.isFavourite
                          ? model.addToFavourite
                          : null,
                ),
                //
                CustomButton(
                  loading: model.isBusy,
                  child: HStack(
                    [
                      "Add to cart".tr().text.white.medium.make().expand(),
                      CurrencyHStack(
                        [
                          model.currencySymbol.text.white.lg.make(),
                          model.total
                              .currencyValueFormat()
                              .text
                              .white
                              .letterSpacing(1.5)
                              .xl
                              .semiBold
                              .make(),
                        ],
                      ),
                    ],
                  ).p12(),
                  onPressed: model.addToCart,
                ).expand(),
              ],
              spacing: 20,
            ).py12(),
          ),

          Visibility(
            visible: !model.product.hasStock,
            child: "No stock"
                .tr()
                .text
                .white
                .makeCentered()
                .p8()
                .box
                .red500
                .roundedSM
                .make()
                .p8()
                .wFull(context),
          ),
        ],
      ),
    )
        .px20()
        .py12()
        .box
        .color(context.theme.colorScheme.surface)
        .shadowSm
        .make()
        .wFull(context);
  }
}
