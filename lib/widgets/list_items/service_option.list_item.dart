import 'package:dartx/dartx.dart';
import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/extensions/string.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/models/service_option.dart';
import 'package:fuodz/models/service_option_group.dart';
import 'package:fuodz/view_models/service_details.vm.dart';
import 'package:fuodz/widgets/currency_hstack.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:velocity_x/velocity_x.dart';

class ServiceOptionListItem extends StatelessWidget {
  const ServiceOptionListItem({
    required this.option,
    required this.optionGroup,
    required this.model,
    Key? key,
  }) : super(key: key);

  final ServiceOption option;
  final ServiceOptionGroup optionGroup;
  final ServiceDetailsViewModel model;

  @override
  Widget build(BuildContext context) {
    //
    final currencySymbol = AppStrings.currencySymbol;
    return VStack(
      [
        HStack(
          [
            //image/photo
            CustomImage(
              imageUrl: option.photo,
              width: Vx.dp32,
              height: Vx.dp32,
              canZoom: true,
              hideDefaultImg: true,
            ).card.clip(Clip.antiAlias).roundedSM.make(),
            Stack(
              children: [
                //

                //
                Visibility(
                  visible: model.isOptionSelected(option),
                  child: Positioned(
                    top: 5,
                    bottom: 5,
                    left: 5,
                    right: 5,
                    child: Container(
                      width: Vx.dp32,
                      height: Vx.dp32,
                      child: Icon(
                        FlutterIcons.check_ant,
                      ).box.color(AppColor.accentColor).roundedSM.make(),
                    ),
                  ),
                ),
              ],
            ),

            //name
            option.name.text.medium.lg.make().px12().expand(),

            //price
            CurrencyHStack(
              [
                currencySymbol.text.sm.medium.make(),
                option.price.currencyValueFormat().text.sm.bold.make(),
              ],
              crossAlignment: CrossAxisAlignment.end,
            ),
          ],
          crossAlignment: CrossAxisAlignment.center,
        ).onInkTap(
          () => model.toggleOptionSelection(optionGroup, option),
        ),

        //
        //details
        (option.description.isNotEmptyAndNotNull &&
                option.description.isNotNullOrBlank)
            ? "${option.description}"
                .text
                .sm
                .maxLines(3)
                .overflow(TextOverflow.ellipsis)
                .make()
            : 0.widthBox,
      ],
      spacing: 5,
    )
        .p(5)
        .box
        .withRounded(value: 4)
        .border(
          color: model.isOptionSelected(option)
              ? context.primaryColor
              : Colors.grey.shade200,
          width: model.isOptionSelected(option) ? 1.5 : 1.0,
        )
        .make();
  }
}
