<div id="yith_ctpw_failed_payment">
<p><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction.', 'yith-custom-thankyou-page-for-woocommerce' ); ?></p>

<p><?php
    if ( is_user_logged_in() )
        _e( 'Please make a new attempt or go to your account page.', 'yith-custom-thankyou-page-for-woocommerce' );
    else
        _e( 'Please make a new attempt.', 'yith-custom-thankyou-page-for-woocommerce' );
    ?></p>

<p>
    <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'yith-custom-thankyou-page-for-woocommerce' ) ?></a>
    <?php if ( is_user_logged_in() ) : ?>
        <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ); ?>" class="button pay"><?php _e( 'My Account', 'yith-custom-thankyou-page-for-woocommerce' ); ?></a>
    <?php endif; ?>
</p>
    </div>