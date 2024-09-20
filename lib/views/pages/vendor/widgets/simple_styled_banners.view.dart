import 'package:flutter/material.dart';
import 'package:fuodz/models/vendor_type.dart';
import 'package:fuodz/view_models/vendor/banners.vm.dart';
import 'package:fuodz/widgets/busy_indicator.dart';
import 'package:fuodz/widgets/list_items/banner.list_item.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:carousel_slider/carousel_slider.dart'as x;

class SimpleStyledBanners extends StatelessWidget {
  const SimpleStyledBanners(
    this.vendorType, {
    this.height,
    this.radius,
    this.withPadding = true,
    this.hideEmpty = false,
    this.viewportFraction = 1,
    Key? key,
  }) : super(key: key);

  final VendorType vendorType;
  final double? height;
  final double? radius;
  final bool withPadding;
  final double viewportFraction;
  final bool hideEmpty;

  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<BannersViewModel>.reactive(
      viewModelBuilder: () => BannersViewModel(
        context,
        vendorType,
        featured: false,
      ),
      onViewModelReady: (model) => model.initialise(),
      builder: (context, model, child) {
        //
        if (hideEmpty && model.banners.isEmpty && !model.isBusy) {
          return SizedBox.shrink();
        }

        //
        return model.isBusy
            ? BusyIndicator().centered().h(150)
            : Padding(
                padding: withPadding
                    ? EdgeInsets.symmetric(
                        horizontal: 20,
                        vertical: 12,
                      )
                    : EdgeInsets.zero,
                child: x.CarouselSlider(
                  options: x.CarouselOptions(
                    autoPlay: true,
                    initialPage: 1,
                    height: (!model.isBusy && model.banners.length > 0)
                        ? (height ?? 250.0)
                        : 0.00,
                    disableCenter: true,
                    enlargeCenterPage: true,
                    viewportFraction: viewportFraction,
                  ),
                  items: model.banners.map(
                    (banner) {
                      return BannerListItem(
                        imageUrl: banner.imageUrl ?? "",
                        radius: radius ?? 7.5,
                        noMargin: true,
                        onPressed: () => model.bannerSelected(banner),
                      );
                    },
                  ).toList(),
                )
                    .box
                    .clip(Clip.antiAlias)
                    .withRounded(value: radius ?? 7.5)
                    .make(),
              );
      },
    );
  }
}
