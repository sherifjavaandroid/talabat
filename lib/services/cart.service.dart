import 'dart:async';
import 'dart:convert';
import 'package:dartx/dartx.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/models/cart.dart';
import 'package:fuodz/models/coupon.dart';
import 'package:fuodz/models/product.dart';
import 'package:fuodz/services/local_storage.service.dart';
import 'package:rx_shared_preferences/rx_shared_preferences.dart';

class CartServices {
  //
  static String cartItemsKey = "cart_items";
  static String totalItemKey = "total_cart_items";
  static StreamController<int> cartItemsCountStream =
      StreamController.broadcast();
  //
  static List<Cart> productsInCart = [];
  //
  static Future<void> getCartItems() async {
    //
    final cartList = await LocalStorageService.prefs!.getString(
      cartItemsKey,
    );

    //
    if (cartList != null) {
      try {
        productsInCart = (jsonDecode(cartList) as List).map((cartObject) {
          return Cart.fromJson(cartObject);
        }).toList();
      } catch (error) {
        productsInCart = [];
      }
    } else {
      productsInCart = [];
    }

    //
    cartItemsCountStream.add(productsInCart.length);
  }

  //
  static bool canAddToCart(Cart cart) {
    if (productsInCart.length > 0) {
      //
      final firstOfferInCart = productsInCart[0];
      if (firstOfferInCart.product?.vendorId == cart.product?.vendorId ||
          AppStrings.enableMultipleVendorOrder) {
        return true;
      } else {
        return false;
      }
    } else {
      return true;
    }
  }

  static bool canAddDigitalProductToCart(Cart cart) {
    if (productsInCart.length > 0) {
      //
      final allDigital = allCartProductsDigital();
      if (allDigital) {
        return true;
      } else {
        return false;
      }
    } else {
      return true;
    }
  }

  static bool allCartProductsDigital() {
    if (productsInCart.length > 0) {
      //
      bool result = true;
      for (var productInCart in productsInCart) {
        if (!productInCart.product!.isDigital) {
          result = false;
          break;
        }
      }
      return result;
    } else {
      return true;
    }
  }

  static clearCart() async {
    await LocalStorageService.prefs?.setString(
      cartItemsKey,
      "",
    );
    await updateTotalCartItemCount(0);
    productsInCart = [];
  }

  static addToCart(Cart cart) async {
    //
    try {
      final mProductsInCart = productsInCart;
      mProductsInCart.add(cart);
      await LocalStorageService.prefs!.setString(
        cartItemsKey,
        jsonEncode(
          mProductsInCart,
        ),
      );
      //
      productsInCart = mProductsInCart;
      //update total item in cart count
      await updateTotalCartItemCount(productsInCart.length);
      await getCartItems();
    } catch (error) {
      print("Saving Cart Error => $error");
    }
  }

  static saveCartItems(List<Cart> productsInCart) async {
    await LocalStorageService.prefs?.setString(
      cartItemsKey,
      jsonEncode(
        productsInCart,
      ),
    );

    //update total item in cart count
    await updateTotalCartItemCount(productsInCart.length);

    await getCartItems();
  }

  static updateTotalCartItemCount(int total) async {
    //update total item in cart count
    await LocalStorageService.rxPrefs!.setInt(totalItemKey, total);
  }

  static bool isMultipleOrder() {
    final vendorIds = CartServices.productsInCart
        .map((e) => e.product?.vendorId)
        .toList()
        .toSet()
        .toList();
    return vendorIds.length > 1;
  }

  static double vendorSubTotal(int id) {
    double subTotalPrice = 0.0;
    CartServices.productsInCart.where((e) => e.product?.vendorId == id).forEach(
      (cartItem) {
        double totalProductPrice =
            (cartItem.price ?? cartItem.product!.sellPrice);
        totalProductPrice = totalProductPrice * cartItem.selectedQty!;
        print("Vendor ==> ${cartItem.product?.vendor.name}");
        print("Total Product Price => $totalProductPrice");
        subTotalPrice += totalProductPrice;
      },
    );
    return subTotalPrice;
  }

  static double vendorOrderDiscount(int id, Coupon coupon) {
    double discountCartPrice = 0.0;
    final cartItems = CartServices.productsInCart
        .where((e) => e.product?.vendorId == id)
        .toList();

    cartItems.forEach(
      (cartItem) {
        //
        final totalProductPrice =
            (cartItem.price ?? cartItem.product!.price) * cartItem.selectedQty!;
        //discount/coupon
        final foundProduct = coupon.products.firstOrNullWhere(
          (product) => cartItem.product?.id == product.id,
        );
        final foundVendor = coupon.vendors.firstOrNullWhere(
          (vendor) => cartItem.product?.vendorId == vendor.id,
        );
        if (foundProduct != null ||
            foundVendor != null ||
            (coupon.products.isEmpty && coupon.vendors.isEmpty)) {
          if (coupon.percentage == 1) {
            discountCartPrice += (coupon.discount / 100) * totalProductPrice;
          } else {
            discountCartPrice += coupon.discount;
          }
        }
      },
    );
    return discountCartPrice;
  }

  //
  static List<Map> multipleVendorOrderPayload(int id) {
    return CartServices.productsInCart
        .where((e) => e.product?.vendorId == id)
        .map((e) => e.toJson())
        .toList();
  }

  //new utils
  static Future<int> productQtyInCart(Product product) async {
    int addedQty = 0;
    //
    await getCartItems();
    (productsInCart.where((e) => e.product?.id == product.id).toList()).forEach(
      (productInCart) {
        //update product qty
        int qty = productInCart.selectedQty!;
        addedQty += qty;
      },
    );
    return addedQty;
  }

  static Future<int> productQtyAllowed(Product product) async {
    int addedQty = await productQtyInCart(product);
    if (product.availableQty == null) {
      return 20;
    }
    return (product.availableQty ?? 20) - addedQty;
  }

  static Future<bool> cartItemQtyAvailable(Product product) async {
    int addedQty = await productQtyInCart(product);
    return product.availableQty == null || (addedQty < product.availableQty!);
  }

  static double get totalSubtotal {
    double total = 0.0;
    productsInCart.forEach((cartItem) {
      double totalProductPrice =
          (cartItem.price ?? cartItem.product!.sellPrice);
      totalProductPrice = totalProductPrice * cartItem.selectedQty!;
      total += totalProductPrice;
    });
    return total;
  }

  //
  static refreshState() async {
    await getCartItems();
    int count = productsInCart.length;
    updateTotalCartItemCount(count);
  }
}
