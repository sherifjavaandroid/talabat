// import 'dart:convert';
// import 'dart:typed_data';

// import 'package:drago_pos_printer/drago_pos_printer.dart';
// import 'package:fuodz/constants/app_strings.dart';
// import 'package:fuodz/extensions/dynamic.dart';
// import 'package:fuodz/models/order.dart';
// import 'package:localize_and_translate/localize_and_translate.dart';
// import 'package:velocity_x/velocity_x.dart';

// class ReceiptService {
//   //
//   static List<int> getInvoiceContent(
//     EscGenerator generator,
//     Order order, {
//     int? lineLen,
//   }) {
//     final titleStyle = PosStyles.defaults(
//       bold: true,
//       width: PosTextSize.size1,
//       align: PosAlign.center,
//     );
//     //
//     List<int> hr = generator.hr();
//     List<int> bytes = [];

//     //if arabice

//     // header
//     bytes += generator.reset();
//     bytes += generator.setGlobalCodeTable('CP864');
//     bytes += generator.emptyLines(1);
//     bytes += simpleText(
//       generator,
//       "${AppStrings.appName}",
//       style: titleStyle,
//     );
//     bytes += generator.emptyLines(1);
//     // vendor info
//     if (order.vendor != null) {
//       bytes += simpleText(
//         generator,
//         "${order.vendor?.name}",
//         style: titleStyle.copyWith(
//           width: PosTextSize.size2,
//         ),
//       );
//       bytes += simpleText(generator, "${order.vendor?.address}");
//       bytes += simpleText(generator, "Phone".tr() + ": ${order.vendor?.phone}");
//       bytes += simpleText(generator, "Email".tr() + ": ${order.vendor?.email}");
//       bytes += hr;
//     }
//     // breif order info
//     // code
//     bytes += twoRow(generator, "Code".tr(), "${order.code}");
//     // status
//     bytes += twoRow(
//       generator,
//       "Status".tr(),
//       "${order.status.allWordsCapitilize()}",
//     );
//     // payment method
//     bytes += twoRow(
//       generator,
//       "Payment Method".tr(),
//       "${order.paymentMethod?.name}",
//     );
//     // customer
//     bytes += twoRow(
//       generator,
//       "Customer".tr(),
//       "${order.user.name}",
//     );
//     bytes += hr;
//     // address/delivery/stop
//     if (order.isPackageDelivery) {
//       bytes += simpleText(generator, "${order.vendor?.address}");
//       bytes += simpleText(generator, "${order.vendor?.address}");
//     } else {
//       bytes += simpleText(
//         generator,
//         "Delivery Address".tr(),
//         align: PosAlign.left,
//         bold: true,
//       );
//       bytes += simpleText(
//         generator,
//         "${order.deliveryAddress != null ? order.deliveryAddress?.name : 'Customer Pickup'.tr()}",
//         align: PosAlign.left,
//       );
//       bytes += hr;
//       if (order.isSerice) {
//         //service
//       } else {
//         // products
//         // bytes += simpleText(
//         //   generator,
//         //   "Products".tr(),
//         //   align: PosAlign.left,
//         // );
//         // bytes += hr;
//         //header
//         bytes += xRows(
//           generator,
//           values: [
//             "S/N".tr(),
//             "Item".tr(),
//             "QTY".tr(),
//             "Price".tr(),
//           ],
//           sizes: [1, 6, 2, 3],
//           align: PosAlign.left,
//         );
//         bytes += hr;
//         //
//         final orderProducts = order.orderProducts ?? [];
//         for (var i = 0; i < orderProducts.length; i++) {
//           final orderProduct = orderProducts[i];
//           bytes += xRows(
//             generator,
//             values: [
//               "${i + 1}",
//               orderProduct.product!.name,
//               "${orderProduct.quantity}",
//               "${orderProduct.price}".currencyValueFormat(),
//             ],
//             sizes: [1, 6, 2, 3],
//             align: PosAlign.left,
//           );
//         }
//         bytes += hr;
//       }

