import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:fuodz/view_models/product_details.vm.dart';
import 'package:fuodz/view_models/service_details.vm.dart';
import 'package:fuodz/view_models/vendor_details.vm.dart';
import 'package:fuodz/widgets/buttons/custom_outline_button.dart';
import 'package:velocity_x/velocity_x.dart';

class ShareButton extends StatelessWidget {
  const ShareButton({
    this.model,
    this.child,
    Key? key,
  }) : super(key: key);

  final dynamic model;
  final Widget? child;

  @override
  Widget build(BuildContext context) {
    if (child != null) {
      return child!.onTap(() {
        onBtnTap();
      });
    }

    return CustomOutlineButton(
      loading: model.busy((model is ProductDetailsViewModel)
          ? model.shareProduct
          : (model is VendorDetailsViewModel)
              ? model.shareVendor
              : model.shareService),
      color: Colors.transparent,
      child: Icon(
        FlutterIcons.share_2_fea,
        color: AppColor.primaryColorDark,
      ),
      onPressed: () {
        onBtnTap();
      },
    ).p2().box.color(Utils.textColorByTheme()).roundedFull.make();
  }

  onBtnTap() {
    if (model is ProductDetailsViewModel) {
      model.shareProduct(model.product);
    } else if (model is VendorDetailsViewModel) {
      model.shareVendor(model.vendor);
    } else if (model is ServiceDetailsViewModel) {
      model.shareService(model.service);
    }
  }
}
