import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_dropdown_alert/alert_controller.dart';
import 'package:flutter_dropdown_alert/model/data_alert.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/models/order.dart';
import 'package:fuodz/services/printer.service.dart';
import 'package:fuodz/widgets/states/loading_indicator.view.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/custom_list_view.dart';
import 'package:print_bluetooth_thermal/print_bluetooth_thermal.dart';
import 'package:velocity_x/velocity_x.dart';

class OrderPrinterSelector extends StatefulWidget {
  OrderPrinterSelector(this.order, {Key? key}) : super(key: key);
  final Order order;

  @override
  _OrderPrinterSelectorState createState() => _OrderPrinterSelectorState();
}

class _OrderPrinterSelectorState extends State<OrderPrinterSelector> {
  //START ORDER PRINTING STUFFS
  List<BluetoothInfo> _devices = [];
  BluetoothInfo? _selectedDevice;
  bool _deviceConnected = false;
  bool isPrintBusy = false;
  bool isPrinterSearching = false;
  String? busyDeviceId;

  @override
  void initState() {
    super.initState();
    initPlatformState();
  }

  Future<void> initPlatformState() async {
    List<BluetoothInfo> devices = [];
    setState(() {
      isPrinterSearching = true;
    });
    try {
      devices = await PrintBluetoothThermal.pairedBluetooths;
    } on PlatformException catch (error) {
      print("Devices search error ==> $error");
    }
    setState(() {
      isPrinterSearching = false;
    });

    if (!mounted) return;

    try {
      setState(() {
        _devices = devices;
      });
    } catch (error) {
      print("Error ==> $error");
    }
  }

  ///view
  @override
  Widget build(BuildContext context) {
    return VStack(
      [
        "Select Printer".tr().text.semiBold.xl2.make(),
        UiSpacer.verticalSpace(),
        //printers
        (_devices.isNotEmpty || isPrinterSearching)
            ? CustomListView(
                isLoading: isPrinterSearching,
                dataSet: _devices,
                itemBuilder: (context, index) {
                  //
                  final currentDevice = _devices[index];
                  return HStack(
                    [
                      VStack(
                        [
                          "${currentDevice.name}".text.make(),
                          "${currentDevice.macAdress}".text.xs.make(),
                        ],
                      ).expand(),
                      LoadingIndicator(
                        loading: busyDeviceId == currentDevice.macAdress,
                        hideChild: true,
                        child: (_selectedDevice != null &&
                                currentDevice.macAdress ==
                                    _selectedDevice?.macAdress)
                            ? Icon(
                                FlutterIcons.check_ant,
                                color: Colors.green,
                              )
                            : 0.heightBox,
                      ).wh(30, 30),
                    ],
                  ).py12().px8().onInkTap(() async {
                    await _disconnect();
                    setState(() {
                      _selectedDevice = currentDevice;
                    });
                    _connect();
                  });
                },
                separatorBuilder: (context, index) => 0.squareBox,
              ).expand()
            : ('Ops something went wrong!. Please check that your bluetooth is ON')
                .tr()
                .text
                .xl
                .makeCentered(),

        //
        UiSpacer.verticalSpace(),
        CustomButton(
          title: "Print".tr(),
          loading: isPrintBusy,
          onPressed: _deviceConnected
              ? () {
                  processPrint();
                }
              : null,
        ).wFull(context),
      ],
    ).p20();
  }

//for connecting to selected bluetooth device
  void _connect() async {
    if (_selectedDevice == null) {
      context.showToast(msg: 'No device selected.', bgColor: Colors.red);
    } else {
      //start connecting
      setState(() {
        busyDeviceId = _selectedDevice!.macAdress;
      });

      //connecting
      bool isConnected = await PrintBluetoothThermal.connectionStatus;
      if (!isConnected) {
        PrintBluetoothThermal.connect(
          macPrinterAddress: _selectedDevice!.macAdress,
        ).then(
          (value) {
            //
            setState(() {
              _deviceConnected = value;
              if (!(value)) {
                _selectedDevice = null;
              }
              //stop loading
              busyDeviceId = null;
            });
          },
        ).catchError(
          (error) {
            AlertController.show(
              "Failed".tr(),
              "Connection Failed. Please try again or select another printer"
                  .tr(),
              TypeAlert.error,
            );
            setState(() {
              _deviceConnected = false;
              _selectedDevice = null;
              //stop loading
              busyDeviceId = null;
            });
          },
        );
      } else {
        setState(() {
          _deviceConnected = isConnected;
          busyDeviceId = null;
        });
      }
    }
  }

  //disconnect from device
  _disconnect() async {
    if (await PrintBluetoothThermal.connectionStatus) {
      await PrintBluetoothThermal.disconnect;
    }
  }

  processPrint() async {
    setState(() {
      isPrintBusy = true;
    });
    try {
      await PrinterService().printOrder(
        context,
        widget.order,
        printerAddress: _selectedDevice?.macAdress,
      );
    } catch (error) {
      print("Error ==> $error");
    }
    setState(() {
      isPrintBusy = false;
    });
  }
}
