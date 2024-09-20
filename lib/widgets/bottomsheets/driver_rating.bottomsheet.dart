import 'package:flutter/material.dart';
import 'package:fuodz/models/order.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/driver_rating.vm.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:fuodz/widgets/custom_text_form_field.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:flutter_rating_bar/flutter_rating_bar.dart';

class DriverRatingBottomSheet extends StatelessWidget {
  const DriverRatingBottomSheet({
    Key? key,
    required this.onSubmitted,
    required this.order,
  }) : super(key: key);

  //
  final Order order;
  final Function onSubmitted;

  //
  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<DriverRatingViewModel>.reactive(
      viewModelBuilder: () =>
          DriverRatingViewModel(context, order, onSubmitted),
      builder: (context, vm, child) {
        return BasePage(
          showAppBar: true,
          showLeadingAction: true,
          title: "Rate Driver".tr(),
          body: VStack(
            [
              UiSpacer.vSpace(),
              //
              CustomImage(
                imageUrl: order.driver!.photo,
                width: 80,
                height: 80,
              ).centered(),
              //
              "${order.driver?.name ?? ''}"
                  .text
                  .center
                  .xl
                  .semiBold
                  .makeCentered()
                  .py12(),
              //
              RatingBar.builder(
                initialRating: 3,
                minRating: 1,
                direction: Axis.horizontal,
                allowHalfRating: false,
                itemCount: 5,
                itemPadding: EdgeInsets.symmetric(horizontal: 4.0),
                itemBuilder: (context, _) => Icon(
                  Icons.star,
                  color: Colors.yellow[700],
                ),
                onRatingUpdate: (rating) {
                  vm.updateRating(rating.toInt().toString());
                },
              ).centered().py12(),

              //
              CustomTextFormField(
                minLines: 1,
                maxLines: 4,
                textEditingController: vm.reviewTEC,
                labelText: "Comment".tr(),
                textInputAction: TextInputAction.newline,
                keyboardType: TextInputType.multiline,
              ).py12(),

              //
              SafeArea(
                child: CustomButton(
                  title: "Submit".tr(),
                  onPressed: vm.submitRating,
                  loading: vm.isBusy,
                ).centered().py16(),
              ),
            ],
          ).p20().scrollVertical(),
        ).hTwoThird(context).pOnly(bottom: context.mq.viewInsets.bottom);
      },
    );
  }
}
