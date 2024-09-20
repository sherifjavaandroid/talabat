import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/models/service.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:fuodz/view_models/service_details.vm.dart';
import 'package:fuodz/views/pages/service/widgets/service_details.bottomsheet.dart';
import 'package:fuodz/views/pages/service/widgets/service_details_price.section.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/buttons/share.btn.dart';
import 'package:fuodz/widgets/cards/service_provider.section.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:fuodz/widgets/custom_masonry_grid_view.dart';
import 'package:fuodz/widgets/html_text_view.dart';
import 'package:fuodz/widgets/list_items/service_option.list_item.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:fuodz/extensions/context.dart';

class ServiceDetailsPage extends StatelessWidget {
  const ServiceDetailsPage(
    this.service, {
    Key? key,
  }) : super(key: key);

  //
  final Service service;

  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<ServiceDetailsViewModel>.reactive(
      viewModelBuilder: () => ServiceDetailsViewModel(context, service),
      onViewModelReady: (model) => model.getServiceDetails(),
      builder: (context, vm, child) {
        return BasePage(
          extendBodyBehindAppBar: true,
          isLoading: vm.busy(vm.service),
          showAppBar: true,
          showLeadingAction: true,
          elevation: 0,
          appBarColor: Colors.transparent,
          appBarItemColor: AppColor.primaryColor,
          // leading: CustomLeading(),
          leading: FittedBox(
            child: SizedBox(
              width: 50,
              height: 40,
              child: Icon(
                !Utils.isArabic
                    ? FlutterIcons.arrow_left_fea
                    : FlutterIcons.arrow_right_fea,
                color: AppColor.primaryColor,
                size: 20,
              )
                  .centered()
                  .p4()
                  .box
                  .roundedSM
                  .color(context.theme.colorScheme.surface)
                  .make()
                  .onTap(
                    () => context.pop(),
                  )
                  .px8(),
            ),
          ),

          actions: [
            SizedBox(
              width: 60,
              height: 60,
              child: FittedBox(
                child: ShareButton(
                  model: vm,
                ),
              ),
            ),
          ],
          body: VStack(
            [
              CustomImage(
                imageUrl:
                    (vm.service.photos != null && vm.service.photos!.isNotEmpty)
                        ? vm.service.photos!.first
                        : '',
                width: double.infinity,
                height: context.percentHeight * 50,
                canZoom: true,
              ),

              //details
              VStack(
                [
                  //name
                  vm.service.name.text.medium.xl.make(),
                  //price
                  ServiceDetailsPriceSectionView(vm.service),

                  //rest details
                  UiSpacer.verticalSpace(),
                  VStack(
                    [
                      //photos
                      CustomMasonryGridView(
                        crossAxisSpacing: 10,
                        mainAxisSpacing: 10,
                        crossAxisCount: 3,
                        items: (vm.service.photos ?? [])
                            .map(
                              (photo) => CustomImage(
                                imageUrl: photo,
                                width: double.infinity,
                                height: 80,
                                canZoom: true,
                              ).box.roundedSM.clip(Clip.antiAlias).make(),
                            )
                            .toList(),
                      ),

                      //description
                      HtmlTextView(vm.service.description),
                    ],
                  )
                      .box
                      .p8
                      .color(context.theme.colorScheme.surface)
                      .roundedSM
                      .make(),
                  //options if any
                  if (vm.service.optionGroups != null &&
                      vm.service.optionGroups!.isNotEmpty)
                    VStack(
                      [
                        UiSpacer.divider().py12(),
                        //title
                        "Additional Options".tr().text.xl.bold.make().py12(),
                        //
                        ListView.builder(
                          padding: EdgeInsets.zero,
                          shrinkWrap: true,
                          physics: NeverScrollableScrollPhysics(),
                          itemCount: vm.service.optionGroups!.length,
                          itemBuilder: (context, index) {
                            //
                            final optionGroup = vm.service.optionGroups![index];
                            //sublist
                            return ListView.builder(
                              padding: EdgeInsets.zero,
                              shrinkWrap: true,
                              physics: NeverScrollableScrollPhysics(),
                              itemCount: optionGroup.options.length,
                              itemBuilder: (context, index) {
                                //
                                final option = optionGroup.options[index];
                                //
                                return ServiceOptionListItem(
                                  option: option,
                                  optionGroup: optionGroup,
                                  model: vm,
                                );
                              },
                            );
                          },
                        ),
                      ],
                    ),

                  20.heightBox,
                  //vendor profile
                  ServiceProviderSection(service: vm.service),

                  //spaces
                  40.heightBox,
                ],
              )
                  .wFull(context)
                  .p20()
                  .box
                  .color(context.theme.colorScheme.surface)
                  .topRounded(value: 30)
                  .make(),
            ],
          ).scrollVertical(),
          //
          bottomNavigationBar: ServiceDetailsBottomSheet(vm),
        );
      },
    );
  }
}
