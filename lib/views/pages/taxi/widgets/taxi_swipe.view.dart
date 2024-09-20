import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:fuodz/view_models/taxi/taxi.vm.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:swipe_button_widget/swipe_button_widget.dart';
import 'package:velocity_x/velocity_x.dart';

class TaxiActionSwipeView extends StatelessWidget {
  const TaxiActionSwipeView(this.vm, {Key? key}) : super(key: key);

  final TaxiViewModel vm;

  @override
  Widget build(BuildContext context) {
    return SwipeButtonWidget(
            // key: vm.onGoingTaxiBookingService.swipeBtnActionKey,
            acceptPoitTransition: 0.7,
            margin: const EdgeInsets.all(0),
            padding: const EdgeInsets.all(0),
            boxShadow: [],
            borderRadius: BorderRadius.circular(0),
            colorBeforeSwipe: AppColor.primaryColor,
            colorAfterSwiped: AppColor.primaryColor,
            height: 50,
            childBeforeSwipe: Container(
              decoration: BoxDecoration(
                borderRadius: BorderRadius.circular(0),
                color: AppColor.primaryColor,
              ),
              width: 60,
              height: double.infinity,
              child: const Center(
                child: Icon(
                  FlutterIcons.chevrons_right_fea,
                  color: Colors.white,
                  size: 38,
                ),
              ),
            ),
            childAfterSwiped: Container(
              decoration: BoxDecoration(
                borderRadius: BorderRadius.circular(0),
                color: AppColor.primaryColorDark,
              ),
              width: 60,
              height: double.infinity,
              child: const Center(
                child: Icon(
                  FlutterIcons.check_ant,
                  color: Colors.white,
                  size: 38,
                ),
              ),
            ),
            leftChildren: [
              Align(
                alignment: Alignment(0.8, 0),
                child: Text(
                  "${vm.onGoingTaxiBookingService.getNewStateStatus}".tr(),
                  style: TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.w400,
                    color: Utils.textColorByTheme(),
                  ),
                ),
              )
            ],
            onHorizontalDragUpdate: (e) {},
            onHorizontalDragRight: (e) async {
              return await vm.onGoingTaxiBookingService
                  .processOrderStatusUpdate();
            },
            onHorizontalDragleft: (e) async {
              return false;
            })
        .h(vm.isBusy ? 0 : 60)
        .box
        .roundedSM
        .clip(Clip.antiAlias)
        .make()
        .wFull(context);
  }
}
