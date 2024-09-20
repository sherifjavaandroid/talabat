import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/models/vendor_type.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:fuodz/view_models/vendor/banners.vm.dart';
import 'package:fuodz/widgets/cards/custom.visibility.dart';
import 'package:fuodz/widgets/list_items/banner.list_item.dart';
import 'package:fuodz/widgets/states/loading.shimmer.dart';
import 'package:smooth_page_indicator/smooth_page_indicator.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:carousel_slider/carousel_slider.dart' as x;

class Banners extends StatelessWidget {
  const Banners(
    this.vendorType, {
    this.viewportFraction = 0.8,
    this.showIndicators = false,
    this.featured = false,
    this.disableCenter = false,
    this.padding = 5,
    this.itemRadius = 10,
    Key? key,
  }) : super(key: key);

  final VendorType? vendorType;
  final double viewportFraction;
  final bool showIndicators;
  final bool featured;
  final bool disableCenter;
  final double padding;
  final double? itemRadius;

  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<BannersViewModel>.reactive(
      viewModelBuilder: () => BannersViewModel(
        context,
        vendorType,
        featured: featured,
      ),
      onViewModelReady: (model) => model.initialise(),
      builder: (context, model, child) {
        return model.isBusy
            ? LoadingShimmer().px20().h(150)
            : Visibility(
                visible: model.banners.isNotEmpty,
                child: Padding(
                  padding: EdgeInsets.all(padding),
                  child: VStack(
                    [
                      x.CarouselSlider(
                        options: x.CarouselOptions(
                          autoPlayCurve: Curves.easeInOutBack,
                          clipBehavior: Clip.antiAlias,
                          viewportFraction: viewportFraction,
                          autoPlay: true,
                          initialPage: 1,
                          height: (!model.isBusy && model.banners.length > 0)
                              ? (AppStrings.bannerHeight)
                              : 0.00,
                          disableCenter: disableCenter,
                          onPageChanged: (index, reason) {
                            model.currentIndex = index;
                            model.notifyListeners();
                          },
                        ),
                        items: model.banners.map(
                          (banner) {
                            return BannerListItem(
                              radius: itemRadius ?? 0.0,
                              imageUrl: banner.imageUrl ?? "",
                              onPressed: () => model.bannerSelected(banner),
                            );
                          },
                        ).toList(),
                      ),
                      //indicators
                      CustomVisibilty(
                        visible: model.banners.length <= 10 || showIndicators,
                        child: AnimatedSmoothIndicator(
                          activeIndex: model.currentIndex,
                          count: model.banners.length,
                          textDirection: Utils.isArabic
                              ? TextDirection.rtl
                              : TextDirection.ltr,
                          effect: ExpandingDotsEffect(
                            dotHeight: 6,
                            dotWidth: 10,
                            activeDotColor: context.primaryColor,
                          ),
                        ).centered().py8(),
                      ),
                    ],
                  ),
                ),
              );
      },
    );
  }
}
