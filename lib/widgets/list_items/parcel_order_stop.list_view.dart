import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/models/delivery_address.dart';
import 'package:fuodz/models/order_stop.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/order_details.vm.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/cards/custom.visibility.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:url_launcher/url_launcher_string.dart';
import 'package:velocity_x/velocity_x.dart';

class ParcelOrderStopListView extends StatefulWidget {
  const ParcelOrderStopListView(
    this.title,
    this.stop, {
    Key? key,
    this.canCall = false,
    this.verify = false,
    this.routeToLocation,
    required this.vm,
  }) : super(key: key);

  final OrderStop stop;
  final String title;
  final bool canCall;
  final bool verify;
  final Function(DeliveryAddress)? routeToLocation;
  final OrderDetailsViewModel vm;

  @override
  _ParcelOrderStopListViewState createState() =>
      _ParcelOrderStopListViewState();
}

class _ParcelOrderStopListViewState extends State<ParcelOrderStopListView> {
  bool isOpen = true;

  @override
  Widget build(BuildContext context) {
    return VStack(
      [
        VStack(
          [
            "${widget.title}".text.gray500.medium.sm.make(),
            "${widget.stop.deliveryAddress?.name}".text.xl.medium.make(),
            "${widget.stop.deliveryAddress?.address}".text.make(),
            "${widget.stop.deliveryAddress?.description}".text.sm.make(),
          ],
        ).pOnly(bottom: Vx.dp4),

        //
        HStack(
          [
            "Contact Info".tr().text.gray500.medium.sm.make().expand(),
            //
            Icon(
              isOpen ? FlutterIcons.caret_up_faw : FlutterIcons.caret_down_faw,
              color: AppColor.primaryColor,
            ),
          ],
        ).onInkTap(() {
          setState(() {
            isOpen = !isOpen;
          });
        }),
        //
        Visibility(
          visible: isOpen,
          child: VStack(
            [
              "Recipient Name".tr().text.gray500.medium.sm.make(),
              "${widget.stop.name}"
                  .text
                  .medium
                  .xl
                  .make()
                  .pOnly(bottom: Vx.dp20),
              "Note".tr().text.gray500.medium.sm.make(),
              "${widget.stop.note}"
                  .text
                  .medium
                  .xl
                  .make()
                  .pOnly(bottom: Vx.dp20),
            ],
          ),
        ),

        //buttons
        Visibility(
          visible: isOpen,
          child: VStack(
            [
              //other buttons
              HStack(
                [
                  //route
                  widget.canCall
                      ? CustomButton(
                          icon: FlutterIcons.navigation_fea,
                          iconColor: Colors.white,
                          color: AppColor.primaryColor,
                          shapeRadius: Vx.dp20,
                          onPressed: (widget.routeToLocation != null)
                              ? () => widget.routeToLocation!(
                                  widget.stop.deliveryAddress!)
                              : null,
                        ).h(Vx.dp40).p12().expand()
                      : UiSpacer.emptySpace(),
                  //call
                  (widget.stop.phone != null && widget.canCall)
                      ? CustomButton(
                          icon: FlutterIcons.phone_call_fea,
                          iconColor: Colors.white,
                          title: "",
                          color: AppColor.primaryColor,
                          shapeRadius: Vx.dp24,
                          onPressed: () async {
                            final phoneNumber = "tel:${widget.stop.phone}";
                            if (await canLaunchUrlString(phoneNumber)) {
                              launchUrlString(phoneNumber);
                            }
                          },
                        ).h(Vx.dp40).p12().expand()
                      : UiSpacer.emptySpace(),
                ],
              ),
              //verify order stop button
              CustomVisibilty(
                visible: widget.verify,
                child: VStack(
                  [
                    //action button
                    CustomButton(
                      title: widget.stop.verified
                          ? "Verified".tr()
                          : "Verify stop".tr(),
                      icon: FlutterIcons.verified_oct,
                      iconColor: Colors.white,
                      color: AppColor.deliveredColor,
                      shapeRadius: Vx.dp20,
                      loading: widget.vm.busy(widget.stop),
                      onLongPress: widget.stop.verified
                          ? null
                          : () => widget.vm.verifyStop(widget.stop),
                    ).h(Vx.dp40).wFull(context),
                    //hint
                    CustomVisibilty(
                      visible: !widget.stop.verified,
                      child: "Long press to confirm stop"
                          .tr()
                          .text
                          .sm
                          .gray400
                          .makeCentered()
                          .p2(),
                    ),
                  ],
                ),
              ),
            ],
          ).pOnly(bottom: Vx.dp4),
        ),
      ],
    )
        .wFull(context)
        .p12()
        .box
        .roundedSM
        .border(color: AppColor.primaryColor)
        .make()
        .pOnly(bottom: Vx.dp12);
  }
}
