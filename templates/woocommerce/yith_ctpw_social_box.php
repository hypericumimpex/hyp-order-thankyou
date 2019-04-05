<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

//getting order to get products infos
$order = wc_get_order(intval($_GET['order-received']));

//DO_ACTION yith_ctpw_before_social_box: hook before the social box
do_action('yith_ctpw_before_social_box');

?>
<!-- Yith Custom Thank You Page Social Box -->
<div id="yith-ctpw-social-box" class="yith-ctpw-tabs" style="display: none">

<h2>
    <?php
    //APPLY_FILTER ctpw_sharebox_title: change the title of Share Box
    echo apply_filters('ctpw_sharebox_title',__('Share on...','yith-custom-thankyou-page-for-woocommerce')); ?>
</h2>
<?php /* socials tabs */ ?>
<div class="yith-ctpw-tabs-nav">
    <?php if ( ( !$is_shortcode && get_option('yith_ctpw_enable_fb_social_box','yes') == 'yes') || ( $is_shortcode && yith_plugin_fw_is_true($social_box_info['facebook'])) ) { ?>
        <a href="#" class="yith-ctpw-tabs-nav__link is-active">
            <span><img src="<?php echo YITH_CTPW_ASSETS_URL . 'images/facebook.png'; ?>" /></span>
            <span><?php _e('Facebook','yith-custom-thankyou-page-for-woocommerce'); ?></span>
        </a>
    <?php }
    if ( (!$is_shortcode && get_option('yith_ctpw_enable_twitter_social_box','yes') == 'yes') || ( $is_shortcode && yith_plugin_fw_is_true($social_box_info['twitter'])) ) {
        ?>
        <a href="#" class="yith-ctpw-tabs-nav__link">
            <span><img src="<?php echo YITH_CTPW_ASSETS_URL . 'images/twitter.png'; ?>" /></span>
            <span><?php _e('Twitter','yith-custom-thankyou-page-for-woocommerce'); ?></span>
        </a>
    <?php }
    if ( ( !$is_shortcode && get_option('yith_ctpw_enable_google_social_box','yes') == 'yes') || ( $is_shortcode && yith_plugin_fw_is_true($social_box_info['google'])) ) {
        ?>
        <a href="#" class="yith-ctpw-tabs-nav__link">
            <span> <img src="<?php echo YITH_CTPW_ASSETS_URL . 'images/google+.png'; ?>" /></span>
            <span><?php _e('Google +','yith-custom-thankyou-page-for-woocommerce'); ?></span>
        </a>
    <?php }
    if ( (!$is_shortcode && get_option('yith_ctpw_enable_pinterest_social_box','yes') == 'yes') || ( $is_shortcode && yith_plugin_fw_is_true($social_box_info['pinterest'])) ) {
        ?>
        <a href="#" class="yith-ctpw-tabs-nav__link">
            <span> <img src="<?php echo YITH_CTPW_ASSETS_URL . 'images/pinterest.png'; ?>" /></span>
            <span><?php _e('Pinterest','yith-custom-thankyou-page-for-woocommerce'); ?></span>
        </a>
    <?php } ?>
</div>

<?php
/*FACEBOOK Container *************************************************************************/