//       //note:
//       bytes += simpleText(
//         generator,
//         "Note".tr() + ":",
//         align: PosAlign.left,
//         bold: true,
//       );
//       bytes += simpleText(
//         generator,
//         "${order.note}",
//         align: PosAlign.left,
//       );

//       // summary
//       bytes += twoRow(
//         generator,
//         "Subtotal".tr(),
//         "${order.subTotal}".currencyValueFormat(),
//       );
//       bytes += twoRow(
//         generator,
//         "Discount".tr(),
//         "${order.discount}".currencyValueFormat(),
//       );
//       bytes += twoRow(
//         generator,
//         "Delivery Fee".tr(),
//         "${order.deliveryFee}".currencyValueFormat(),
//       );
//       bytes += twoRow(
//         generator,
//         "Tax".tr() + "(${order.taxRate}%)",
//         "${order.tax}".currencyValueFormat(),
//       );
//       bytes += twoRow(
//         generator,
//         "Driver Tip".tr(),
//         "${order.tip}".currencyValueFormat(),
//       );
//       bytes += hr;
//       bytes += twoRow(
//         generator,
//         "Total".tr(),
//         "${order.total}".formatCurSym(),
//         valueStyle: titleStyle.copyWith(
//           align: PosAlign.right,
//         ),
//         titleStyle: titleStyle.copyWith(
//           align: PosAlign.left,
//         ),
//       );
//       bytes += hr;
//       bytes += simpleText(
//         generator,
//         "Thank You".tr(),
//       );
//       bytes += hr;
//     }

//     return bytes;
//   }

//   // two row column
//   static List<int> twoRow(
//     EscGenerator generator,
//     String label,
//     String value, {
//     PosStyles? titleStyle,
//     PosStyles? valueStyle,
//   }) {
//     titleStyle ??= PosStyles(
//       // bold: true,
//       // width: PosTextSize.size3,
//       align: PosAlign.left,
//     );
//     //
//     valueStyle ??= PosStyles(
//       // bold: false,
//       // width: PosTextSize.size8,
//       align: PosAlign.right,
//     );
//     //
//     return generator.row(
//       [
//         PosColumn(
//           width: 4,
//           textEncoded: encodeArabicText(label),
//           styles: titleStyle,
//         ),
//         PosColumn(
//           width: 8,
//           textEncoded: encodeArabicText(value),
//           styles: valueStyle,
//         ),
//       ],
//     );
//   }

//   static List<int> simpleText(
//     EscGenerator generator,
//     String value, {
//     PosAlign align = PosAlign.center,
//     PosStyles? style,
//     bool bold = false,
//   }) {
//     //
//     style ??= PosStyles(align: align, bold: bold);
//     //if arabic encode
//     // if (Utils.isArabic) {
//     //   return generator.row(
//     //     [
//     //       PosColumn(
//     //         width: 12,
//     //         textEncoded: encodeArabicText(value),
//     //         styles: style,
//     //       ),
//     //     ],
//     //   );
//     // }
//     //
//     return generator.row(
//       [
//         PosColumn(
//           width: 12,
//           // text: value,
//           textEncoded: encodeArabicText(value),
//           styles: style,
//         ),
//       ],
//     );
//   }

//   static List<int> xRows(
//     generator, {
//     required List<String> values,
//     List<int>? sizes,
//     PosAlign align = PosAlign.center,
//     PosStyles? style,
//   }) {
//     if (sizes != null) {
//       assert(sizes.length == values.length, "");
//     }
//     style ??= PosStyles(align: align);
//     return generator.row(
//       List.generate(
//         values.length,
//         (index) {
//           final value = values[index];
//           return PosColumn(
//             width:
//                 sizes != null ? sizes[index] : ((12 / values.length).floor()),
//             // text: value,
//             textEncoded: encodeArabicText(value),
//             styles: style!,
//           );
//         },
//       ),
//     );
//   }

//   //MISC FUNCTION
//   static Uint8List encodeArabicText(String text) {
//     return Uint8List.fromList(utf8.encode(text));
//   }
// }
