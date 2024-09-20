// ignore_for_file: deprecated_member_use

import 'package:flutter/material.dart';
import 'package:flag/flag.dart';
import 'package:fuodz/constants/app_languages.dart';
import 'package:fuodz/services/auth.service.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/custom_grid_view.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:fuodz/extensions/context.dart';

class AppLanguageSelector extends StatefulWidget {
  const AppLanguageSelector({Key? key}) : super(key: key);

  @override
  State<AppLanguageSelector> createState() => _AppLanguageSelectorState();
}

class _AppLanguageSelectorState extends State<AppLanguageSelector> {
  String selectedLanguage = translator.activeLanguageCode;
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(
        child: VStack(
          [
            //
            HStack(
              [
                //close icon
                Icon(
                  Icons.close,
                  color: context.primaryColor,
                ).onInkTap(() {
                  context.pop();
                }),
                //
                "Select your preferred language"
                    .tr()
                    .text
                    .xl
                    .semiBold
                    .make()
                    .expand(),
              ],
              spacing: 20,
            ).py20().px12(),
            UiSpacer.divider(
              thickness: 0.2,
              height: 2,
            ),

            "You can change language later from the settings menu"
                .tr()
                .text
                .make()
                .px12()
                .py(10),

            //
            //
            CustomGridView(
              crossAxisCount: 3,
              crossAxisSpacing: 10,
              mainAxisSpacing: 10,
              padding: EdgeInsets.all(12),
              dataSet: AppLanguages.codes,
              itemBuilder: (ctx, index) {
                Widget widget = VStack(
                  [
                    //
                    Flag.fromString(
                      AppLanguages.flags[index],
                      height: 40,
                      width: 40,
                    ),
                    UiSpacer.verticalSpace(space: 5),
                    //
                    AppLanguages.names[index].tr().text.lg.make(),
                  ],
                  crossAlignment: CrossAxisAlignment.center,
                  alignment: MainAxisAlignment.center,
                ).card.roundedSM.color(context.canvasColor).make().onTap(() {
                  setState(() {
                    selectedLanguage = AppLanguages.codes[index];
                  });
                  //
                  _onSelected(
                    context,
                    AppLanguages.codes[index],
                    complete: false,
                  );
                });
                //add badge
                bool isSelected = selectedLanguage == AppLanguages.codes[index];
                if (isSelected) {
                  widget = Stack(
                    children: [
                      widget.wFull(context).hFull(context),
                      //check icon, top right
                      Icon(
                        Icons.check_circle,
                        color: Colors.green,
                        size: 30,
                      ).positioned(
                        top: 0,
                        right: 0,
                      ),
                    ],
                  );
                }

                //
                return widget;
              },
            ).expand(),

            //add confirm button
            CustomButton(
              title: "Save".tr(),
              onPressed: () {
                _onSelected(context, selectedLanguage);
              },
            ).p12(),
          ],
        ),
      ),
    );
  }

  void _onSelected(
    BuildContext context,
    String code, {
    bool complete = true,
  }) async {
    await AuthServices.setLocale(code);
    //
    await translator.setNewLanguage(
      context,
      newLanguage: code,
      remember: true,
      restart: true,
    );
    await Utils.setJiffyLocale();
    //
    if (complete) {
      //is reload required
      bool reload = translator.activeLocale != code;
      if (reload) {
        context.pop(true);
      } else {
        context.pop();
      }
    }
  }
}
