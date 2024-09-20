import 'package:flutter/material.dart';
import 'package:flutter_staggered_animations/flutter_staggered_animations.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/constants/app_ui_styles.dart';
import 'package:fuodz/models/vendor_type.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:velocity_x/velocity_x.dart';

class VendorTypeListItem extends StatelessWidget {
  const VendorTypeListItem(
    this.vendorType, {
    required this.onPressed,
    Key? key,
  }) : super(key: key);

  final VendorType vendorType;
  final Function onPressed;
  @override
  Widget build(BuildContext context) {
    //
    final textColor = Utils.textColorByColor(Vx.hexToColor(vendorType.color));
    //
    return AnimationConfiguration.staggeredList(
      position: vendorType.id,
      duration: const Duration(milliseconds: 375),
      child: SlideAnimation(
        verticalOffset: 50.0,
        child: FadeInAnimation(
          child: InkWell(
            onTap: () => this.onPressed(),
            child: VStack(
              [
                //image + details
                Visibility(
                  visible: !AppStrings.showVendorTypeImageOnly,
                  child: HStack(
                    [
                      //
                      CustomImage(
                        imageUrl: vendorType.logo,
                        boxFit: AppUIStyles.vendorTypeImageStyle,
                        height: AppUIStyles.vendorTypeHeight,
                        width: AppUIStyles.vendorTypeWidth,
                      ).p12(),
                      //

                      VStack(
                        [
                          vendorType.name.text.xl
                              .color(textColor)
                              .semiBold
                              .make(),
                          Visibility(
                            visible: vendorType.description.isNotEmpty,
                            child: "${vendorType.description}"
                                .text
                                .color(textColor)
                                .sm
                                .make()
                                .pOnly(top: 5),
                          ),
                        ],
                      ).expand(),
                    ],
                  ).p12(),
                ),

                //image only
                Visibility(
                  visible: AppStrings.showVendorTypeImageOnly,
                  child: CustomImage(
                    imageUrl: vendorType.logo,
                    boxFit: AppUIStyles.vendorTypeImageStyle,
                    height: AppUIStyles.vendorTypeHeight,
                    width: AppUIStyles.vendorTypeWidth,
                  ),
                ),
              ],
            ),
          )
              .box
              .clip(Clip.antiAlias)
              .withRounded(value: 10)
              .outerShadow
              .color(Vx.hexToColor(vendorType.color))
              .make()
              .pOnly(bottom: Vx.dp20),
        ),
      ),
    );
  }
}
