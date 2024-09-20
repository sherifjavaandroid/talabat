import 'package:dartx/dartx.dart';
import 'package:dotted_border/dotted_border.dart';
import 'package:dotted_line/dotted_line.dart';
import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/extensions/string.dart';
import 'package:fuodz/models/service.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/service_booking_summary.vm.dart';
import 'package:fuodz/views/pages/checkout/widgets/payment_methods.view.dart';
import 'package:fuodz/views/pages/checkout/widgets/schedule_order.view.dart';
import 'package:fuodz/views/pages/service/widgets/service_delivery_address.view.dart';
import 'package:fuodz/views/pages/service/widgets/service_details_price.section.dart';
import 'package:fuodz/views/pages/service/widgets/service_discount_section.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/cards/order_summary.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:fuodz/widgets/custom_text_form_field.dart';
import 'package:fuodz/widgets/states/loading.shimmer.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class ServiceBookingSummaryPage extends StatelessWidget {
  const ServiceBookingSummaryPage(
    this.service, {
    Key? key,
  }) : super(key: key);

  //
  final Service service;

  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<ServiceBookingSummaryViewModel>.reactive(
      viewModelBuilder: () => ServiceBookingSummaryViewModel(context, service),
      onViewModelReady: (vm) => vm.initialise(),
      builder: (context, vm, child) {
        return BasePage(
          showAppBar: true,
          title: "Booking Summary".tr(),
          showLeadingAction: true,
          body: VStack(
            [
              VStack(
                [
                  //service details in summary page
                  HStack(
                    [
                      //service logo
                      CustomImage(
                        imageUrl: (vm.service!.photos != null &&
                                vm.service!.photos!.isNotEmpty)
                            ? (vm.service!.photos?.first ?? "")
                            : '',
                        width: context.percentWidth * 18,
                        height: 80,
                      ),
                      //service details
                      VStack(
                        [
                          vm.service!.name.text.xl.maxLines(2).ellipsis.make(),
                          5.heightBox,
                          //price
                          ServiceDetailsPriceSectionView(
                            service,
                            onlyPrice: true,
                            showDiscount: true,
                          ),
                          //selected hours
                          HStack(
                            [
                              "${vm.service!.duration.capitalize().tr()}:"
                                  .text
                                  // .sm
                                  .make(),
                              //
                              "${vm.service!.selectedQty}"
                                  .text
                                  // .sm
                                  .bold
                                  .make(),
                            ],
                            spacing: 5,
                          ),
                        ],
                      ).px12().expand(),
                    ],
                  ),
                  //selected options if any
                  if (vm.service!.selectedOptions.isNotEmpty) ...[
                    20.heightBox,
                    "Selected Options".tr().text.semiBold.make().px(10),
                    2.heightBox,
                    ListView(
                      shrinkWrap: true,
                      physics: NeverScrollableScrollPhysics(),
                      padding: EdgeInsets.zero,
                      children: vm.service!.selectedOptions.map(
                        (option) {
                          return HStack(
                            [
                              "${option.name}".text.make().expand(),
                              10.widthBox,
                              //price
                              "${AppStrings.currencySymbol} ${option.price}"
                                  .currencyFormat()
                                  .text
                                  .bold
                                  .make(),
                            ],
                          );
                        },
                      ).toList(),
                    ).px(12),
                    20.heightBox,
                  ],
                ],
              )
                  .box
                  .color(context.theme.colorScheme.surface)
                  .shadowXs
                  .roundedSM
                  .clip(Clip.antiAlias)
                  .make(),

              //
              //
              Divider(thickness: 3).py12(),
              //note
              CustomTextFormField(
                labelText: "Note".tr(),
                textEditingController: vm.noteTEC,
              ),
              UiSpacer.verticalSpace(),

              //pickup time slot
              ScheduleOrderView(vm),

              //address
              Visibility(
                visible: vm.service!.location,
                child: ServiceDeliveryAddressPickerView(
                  vm,
                  service: service,
                ),
              ),
              Divider(thickness: 3).py12(),
              DottedBorder(
                dashPattern: [5, 1],
                color: AppColor.accentColor,
                child: ServiceDiscountSection(vm)
                    .p20()
                    .box
                    .color(AppColor.accentColor.withOpacity(0.10))
                    .clip(Clip.antiAlias)
                    .roundedSM
                    .make(),
                radius: Radius.circular(10),
                borderType: BorderType.RRect,
                padding: EdgeInsets.all(0),
              ).py12(),
              DottedLine().py12(),

              //order final price preview
              LoadingShimmer(
                loading: vm.isBusy,
                child: OrderSummary(
                  subTotal: vm.checkout?.subTotal,
                  discount: vm.checkout?.discount,
                  deliveryFee:
                      vm.service!.location ? vm.checkout?.deliveryFee : null,
                  tax: vm.checkout?.tax,
                  vendorTax: vm.vendor?.tax,
                  total: vm.checkout!.total,
                  fees: vm.vendor?.fees ?? [],
                ),
              ),

              //
              Divider(thickness: 3).py12(),
              //payment options
              PaymentMethodsView(vm),

              //checkout button
              CustomButton(
                title: "Book Now".tr().padRight(14),
                icon: FlutterIcons.credit_card_fea,
                loading: vm.isBusy,
                onPressed: vm.placeOrder,
              ).wFull(context),
            ],
          ).p20().scrollVertical(),
        );
      },
    );
  }
}
