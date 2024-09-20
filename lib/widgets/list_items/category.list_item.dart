import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/models/category.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:velocity_x/velocity_x.dart';

class CategoryListItem extends StatelessWidget {
  const CategoryListItem({
    required this.category,
    required this.onPressed,
    this.maxLine = true,
    this.h,
    this.inverted = false,
    Key? key,
  }) : super(key: key);

  final Function(Category) onPressed;
  final Category category;
  final bool maxLine;
  final double? h;
  final bool inverted;
  @override
  Widget build(BuildContext context) {
    Widget child = 5.heightBox;
    if (inverted) {
      Color bgColor = Vx.hexToColor(category.color ?? "#ffffff");
      Color textColor = Utils.textColorByColor(bgColor);
      child = _buildCategoryViewBase(maxLine, inverted, textColor);
      child = child.p(8).box.roundedSM.color(bgColor).make();
    } else {
      Color textColor = Utils.textColorByColor(Colors.transparent);
      child = _buildCategoryViewBase(maxLine, inverted, textColor);
    }

    //
    if (maxLine) {
      child = child
          .w((AppStrings.categoryImageWidth * 1.8) +
              AppStrings.categoryTextSize)
          .h(h ??
              ((AppStrings.categoryImageHeight * 1.8) +
                  AppStrings.categoryImageHeight))
          .onInkTap(
            () => this.onPressed(this.category),
          )
          .px4();
    } else {
      child = child
          // .w((AppStrings.categoryImageWidth * 1.8) +
          //     AppStrings.categoryTextSize)
          .onInkTap(
            () => this.onPressed(this.category),
          )
          .px4();
    }
    return child;
    /*
    return VStack(
      [
        //max line applied
        CustomVisibilty(
          visible: maxLine,
          child: VStack(
            [
              //
              CustomImage(
                imageUrl: category.imageUrl ?? "",
                boxFit: BoxFit.fill,
                width: AppStrings.categoryImageWidth,
                height: AppStrings.categoryImageHeight,
              )
                  .box
                  .roundedSM
                  .clip(Clip.antiAlias)
                  .color(Vx.hexToColor(category.color ?? "#ffffff"))
                  .make()
                  .py2(),

              category.name.text
                  .minFontSize(AppStrings.categoryTextSize)
                  .size(AppStrings.categoryTextSize)
                  .center
                  .maxLines(1)
                  .overflow(TextOverflow.ellipsis)
                  .make()
                  .p2()
                  .expand(),
            ],
            crossAlignment: CrossAxisAlignment.center,
            alignment: MainAxisAlignment.start,
          )
              .w((AppStrings.categoryImageWidth * 1.8) +
                  AppStrings.categoryTextSize)
              .h(h ??
                  ((AppStrings.categoryImageHeight * 1.8) +
                      AppStrings.categoryImageHeight))
              .onInkTap(
                () => this.onPressed(this.category),
              )
              .px4(),
        ),

        //no max line applied
        CustomVisibilty(
          visible: !maxLine,
          child: VStack(
            [
              //
              CustomImage(
                imageUrl: category.imageUrl ?? "",
                boxFit: BoxFit.fill,
                width: AppStrings.categoryImageWidth,
                height: AppStrings.categoryImageHeight,
              )
                  .box
                  .roundedSM
                  .clip(Clip.antiAlias)
                  .color(Vx.hexToColor(category.color ?? "#ffffff"))
                  .make()
                  .py2(),

              //
              category.name.text
                  .size(AppStrings.categoryTextSize)
                  .wrapWords(true)
                  .center
                  .make()
                  .p2(),
            ],
            crossAlignment: CrossAxisAlignment.center,
            alignment: MainAxisAlignment.start,
          )
              .w((AppStrings.categoryImageWidth * 1.8) +
                  AppStrings.categoryTextSize)
              .onInkTap(
                () => this.onPressed(this.category),
              )
              .px4(),
        )

        //
      ],
    );
    */
  }

  //
  Widget _buildCategoryViewBase(bool maxLine, bool inverted, Color textColor) {
    Widget nameView = category.name.text
        .size(AppStrings.categoryTextSize)
        .wrapWords(true)
        .center
        .color(textColor)
        .make()
        .p2();
    if (maxLine) {
      nameView = category.name.text
          .minFontSize(AppStrings.categoryTextSize)
          .size(AppStrings.categoryTextSize)
          .center
          .color(textColor)
          .maxLines(2)
          .ellipsis
          .make()
          .p2();
    }
    return VStack(
      [
        //
        CustomImage(
          imageUrl: category.imageUrl ?? "",
          boxFit: BoxFit.fill,
          width: AppStrings.categoryImageWidth * (inverted ? 0.75 : 1),
          height: AppStrings.categoryImageHeight * (inverted ? 0.75 : 1),
        )
            .box
            .roundedSM
            .clip(Clip.antiAlias)
            .color(inverted
                ? Colors.transparent
                : Vx.hexToColor(category.color ?? "#ffffff"))
            .make()
            .py2(),

        //
        nameView,
      ],
      crossAlignment: CrossAxisAlignment.center,
      alignment: MainAxisAlignment.start,
    );
  }
}
