import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_routes.dart';
import 'package:fuodz/models/category.dart';
import 'package:fuodz/models/search.dart';
import 'package:fuodz/models/search_data.dart';
import 'package:fuodz/models/vendor.dart';
import 'package:fuodz/models/product.dart';
import 'package:fuodz/models/vendor_type.dart';
import 'package:fuodz/requests/search.request.dart';
import 'package:fuodz/services/navigation.service.dart';
import 'package:fuodz/view_models/search.vm.dart';
import 'package:fuodz/view_models/search_filter.vm.dart';
import 'package:fuodz/widgets/bottomsheets/search_filter.bottomsheet.dart';
import 'package:pull_to_refresh_flutter3/pull_to_refresh_flutter3.dart';
import 'package:velocity_x/velocity_x.dart';

class ServiceSearchViewModel extends SearchViewModel {
  //
  SearchRequest _searchRequest = SearchRequest();
  ScrollController scrollController = ScrollController();
  RefreshController refreshController = RefreshController();
  TextEditingController searchTEC = TextEditingController();
  SearchData? searchData;
  String keyword = "";
  String type = "";
  Category? category;
  VendorType? vendorType;
  int selectTagId = 2;
  bool showGrid = false;
  bool byLocation;
  bool showVendors;
  bool showServices;
  //
  int queryPage = 1;
  List<dynamic> searchResults = [];
  SearchFilterViewModel? searchFilterVM;
  List<String> searchHistory = [];

  ServiceSearchViewModel(
    BuildContext context, {
    this.category,
    this.vendorType,
    this.byLocation = true,
    this.showVendors = true,
    this.showServices = true,
  }) : super(context, null) {
    //local constructor should override the parent constructor
    //this is to ensure that the parent constructor is not called

    this.viewContext = context;
    //construct the search object
    // selectTagId = 1 - vendors
    if (showVendors) {
      selectTagId = 1;
    }
    if (showServices) {
      selectTagId = 3;
    }
    // selectTagId = 3 - services
    search = Search(
      category: category,
      vendorType: vendorType ?? category?.vendorType,
      byLocation: byLocation,
    );
    //
    setSlectedTag(selectTagId, initiateSearch: false);
  }

  //
  startSearch({bool initialLoaoding = true}) async {
    //
    if (initialLoaoding) {
      setBusy(true);
      queryPage = 1;
      refreshController.refreshCompleted();
    } else {
      queryPage = queryPage + 1;
    }

    //
    try {
      final searchResult = await _searchRequest.serviceSearchRequest(
        keyword: keyword,
        search: search!,
        page: queryPage,
      );
      clearErrors();

      //
      if (initialLoaoding) {
        searchResults = searchResult;
      } else {
        searchResults.addAll(searchResult);
      }
    } catch (error) {
      print("Error ==> $error");
      setError(error);
    }

    if (!initialLoaoding) {
      refreshController.loadComplete();
    }
    //done loading data
    setBusy(false);
  }

  //
  void showFilterOptions() async {
    if (searchFilterVM == null) {
      searchFilterVM = SearchFilterViewModel(viewContext, search!);
    }

    //
    refreshController = refreshController = RefreshController();
    notifyListeners();

    print("vendortype ==> ${search?.vendorType?.id}");
    print("vendortype ==> ${(vendorType ?? category?.vendorType)?.id}");

    showModalBottomSheet(
      context: viewContext,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) {
        return SearchFilterBottomSheet(
          search: search!,
          vm: searchFilterVM!,
          onSubmitted: (mSearch) {
            search = mSearch;
            queryPage = 1;
            startSearch();
          },
        );
      },
    );
  }

  //
  productSelected(Product product) async {
    final page = NavigationService().productDetailsPageWidget(product);
    viewContext.nextPage(page);
  }

  //
  vendorSelected(Vendor vendor) async {
    Navigator.of(viewContext).pushNamed(
      AppRoutes.vendorDetails,
      arguments: vendor,
    );
  }

  setSlectedTag(int tagId, {bool initiateSearch = true}) {
    //start the reassign the tagId from search to the view type of tag
    if (tagId == 4) {
      tagId = 1;
    } else if (tagId == 5) {
      tagId = 3;
    }
    //END

    selectTagId = tagId;
    refreshController = new RefreshController();
    //
    search?.genApiType(selectTagId);
    if (initiateSearch) {
      startSearch();
    }
  }

  toggleShowGird(bool mShowGrid) {
    showGrid = mShowGrid;
    refreshController = new RefreshController();
    notifyListeners();
  }
}
