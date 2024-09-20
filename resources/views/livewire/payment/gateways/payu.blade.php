<div>
    <form 
    @production
        action="https://secure.payu.in/_payment"
    @else
        action="https://sandboxsecure.payu.in/_payment"
   @endproduction
      name="payuform"
      method="POST">
        <input id="payU_key" type="hidden" name="key" value="" />
        <input id="payU_hash_string" type="hidden" name="hash_string" value="" />
        <input id="payU_hash" type="hidden" name="hash" />
        <input id="payU_txnid" type="hidden" name="txnid" />
        <input id="payU_amount" type="hidden" name="amount" />
        <input id="payU_firstname" type="hidden" name="firstname" id="firstname" />
        <input id="payU_email" type="hidden" name="email" id="email" />
        <input id="payU_phone" type="hidden" name="phone" />
        <input id="payU_productinfo" type="hidden" name="productinfo" />
        <input id="payU_surl" type="hidden" name="surl" />
        <input id="payU_furl" type="hidden" name="furl" />
        <input id="payU_service_provider" type="hidden" name="service_provider" value="payu_paisa" />
    </form>
</div>
