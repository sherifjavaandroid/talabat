<div>
    <form 
    method="post" 
    @production
        action="https://securegw.paytm.in/theia/processTransaction" 
    @else
        action="https://securegw-stage.paytm.in/theia/processTransaction" 
    @endproduction
    
    name="payTmForm"
    style="visibility: hidden;">

        <table border="1">
            <tbody>
                <input id="paytm_REQUEST_TYPE" type="hidden" name="REQUEST_TYPE" value="" />
                <input id="paytm_MID" type="hidden" name="MID" value="" />
                <input id="paytm_ORDER_ID" type="hidden" name="ORDER_ID" value="" />
                <input id="paytm_CUST_ID" type="hidden" name="CUST_ID" value="" />
                <input id="paytm_INDUSTRY_TYPE_ID" type="hidden" name="INDUSTRY_TYPE_ID" value="" />
                <input id="paytm_CHANNEL_ID" type="hidden" name="CHANNEL_ID" value="" />
                <input id="paytm_TXN_AMOUNT" type="hidden" name="TXN_AMOUNT" value="" />
                <input id="paytm_WEBSITE" type="hidden" name="WEBSITE" value="" />
                <input id="paytm_CALLBACK_URL" type="hidden" name="CALLBACK_URL" value="" />
                <input id="paytm_MOBILE_NO" type="hidden" name="MOBILE_NO" value="" />
                <input id="paytm_EMAIL" type="hidden" name="EMAIL" value="" />
                <input id="paytm_CHECKSUMHASH" type="hidden" name="CHECKSUMHASH" value="">
            </tbody>
        </table>
    </form>
</div>
