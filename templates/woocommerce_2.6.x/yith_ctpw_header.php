<ul class="order_details">
    <p><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'yith-custom-thankyou-page-for-woocommerce' ), $order ); ?></p>
    <li class="order">
        <?php _e( 'Order:', 'yith-custom-thankyou-page-for-woocommerce' ); ?>
        <strong><?php echo $order->get_order_number(); ?></strong>
    </li>
    <li class="date">
        <?php _e( 'Date:', 'yith-custom-thankyou-page-for-woocommerce' ); ?>
        <strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></strong>
    </li>
    <li class="total">
        <?php _e( 'Total:', 'yith-custom-thankyou-page-for-woocommerce' ); ?>
        <strong><?php echo $order->get_formatted_order_total(); ?></strong>
    </li>
    <?php if ( $order->payment_method_title ) : ?>
        <li class="method">
            <?php _e( 'Payment method:', 'yith-custom-thankyou-page-for-woocommerce' ); ?>
            <strong><?php echo $order->payment_method_title; ?></strong>
        </li>
    <?php endif; ?>
</ul>
<div class="clear"></div>