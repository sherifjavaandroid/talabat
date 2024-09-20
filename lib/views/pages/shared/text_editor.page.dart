//create statless widget

import 'package:flutter/material.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:quill_html_editor/quill_html_editor.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:fuodz/extensions/context.dart';

class CustomTextEditorPage extends StatefulWidget {
  CustomTextEditorPage({
    required this.title,
    this.content,
    Key? key,
  }) : super(key: key);
  final String? content;
  final String title;

  @override
  State<CustomTextEditorPage> createState() => _CustomTextEditorPageState();
}

class _CustomTextEditorPageState extends State<CustomTextEditorPage> {
  final QuillEditorController quillEditorController = QuillEditorController();

  @override
  Widget build(BuildContext context) {
    //

    //
    return Scaffold(
      appBar: AppBar(
        title: Text(widget.title),
        automaticallyImplyLeading: true,
      ),
      body: VStack(
        [
          ToolBar(
            toolBarColor: Colors.cyan.shade50,
            activeIconColor: Colors.green,
            padding: const EdgeInsets.all(8),
            iconSize: 20,
            controller: quillEditorController,
            customButtons: [
              InkWell(onTap: () {}, child: const Icon(Icons.favorite)),
              InkWell(onTap: () {}, child: const Icon(Icons.add_circle)),
            ],
          ),
          Flexible(
            fit: FlexFit.tight,
            child: QuillHtmlEditor(
              text: widget.content,
              hintText: "",
              controller: quillEditorController,
              isEnabled: true,
              minHeight: 400,
              hintTextAlign: TextAlign.start,
              padding: const EdgeInsets.only(left: 10, top: 5),
              hintTextPadding: EdgeInsets.zero,
            ),
          ),
          UiSpacer.vSpace(),
          //done button
          CustomButton(
            title: "Done".tr(),
            onPressed: () async {
              FocusScope.of(context).unfocus();
              FocusScope.of(context).requestFocus(new FocusNode());

              String htmlContent = await quillEditorController.getText();
              context.pop(htmlContent);
            },
          ).wFull(context).safeArea(top: false),
        ],
      ).p20(),
    );
  }
}
