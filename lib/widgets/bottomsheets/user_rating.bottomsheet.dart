import 'package:flutter/material.dart';
import 'package:flutter_rating_bar/flutter_rating_bar.dart';
import 'package:fuodz/constants/app_images.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/extensions/dynamic.dart';
import 'package:fuodz/models/order.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/user_rating.vm.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class UserRatingBottomSheet extends StatelessWidget {
  const UserRatingBottomSheet({
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
    return ViewModelBuilder<UserRatingViewModel>.reactive(
      viewModelBuilder: () => UserRatingViewModel(context, order, onSubmitted),
      builder: (context, vm, child) {
        return PopScope(
          canPop: false,
          child: BasePage(
            body: VStack(
              [
                //price
                UiSpacer.verticalSpace(),
                UiSpacer.verticalSpace(),
                "Total".tr().text.medium.xl.makeCentered(),
                "${order.taxiOrder?.currency != null ? order.taxiOrder?.currency?.symbol : AppStrings.currencySymbol} ${order.total}"
                    .currencyFormat()
                    .text
                    .xl4
                    .bold
                    .makeCentered(),
                UiSpacer.verticalSpace(),
                UiSpacer.divider().py12(),
                UiSpacer.verticalSpace(),

                //
                Image.asset(
                  AppImages.user,
                  width: 60,
                  height: 60,
                ).centered(),
                //
                "Rate Rider".tr().text.center.xl.medium.makeCentered().py12(),
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
                SafeArea(
                  child: CustomButton(
                    title: "Submit".tr(),
                    onPressed: vm.submitRating,
                    loading: vm.isBusy,
                  ).centered(),
                ),
              ],
            ).p20().scrollVertical(),
          ).hTwoThird(context).pOnly(bottom: context.mq.viewInsets.bottom),
        );
      },
    );
  }
}
