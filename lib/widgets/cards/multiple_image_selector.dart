import 'dart:io';

import 'package:flutter/material.dart';
import 'package:fuodz/utils/permission_utils.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/custom_grid_view.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:image_picker/image_picker.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class MultipleImageSelectorView extends StatefulWidget {
  const MultipleImageSelectorView({
    this.photos,
    required this.onImagesSelected,
    Key? key,
  }) : super(key: key);

  final List<String>? photos;
  final Function(List<File>) onImagesSelected;

  @override
  _MultipleImageSelectorViewState createState() =>
      _MultipleImageSelectorViewState();
}

class _MultipleImageSelectorViewState extends State<MultipleImageSelectorView> {
  //
  List<File>? selectedFiles = [];
  final picker = ImagePicker();

  @override
  Widget build(BuildContext context) {
    return VStack(
      [
        //
        if (showImageUrl() && !showSelectedImage())
          CustomGridView(
            noScrollPhysics: true,
            dataSet: widget.photos ?? [],
            crossAxisCount: 2,
            crossAxisSpacing: 10,
            itemBuilder: (context, index) {
              final photo = widget.photos![index];
              return CustomImage(
                imageUrl: photo,
              ).h20(context).wFull(context);
            },
          ),

        //
        showSelectedImage()
            ? CustomGridView(
                noScrollPhysics: true,
                dataSet: selectedFiles ?? [],
                crossAxisCount: 2,
                crossAxisSpacing: 10,
                itemBuilder: (context, index) {
                  final file = selectedFiles![index];
                  return Image.file(
                    file,
                    fit: BoxFit.cover,
                  ).h20(context).wFull(context);
                },
              )
            : UiSpacer.emptySpace(),
        //
        Visibility(
          // visible: !showImageUrl() && !showSelectedImage(),
          visible: true,
          child: CustomButton(
            title: "Select a photo".tr(),
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
    return widget.photos != null && widget.photos!.isNotEmpty;
  }

  bool showSelectedImage() {
    return selectedFiles != null;
  }

  //
  pickNewPhoto() async {
    //check for permission first
    final permission =
        await PermissionUtils.handleImagePermissionRequest(context);
    if (!permission) {
      return;
    }

    try {
      final pickedFiles = await picker.pickMultiImage();
      setState(() {
        selectedFiles = pickedFiles.map((e) => File(e.path)).toList();
      });
      //
      widget.onImagesSelected(selectedFiles!);
      showSelectedImage();
    } catch (error) {
      print(error);
    }
  }
}