if ( (!$is_shortcode && get_option('yith_ctpw_enable_fb_social_box','yes') == 'yes') || ( $is_shortcode && yith_plugin_fw_is_true($social_box_info['facebook'])) ) : ?>

    <div class="yith-ctpw-tab is-active">
        <div class="yith-ctpw-tab__content">
            <div id="yith-ctwp-social-slider" class="ctpw_facebook">

                <?php //print the nav header only if there's more than one product
                if ( count($order->get_items()) > 1 ) : ?>

                    <div class="yith-ctwp-social_nav_container">
                        <p class="yith-ctwp-social_navigation yith-ctwp-slider_prev"><img src="<?php echo apply_filters('yith-ctpw-slider-prev',  YITH_CTPW_ASSETS_URL . 'images/prev.png' ); ?>" /></p>
                        <p class="yith-ctwp-social_navigation_message"><?php _e('Select the product to share','yith-custom-thankyou-page-for-woocommerce'); ?></p>
                        <p class="yith-ctwp-social_navigation yith-ctwp-slider_next"><img src="<?php echo apply_filters('yith-ctpw-slider-next',  YITH_CTPW_ASSETS_URL . 'images/next.png' ); ?>" /></p>
                    </div>

                <?php
                endif;

                //print a slide for each product
                foreach( $order->get_items() as $item ) {

                    $_product = apply_filters('woocommerce_order_item_product', $order->get_product_from_item($item), $item);

                    ?>

                    <div class="yith-ctwp-social-slider_container">
                        <div id="yith-ctpw-tab_sharing_product">
                            <div class="yith-ctpw-tab_sharing_product_thumb">
                                <?php echo $_product->get_image(); ?>
                            </div>
                            <div id="ctpw_facebook_p_id_<?php echo $_product->get_id(); ?>" class="yith-ctpw-tab_sharing_product_info">
                                <?php

                                //getting the image url
                                if (has_post_thumbnail($_product->get_id())) {
                                    $att_id[0] = get_post_thumbnail_id($_product->get_id());
                                    $att_url = wp_get_attachment_image_src($att_id[0], 'full');
                                } else {
                                    $att_url[0] = wc_placeholder_img_src();
                                }

                                //check the url to use, shortened or normal
                                if (get_option('ctpw_url_shortening') != 'none' && get_option('ctpw_url_shortening') != null && function_exists('YITH_url_shortener')) {
                                    $p_url = YITH_url_shortener()->url_shortening( apply_filters('yith_ctwp_social_url', $_product->get_permalink()) );
                                } else {
                                    $p_url = rawurlencode(apply_filters('yith_ctwp_social_url', $_product->get_permalink()));
                                }

                                ?>
                                <input class="ctpw_image_field" type="hidden" value="<?php echo rawurlencode($att_url[0]) ?>" />
                                <input class="ctpw_url_field" type="hidden" value="<?php echo $p_url; ?>" />
                                <input class="ctpw_sharer_field" type="hidden" value="https://www.facebook.com/sharer/sharer.php?u=ctpw_url&picture=ctpw_img&title=ctpw_title&description=ctpw_description" />
                                <input class="ctpw_title_field" ctpw_default_title="<?php echo __('I\'ve just purchased: ',
                                        'yith-custom-thankyou-page-for-woocommerce') . '\'' . $_product->get_title() . '\'' ; ?>" type="text"
                                       value="<?php echo __('I\'ve just purchased: ','yith-custom-thankyou-page-for-woocommerce') . '\'' . $_product->get_title() . '\'' ; ?>" />
                                <?php
                                $description = '';
                                if ( $_product instanceof WC_Product_Variation ) {
                                        $tp = get_post( $_product->get_parent_id() );
                                        $description = $tp->post_excerpt;
                                } else {
                                    $description = (yit_get_prop($_product,'short_description') != '') ? yit_get_prop($_product,'short_description') : $_product->get_short_description();
                                }

                                if (empty( $description) ) {
                                    $description = yit_get_prop($_product, 'description');
                                }

                                $description = substr(strip_tags(strip_shortcodes($description)), 0 , 250);
                                ?>
                                <textarea class="ctpw_excerpt" ctpw_default_description="<?php echo $description . '...'; ?>"><?php echo $description . '...'; ?></textarea>

                            </div>
                            <div class="ctpw_share_it"><a  href="javascript:void(0);" onclick="ctpw_socialize('ctpw_facebook_p_id_<?php echo $_product->get_id(); ?>')"><?php _e('Share','yith-custom-thankyou-page-for-woocommerce'); ?></a></div>
                            <div style="clear:both"></div>
                        </div>
                        <div style="clear:both"></div>
                    </div>

                <?php
                }//end for
                ?>
            </div> <?php // end slider ?>
        </div>
    </div>
