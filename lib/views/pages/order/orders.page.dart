import 'package:flutter/material.dart';
import 'package:fuodz/services/order.service.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/orders.vm.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/custom_list_view.dart';
import 'package:fuodz/widgets/list_items/order.list_item.dart';
import 'package:fuodz/widgets/list_items/taxi_order.list_item.dart';
import 'package:fuodz/widgets/states/empty.state.dart';
import 'package:fuodz/widgets/states/error.state.dart';
import 'package:fuodz/widgets/states/order.empty.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class OrdersPage extends StatefulWidget {
  const OrdersPage({Key? key}) : super(key: key);

  @override
  _OrdersPageState createState() => _OrdersPageState();
}

class _OrdersPageState extends State<OrdersPage>
    with AutomaticKeepAliveClientMixin<OrdersPage>, WidgetsBindingObserver {
  //
  late OrdersViewModel vm;
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addObserver(this);
  }

  @override
  void dispose() {
    WidgetsBinding.instance.removeObserver(this);
    super.dispose();
  }

  @override
  void didChangeAppLifecycleState(AppLifecycleState state) {
    if (state == AppLifecycleState.resumed) {
      vm.fetchMyOrders();
    }
  }

  @override
  Widget build(BuildContext context) {
    vm = OrdersViewModel(context);
    super.build(context);
    return BasePage(
      body: SafeArea(
        child: ViewModelBuilder<OrdersViewModel>.reactive(
          viewModelBuilder: () => vm,
          onViewModelReady: (vm) => vm.initialise(),
          builder: (context, vm, child) {
            return VStack(
              [
                //

                "My Orders"
                    .tr()
                    .text
                    .xl2
                    .semiBold
                    .make()
                    .pOnly(bottom: Vx.dp10),
                //
                vm.isAuthenticated()
                    ? CustomListView(
                        canRefresh: true,
                        canPullUp: true,
                        refreshController: vm.refreshController,
                        onRefresh: vm.fetchMyOrders,
                        onLoading: () =>
                            vm.fetchMyOrders(initialLoading: false),
                        isLoading: vm.isBusy,
                        dataSet: vm.orders,
                        hasError: vm.hasError,
                        errorWidget: LoadingError(
                          onrefresh: vm.fetchMyOrders,
                        ),
                        //
                        emptyWidget: EmptyOrder(),
                        itemBuilder: (context, index) {
                          //
                          final order = vm.orders[index];
                          //for taxi tye of order
                          if (order.taxiOrder != null) {
                            return TaxiOrderListItem(
                              order: order,
                              orderPressed: () => vm.openOrderDetails(order),
                            );
                          }
                          return OrderListItem(
                            order: order,
                            orderPressed: () => vm.openOrderDetails(order),
                            onPayPressed: () =>
                                OrderService.openOrderPayment(order, vm),
                          );
                        },
                        separatorBuilder: (context, index) =>
                            UiSpacer.verticalSpace(space: 2),
                      ).expand()
                    : EmptyState(
                        auth: true,
                        showAction: true,
                        actionPressed: vm.openLogin,
                      ).py12().centered().expand(),
              ],
            ).px20().pOnly(top: Vx.dp20);
          },
        ),
      ),
    );
  }

  @override
  bool get wantKeepAlive => true;
}
