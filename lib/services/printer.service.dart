import 'dart:io';
import 'dart:typed_data';
import 'package:flutter_esc_pos_utils/flutter_esc_pos_utils.dart';
import 'package:fuodz/services/local_storage.service.dart';
import 'package:image/image.dart' as img;
import 'package:dartx/dartx.dart' show IterableFirstOrNullWhere;
import 'package:flutter/material.dart';
import 'package:flutter_dropdown_alert/alert_controller.dart';
import 'package:flutter_dropdown_alert/model/data_alert.dart';
import 'package:fuodz/constants/printer_values.dart';
import 'package:fuodz/models/order.dart';
import 'package:fuodz/services/app.service.dart';
import 'package:fuodz/views/pages/order/print/print_preview.page.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:path_provider/path_provider.dart';
import 'package:share_plus/share_plus.dart';
import 'package:fuodz/extensions/context.dart';
import 'package:print_bluetooth_thermal/print_bluetooth_thermal.dart';

class PrinterService {
  //
  handleAutoPrint(Order order) async {
    BuildContext context = AppService().navigatorKey.currentContext!;
    //process printing orders
    final isExternalPrinter = PrinterSettingValues.useExternalPrinter;
    if (isExternalPrinter) {
      //print to external printer
      await printToExternalPrinter(context, order);
    } else {
      BluetoothInfo? _selectedDevice = await getConnectedPrinter();
      if (_selectedDevice != null) {
        //print order
        await printOrder(
          context,
          order,
          printer: _selectedDevice,
        );
      } else {
        AlertController.show(
          "Error".tr(),
          "No printer is currently connected".tr(),
          TypeAlert.warning,
        );
      }
    }
  }

  Future<void> printOrder(
    BuildContext context,
    Order order, {
    BluetoothInfo? printer,
    String? printerAddress,
  }) async {
    //
    final previewResult = await context.push(
      (context) => OrderPrintPreviewPage(
        order: order,
      ),
    );

    //check result
    if (previewResult == null || !(previewResult is Uint8List)) {
      AlertController.show(
        "Error".tr(),
        "Error generating printout".tr(),
        TypeAlert.error,
      );
      return;
    }

    //if printer is null
    if (printer == null) {
      BluetoothInfo? _selectedDevice = await getConnectedPrinter(
        address: printerAddress,
      );
      if (_selectedDevice == null) {
        AlertController.show(
          "Error".tr(),
          "There is no printer is currently connected".tr(),
          TypeAlert.error,
        );
        return;
      }

      //set the new connected device
      printer = _selectedDevice;
    }

    // get the receipt in bytes
    final paperSize = PrinterSettingValues.paperSizeToWidth().width;
    List<int> bytes = await getReceiptBytes(previewResult, paperSize);
    bool alreadyConnected = await PrintBluetoothThermal.connectionStatus;
    if (!alreadyConnected) {
      await PrintBluetoothThermal.connect(macPrinterAddress: printer.macAdress);
    }
    PrintBluetoothThermal.writeBytes(bytes);
  }

  /*
    try {
      await Printing.layoutPdf(onLayout: (_) => previewResult);
    } catch (error) {
      //SIZE
      // 0- normal size text
      // 1- only bold text
      // 2- bold with medium text
      // 3- bold with large text
      //ALIGN
      // 0- ESC_ALIGN_LEFT
      // 1- ESC_ALIGN_CENTER
      // 2- ESC_ALIGN_RIGHT
      bluetooth.isConnected.then((isConnected) async {
        if (isConnected != null && isConnected) {
          //
          bluetooth.printNewLine();
          bluetooth.printImageBytes(previewResult);
          bluetooth.printNewLine();
          bluetooth.paperCut();
        }
      });
    }
    */

//if device is arabic locale
  Future<void> printToExternalPrinter(
    BuildContext context,
    Order order,
  ) async {
    final previewResult = await context.push(
      (context) => OrderPrintPreviewPage(
        order: order,
      ),
    );

    if (previewResult == null || !(previewResult is Uint8List)) {
      AlertController.show(
        "Error".tr(),
        "Error sharing printout".tr(),
        TypeAlert.error,
      );
      return;
    }

    Uint8List imgUint8List = previewResult;
    final directory = await getDownloadsDirectory();
    String fileName = 'order_print_${order.code}';
    fileName += "_${DateTime.now().millisecond}.png";
    String imagePath = '${directory?.path}/$fileName';
    print("Path: $imagePath");
    final receiptImage = await File(imagePath).create();
    await receiptImage.writeAsBytes(imgUint8List);
    //share image
    final result = await Share.shareXFiles(
      [XFile(imagePath)],
      text: 'Order Print Out'.tr(),
    );

    if (result.status != ShareResultStatus.success) {
      AlertController.show(
        "Error".tr(),
        "Error sharing printout".tr(),
        TypeAlert.error,
      );
    }
  }

//MISC. FUnctions
  Future<BluetoothInfo?> getConnectedPrinter({String? address}) async {
    final String addressKey = "BLE_MAC_ADDRESS";
    //if address empty, try get previous address
    if (address == null) {
      address = LocalStorageService.prefs!.getString(addressKey);
    }
    BluetoothInfo? selectedDevice;
    var devices = await PrintBluetoothThermal.pairedBluetooths;
    if (devices.isNotEmpty) {
      //find where device is connected
      selectedDevice = devices.firstOrNullWhere(
        (device) => device.macAdress == address,
      );
    }

    //
    if (selectedDevice != null) {
      await LocalStorageService.prefs!.setString(
        addressKey,
        selectedDevice.macAdress,
      );
    }

    //
    return selectedDevice;
  }

