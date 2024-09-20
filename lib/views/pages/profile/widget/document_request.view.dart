import 'package:flutter/material.dart';
import 'package:fuodz/view_models/document_request.vm.dart';
import 'package:fuodz/views/pages/profile/document_request.page.dart';
import 'package:fuodz/widgets/busy_indicator.dart';
import 'package:fuodz/widgets/menu_item.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class DocumentRequestView extends StatelessWidget {
  const DocumentRequestView({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<DocumentRequestViewModel>.reactive(
      viewModelBuilder: () => DocumentRequestViewModel(),
      onViewModelReady: (model) => model.initialise(),
      builder: (
        BuildContext context,
        DocumentRequestViewModel model,
        Widget? child,
      ) {
        //if model is busy
        if (model.isBusy) {
          return BusyIndicator();
        }
        //if current user has document requested or pending verification
        if (model.currentUser != null &&
            (!model.currentUser!.documentRequested &&
                !model.currentUser!.pendingDocumentApproval)) {
          return SizedBox();
        }

        //
        return MenuItem(
          title: "Document Request".tr(),
          onPressed: () {
            context.nextPage(DocumentRequestPage());
          },
          suffix: VxBox(
            child: "1".text.white.bold.xl.make().px(6),
          ).p3.roundedSM.red500.make(),
          topDivider: true,
          divider: false,
        );
      },
    );
  }
}
