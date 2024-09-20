import 'dart:io';

import 'package:flutter/material.dart';
import 'package:fuodz/utils/permission_utils.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:image_picker/image_picker.dart';
import 'package:velocity_x/velocity_x.dart';

class ImageSelectorView extends StatefulWidget {
  const ImageSelectorView({
    this.imageUrl = "",
    required this.onImageselected,
    Key? key,
  }) : super(key: key);

  final String imageUrl;
  final Function(File) onImageselected;

  @override
  _ImageSelectorViewState createState() => _ImageSelectorViewState();
}

class _ImageSelectorViewState extends State<ImageSelectorView> {
  //
  File? selectedFile;
  final picker = ImagePicker();

  @override
  Widget build(BuildContext context) {
    return VStack(
      [
        //
        if (showImageUrl() && !showSelectedImage())
          CustomImage(
            imageUrl: widget.imageUrl,
          ).h20(context).wFull(context),

        //
        showSelectedImage()
            ? Image.file(
                selectedFile!,
                fit: BoxFit.cover,
              ).h20(context).wFull(context)
            : UiSpacer.emptySpace(),
        //
        Visibility(
          // visible: !showImageUrl() && !showSelectedImage(),
          visible: true,
          child: CustomButton(
            title: "Select a photo",
            onPressed: pickNewPhoto,
          ).centered(),
        ),
      ],
    )
        .wFull(context)
        .box
        .clip(Clip.antiAlias)
        .border(color: context.accentColor)
        .roundedSM
        .outerShadow
        .make()
        .onTap(pickNewPhoto);
  }

  bool showImageUrl() {
    return widget.imageUrl.isNotBlank;
  }

  bool showSelectedImage() {
    return selectedFile != null;
  }

  //
  pickNewPhoto() async {
    //check for permission first
    final permission =
        await PermissionUtils.handleImagePermissionRequest(context);
    if (!permission) {
      return;
    }
    //End of permission check
    final pickedFile = await picker.pickImage(source: ImageSource.gallery);
    if (pickedFile != null) {
      selectedFile = File(pickedFile.path);
      //
      widget.onImageselected(selectedFile!);
    }
  }
}
