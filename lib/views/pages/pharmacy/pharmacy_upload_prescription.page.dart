import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/models/vendor.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/pharmacy_upload_prescription.vm.dart';
import 'package:fuodz/views/pages/checkout/widgets/order_delivery_address.view.dart';
import 'package:fuodz/views/pages/checkout/widgets/schedule_order.view.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/custom_grid_view.dart';
import 'package:fuodz/widgets/custom_text_form_field.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class PharmacyUploadPrescription extends StatelessWidget {
  const PharmacyUploadPrescription(this.vendor, {Key? key}) : super(key: key);

  final Vendor vendor;

  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<PharmacyUploadPrescriptionViewModel>.reactive(
      viewModelBuilder: () => PharmacyUploadPrescriptionViewModel(
        context,
        vendor,
      ),
      onViewModelReady: (vm) => vm.initialise(),
      builder: (context, vm, child) {
        return BasePage(
          showAppBar: true,
          showLeadingAction: true,
          elevation: 0,
          title: ("Upload Prescription".tr() + " ${vendor.name}"),
          appBarColor: context.theme.colorScheme.surface,
          appBarItemColor: AppColor.primaryColor,
          showCart: true,
          body: VStack(
            [
              // prescription photo
              VStack(
                [
                  //
                  CustomGridView(
                    noScrollPhysics: true,
                    dataSet: (vm.prescriptionPhotos.isNotEmpty)
                        ? vm.prescriptionPhotos
                        : [],
                    separatorBuilder: (p0, p1) => UiSpacer.vSpace(10),
                    itemBuilder: (ctx, index) {
                      final prescriptionPhoto = vm.prescriptionPhotos[index];

                      //stack with image and remove button
                      return Stack(
                        children: [
                          //image
                          Image.file(
                            prescriptionPhoto,
                            fit: BoxFit.cover,
                          ).wFull(context),
                          //remove button
                          Positioned(
                            right: 0,
                            top: 0,
                            child: Icon(
                              FlutterIcons.delete_ant,
                              color: Colors.white,
                              size: 16,
                            )
                                .p4()
                                .box
                                .red500
                                .roundedSM
                                .clip(Clip.antiAlias)
                                .make()
                                .onTap(() {
                              vm.removePhoto(index);
                            }),
                          ),
                        ],
                      ).wFull(context).h(100);
                    },
                  ),
                  //upload photo
                  CustomButton(
                    child: HStack(
                      [
                        Icon(
                          FlutterIcons.camera_ant,
                          size: 18,
                        ),
                        UiSpacer.horizontalSpace(space: 10),
                        "Upload Photo".text.make(),
                      ],
                    ).centered(),
                    shapeRadius: 30,
                    height: 20,
                    titleStyle: context.textTheme.bodyLarge!.copyWith(
                      fontSize: 11,
                    ),
                    onPressed: vm.changePhoto,
                  ).px(context.percentWidth * 25).py(15).centered(),
                  // "Upload Prescription Photo".text.make().centered(),
                ],
              ).wFull(context),

              // slots
              UiSpacer.verticalSpace(),
              ScheduleOrderView(vm),
              //
              OrderDeliveryAddressPickerView(vm),

              //place order
              UiSpacer.verticalSpace(),
              CustomTextFormField(
                labelText: "Note".tr(),
                textEditingController: vm.noteTEC,
              ),
              UiSpacer.verticalSpace(),
              CustomButton(
                title: "PLACE ORDER REQUEST".tr(),
                loading: vm.isBusy,
                onPressed: (vm.prescriptionPhotos.isNotEmpty)
                    ? () => vm.placeOrder(ignore: true)
                    : null,
              ).wFull(context),
            ],
          ).p20().scrollVertical().pOnly(
                bottom: context.mq.viewInsets.bottom,
              ),
        );
      },
    );
  }
}