  Future<List<int>> getReceiptBytes(
    Uint8List screenshot,
    int _selectedSize,
  ) async {
    List<int> bytes = [];
    final img.Image? image = img.decodeImage(screenshot);
    img.Image resized = img.copyResize(
      image!,
      width: PrinterSettingValues.paperSizeToWidth().width,
    );
    final profile = await CapabilityProfile.load();
    final generator = Generator(
      PrinterSettingValues.paperSize(),
      profile,
    );

    bytes += generator.feed(1);
    bytes += generator.image(resized);
    bytes += generator.cut();
    return bytes;
  }
}



/*

 printOrder(
    BuildContext context,
    BlueThermalPrinter bluetooth,
    Order order,
  ) async {
    //SIZE
    // 0- normal size text
    // 1- only bold text
    // 2- bold with medium text
    // 3- bold with large text
    //ALIGN
    // 0- ESC_ALIGN_LEFT
    // 1- ESC_ALIGN_CENTER
    // 2- ESC_ALIGN_RIGHT
    bluetooth.isConnected.then((isConnected) {
      if (isConnected != null && isConnected) {
        bluetooth.printNewLine();
        bluetooth.printCustom("${AppStrings.appName}", 3, 1);
        bluetooth.printNewLine();
        bluetooth.printNewLine();
        bluetooth.printCustom("${order.vendor?.name}", 2, 1);
        bluetooth.printNewLine();
        bluetooth.printCustom("${order.vendor?.address}", 1, 1);
        bluetooth.printNewLine();
        bluetooth.printLeftRight("Code", "  ${order.code}", 1);
        bluetooth.printLeftRight(
            "Status", "  ${order.status.allWordsCapitilize()}", 1);
        bluetooth.printLeftRight("Customer", "  ${order.user.name}", 1);
        bluetooth.printNewLine();
        //parcel order
        if (order.isPackageDelivery) {
          //print stops
          order.orderStops?.forEachIndexed((index, orderStop) {
            if (index == 0) {
              bluetooth.printCustom("Pickup Address".tr(), 1, 0);
            } else {
              bluetooth.printCustom("Stop".tr(), 1, 0);
            }
            bluetooth.printCustom("${orderStop.deliveryAddress?.name}", 2, 0);
            // recipient info
            bluetooth.printLeftRight("Name".tr(), "  ${orderStop.name}", 1);
            bluetooth.printLeftRight("Phone".tr(), "  ${orderStop.phone}", 1);
            bluetooth.printLeftRight("Note".tr(), "  ${orderStop.note}", 1);
            bluetooth.printNewLine();
          });

          //
          bluetooth.printNewLine();
          bluetooth.printCustom("Package Details".tr(), 2, 0);
          bluetooth.printLeftRight(
              "Package Type".tr(), "  ${order.packageType?.name}", 1);
          bluetooth.printLeftRight(
              "Width".tr() + "   ", "${order.width} cm", 1);
          bluetooth.printLeftRight(
              "Length".tr() + "   ", "${order.length} cm", 1);
          bluetooth.printLeftRight(
              "Height".tr() + "   ", "${order.height} cm", 1);
          bluetooth.printLeftRight(
              "Weight".tr() + "   ", "${order.weight} kg", 1);
        } else {
          bluetooth.printCustom("Delivery Address".tr(), 1, 0);
          bluetooth.printCustom(
              "${order.deliveryAddress != null ? order.deliveryAddress?.name : 'Customer Pickup'}",
              2,
              0);

          //
          bluetooth.printNewLine();
          bluetooth.printCustom("Products".tr(), 2, 1);
          //products
          for (var orderProduct in order.orderProducts ?? []) {
            //
            bluetooth.printLeftRight(
                "${orderProduct.product.name} x${orderProduct.quantity}",
                "    ${AppStrings.currencySymbol} ${orderProduct.price}"
                    .currencyFormat(),
                1);
            //product options
            if (orderProduct.options != null) {
              bluetooth.printCustom("${orderProduct.options}", 1, 0);
            }
          }
          //
          bluetooth.printNewLine();
          bluetooth.printCustom("Note".tr(), 2, 0);
          bluetooth.printCustom("${order.note}", 1, 0);
        }
        bluetooth.printNewLine();
        bluetooth.printLeftRight(
          "Subtotal".tr(),
          "  ${AppStrings.currencySymbol} ${order.subTotal}".currencyFormat(),
          1,
        );
        bluetooth.printLeftRight(
          "Discount".tr(),
          "  ${AppStrings.currencySymbol} ${order.discount}".currencyFormat(),
          1,
        );
        bluetooth.printLeftRight(
          "Delivery Fee".tr(),
          "  ${AppStrings.currencySymbol} ${order.deliveryFee}"
              .currencyFormat(),
          1,
        );
        bluetooth.printLeftRight(
          "Tax".tr(),
          "  ${AppStrings.currencySymbol} ${order.tax}".currencyFormat(),
          1,
        );
        bluetooth.printLeftRight(
          "Driver Tip".tr(),
          "  ${AppStrings.currencySymbol} ${order.tip != null ? order.tip : '0.00'}"
              .currencyFormat(),
          1,
        );
        bluetooth.printNewLine();
        bluetooth.printLeftRight(
          "Total".tr(),
          "  ${AppStrings.currencySymbol} ${order.total}".currencyFormat(),
          1,
        );
        bluetooth.printNewLine();
        bluetooth.printNewLine();
        bluetooth.printCustom("${order.code}", 3, 1);
        bluetooth.printNewLine();
        bluetooth.paperCut();
        bluetooth.printNewLine();
      }
    });
  }


Widget getOrderPrintOutView(Order order) {
  //
  return Container(
    child: Column(
      children: [
        Text("${AppStrings.appName}"),
        10.heightBox,
        Text("${order.vendor?.name}"),
        Text("${order.vendor?.address}"),
        15.heightBox,
        Row(
          children: [
            Expanded(child: Text("Code".tr())),
            Text("${order.code}"),
          ],
        ),
        HStack([
          Expanded(child: Text("Status".tr())),
          Text("${order.status.allWordsCapitilize()}"),
        ]),
        HStack([
          Expanded(child: Text("Customer".tr())),
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
                      Text("Pickup Address".tr())
                    else
                      Text("Stop".tr()),
                    //
                    Text("${orderStop.deliveryAddress?.name}"),
                    //
                    HStack([
                      Text("Name".tr()).expand(),
                      Text("  ${orderStop.name}"),
                    ]),
                    //phone
                    HStack([
                      Text("Phone".tr()).expand(),
                      Text("  ${orderStop.phone}"),
                    ]),
                    //note
                    HStack([
                      Text("Note".tr()).expand(),
                      Text("  ${orderStop.note}"),
                    ]),
                    15.heightBox,
                    //
                    Text("Package Details".tr()),
                    10.heightBox,
                    HStack([
                      Text("Package Type".tr()).expand(),
                      Text("${order.packageType?.name}"),
                    ]),
                    HStack([
                      Text("Width".tr()).expand(),
                      Text("${order.width} cm"),
                    ]),
                    HStack([
                      Text("Length".tr()).expand(),
                      Text("${order.length} cm"),
                    ]),
                    HStack([
                      Text("Height".tr()).expand(),
                      Text("${order.height} cm"),
                    ]),
                    HStack([
                      Text("Weight".tr()).expand(),
                      Text("${order.weight} kg"),
                    ]),
                  ],
                );
              },
            ).toList()),
          ])
        else
          VStack([
            Text("Delivery Address".tr()),
            Text(
                "${order.deliveryAddress != null ? order.deliveryAddress?.name : 'Customer Pickup'}"),
            10.heightBox,
            //
            Text("Products".tr()),
            //products
            ...((order.orderProducts ?? []).mapIndexed((orderProduct, index) {
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
            Text("Note".tr()),
            Text("${order.note}"),
          ]),

        //
        20.heightBox,
        HStack([
          Text("Subtotal".tr()).expand(),
          Text(
            "  ${AppStrings.currencySymbol} ${order.subTotal}".currencyFormat(),
          ),
        ]),
        HStack([
          Text("Discount".tr()).expand(),
          Text(
            "  ${AppStrings.currencySymbol} ${order.discount}".currencyFormat(),
          ),
        ]),
        HStack([
          Text("Delivery Fee".tr()).expand(),
          Text(
            "  ${AppStrings.currencySymbol} ${order.deliveryFee}"
                .currencyFormat(),
          ),
        ]),
        HStack([
          Text("Tax".tr()).expand(),
          Text(
            "  ${AppStrings.currencySymbol} ${order.tax}".currencyFormat(),
          ),
        ]),
        HStack([
          Text("Driver Tip".tr()).expand(),
          Text(
            "  ${AppStrings.currencySymbol} ${order.tip != null ? order.tip : '0.00'}"
                .currencyFormat(),
          ),
        ]),
        HStack([
          Text("Total".tr()).expand(),
          Text(
            "  ${AppStrings.currencySymbol} ${order.total}".currencyFormat(),
          ),
        ]),
        20.heightBox,
        Text("${order.code}"),
        20.heightBox,
      ],
    ),
  );
}
*/