<?php endif; ?>
<?php
/* TWITTER  *************************************************************************/
if ( (!$is_shortcode && get_option('yith_ctpw_enable_twitter_social_box','yes') == 'yes') || ( $is_shortcode && yith_plugin_fw_is_true($social_box_info['twitter'])) ) : ?>
    <div class="yith-ctpw-tab">
        <div class="yith-ctpw-tab__content">
            <div id="yith-ctwp-social-slider" class="ctpw_twitter">
                <?php
                //print the nav header only if there's more than one product
                if (count($order->get_items()) > 1) : ?>
                    <div class="yith-ctwp-social_nav_container">
                        <p class="yith-ctwp-social_navigation yith-ctwp-slider_prev"><img
                                src="<?php echo apply_filters('yith-ctpw-slider-prev', YITH_CTPW_ASSETS_URL . 'images/prev.png'); ?>"/>
                        </p>
                        <p class="yith-ctwp-social_navigation_message"><?php _e('Select the product to share', 'yith-custom-thankyou-page-for-woocommerce'); ?></p>
                        <p class="yith-ctwp-social_navigation yith-ctwp-slider_next"><img
                                src="<?php echo apply_filters('yith-ctpw-slider-next', YITH_CTPW_ASSETS_URL . 'images/next.png'); ?>"/>
                        </p>
                    </div>
                <?php
                endif;
                //print a slide for each product
                foreach( $order->get_items() as $item ) {
                    $_product = apply_filters('woocommerce_order_item_product', $order->get_product_from_item($item), $item);
                    ?>
                    <div class="yith-ctwp-social-slider_container">
                        <div id="yith-ctpw-tab_sharing_product">
                            <div class="yith-ctpw-tab_sharing_product_thumb">
                                <?php echo $_product->get_image(); ?>
                            </div>
                            <div id="ctpw_twitter_p_id_<?php echo $_product->get_id(); ?>" class="yith-ctpw-tab_sharing_product_info">
                                <?php

                                //check the url to use, shortened or normal
                                if (get_option('ctpw_url_shortening') != 'none' && get_option('ctpw_url_shortening') != null && function_exists('YITH_url_shortener')) {
                                    $p_url = YITH_url_shortener()->url_shortening( apply_filters('yith_ctwp_social_url', $_product->get_permalink()) );
                                } else {
                                    $p_url = rawurlencode(apply_filters('yith_ctwp_social_url', $_product->get_permalink()));
                                }
                                ?>

                                <input class="ctpw_image_field" type="hidden" value="" />
                                <input class="ctpw_url_field" type="hidden" value="<?php echo $p_url; ?>" />
                                <input class="ctpw_sharer_field" type="hidden" value="https://twitter.com/share?url=ctpw_url&text=ctpw_description" />
                                <input class="ctpw_title_field" type="hidden" value="" />
                                <textarea class="ctpw_excerpt" ctpw_default_description="<?php echo __('I\'ve just purchased: ', 'yith-custom-thankyou-page-for-woocommerce') . '\'' . $_product->get_title() . '\''; ?>"><?php echo __('I\'ve just purchased: ','yith-custom-thankyou-page-for-woocommerce') . '\'' . $_product->get_title() . '\''; ?></textarea>
                                <p id="twit_c_counter" style="display: none;"><?php _e('characters left',
                                        'yith-custom-thankyou-page-for-woocommerce'); ?> <span></span></p>
                            </div>
                            <div class="ctpw_share_it"><a  href="javascript:void(0);" onclick="ctpw_socialize('ctpw_twitter_p_id_<?php echo $_product->get_id(); ?>')"><?php _e('Tweet','yith-custom-thankyou-page-for-woocommerce'); ?></a></div>
                            <div style="clear:both"></div>
                        </div>
                        <div style="clear:both"></div>
                    </div>

                <?php
                }//end for
                ?>

            </div><?php //end slider ?>
        </div>
    </div>
