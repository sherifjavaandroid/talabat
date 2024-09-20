import 'dart:io';
import 'package:flutter/material.dart';
import 'package:fuodz/services/toast.service.dart';
import 'package:fuodz/utils/permission_utils.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/custom_grid_view.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:image_picker/image_picker.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class MultiImageSelectorView extends StatefulWidget {
  const MultiImageSelectorView({
    this.links,
    required this.onImagesSelected,
    this.crossAxisCount = 2,
    this.itemHeight,
    Key? key,
  }) : super(key: key);

  final List<String>? links;
  final Function(List<File>) onImagesSelected;
  final int crossAxisCount;
  final double? itemHeight;

  @override
  _MultiImageSelectorViewState createState() => _MultiImageSelectorViewState();
}

class _MultiImageSelectorViewState extends State<MultiImageSelectorView> {
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
            dataSet: widget.links!,
            crossAxisCount: widget.crossAxisCount,
            itemBuilder: (ctx, index) {
              return CustomImage(
                imageUrl: widget.links![index],
              )
                  .h(widget.itemHeight ?? context.mq.size.height * 0.2)
                  // .h20(context)
                  .wFull(context);
            },
          ),

        //
        if (showSelectedImage())
          CustomGridView(
            noScrollPhysics: true,
            dataSet: selectedFiles ?? [],
            crossAxisCount: widget.crossAxisCount,
            itemBuilder: (ctx, index) {
              return Image.file(
                selectedFiles![index],
                fit: BoxFit.cover,
              )
                  .h(widget.itemHeight ?? context.mq.size.height * 0.2)
                  // .h20(context)
                  .wFull(context);
            },
          ),

        //
        Visibility(
          // visible: !showImageUrl() && !showSelectedImage(),
          visible: true,
          child: CustomButton(
            title: "Select photo(s)".tr(),
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
    return widget.links != null && widget.links!.isNotEmpty;
  }

  bool showSelectedImage() {
    return selectedFiles != null && selectedFiles!.isNotEmpty;
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
      selectedFiles = [];

      for (var selectedFile in pickedFiles) {
        selectedFiles!.add(File(selectedFile.path));
      }
      //
      widget.onImagesSelected(selectedFiles ?? []);
      setState(() {
        selectedFiles = selectedFiles;
      });
    } catch (error) {
      ToastService.toastError("No Image/Photo selected".tr());
    }
  }
}
