import 'package:fuodz/models/option.dart';
import 'package:fuodz/models/option_group.dart';
import 'package:fuodz/view_models/base.view_model.dart';

class ProductManageViewModel extends MyBaseViewModel {
  List<OptionGroup> productOptionGroups = [];
  //
  onAddOptionGroup() {
    final option = Option(
      id: 0,
      name: "",
      price: 0,
      description: "",
      optionGroupId: 0,
      photo: "",
    );

    final optionGroup = OptionGroup(
      id: 0,
      name: "",
      multiple: 0,
      required: 0,
      isActive: 1,
      photo: "",
      options: [
        option,
      ],
    );
    productOptionGroups.add(optionGroup);
    notifyListeners();
  }

  onAddOption(int index) {
    final option = Option(
      id: 0,
      name: "",
      price: 0,
      description: "",
      optionGroupId: 0,
      photo: "",
    );
    productOptionGroups[index].options.add(option);
    notifyListeners();
  }

  removeOptionGroup(int index) {
    productOptionGroups.removeAt(index);
    notifyListeners();
  }

  onMultipleChange(int index, bool value) {
    productOptionGroups[index].multiple = value ? 1 : 0;
    notifyListeners();
  }

  removeOption(int groupIndex, int optionIndex) {
    //prevent removing the last option
    if (productOptionGroups[groupIndex].options.length == 1) {
      return;
    }
    productOptionGroups[groupIndex].options.removeAt(optionIndex);
    notifyListeners();
  }

  //
  Map<String, dynamic> appendOptionGroupData(Map<String, dynamic> productData) {
    //START OF OPTION GROUPS AND OPTIONS
    //create option groups and options from the form data to array
    List<Map<String, dynamic>> mOptionGroups = [];
    for (var i = 0; i < productOptionGroups.length; i++) {
      Map<String, dynamic> optionGroupData = {
        "id": productOptionGroups[i].id,
        "name": productData["option_groups.$i.name"],
        "required": productData["option_groups.$i.required"],
        "multiple": productData["option_groups.$i.multiple"],
        "max_options": productData["option_groups.$i.max_options"] ?? null,
      };
      List<Map<String, dynamic>> mOptionGroupOptions = [];
      List mOptions = productOptionGroups[i].options;
      //loop through the options
      for (var j = 0; j < mOptions.length; j++) {
        Map<String, dynamic> optionGroupOptionData = {
          "id": mOptions[j].id,
          "name": productData["option_groups.$i.options.$j.name"],
          "price": productData["option_groups.$i.options.$j.price"],
        };
        //adding the option to option list
        mOptionGroupOptions.add(optionGroupOptionData);
      }
      //adding options to option group
      optionGroupData["options"] = mOptionGroupOptions;
      mOptionGroups.add(optionGroupData);
    }
    productData["option_groups"] = mOptionGroups;
    //END OF OPTION GROUPS AND OPTIONS
    return productData;
  }
}
