import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/constants/sizes.dart';
import 'package:fuodz/extensions/string.dart';
import 'package:fuodz/view_models/cart.vm.dart';
import 'package:fuodz/views/pages/cart/widgets/amount_tile.dart';
import 'package:fuodz/views/pages/cart/widgets/apply_coupon.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/custom_list_view.dart';
import 'package:fuodz/widgets/list_items/cart.list_item.dart';
import 'package:fuodz/widgets/states/cart.empty.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:dotted_line/dotted_line.dart';

class CartPage extends StatefulWidget {
  const CartPage({Key? key}) : super(key: key);

  @override
  _CartPageState createState() => _CartPageState();
}

class _CartPageState extends State<CartPage> {
  @override
  Widget build(BuildContext context) {
//amount styling
    TextStyle totalStyle = context.textTheme.bodyLarge!.copyWith(
      fontSize: Sizes.fontSizeExtraLarge,
      fontWeight: FontWeight.w600,
    );
    TextStyle summaryStyle = context.textTheme.bodyLarge!.copyWith(
      fontSize: Sizes.fontSizeLarge,
    );
    //
    return BasePage(
      showAppBar: true,
      showLeadingAction: true,
      title: "My Cart".tr(),
      backgroundColor: context.backgroundColor,
      body: SafeArea(
        child: ViewModelBuilder<CartViewModel>.reactive(
          viewModelBuilder: () => CartViewModel(context),
          onViewModelReady: (model) => model.initialise(),
          builder: (context, model, child) {
            return Container(
              key: model.pageKey,
              child: VStack(
                [
                  //
                  if (model.cartItems.isEmpty)
                    EmptyCart().centered().expand()
                  else
                    VStack(
                      [
                        //cart items list
                        CustomListView(
                          padding: EdgeInsets.fromLTRB(20, 20, 20, 30),
                          noScrollPhysics: true,
                          dataSet: model.cartItems,
                          separatorBuilder: (_, __) => 12.heightBox,
                          itemBuilder: (context, index) {
                            //
                            final cart = model.cartItems[index];
                            final product = cart.product;
                            return InkWell(
                              child: CartListItem(
                                cart,
                                onQuantityChange: (qty) =>
                                    model.updateCartItemQuantity(qty, index),
                                deleteCartItem: () =>
                                    model.deleteCartItem(index),
                              ),
                              onTap: () => model.productSelected(product!),
                            );
                          },
                        ).box.color(AppColor.faintBgColor).make(),

                        //
                        Container(
                          padding: EdgeInsets.all(20),
                          decoration: BoxDecoration(
                            color: context.backgroundColor,
                            boxShadow: [
                              BoxShadow(
                                color: Colors.grey.shade200,
                                blurRadius: 4,
                                spreadRadius: 1,
                                offset: Offset(4, -4),
                              ),
                            ],
                          ),
                          child: VStack(
                            [
                              ApplyCoupon(model),
                              10.heightBox,
                              AmountTile(
                                "Total Item".tr(),
                                model.totalCartItems.toString(),
                                amountStyle: summaryStyle,
                              ),
                              AmountTile(
                                "Sub-Total".tr(),
                                "${model.currencySymbol} ${model.subTotalPrice}"
                                    .currencyFormat(),
                                amountStyle: summaryStyle,
                              ),
                              Visibility(
                                visible: model.coupon != null &&
                                    !model.coupon!.for_delivery,
                                child: AmountTile(
                                  "Discount".tr(),
                                  "${model.currencySymbol} ${model.discountCartPrice}"
                                      .currencyFormat(),
                                  amountStyle: summaryStyle,
                                ),
                              ),
                              //
                              Visibility(
                                visible: (model.coupon != null &&
                                    model.coupon!.for_delivery),
                                child: VStack(
                                  [
                                    DottedLine(
                                      dashColor:
                                          context.textTheme.bodyLarge!.color!,
                                    ).py12(),
                                    "Discount will be applied to delivery fee on checkout"
                                        .tr()
                                        .text
                                        .medium
                                        .make(),
                                  ],
                                ).py(4),
                              ),
                              DottedLine(
                                dashColor: context.textTheme.bodyLarge!.color!,
                              ).py(10),
                              AmountTile(
                                "Total".tr(),
                                "${model.currencySymbol} ${model.totalCartPrice}"
                                    .currencyFormat(),
                                amountStyle: totalStyle,
                              ),
                              //
                              CustomButton(
                                title: "CHECKOUT".tr(),
                                onPressed: model.checkoutPressed,
                              ).h(Vx.dp48).py32(),
                            ],
                          ),
                        ),
                      ],
                    )
                        .pOnly(bottom: context.mq.viewPadding.bottom)
                        .scrollVertical()
                        .expand(),
                ],
              ),
            );
          },
        ),
      ),
    );
  }
}
