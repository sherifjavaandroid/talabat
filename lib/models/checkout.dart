import 'dart:io';

import 'package:fuodz/models/cart.dart';
import 'package:fuodz/models/coupon.dart';
import 'package:fuodz/models/delivery_address.dart';
import 'package:fuodz/models/fee.dart';
import 'package:fuodz/models/payment_method.dart';

class CheckOut {
  double subTotal;
  double discount;
  double deliveryFee;
  double? deliveryDiscount;
  double tax;
  double total;
  double totalWithTip;
  String? pickupTime;
  String? pickupDate;
  bool? isPickup;
  bool? isScheduled;
  DeliveryAddress? deliveryAddress;
  String deliverySlotDate;
  String deliverySlotTime;
  PaymentMethod? paymentMethod;
  List<Cart>? cartItems;
  File? photo;
  Coupon? coupon;
  String? token;
  String? deliveryFeeToken;
  List<Fee> fees = [];
  double totalFee;

  //
  CheckOut({
    this.subTotal = 0.00,
    this.discount = 0.00,
    this.deliveryFee = 0.00,
    this.deliveryDiscount,
    this.tax = 0.00,
    this.total = 0.00,
    this.totalWithTip = 0.00,
    this.isPickup,
    this.deliveryAddress,
    this.isScheduled,
    this.pickupDate,
    this.pickupTime,
    this.paymentMethod,
    this.cartItems,
    this.deliverySlotDate = "",
    this.deliverySlotTime = "",
    this.photo,
    this.coupon,
    this.token,
    this.deliveryFeeToken,
    this.fees = const [],
    this.totalFee = 0,
  });

  //
  double get flexTotal {
    return ((subTotal + deliveryFee) - discount) + tax;
  }

  //from json
  factory CheckOut.fromJson(Map<String, dynamic> json) {
    return CheckOut(
      subTotal: json["sub_total"] == null
          ? 0.00
          : double.parse(json["sub_total"].toString()),
      discount: json["discount"] == null
          ? 0.00
          : double.parse(json["discount"].toString()),
      deliveryDiscount: json["delivery_discount"] == null
          ? 0.00
          : double.parse(json["delivery_discount"].toString()),
      deliveryFee: json["delivery_fee"] == null
          ? 0.00
          : double.parse(json["delivery_fee"].toString()),
      tax: json["tax"] == null ? 0.00 : double.parse(json["tax"].toString()),
      total:
          json["total"] == null ? 0.00 : double.parse(json["total"].toString()),
      totalWithTip: json["total_with_tip"] == null
          ? 0.00
          : double.parse(json["total_with_tip"].toString()),
      isPickup: json["is_pickup"] == null ? false : json["is_pickup"],
      isScheduled: json["is_scheduled"] == null ? false : json["is_scheduled"],
      pickupDate: json["pickup_date"] == null ? "" : json["pickup_date"],
      pickupTime: json["pickup_time"] == null ? "" : json["pickup_time"],
      deliveryAddress: json["delivery_address"] == null
          ? null
          : DeliveryAddress.fromJson(json["delivery_address"]),
      paymentMethod: json["payment_method"] == null
          ? null
          : PaymentMethod.fromJson(json["payment_method"]),
      cartItems: json["products"] == null
          ? null
          : List<Cart>.from(json["products"].map((x) => Cart.fromJson(x))),
      deliverySlotDate:
          json["delivery_slot_date"] == null ? "" : json["delivery_slot_date"],
      deliverySlotTime:
          json["delivery_slot_time"] == null ? "" : json["delivery_slot_time"],
      photo: json["photo"] == null ? null : File(json["photo"]),
      coupon: json["coupon"] == null ? null : Coupon.fromJson(json["coupon"]),
      token: json["token"] == null ? null : json["token"],
      deliveryFeeToken: json["delivery_fee_token"] == null
          ? null
          : json["delivery_fee_token"],
      //
      fees: json["fees"] == null
          ? []
          : List<Fee>.from(json["fees"].map((x) => Fee.fromJson(x))),
      totalFee: json["total_fee"] == null
          ? 0.00
          : double.parse(json["total_fee"].toString()),
    );
  }

  //
  Map<String, dynamic> toJson() {
    return {
      "sub_total": subTotal,
      "discount": discount,
      "delivery_fee": deliveryFee,
      "tax": tax,
      "total": total,
      "total_with_tip": totalWithTip,
      "is_pickup": isPickup,
      "is_scheduled": isScheduled,
      "pickup_date": pickupDate,
      "pickup_time": pickupTime,
      "delivery_address": deliveryAddress?.toJson(),
      "payment_method": paymentMethod?.toJson(),
      "products": cartItems?.map((x) => x.toJson()).toList(),
      "delivery_slot_date": deliverySlotDate,
      "delivery_slot_time": deliverySlotTime,
      "photo": photo?.path,
      "coupon": coupon?.toJson(),
      "token": token,
      "delivery_fee_token": deliveryFeeToken,
      "fees": fees.map((x) => x.toJson()).toList(),
      "total_fee": totalFee,
    };
  }

  //add copyWith do not return new instance
  void copyWith({
    double? subTotal,
    double? discount,
    double? deliveryFee,
    double? tax,
    double? total,
    double? totalWithTip,
    String? token,
    List<Fee>? fees,
  }) {
    this.subTotal = subTotal ?? this.subTotal;
    this.discount = discount ?? this.discount;
    this.deliveryFee = deliveryFee ?? this.deliveryFee;
    this.tax = tax ?? this.tax;
    this.total = total ?? this.total;
    this.totalWithTip = totalWithTip ?? this.totalWithTip;
    this.token = token ?? this.token;
    this.fees = fees ?? this.fees;
  }
}