<?php endif; ?>
<?php
/* GOOGLE PLUS  *************************************************************************/
if ( (!$is_shortcode && get_option('yith_ctpw_enable_google_social_box','yes') == 'yes' ) || ( $is_shortcode && yith_plugin_fw_is_true($social_box_info['google'])) )  : ?>
    <div class="yith-ctpw-tab">
        <div class="yith-ctpw-tab__content">
            <div id="yith-ctwp-social-slider" class="ctpw_googleplus">
                <?php
                //print the nav header only if there's more than one product
                if (count($order->get_items()) > 1) : ?>
                    <div class="yith-ctwp-social_nav_container">
                        <p class="yith-ctwp-social_navigation yith-ctwp-slider_prev"><img src="<?php echo apply_filters('yith-ctpw-slider-prev',  YITH_CTPW_ASSETS_URL . 'images/prev.png' ); ?>" /></p>
                        <p class="yith-ctwp-social_navigation_message"><?php _e('Select the product to share','yith-custom-thankyou-page-for-woocommerce'); ?></p>
                        <p class="yith-ctwp-social_navigation yith-ctwp-slider_next"><img src="<?php echo apply_filters('yith-ctpw-slider-next',  YITH_CTPW_ASSETS_URL . 'images/next.png' ); ?>" /></p>
                    </div>
                <?php
                endif;
                //print a slide for each product
                foreach( $order->get_items() as $item ) {
                    $_product = apply_filters('woocommerce_order_item_product', $order->get_product_from_item($item), $item);

                    ?>
                    <div class="yith-ctwp-social-slider_container">
                        <div id="yith-ctpw-tab_sharing_product">
                            <div class="yith-ctpw-tab_sharing_product_thumb">
                                <?php echo $_product->get_image(); ?>
                            </div>
                            <div id="ctpw_google_p_id_<?php echo $_product->get_id(); ?>" class="yith-ctpw-tab_sharing_product_info">
                                <?php

                                //check the url to use, shortened or normal
                                if (get_option('ctpw_url_shortening') != 'none' && get_option('ctpw_url_shortening') != null && function_exists('YITH_url_shortener')) {
                                    $p_url = YITH_url_shortener()->url_shortening( apply_filters('yith_ctwp_social_url', $_product->get_permalink()) );
                                } else {
                                    $p_url = rawurlencode(apply_filters('yith_ctwp_social_url', $_product->get_permalink()));
                                }

                                //creating the sharer link
                                //this is different from other socials because google not make you able to share custom datas
                                $ctpw_sharer = 'https://plus.google.com/share?';
                                $ctpw_sharer .= 'url=' . $p_url;
                                $ctpw_sharer .= '&title=' . rawurlencode(__('I\'ve just purchased: ','yith-custom-thankyou-page-for-woocommerce') .
                                        '\'' . $_product->get_title() . '\'' );

                                ?>
                                <p class="ctpw_title"><?php echo __('I\'ve just purchased: ','yith-custom-thankyou-page-for-woocommerce') . '\'' .
                                        $_product->get_title() . '\'' ; ?></p>
                                <?php
                                $description = '';
                                if ( $_product instanceof WC_Product_Variation ) {
                                    $tp = get_post( $_product->get_parent_id() );
                                    $description = $tp->post_excerpt;
                                } else {
                                    $description = (yit_get_prop($_product,'short_description') != '') ? yit_get_prop($_product,'short_description') : $_product->get_short_description();
                                }

                                if (empty( $description) ) {
                                    $description = yit_get_prop($_product, 'description');
                                }

                                $description = substr(strip_tags(strip_shortcodes($description)), 0 , 250);

                                ?>
                                <p class="ctpw_excerpt"><?php echo $description . '...'; ?></p>

                            </div>
                            <div class="ctpw_share_it"><a target="_blank" href="<?php echo $ctpw_sharer; ?>"><?php _e('Share','yith-custom-thankyou-page-for-woocommerce'); ?></a></div>
                            <div style="clear:both"></div>
                        </div>
                        <div style="clear:both"></div>
                    </div>

                <?php
                }//end for
                ?>
            </div> <?php // end slider ?>

        </div>
    </div>
