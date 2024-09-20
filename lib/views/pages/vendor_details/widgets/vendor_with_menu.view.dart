import 'package:flutter/material.dart';
import 'package:fuodz/models/vendor.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:fuodz/view_models/vendor_menu_details.vm.dart';
import 'package:fuodz/views/pages/vendor_details/widgets/upload_prescription.btn.dart';
import 'package:fuodz/views/pages/vendor_details/widgets/vendor_details_header.view.dart';
import 'package:fuodz/widgets/bottomsheets/cart.bottomsheet.dart';
import 'package:fuodz/widgets/busy_indicator.dart';
import 'package:fuodz/widgets/buttons/custom_rounded_leading.dart';
import 'package:fuodz/widgets/buttons/share.btn.dart';
import 'package:fuodz/widgets/cart_page_action.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:fuodz/widgets/custom_list_view.dart';
import 'package:fuodz/widgets/list_items/vendor_menu_product.list_item.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class VendorDetailsWithMenuPage extends StatefulWidget {
  VendorDetailsWithMenuPage({
    required this.vendor,
    Key? key,
  }) : super(key: key);

  final Vendor vendor;

  @override
  _VendorDetailsWithMenuPageState createState() =>
      _VendorDetailsWithMenuPageState();
}

class _VendorDetailsWithMenuPageState extends State<VendorDetailsWithMenuPage>
    with TickerProviderStateMixin {
  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<VendorDetailsWithMenuViewModel>.reactive(
      viewModelBuilder: () => VendorDetailsWithMenuViewModel(
        context,
        widget.vendor,
        tickerProvider: this,
      ),
      onViewModelReady: (model) {
        model.tabBarController = TabController(
          length: model.vendor?.menus.length ?? 0,
          vsync: this,
        );
        model.getVendorDetails();
      },
      builder: (context, model, child) {
        //feature image height
        double featureImageHeight = context.percentHeight * 20;
        //limit to 250 for most
        if (featureImageHeight > 250) {
          featureImageHeight = 250;
        }
        //
        return Scaffold(
          backgroundColor: context.theme.colorScheme.surface,
          floatingActionButton: UploadPrescriptionFab(model),
          body: NestedScrollView(
            headerSliverBuilder: (BuildContext context, bool scrolled) {
              return <Widget>[
                SliverAppBar(
                  expandedHeight: featureImageHeight,
                  floating: false,
                  pinned: true,
                  leading: CustomRoundedLeading(),
                  backgroundColor: context.backgroundColor,
                  actions: [
                    SizedBox(
                      width: 50,
                      height: 50,
                      child: FittedBox(
                        child: ShareButton(
                          model: model,
                        ),
                      ),
                    ),
                    UiSpacer.hSpace(10),
                    Container(
                      margin: EdgeInsets.symmetric(vertical: 2),
                      child: PageCartAction(),
                    )
                  ],
                  flexibleSpace: FlexibleSpaceBar(
                    centerTitle: true,
                    // title: Text(""),
                    //vendor image
                    background: CustomImage(
                      imageUrl: model.vendor!.featureImage,
                      height: featureImageHeight,
                      canZoom: true,
                    ).wFull(context),
                  ),
                ),
                SliverToBoxAdapter(
                  child: VendorDetailsHeader(
                    model,
                    showFeatureImage: false,
                    featureImageHeight: featureImageHeight,
                  ),
                ),
                SliverAppBar(
                  // backgroundColor: context.theme.primaryColor,
                  // backgroundColor: Colors.transparent,
                  title: "".text.make(),
                  floating: false,
                  pinned: true,
                  snap: false,
                  primary: false,
                  automaticallyImplyLeading: false,
                  // toolbarHeight: kToolbarHeight * 0.85,
                  flexibleSpace: TabBar(
                    padding: EdgeInsets.symmetric(horizontal: 20),
                    isScrollable: true,
                    labelColor: Utils.textColorByTheme(true),
                    unselectedLabelColor: context.theme.primaryColor,
                    indicatorWeight: 4,
                    indicator: BoxDecoration(
                      // color: Colors.red,
                      border: Border(
                        bottom: BorderSide(
                          color: context.theme.primaryColor,
                          width: 3,
                        ),
                      ),
                    ),
                    controller: model.tabBarController,
                    indicatorSize: TabBarIndicatorSize.tab,
                    tabAlignment: TabAlignment.start,
                    dividerHeight: 0,
                    tabs: model.vendor!.menus.map(
                      (menu) {
                        return Tab(
                          text: menu.name,
                          iconMargin: EdgeInsets.zero,
                        );
                      },
                    ).toList(),
                  ),
                ),
              ];
            },
            body: Container(
              child: model.isBusy
                  ? BusyIndicator().p20().centered()
                  : TabBarView(
                      controller: model.tabBarController,
                      children: model.vendor!.menus.map(
                        (menu) {
                          //
                          return CustomListView(
                            noScrollPhysics: true,
                            refreshController:
                                model.getRefreshController(menu.id),
                            canPullUp: true,
                            canRefresh: true,
                            padding: EdgeInsets.symmetric(vertical: 10),
                            dataSet: model.menuProducts[menu.id] ?? [],
                            isLoading: model.busy(menu.id),
                            onLoading: () => model.loadMoreProducts(
                              menu.id,
                              initialLoad: false,
                            ),
                            onRefresh: () => model.loadMoreProducts(menu.id),
                            itemBuilder: (context, index) {
                              //
                              final product =
                                  model.menuProducts[menu.id]?[index];
                              return VendorMenuProductListItem(
                                product,
                                onPressed: model.productSelected,
                                qtyUpdated: model.addToCartDirectly,
                              );
                            },
                            separatorBuilder: (context, index) => 5.heightBox,
                          );
                        },
                      ).toList(),
                    ),
            ),
          ),
          bottomSheet: CartViewBottomSheet(),
        );
      },
    );
  }
}
