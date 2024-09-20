import 'dart:async';
import 'package:flutter/material.dart';
import 'package:flutter_typeahead/flutter_typeahead.dart';
import 'package:velocity_x/velocity_x.dart';

class CustomTypeAheadField<T> extends StatelessWidget {
  const CustomTypeAheadField({
    this.title,
    this.hint,
    this.textEditingController,
    this.items,
    this.textFieldConfiguration,
    required this.suggestionsCallback,
    this.itemBuilder,
    required this.onSuggestionSelected,
    Key? key,
  }) : super(key: key);

  final String? title;
  final String? hint;
  final List<dynamic>? items;
  final TextEditingController? textEditingController;
  final TextFieldConfiguration? textFieldConfiguration;
  final FutureOr<Iterable<T>> Function(String) suggestionsCallback;
  final void Function(T) onSuggestionSelected;
  final Widget Function(BuildContext, T)? itemBuilder;
  @override
  Widget build(BuildContext context) {
    return TypeAheadField<T>(
      textFieldConfiguration: textFieldConfiguration ??
          TextFieldConfiguration(
            controller: textEditingController,
            autofocus: false,
            style: DefaultTextStyle.of(context)
                .style
                .copyWith(fontStyle: FontStyle.italic),
            decoration: InputDecoration(
              border: OutlineInputBorder(),
              hintText: hint,
              label: title != null ? "$title".text.make() : null,
            ),
          ),
      suggestionsCallback: suggestionsCallback,
      itemBuilder: itemBuilder ??
          (context, suggestion) {
            return ListTile(
              title: Text(
                  "${suggestion is Map ? suggestion['name'] : suggestion}"),
            );
          },
      onSuggestionSelected: onSuggestionSelected,
    );
  }
}
