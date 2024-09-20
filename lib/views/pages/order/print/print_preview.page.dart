import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_images.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/constants/printer_values.dart';
import 'package:fuodz/extensions/dynamic.dart';
import 'package:fuodz/models/order.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:screenshot/screenshot.dart';
import 'package:velocity_x/velocity_x.dart';

class OrderPrintPreviewPage extends StatefulWidget {
  const OrderPrintPreviewPage({
    required this.order,
    super.key,
  });

  final Order order;

  @override
  State<OrderPrintPreviewPage> createState() => _OrderPrintPreviewPageState();
}

class _OrderPrintPreviewPageState extends State<OrderPrintPreviewPage> {
  //
  ScreenshotController screenshotController = ScreenshotController();
  //
  @override
  void initState() {
    super.initState();
    //on finish build
    WidgetsBinding.instance.addPostFrameCallback((timeStamp) {
      //take screenshot
      screenshotController
          .capture(
        delay: const Duration(milliseconds: 10),
      )
          .then((capturedImage) {
        //pop the page and return the image
        Navigator.of(context).pop(capturedImage);
      });
    });
  }

  @override
  Widget build(BuildContext context) {
    final Order order = widget.order;
    //only bold text
    final boldStyle = TextStyle(fontWeight: FontWeight.bold);
    //bold with medium text
    final boldMediumStyle = TextStyle(
      fontWeight: FontWeight.bold,
      fontSize: 16,
    );
    //bold with large text
    final boldLargeStyle = TextStyle(
      fontWeight: FontWeight.bold,
      fontSize: 18,
    );

    final printOutWidth = PrinterSettingValues.paperSizeToWidth().width;

    return Scaffold(
      body: Screenshot(
        controller: screenshotController,
        child: SingleChildScrollView(
          child: Center(
            child: Container(
              color: Colors.white,
              width: double.infinity,
              padding: EdgeInsets.all(5),
              child: Column(
                children: [
                  //logo
                  Image.asset(
                    AppImages.appLogo,
                    width: printOutWidth * 0.12,
                    height: printOutWidth * 0.12,
                  ),
                  Text("${AppStrings.appName}", style: boldLargeStyle),
                  10.heightBox,
                  Text("${order.vendor?.name}", style: boldMediumStyle),
                  Text("${order.vendor?.address}"),
                  15.heightBox,
                  Row(
                    children: [
                      Expanded(child: Text("Code".tr(), style: boldStyle)),
                      Text("${order.code}"),
                    ],
                  ),
                  HStack([
                    Expanded(child: Text("Status".tr(), style: boldStyle)),
                    Text("${order.status.allWordsCapitilize()}"),
                  ]),
                  HStack([
                    Expanded(child: Text("Customer".tr(), style: boldStyle)),
                    Text("  ${order.user.name}"),
                  ]),
                  10.heightBox,
                  //
                  if (order.isPackageDelivery)
                    VStack([
                      //
                      ...((order.orderStops ?? []).mapIndexed(
                        (orderStop, index) {
                          //
                          return VStack(
                            [
                              if (index == 0)
                                Text("Pickup Address".tr(), style: boldStyle)
                              else
                                Text("Stop".tr(), style: boldStyle),
                              //
                              Text("${orderStop.deliveryAddress?.name}"),
                              //
                              HStack([
                                Text("Name".tr(), style: boldStyle).expand(),
                                Text("  ${orderStop.name}"),
                              ]),
                              //phone
                              HStack([
                                Text("Phone".tr(), style: boldStyle).expand(),
                                Text("  ${orderStop.phone}"),
                              ]),
                              //note
                              HStack([
                                Text("Note".tr(), style: boldStyle).expand(),
                                Text("  ${orderStop.note}"),
                              ]),
                              15.heightBox,
                              //
                              Text("Package Details".tr(), style: boldStyle),
                              10.heightBox,
                              HStack([
                                Text("Package Type".tr(), style: boldStyle)
                                    .expand(),
                                Text("${order.packageType?.name}"),
                              ]),
                              HStack([
                                Text("Width".tr(), style: boldStyle).expand(),
                                Text("${order.width} cm"),
                              ]),
                              HStack([
                                Text("Length".tr(), style: boldStyle).expand(),
                                Text("${order.length} cm"),
                              ]),
                              HStack([
                                Text("Height".tr(), style: boldStyle).expand(),
                                Text("${order.height} cm"),
                              ]),
                              HStack([
                                Text("Weight".tr(), style: boldStyle).expand(),
                                Text("${order.weight} kg"),
                              ]),
                            ],
                          );
                        },
                      ).toList()),
                    ])
                  else
                    VStack([
                      Text("Delivery Address".tr(), style: boldMediumStyle),
                      Text(
                          "${order.deliveryAddress != null ? order.deliveryAddress?.name : 'Customer Pickup'}"),
                      10.heightBox,
                      //
                      Text("Products".tr(), style: boldStyle),
                      //products
                      ...((order.orderProducts ?? [])
                          .mapIndexed((orderProduct, index) {
                        return VStack(
                          [
                            HStack(
                              [
                                Text("${orderProduct.product?.name} x${orderProduct.quantity}")
                                    .expand(),
                                Text(
                                  "    ${AppStrings.currencySymbol} ${orderProduct.price}"
                                      .currencyFormat(),
                                ),
                              ],
                            ),

                            5.heightBox,
                            //product options
                            if (orderProduct.options != null)
                              Text("${orderProduct.options}"),
                          ],
                        );
                      }).toList()),
                      //
                      10.heightBox,
                      Text("Note".tr(), style: boldStyle),
                      Text("${order.note}"),
                    ]),

                  //
                  20.heightBox,
                  HStack([
                    Text("Subtotal".tr(), style: boldStyle).expand(),
                    Text(
                      "  ${AppStrings.currencySymbol} ${order.subTotal}"
                          .currencyFormat(),
                    ),
                  ]),
                  HStack([
                    Text("Discount".tr(), style: boldStyle).expand(),
                    Text(
                      "  ${AppStrings.currencySymbol} ${order.discount}"
                          .currencyFormat(),
                    ),
                  ]),
                  HStack([
                    Text("Delivery Fee".tr(), style: boldStyle).expand(),
                    Text(
                      "  ${AppStrings.currencySymbol} ${order.deliveryFee}"
                          .currencyFormat(),
                    ),
                  ]),
                  HStack([
                    Text("Tax".tr(), style: boldStyle).expand(),
                    Text(
                      "  ${AppStrings.currencySymbol} ${order.tax}"
                          .currencyFormat(),
                    ),
                  ]),
                  HStack([
                    Text("Driver Tip".tr(), style: boldStyle).expand(),
                    Text(
                      "  ${AppStrings.currencySymbol} ${order.tip != null ? order.tip : '0.00'}"
                          .currencyFormat(),
                    ),
                  ]),
                  HStack([
                    Text("Total".tr(), style: boldStyle).expand(),
                    Text(
                      "  ${AppStrings.currencySymbol} ${order.total}"
                          .currencyFormat(),
                    ),
                  ]),

                  20.heightBox,
                  Text("${order.code}", style: boldMediumStyle),
                  20.heightBox,
                ],
              ),
            ),
          ),
        ),
      ),
      // body: Screenshot(
      //   child: widget.child.scrollVertical(),
      //   controller: widget.screenshotController,
      // ),
    );
  }
}
