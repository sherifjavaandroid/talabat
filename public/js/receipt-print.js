$(function () {
    "use strict";


    var barcodeValue = $("#orderCode").text();
    JsBarcode("#barcode", barcodeValue, {
        height: 60,
    });

    // //listen to select option change
    // livewire.on('setPaperSize', function (paperSize) {
    //     //
    //     //SET THE PAPER SIZE Width in mm
    //     $(".printSection").css("width", paperSize + "mm");

    // });

    // print
    // window.onload = print;
});
