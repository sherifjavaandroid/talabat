import 'package:flutter/material.dart';
import 'package:fuodz/models/option_group.dart';
import 'package:fuodz/models/product.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/product_details.vm.dart';
import 'package:fuodz/views/pages/product/widgets/product_details.header.dart';
import 'package:fuodz/views/pages/product/widgets/product_option_group.dart';
import 'package:fuodz/views/pages/product/widgets/product_options.header.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/busy_indicator.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:fuodz/widgets/html_text_view.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:localize_and_translate/localize_and_translate.dart';

class ProductDetailsPage extends StatelessWidget {
  ProductDetailsPage({
    required this.product,
    Key? key,
  }) : super(key: key);

  final Product product;
  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<ProductDetailsViewModel>.reactive(
      viewModelBuilder: () => ProductDetailsViewModel(context, product),
      onViewModelReady: (model) => model.getProductDetails(),
      builder: (context, model, child) {
        return BasePage(
          title: model.product.name,
          showAppBar: true,
          showLeadingAction: true,
          showCart: true,
          body: VStack(
            [
              //product image
              CustomImage(
                imageUrl: model.product.photo,
              ).wFull(context).hOneThird(context),

              //product header
              ProductDetailsHeader(product: model.product),
              UiSpacer.divider().pOnly(bottom: Vx.dp12),

              //product details
              "Product Description"
                  .tr()
                  .text
                  .semiBold
                  .xl
                  .underline
                  .make()
                  .px20(),
              UiSpacer.vSpace(),
              HtmlTextView(model.product.description).px20(),
              UiSpacer.divider().py12(),
              //options section
              Visibility(
                visible: model.product.optionGroups.isNotEmpty,
                child: VStack(
                  [
                    //options header
                    ProductOptionsHeader(
                      description:
                          "Available options attached to this product".tr(),
                    ),

                    //options
                    model.busy(model.product)
                        ? BusyIndicator().centered().py20()
                        : VStack(
                            [
                              ...buildProductOptions(model),
                            ],
                          ),
                  ],
                ),
              ),
            ],
          ).pOnly(bottom: context.percentHeight * 30).scrollVertical(),
        );
      },
    );
  }

  //
  buildProductOptions(model) {
    return model.product.optionGroups.map((OptionGroup optionGroup) {
      return ProductOptionGroup(optionGroup: optionGroup, model: model).py12();
    }).toList();
  }
}
