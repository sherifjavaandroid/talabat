import 'package:fuodz/models/coupon.dart';

class CheckoutService {
  static double generateOrderDiscount(
    Coupon? coupon,
    double total,
    double deliveryFee,
  ) {
    if (coupon == null) {
      return 0.00;
    }
    //if coupon is for_delivery
    if (coupon.for_delivery) {
      if (coupon.min_order_amount != null && total < coupon.min_order_amount!) {
        return 0.00;
      }
      //
      if (coupon.percentage == 1) {
        print("deliveryFee: $deliveryFee");
        return (coupon.discount / 100) * deliveryFee;
      }
      double newDeliveryFee = deliveryFee - coupon.discount;
      if (newDeliveryFee < 0) {
        newDeliveryFee = 0.00;
      }
      return newDeliveryFee;
    }

    //for order total
    if (coupon.min_order_amount != null && total < coupon.min_order_amount!) {
      return 0.00;
    }
    //
    if (coupon.percentage == 1) {
      return (coupon.discount / 100) * total;
    }

    double newTotal = total - coupon.discount;
    if (newTotal < 0) {
      newTotal = 0.00;
    }
    return newTotal;
  }
}
