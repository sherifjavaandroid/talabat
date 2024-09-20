import 'package:flutter/material.dart';
import 'package:fuodz/constants/printer_values.dart';
import 'package:fuodz/view_models/printer_settings.vm.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class PrinterSettingsPage extends StatelessWidget {
  const PrinterSettingsPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return BasePage(
      showLeadingAction: true,
      showAppBar: true,
      title: "Printer Settings".tr(),
      body: ViewModelBuilder<PrinterSettingsPageViewModel>.reactive(
        viewModelBuilder: () => PrinterSettingsPageViewModel(context),
        builder: (context, model, child) {
          return VStack(
            [
              //print receipt: type - in-built or external
              ListTile(
                contentPadding: EdgeInsets.zero,
                dense: true,
                title: "Printer Type".tr().text.make(),
                subtitle: DropdownButton<String>(
                  isExpanded: true,
                  padding: EdgeInsets.zero,
                  hint: "Printer Type".tr().text.make(),
                  items: PrinterSettingValues.printerTypes.map((e) {
                    return DropdownMenuItem(
                      child: e.text.make(),
                      value: e,
                    );
                  }).toList(),
                  onChanged: (value) {
                    model.setPrinterType(value);
                  },
                  value: model.printerType,
                ),
              ),

              //paper size
              ListTile(
                contentPadding: EdgeInsets.zero,
                dense: true,
                title: "Paper Size".tr().text.make(),
                subtitle: DropdownButton<String>(
                  isExpanded: true,
                  padding: EdgeInsets.zero,
                  hint: "Paper Size".tr().text.make(),
                  items: PrinterSettingValues.paperSizes.map((e) {
                    return DropdownMenuItem(
                      child: e.text.make(),
                      value: e,
                    );
                  }).toList(),
                  onChanged: (value) {
                    model.setPaperSize(value);
                  },
                  value: model.paperSize,
                ),
              ),

              //auto print
              HStack(
                [
                  VStack(
                    [
                      "Auto Print".tr().text.make(),
                      "Print receipt automatically after order has been placed/received"
                          .text
                          .gray500
                          .sm
                          .make(),
                    ],
                  ).expand(),
                  Switch(
                    value: model.autoPrint,
                    onChanged: (value) {
                      model.setAutoPrint(value);
                    },
                  ),
                ],
                spacing: 10,
              ),

              //save button
              CustomButton(
                title: "Save".tr(),
                onPressed: () {
                  model.save();
                },
              ).wFull(context),
            ],
            spacing: 15,
          ).p(20).scrollVertical();
        },
      ),
    );
  }
}
