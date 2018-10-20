    <h2 class="customer_details"><?php echo apply_filters('ctpw_customer_details_title', __( 'Customer details', 'yith-custom-thankyou-page-for-woocommerce' ) ); ?></h2>
<ul class="customer_details">
    <?php
    if ( $order->billing_email ) echo '<li><p>' . __( 'Email:', 'yith-custom-thankyou-page-for-woocommerce' ) . '</p><span> ' . $order->billing_email . '</span></li>';
    if ( $order->billing_phone ) echo '<li><p>' . __( 'Telephone:', 'yith-custom-thankyou-page-for-woocommerce' ) . '</p><span> ' . $order->billing_phone . '</span></li>';

    // Additional customer details hook
    do_action( 'woocommerce_order_details_after_customer_details', $order );
    ?>
    <div style="clear:both;"></div>
</ul>

<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && get_option( 'woocommerce_calc_shipping' ) !== 'no' ) : ?>

<div class="col2-set addresses">

    <div class="col-1">

        <?php endif; ?>

        <header class="billig_address_title">
            <h3><?php _e( 'Billing address', 'yith-custom-thankyou-page-for-woocommerce' ); ?></h3>
        </header>
        <address>
            <?php
            if ( ! $order->get_formatted_billing_address() ) _e( 'N/A', 'yith-custom-thankyou-page-for-woocommerce' ); else echo $order->get_formatted_billing_address();
            ?>
        </address>

        <?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && get_option( 'woocommerce_calc_shipping' ) !== 'no' ) : ?>

    </div><!-- /.col-1 -->

    <div class="col-2">

        <header class="shipping_address_title">
            <h3><?php _e( 'Shipping address', 'yith-custom-thankyou-page-for-woocommerce' ); ?></h3>
        </header>
        <address>
            <?php
            if ( ! $order->get_formatted_shipping_address() ) _e( 'N/A', 'yith-custom-thankyou-page-for-woocommerce' ); else echo $order->get_formatted_shipping_address();
            ?>
        </address>

    </div><!-- /.col-2 -->

</div><!-- /.col2-set -->

<?php endif; ?>

<div class="clear"></div>
