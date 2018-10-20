<h2 class="customer_details">
    <?php
    //APPLY_FILTER ctpw_customer_details_title: change the title for Customer Details table
    echo apply_filters('ctpw_customer_details_title', __( 'Customer details', 'yith-custom-thankyou-page-for-woocommerce' ) ); ?>
</h2>
<ul class="woocommerce-customer-details customer_details">
    <?php
    if ( $order->get_billing_email() ) echo '<li><p class="woocommerce-customer-details--email">' . __( 'Email:', 'yith-custom-thankyou-page-for-woocommerce' ) . '</p><span> ' . $order->get_billing_email() . '</span></li>';
    if ( $order->get_billing_phone() ) echo '<li><p class="woocommerce-customer-details--phone">' . __( 'Telephone:', 'yith-custom-thankyou-page-for-woocommerce' ) . '</p><span> ' . $order->get_billing_phone() . '</span></li>';

    // DO_ACTION woocommerce_order_details_after_customer_details: hook after Customer Details after email and telephone: provided $order object
    do_action( 'woocommerce_order_details_after_customer_details', $order );
    ?>
    <div style="clear:both;"></div>
</ul>

<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && get_option( 'woocommerce_calc_shipping' ) !== 'no' ) : ?>

<div class="col2-set addresses">

    <div class="col-1">

        <?php endif; ?>

        <header class="billig_address_title">
            <h3 class="woocommerce-column__title"><?php _e( 'Billing address', 'yith-custom-thankyou-page-for-woocommerce' ); ?></h3>
        </header>
        <address class="woocommerce-column--billing-address">
            <?php
            if ( ! $order->get_formatted_billing_address() ) _e( 'N/A', 'yith-custom-thankyou-page-for-woocommerce' ); else echo $order->get_formatted_billing_address();
            ?>
        </address>

        <?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && get_option( 'woocommerce_calc_shipping' ) !== 'no' ) : ?>

    </div><!-- /.col-1 -->

    <div class="col-2">

        <header class="shipping_address_title">
            <h3 class="woocommerce-column__title"><?php _e( 'Shipping address', 'yith-custom-thankyou-page-for-woocommerce' ); ?></h3>
        </header>
        <address class="woocommerce-column--shipping-address">
            <?php
            if ( ! $order->get_formatted_shipping_address() ) _e( 'N/A', 'yith-custom-thankyou-page-for-woocommerce' ); else echo $order->get_formatted_shipping_address();
            ?>
        </address>

    </div><!-- /.col-2 -->

</div><!-- /.col2-set -->

<?php endif; ?>

<div class="clear"></div>
