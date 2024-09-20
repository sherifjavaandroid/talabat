import 'package:flutter/material.dart';

class ScrollIndicator extends StatefulWidget {
  final List<Widget> children;
  final Axis scrollDirection;
  final bool snap;

  ScrollIndicator({
    required this.children,
    this.scrollDirection = Axis.vertical,
    this.snap = false,
  });

  @override
  _ScrollIndicatorState createState() => _ScrollIndicatorState();
}

class _ScrollIndicatorState extends State<ScrollIndicator> {
  bool _showLeadingArrow = false;
  bool _showTrailingArrow = false;
  late ScrollController _controller;

  @override
  void initState() {
    super.initState();
    _controller = ScrollController();
    _controller.addListener(_scrollListener);
    WidgetsBinding.instance.addPostFrameCallback((_) => _scrollListener());
  }

  @override
  void dispose() {
    _controller.removeListener(_scrollListener);
    _controller.dispose();
    super.dispose();
  }

  void _scrollListener() {
    if (!_controller.hasClients) return;

    setState(() {
      _showLeadingArrow = _controller.offset > 0;
      _showTrailingArrow =
          _controller.offset < _controller.position.maxScrollExtent;
    });
  }

  @override
  Widget build(BuildContext context) {
    return widget.scrollDirection == Axis.vertical
        ? Column(
            children: [
              Expanded(
                child: NotificationListener<ScrollNotification>(
                  onNotification: (scrollNotification) {
                    _scrollListener();
                    return false;
                  },
                  child: ListView.builder(
                    controller: _controller,
                    scrollDirection: widget.scrollDirection,
                    itemCount: widget.children.length,
                    itemBuilder: (context, index) {
                      return widget.children[index];
                    },
                    physics: widget.snap
                        ? PageScrollPhysics()
                        : ClampingScrollPhysics(),
                  ),
                ),
              ),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  if (_showLeadingArrow)
                    Icon(
                      Icons.arrow_upward,
                      color: Colors.grey,
                    ),
                  if (_showTrailingArrow)
                    Icon(
                      Icons.arrow_downward,
                      color: Colors.grey,
                    ),
                ],
              ),
            ],
          )
        : Column(
            children: [
              Expanded(
                child: NotificationListener<ScrollNotification>(
                  onNotification: (scrollNotification) {
                    _scrollListener();
                    return false;
                  },
                  child: ListView.builder(
                    controller: _controller,
                    scrollDirection: widget.scrollDirection,
                    itemCount: widget.children.length,
                    itemBuilder: (context, index) {
                      return widget.children[index];
                    },
                    physics: widget.snap
                        ? PageScrollPhysics()
                        : ClampingScrollPhysics(),
                  ),
                ),
              ),
              Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  if (_showLeadingArrow)
                    Icon(
                      Icons.arrow_back,
                      color: Colors.grey,
                    ),
                  if (_showTrailingArrow)
                    Icon(
                      Icons.arrow_forward,
                      color: Colors.grey,
                    ),
                ],
              ),
            ],
          );
  }
}
