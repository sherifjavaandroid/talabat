import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_images.dart';
import 'package:fuodz/constants/app_page_settings.dart';
import 'package:fuodz/view_models/document_request.vm.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/busy_indicator.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/cards/document_selection.view.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class DocumentRequestPage extends StatefulWidget {
  const DocumentRequestPage({Key? key}) : super(key: key);
  @override
  _DocumentRequestPageState createState() => _DocumentRequestPageState();
}

class _DocumentRequestPageState extends State<DocumentRequestPage> {
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
        return BasePage(
          showAppBar: true,
          showLeadingAction: true,
          title: "Document Request".tr(),
          isLoading: model.isBusy,
          body: model.isBusy
              ? BusyIndicator().centered().p(20)
              : VStack(
                  [
                    //if the document is pending verification
                    Visibility(
                      visible: model.currentUser!.pendingDocumentApproval,
                      child: VStack(
                        [
                          Image.asset(
                            AppImages.pendingDocument,
                            //width 20% of screen
                            width: context.screenWidth * 0.2,
                            height: context.screenWidth * 0.2,
                          ),
                          20.heightBox,
                          "Pending Verification".tr().text.xl2.bold.make(),
                          6.heightBox,
                          "Your document is pending verification, once verified you will receive an email from us. Thank you"
                              .tr()
                              .text
                              .center
                              .make(),
                        ],
                        crossAlignment: CrossAxisAlignment.center,
                      ).p(20).py(32),
                    ),

                    //if the document is requested
                    Visibility(
                      visible: model.currentUser!.documentRequested,
                      child: VStack(
                        [
                          Image.asset(
                            AppImages.pendingDocument,
                            //width 20% of screen
                            width: context.screenWidth * 0.2,
                            height: context.screenWidth * 0.2,
                          ),
                          20.heightBox,
                          "Document Requested".tr().text.xl2.bold.make(),
                          6.heightBox,
                          //doucment selection
                          DocumentSelectionView(
                            title: "Documents".tr(),
                            instruction:
                                AppPageSettings.driverDocumentInstructions,
                            max: AppPageSettings.maxDriverDocumentCount,
                            onSelected: model.onDocumentsSelected,
                          ).py(12),
                          10.heightBox,
                          //submit button
                          CustomButton(
                            title: "Submit".tr(),
                            loading: model.busy(model.selectedDocuments),
                            onPressed: () => model.submitDocuments(),
                          )
                        ],
                        crossAlignment: CrossAxisAlignment.center,
                      ).p(20).wFull(context),
                    ),
                  ],
                ),
        );
      },
    );
  }
}
