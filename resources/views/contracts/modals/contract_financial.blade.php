<div class="modal modal-wide fade" id="financialservice" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered mw-100 w-50" role="document">
        <div class="modal-content">   
            <div class="modal-header bg-lightblue color-palette">  
                <h4 class="modal-title" id="modelDocumentHeadingCase">Enviar a SeQura</h4>   
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fad fa-times text-white"></i></span>
                </button>        
            </div>
            <div class="modal-body">
                <div class="mt-3 text-lightblue">
                    <h5><i class="fad fa-info-circle"></i> Información que será enviada a SeQura</h5>
                    <div id="sequrainfo"></div>
                </div>
                <form action="https://pay.sequra.es/solicitations/preset" id="formSequra" accept-charset="UTF-8" method="GET" target="_blank">
                    @csrf
                    <!-- Unique order ID -->
                    <input type="hidden" id="order_order_ref_1" name="order[order_ref_1]" value="TEST-123-001">
                    <!-- Merchant's reference in SeQura -->
                    <input type="hidden" id="order_merchant_reference" name="order[merchant_reference]" value="MERCHANT_REFERENCE">
                    <!-- URL SeQura will POST to tell you (the merchant) that the order has been confirmed -->
                    <input type="hidden" id="order_echo_notify_url" name="order[echo_notify_url]" value="https://example.com/sequra-confirmation?ref=pedido-test-123-001&token=some-secret">
                    <!-- Chosen product code -->
                    <input type="hidden" id="order_product_code" name="order[product_code]" value="pp3">
                    <!-- Customer data -->
                    <input type="hidden" id="order_nin" name="order[nin]" value="12345678Z">
                    <input type="hidden" id="order_given_names" name="order[given_names]" value="María José">
                    <input type="hidden" id="order_surnames" name="order[surnames]" value="Barroso Rajoy">
                    <input type="hidden" id="order_email_address" name="order[email_address]" value="test+maria.jose@sequra.es">
                    <input type="hidden" id="order_date_of_birth_day" name="order[date_of_birth][day]" value="31">
                    <input type="hidden" id="order_date_of_birth_month" name="order[date_of_birth][month]" value="12">
                    <input type="hidden" id="order_date_of_birth_year" name="order[date_of_birth][year]" value="1972">
                    <input type="hidden" id="order_address_street" name="order[address_street]" value="c/Principal 34, 5ºA">
                    <input type="hidden" id="order_address_postal_code" name="order[address_postal_code]" value="08043">
                    <input type="hidden" id="order_address_city" name="order[address_city]" value="Barcelona">
                    <input type="hidden" id="order_mobile_phone" name="order[mobile_phone]" value="666888999">
                    <!-- Cart data -->
                    <!-- Service -->
                    <!-- Service reference previously provided to SeQura -->
                    <input type="hidden" id="order_service_reference" name="order[service][reference]" value="02350A1">
                    <input type="hidden" id="order_service_name" name="order[service][name]" value="GESO NIVEL II">
                    <input type="hidden" id="order_service_price_with_tax" name="order[service][price_with_tax]" value="">
                    <input type="hidden" id="order_service_quantity" name="order[service][quantity]" value="1">
                    <!-- Items -->
                    <!-- No Items Needed -->
                    <input type="submit" id="sequraSave" class="btn bg-lightblue" value="Financiar con SeQura">
                </form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar Proceso</button>
			</div>
        </div>
    </div>
</div>