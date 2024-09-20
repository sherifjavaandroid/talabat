import 'package:flutter/material.dart';

class QtyStepper extends StatefulWidget {
  final int defaultValue;
  final int min;
  final int max;
  final bool disableInput;
  final Function(int) onChange;
  final Color? actionIconColor;
  final Color? actionButtonColor;
  final double? valueSize;

  QtyStepper({
    required this.defaultValue,
    required this.min,
    required this.max,
    this.disableInput = false,
    required this.onChange,
    this.actionIconColor,
    this.actionButtonColor,
    this.valueSize,
  });

  @override
  _QtyStepperState createState() => _QtyStepperState();
}

class _QtyStepperState extends State<QtyStepper> {
  late int _value;

  @override
  void initState() {
    super.initState();
    _value = widget.defaultValue;
  }

  void _decrement() {
    if (_value > widget.min) {
      setState(() {
        _value--;
        widget.onChange(_value);
      });
    }
  }

  void _increment() {
    if (_value < widget.max) {
      setState(() {
        _value++;
        widget.onChange(_value);
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final iconColor = widget.actionIconColor ?? Theme.of(context).primaryColor;
    final buttonColor =
        widget.actionButtonColor ?? Theme.of(context).primaryColor;

    return Row(
      children: [
        IconButton(
          onPressed: _decrement,
          icon: Icon(
            Icons.remove,
            color: iconColor,
            size: widget.valueSize,
          ),
          color: buttonColor,
        ),
        SizedBox(width: 8),
        widget.disableInput
            ? Text(
                '$_value',
                style: TextStyle(fontSize: widget.valueSize ?? 16),
              )
            : TextFormField(
                initialValue: '$_value',
                onChanged: (value) {
                  setState(() {
                    _value = int.parse(value);
                    widget.onChange(_value);
                  });
                },
                keyboardType: TextInputType.number,
                decoration: InputDecoration(
                  border: OutlineInputBorder(),
                  contentPadding: EdgeInsets.symmetric(horizontal: 12),
                ),
              ),
        SizedBox(width: 8),
        IconButton(
          onPressed: _increment,
          icon: Icon(
            Icons.add,
            color: iconColor,
            size: widget.valueSize,
          ),
          color: buttonColor,
        ),
      ],
    );
  }
}