<?php endif; ?>
<?php
/* PINTEREST  *************************************************************************/
if ( (!$is_shortcode && get_option('yith_ctpw_enable_pinterest_social_box','yes') == 'yes' ) || ( $is_shortcode && yith_plugin_fw_is_true($social_box_info['pinterest'])) ) : ?>
    <div class="yith-ctpw-tab">
        <div class="yith-ctpw-tab__content">
            <div id="yith-ctwp-social-slider" class="ctpw_pinterest">
                <?php
                //print the nav header only if there's more than one product
                if (count($order->get_items()) > 1) : ?>
                    <div class="yith-ctwp-social_nav_container">
                        <p class="yith-ctwp-social_navigation yith-ctwp-slider_prev"><img src="<?php echo apply_filters('yith-ctpw-slider-prev',  YITH_CTPW_ASSETS_URL . 'images/prev.png' ); ?>" /></p>
                        <p class="yith-ctwp-social_navigation_message"><?php _e('Select the product to share','yith-custom-thankyou-page-for-woocommerce'); ?></p>
                        <p class="yith-ctwp-social_navigation yith-ctwp-slider_next"><img src="<?php echo apply_filters('yith-ctpw-slider-next',  YITH_CTPW_ASSETS_URL . 'images/next.png' ); ?>" /></p>
                    </div>
                <?php
                endif;
                //print a slide for each product
                foreach( $order->get_items() as $item ) {
                    $_product = apply_filters('woocommerce_order_item_product', $order->get_product_from_item($item), $item);

                    ?>
                    <div class="yith-ctwp-social-slider_container">
                        <div id="yith-ctpw-tab_sharing_product">
                            <div class="yith-ctpw-tab_sharing_product_thumb">
                                <?php echo $_product->get_image(); ?>
                            </div>
                            <div id="ctpw_pinterest_p_id_<?php echo $_product->get_id(); ?>" class="yith-ctpw-tab_sharing_product_info">
                                <?php

                                if ( has_post_thumbnail($_product->get_id()) ) {
                                    $att_id[0] = get_post_thumbnail_id( $_product->get_id() );
                                    $att_url = wp_get_attachment_image_src($att_id[0], 'full' );
                                } else {
                                    $att_url[0] = wc_placeholder_img_src();
                                }

                                //check the url to use, shortened or normal
                                if (get_option('ctpw_url_shortening') != 'none' && get_option('ctpw_url_shortening') != null && function_exists('YITH_url_shortener')) {
                                    $p_url = YITH_url_shortener()->url_shortening( apply_filters('yith_ctwp_social_url', $_product->get_permalink()) );
                                } else {
                                    $p_url = rawurlencode(apply_filters('yith_ctwp_social_url', $_product->get_permalink()));
                                }

                                ?>
                                <input type="hidden" value="<?php echo rawurlencode($att_url[0]) ?>" />
                                <input type="hidden" value="<?php echo $p_url ?>" />
                                <input type="hidden" value="http://pinterest.com/pin/create/button/?url=ctpw_url&media=ctpw_img&description=ctpw_title - ctpw_description" />
                                <input class="ctpw_title" ctpw_default_title="<?php echo __('I\'ve just purchased: ',
                                        'yith-custom-thankyou-page-for-woocommerce') . '\'' . $_product->get_title() . '\'' ; ?>" type="text"
                                       value="<?php echo __('I\'ve just purchased: ','yith-custom-thankyou-page-for-woocommerce') . '\'' .
                                           $_product->get_title() . '\'' ; ?>" />
                                <?php

                                $description = '';
                                if ( $_product instanceof WC_Product_Variation ) {
                                    $tp = get_post( $_product->get_parent_id() );
                                    $description = $tp->post_excerpt;
                                } else {
                                    $description = (yit_get_prop($_product,'short_description') != '') ? yit_get_prop($_product,'short_description') : $_product->get_short_description();
                                }

                                if (empty( $description) ) {
                                    $description = yit_get_prop($_product, 'description');
                                }

                                $description = substr(strip_tags(strip_shortcodes($description)), 0 , 250);

                                ?>
                                <textarea ctpw_default_description="<?php echo $description . '...'; ?>" class="ctpw_excerpt"><?php echo $description . '...'; ?></textarea>

                            </div>
                            <div class="ctpw_share_it"><a  href="javascript:void(0);" onclick="ctpw_socialize('ctpw_pinterest_p_id_<?php echo
                                $_product->get_id(); ?>')"><?php _e('Pin it','yith-custom-thankyou-page-for-woocommerce'); ?></a></div>
                            <div style="clear:both"></div>
                        </div>
                        <div style="clear:both"></div>
                    </div>

                <?php
                }//end for
                ?>
            </div> <?php // end slider ?>

        </div>
    </div>
<?php endif; ?>
</div>
<?php
//DO_ACTION yith_ctpw_after_social_box: hook before the social box
do_action('yith_ctpw_after_social_box');
?>