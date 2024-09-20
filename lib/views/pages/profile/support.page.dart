import 'package:flutter/material.dart';
import 'package:fuodz/view_models/profile.vm.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/menu_item.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class SupportPage extends StatelessWidget {
  const SupportPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return BasePage(
      showAppBar: true,
      showLeadingAction: true,
      title: "Support".tr(),
      body: ViewModelBuilder<ProfileViewModel>.reactive(
        viewModelBuilder: () => ProfileViewModel(context),
        onViewModelReady: (vm) => vm.initialise(),
        builder: (context, model, child) {
          return VStack(
            [
              MenuItem(
                title: "Faqs".tr(),
                onPressed: model.openFaqs,
              ),
              //
              MenuItem(
                title: "Contact Us".tr(),
                onPressed: model.openContactUs,
              ),
              MenuItem(
                title: "Live support".tr(),
                onPressed: model.openLivesupport,
              ),
            ],
          ).p20().scrollVertical();
        },
      ),
    );
  }
}
