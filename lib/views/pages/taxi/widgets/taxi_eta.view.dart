import 'package:flutter/material.dart';
import 'package:fuodz/view_models/taxi/taxi.vm.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class TaxiETAView extends StatefulWidget {
  const TaxiETAView(this.vm, this.latlng, {Key? key}) : super(key: key);

  final TaxiViewModel vm;
  final LatLng latlng;

  @override
  State<TaxiETAView> createState() => _TaxiETAViewState();
}

class _TaxiETAViewState extends State<TaxiETAView> {
  @override
  void initState() {
    super.initState();
    widget.vm.taxiLocationService.calculatedETAToLocation(widget.latlng);
  }

  @override
  Widget build(BuildContext context) {
    return StreamBuilder(
      stream: widget.vm.taxiLocationService.etaStream,
      builder: (ctx, snapshot) {
        return ((snapshot.hasData ? "${snapshot.data}" : "--") +
                " " +
                "min".tr())
            .text
            .xl2
            .extraBold
            .make();
      },
    );
  }
}
