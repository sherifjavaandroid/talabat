$(function () {

    //
    var isFlutterInAppWebViewReady = false;
    window.addEventListener("flutterInAppWebViewPlatformReady", function (event) {
        isFlutterInAppWebViewReady = true;
    });
    //custom code
    livewire.on("openExternalBrowser", url => {
        if (isFlutterInAppWebViewReady) {
            window.flutter_inappwebview.callHandler('handlerOpenLink', url, true);
        }
    });


    //Stripe
    livewire.on("initStripe", (data) => {
        var stripe = Stripe(data[0]);
        stripe.redirectToCheckout({ sessionId: "" + data[1] + "" });
    });

    //Paystack
    livewire.on("initPaystack", (data) => {
        let handler = PaystackPop.setup({
            key: data[0], // Replace with your public key
            email: data[1],
            amount: data[2],
            currency: data[3],
            ref: "" + data[4] + "", // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
            // label: "Optional string that replaces customer email"
            onClose: function () {
                window.location.href = "" + data[5] + "";
            },
            callback: function (response) {
                window.location.href = "" + data[5] + "";
            },
        });
        handler.openIframe();
    });

    //Razorpay
    livewire.on("initRazorpay", (data) => {

        var options = {
            key: "" + data[0] + "", // Enter the Key ID generated from the Dashboard
            amount: "" + data[1] + "", // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
            currency: "" + data[2] + "",
            name: "" + data[3] + "",
            description: "",
            image: "" + data[4] + "",
            order_id: "" + data[5] + "", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
            redirect: true,
            callback_url: data[6],
            handler: function (response) {
                window.location.href = "" + data[7] + "";
            },
            theme: {
                // color: "" + data[7] + ""
            },
        };
        var rzp1 = new Razorpay(options);
        rzp1.on("payment.failed", function (response) {
            alert(data[6]);
            window.location.href = "" + data[6] + "";
        });
        rzp1.open();
    });

    //Flutterwave
    livewire.on("initFlwPayment", (data) => {
        FlutterwaveCheckout({
            public_key: "" + data[0] + "",
            tx_ref: "" + data[1] + "",
            amount: "" + data[2] + "",
            currency: "" + data[3] + "",
            country: "" + data[4] + "",
            payment_options: "card,mobilemoney,ussd,banktransfer",
            // specified redirect URL
            redirect_url: "" + data[5] + "",
            meta: {
                // consumer_id: 23,
                // consumer_mac: "92a3-912ba-1192a"
            },
            customer: {
                email: "" + data[6][0] + "",
                phone_number: "" + data[6][1] + "",
                name: "" + data[6][2] + "",
            },
            callback: function (data) {
                console.log(data);
            },
            onclose: function () {
                // close modal
            },
            customizations: {
                title: "" + data[7][0] + "",
                description: "",
                logo: "" + data[7][1] + "",
            },
        });
    });

    //paypal
    livewire.on("initPaypalPayment", (mainData) => {
        //
        paypal
            .Buttons({
                createOrder: function (data, actions) {
                    // This function sets up the details of the transaction, including the amount and line item details.
                    return actions.order.create({
                        purchase_units: [
                            {
                                amount: {
                                    value: mainData[0],
                                    currency: mainData[1],
                                },
                            },
                        ],
                    });
                },
                onApprove: function (data, actions) {
                    // This function captures the funds from the transaction.
                    return actions.order.capture().then(function (details) {
                        window.location.href =
                            "" +
                            mainData[2] +
                            "&transaction_id=" +
                            details["id"] +
                            "";
                    });
                },
            })
            .render("#paypal-button-container");
    });

    //paytm
    livewire.on("initPayTmPayment", (mainData) => {
        //
        let paymentData = mainData;
        $("#paytm_REQUEST_TYPE").val(paymentData["REQUEST_TYPE"]);
        $("#paytm_MID").val(paymentData["MID"]);
        $("#paytm_ORDER_ID").val(paymentData["ORDER_ID"]);
        $("#paytm_CUST_ID").val(paymentData["CUST_ID"]);
        $("#paytm_INDUSTRY_TYPE_ID").val(paymentData["INDUSTRY_TYPE_ID"]);
        $("#paytm_CHANNEL_ID").val(paymentData["CHANNEL_ID"]);
        $("#paytm_TXN_AMOUNT").val(paymentData["TXN_AMOUNT"]);
        $("#paytm_WEBSITE").val(paymentData["WEBSITE"]);
        $("#paytm_CALLBACK_URL").val(paymentData["CALLBACK_URL"]);
        $("#paytm_MOBILE_NO").val(paymentData["MOBILE_NO"]);
        $("#paytm_EMAIL").val(paymentData["EMAIL"]);
        $("#paytm_CHECKSUMHASH").val(paymentData["CHECKSUMHASH"]);

        //
        document.payTmForm.submit();
    });
    //payu
    livewire.on("initPayUPayment", (paymentData) => {
        //

        $("#payU_key").val(paymentData["payU_key"]);
        $("#payU_hash_string").val(paymentData["payU_hash_string"]);
        $("#payU_hash").val(paymentData["payU_hash"]);
        $("#payU_txnid").val(paymentData["payU_txnid"]);
        $("#payU_amount").val(paymentData["payU_amount"]);
        $("#payU_firstname").val(paymentData["payU_firstname"]);
        $("#payU_email").val(paymentData["payU_email"]);
        $("#payU_phone").val(paymentData["payU_phone"]);
        $("#payU_productinfo").val(paymentData["payU_productinfo"]);
        $("#payU_surl").val(paymentData["payU_surl"]);
        $("#payU_furl").val(paymentData["payU_furl"]);
        $("#payU_service_provider").val(paymentData["payU_service_provider"]);

        //
        document.payuform.submit();
    });

    //custom payment
});
