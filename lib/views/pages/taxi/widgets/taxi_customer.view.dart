import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/models/user.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:velocity_x/velocity_x.dart';

class TaxiCustomerView extends StatelessWidget {
  const TaxiCustomerView(this.user, {Key? key}) : super(key: key);

  final User user;

  @override
  Widget build(BuildContext context) {
    return HStack(
      [
        CustomImage(
          imageUrl: user.photo,
          width: 40,
          height: 40,
        ).box.roundedFull.clip(Clip.antiAlias).make(),
        VStack(
          [
            //
            "${user.name}".text.xl.medium.make(),
            VxRating(
              maxRating: 5,
              selectionColor: AppColor.ratingColor,
              value: user.rating,
              onRatingUpdate: (value) {},
              isSelectable: false,
            ),
          ],
        ).px12().expand(),
      ],
    );
  }
}
