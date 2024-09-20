import 'package:flutter/material.dart';
import 'package:flutter_overboard/flutter_overboard.dart';
import 'package:fuodz/constants/app_images.dart';
import 'package:fuodz/constants/app_routes.dart';
import 'package:fuodz/requests/settings.request.dart';
import 'package:fuodz/services/auth.service.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'base.view_model.dart';
import 'package:velocity_x/velocity_x.dart';

class OnboardingViewModel extends MyBaseViewModel {
  OnboardingViewModel(BuildContext context, this.finishLoading) {
    this.viewContext = context;
  }

  final Function finishLoading;

  List<PageModel> onBoardData = [];

  initialise() {
    final bgColor = viewContext.theme.colorScheme.surface;
    final textColor =
        Utils.textColorByColor(viewContext.theme.colorScheme.surface);

    onBoardData = [
      PageModel(
        color: bgColor,
        titleColor: textColor,
        bodyColor: textColor,
        imageAssetPath: AppImages.onboarding1,
        title: "Delivery made easy".tr(),
        body: "Get notified as soon as an order is available for delivery".tr(),
        doAnimateImage: true,
      ),
      PageModel(
        color: bgColor,
        titleColor: textColor,
        bodyColor: textColor,
        imageAssetPath: AppImages.onboarding2,
        title: "Chat with vendor/customer".tr(),
        body:
            "Call/Chat with vendor/delivery boy for update about your order and more"
                .tr(),
        doAnimateImage: true,
      ),
      PageModel(
        color: bgColor,
        titleColor: textColor,
        bodyColor: textColor,
        imageAssetPath: AppImages.onboarding3,
        title: "Earning".tr(),
        body: "You get commissions from every delivery made".tr(),
        doAnimateImage: true,
      ),
    ];
    //
    loadOnboardingData();
  }

  loadOnboardingData() async {
    setBusy(true);
    try {
      final apiResponse = await SettingsRequest().appOnboardings();
      //load the data
      if (apiResponse.allGood) {
        final mOnBoardDatas = (apiResponse.body as List).map(
          (e) {
            return PageModel.withChild(
              child: VStack(
                [
                  Padding(
                    padding: new EdgeInsets.only(bottom: 25.0),
                    child: CustomImage(
                      imageUrl: "${e['photo']}",
                      width: viewContext.percentWidth * 50,
                      height: viewContext.percentWidth * 50,
                      boxFit: BoxFit.cover,
                    ).centered(),
                  ),
                  "${e["title"]}".tr().text.xl3.bold.make(),
                  UiSpacer.vSpace(5),
                  "${e["description"]}".tr().text.lg.hairLine.make(),
                ],
              ).p20(),
              color: viewContext.theme.colorScheme.surface,
              doAnimateChild: true,
            );
          },
        ).toList();
        //
        if (mOnBoardDatas.isNotEmpty) {
          onBoardData = mOnBoardDatas;
        }
      } else {
        toastError("${apiResponse.message}");
      }
    } catch (error) {
      toastError("$error");
    }
    setBusy(false);
    finishLoading();
  }

  void onDonePressed() async {
    //
    await AuthServices.firstTimeCompleted();
    Navigator.of(viewContext).pushNamedAndRemoveUntil(
      AppRoutes.loginRoute,
      (route) => false,
    );
  }
}
