<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<p class="woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text_yctpw_pdf', __( 'Thank you. Your order has been received.', 'yith-custom-thankyou-page-for-woocommerce' ), $order ); ?></p>
<h2 class="order_details_title">
    <?php
    //APPLY_FILTER ctpw_order_details_title: change the Order Details Table title
    echo apply_filters('ctpw_order_details_title',__( 'Order details', 'yith-custom-thankyou-page-for-woocommerce' )); ?>
</h2>
<table class="order_details">
    <thead>
    <tr class="woocommerce-order-overview__order order">
        <td class="order"><?php _e( 'Order:', 'yith-custom-thankyou-page-for-woocommerce' ); ?></td>
        <td class="date"><?php _e( 'Date:', 'yith-custom-thankyou-page-for-woocommerce' ); ?></td>
        <td class="total"><?php _e( 'Total:', 'yith-custom-thankyou-page-for-woocommerce' ); ?></td>
        <?php if ( $order->get_payment_method_title() ) : ?>
            <td class="woocommerce-order-overview__payment-method method">
                <?php _e( 'Payment method:', 'yith-custom-thankyou-page-for-woocommerce' ); ?>
            </td>
        <?php endif; ?>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="order"><?php echo $order->get_order_number(); ?></td>
        <td class="date"><strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->get_date_created() ) ); ?></strong></td>
        <td class="total"><strong><?php echo $order->get_formatted_order_total(); ?></strong></td>
        <?php if ( $order->get_payment_method_title() ) : ?>
            <td class="woocommerce-order-overview__payment-method method">
                <strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
            </td>
        <?php endif; ?>
    </tr>
    </tbody>
</table>
<hr />