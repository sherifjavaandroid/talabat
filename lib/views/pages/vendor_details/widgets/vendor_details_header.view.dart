import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:flutter_rating_bar/flutter_rating_bar.dart';
import 'package:fuodz/constants/app_ui_settings.dart';
import 'package:fuodz/models/vendor.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/vendor_details.vm.dart';
import 'package:fuodz/views/pages/vendor/vendor_reviews.page.dart';
import 'package:fuodz/views/pages/vendor_details/widgets/bottomsheets/vendor_full_profie.bottomsheet.dart';
import 'package:fuodz/widgets/cards/custom.visibility.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:fuodz/widgets/inputs/search_bar.input.dart';
import 'package:fuodz/widgets/tags/close.tag.dart';
import 'package:fuodz/widgets/tags/delivery.tag.dart';
import 'package:fuodz/widgets/tags/open.tag.dart';
import 'package:fuodz/widgets/tags/pickup.tag.dart';
import 'package:fuodz/widgets/tags/time.tag.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class VendorDetailsHeader extends StatelessWidget {
  const VendorDetailsHeader(
    this.model, {
    this.showFeatureImage = true,
    this.featureImageHeight = 220,
    Key? key,
  }) : super(key: key);

  final VendorDetailsViewModel model;
  final bool showFeatureImage;
  final double featureImageHeight;
  @override
  Widget build(BuildContext context) {
    return VStack(
      [
        VStack(
          [
            //vendor image
            CustomVisibilty(
              visible: showFeatureImage,
              child: CustomImage(
                imageUrl: model.vendor!.featureImage,
                height: featureImageHeight,
                canZoom: true,
              ).wFull(context),
            ),

            //vendor header
            VStack(
              [
                //vendor important details
                HStack(
                  [
                    //logo
                    CustomImage(
                      imageUrl: model.vendor!.logo,
                      width: Vx.dp56,
                      height: Vx.dp56,
                      canZoom: true,
                    ).box.clip(Clip.antiAlias).withRounded(value: 5).make(),
                    //
                    VStack(
                      [
                        model.vendor!.name.text.semiBold.lg.make(),
                        CustomVisibilty(
                          visible: model.vendor!.address.isNotEmptyAndNotNull &&
                              AppUISettings.showVendorAddress,
                          child: "${model.vendor!.address}"
                              .text
                              .light
                              .sm
                              .maxLines(1)
                              .make(),
                        ),
                        Visibility(
                          visible: AppUISettings.showVendorPhone,
                          child: model.vendor!.phone.text.light.sm.make(),
                        ),

                        //rating
                        HStack(
                          [
                            RatingBar(
                              itemSize: 12,
                              initialRating: model.vendor!.rating.toDouble(),
                              ignoreGestures: true,
                              ratingWidget: RatingWidget(
                                full: Icon(
                                  FlutterIcons.ios_star_ion,
                                  size: 12,
                                  color: Colors.yellow[800],
                                ),
                                half: Icon(
                                  FlutterIcons.ios_star_half_ion,
                                  size: 12,
                                  color: Colors.yellow[800],
                                ),
                                empty: Icon(
                                  FlutterIcons.ios_star_ion,
                                  size: 12,
                                  color: Colors.grey.shade400,
                                ),
                              ),
                              onRatingUpdate: (value) {},
                            ).pOnly(right: 2),
                            "(${model.vendor!.reviews_count} ${'Reviews'.tr()})"
                                .text
                                .sm
                                .thin
                                .make(),
                          ],
                        ).py2().onTap(
                          () {
                            context.nextPage(
                              VendorReviewsPage(model.vendor!),
                            );
                          },
                        ),
                      ],
                    ).pOnly(left: Vx.dp12).expand(),
                    //icons
                    VStack(
                      [
                        // details icon
                        Icon(
                          FlutterIcons.info_circle_faw,
                          size: 30,
                          color: context.theme.primaryColor,
                        ).p(12).onTap(
                          () {
                            //open vendor details bottom sheet
                            openVendorDetailsBottomSheet(
                              context,
                              model.vendor!,
                            );
                          },
                        ),

                        // //
                        // CustomVisibilty(
                        //   visible:
                        //       (model.vendor!.longitude.isNotEmptyAndNotNull) &&
                        //           AppUISettings.showVendorAddress,
                        //   //location routing
                        //   child: RouteButton(model.vendor!, size: 10),
                        // ),
                        // UiSpacer.verticalSpace(space: 5),
                        // //call button
                        // if (model.vendor!.phone.isNotEmptyAndNotNull)
                        //   Visibility(
                        //     visible: AppUISettings.showVendorPhone,
                        //     child: CallButton(model.vendor, size: 10),
                        //   )
                        // else
                        //   UiSpacer.emptySpace(),
                      ],
                    ).pOnly(left: Vx.dp12),
                  ],
                ),
              ],
            ).p8().card.make().p12(),
          ],
        ),

        //
        //
        VStack(
          [
            //tags
            Wrap(
              children: [
                //is open
                model.vendor!.isOpen ? OpenTag() : CloseTag(),

                //can deliveree
                if (model.vendor!.delivery == 1) DeliveryTag(),

                //can pickup
                if (model.vendor!.pickup == 1) PickupTag(),

                //prepare time
                TimeTag(
                  "${model.vendor!.prepareTime} ${model.vendor!.prepareTimeUnit}",
                  iconData: FlutterIcons.clock_outline_mco,
                ),
                //delivery time
                TimeTag(
                  "${model.vendor!.deliveryTime} ${model.vendor!.deliveryTimeUnit}",
                  iconData: FlutterIcons.ios_bicycle_ion,
                ),
              ],
              spacing: 12,
              runSpacing: 12,
              crossAxisAlignment: WrapCrossAlignment.center,
            ),
            UiSpacer.verticalSpace(space: 10),

            // //description
            // "Description".tr().text.sm.bold.uppercase.make(),
            // HtmlTextView(
            //   model.vendor!.description,
            //   padding: EdgeInsets.zero,
            // ),
            // UiSpacer.verticalSpace(space: 10),
          ],
        ).px20().py(0),
        UiSpacer.divider(),
        10.heightBox,
        //search bar
        SearchBarInput(
          onTap: model.openVendorSearch,
          showFilter: false,
        ).px20(),
        10.heightBox,
        UiSpacer.divider(),
      ],
    );
  }

  //
  openVendorDetailsBottomSheet(BuildContext context, Vendor vendor) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      builder: (context) {
        return VendorFullProfileBottomSheet(vendor);
      },
    );
  }
}
