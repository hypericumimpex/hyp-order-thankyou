<?php
    //DO_ACTION yith_ctpw_before_header_details: hook before the header details: provide $order object
    do_action('yith_ctpw_before_header_details', $order);
?>
<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">
    <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'yith-custom-thankyou-page-for-woocommerce' ), $order ); ?></p>
    <li class="woocommerce-order-overview__order order">
        <?php _e( 'Order:', 'yith-custom-thankyou-page-for-woocommerce' ); ?>
        <strong><?php echo $order->get_order_number(); ?></strong>
    </li>
    <li class="woocommerce-order-overview__date date">
        <?php _e( 'Date:', 'yith-custom-thankyou-page-for-woocommerce' ); ?>
        <strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->get_date_created() ) ); ?></strong>
    </li>
    <li class="woocommerce-order-overview__total total">
        <?php _e( 'Total:', 'yith-custom-thankyou-page-for-woocommerce' ); ?>
        <strong><?php echo $order->get_formatted_order_total(); ?></strong>
    </li>
    <?php if ( $order->get_payment_method_title() ) : ?>
        <li class="woocommerce-order-overview__payment-method method">
            <?php _e( 'Payment method:', 'yith-custom-thankyou-page-for-woocommerce' ); ?>
            <strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
        </li>
    <?php endif; ?>
</ul>
<?php
//DO_ACTION yith_ctpw_after_header_details: hook after the header details: provide $order object
do_action('yith_ctpw_after_header_details', $order);
?>

<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>


<div class="clear"></div>