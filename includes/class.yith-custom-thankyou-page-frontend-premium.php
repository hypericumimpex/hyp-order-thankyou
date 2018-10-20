<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
if ( ! defined( 'YITH_CTPW_VERSION' ) ) {
    exit( 'Direct access forbidden.' );
}


if ( ! class_exists( 'YITH_Custom_Thankyou_Page_Frontend_Premium' ) ) {
    /**
     * YITH Custom Thankyou Page Frontend Premiuim Class
     *
     * @class      YITH_Custom_Thankyou_Page_Frontend_Premium
     * @package    Yithemes
     * @since      Version 1.0.0
     * @author     Your Inspiration Themes
     * @category   Class
     *
     * @property   string   $yith_ctw_wc_version The WC current version
     * @property   int      $ctpw_general_page The general Thank You page id
     *
     */
    class YITH_Custom_Thankyou_Page_Frontend_Premium extends YITH_Custom_Thankyou_Page_Frontend     {

        public $shortner = '';
        public $YITH_PDF = '';

        /**
         * Construct
         *
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @since 1.0.0
         */
        public function __construct() {
            //load the url shortener class if selected in settings
            if (get_option('ctpw_url_shortening') != 'none' && get_option('ctpw_url_shortening') != null) {
                require_once(YITH_CTPW_PATH . 'includes/class.yith-url-shortener.php');
            }

            // check the parts options, if they are selected we show them
            //main filter is in YITH_Custom_Thankyou_Page_Frontend __constructor
            //APPLY_FILTER: yith_ctpw_show_header_filter: show header part of thank you page: value can be true or false
            if (get_option('yith_ctpw_show_header','yes') == 'no'){ add_filter('yith_ctpw_show_header_filter','__return_false' ); }
            //APPLY_FILTER: yith_ctpw_show_table_filter: show order details table: value can be true or false
            if (get_option('yith_ctpw_show_order_table','yes') == 'no'){ add_filter('yith_ctpw_show_table_filter','__return_false' ); }
            //APPLY_FILTER: yith_ctpw_show_details_filter: show customer details table: value can be true or false
            if (get_option('yith_ctpw_show_customer_details','yes') == 'no'){ add_filter('yith_ctpw_show_details_filter','__return_false' ); }

            //if also one of order review parts is showed we add the styles from the settings */
            if (get_option('yith_ctpw_show_header','yes') == 'yes' || get_option('yith_ctpw_show_order_table','yes') == 'yes' || get_option('yith_ctpw_show_customer_details','yes') == 'yes') {
                add_action('yith_ctpw_successful_ac', array($this, 'yith_order_parts_style'), 1);
            }

            //social box
            if (get_option('yith_ctpw_enable_social_box','yes') == 'yes') {
                add_action('yith_ctpw_successful_ac', array($this, 'yith_ctpw_social_box'), 40);
                global $is_shortcode;
                $is_shortcode = false;
            }

            //register the script for social box tabs
            //it is only loaded if social box is showed, so it is loaded in yith_ctpw_social_box function
            wp_register_script('yith-ctpw-tabs',YITH_CTPW_ASSETS_URL . 'js/yith_ctpw_tabs.js',array('jquery'),false,true);

            /* register order review parts shortcodes to put that elements where you want on custom page
            Note: the order review parts should be disabled in settings to use the shortcodes */
            add_shortcode('yith_orderreview_header', array($this, 'yith_ctpw_header_shortcode'));
            add_shortcode('yith_orderreview_table', array($this, 'yith_ctpw_table_shortcode'));
            add_shortcode('yith_orderreview_customer_details', array($this, 'yith_ctpw_customer_details_shortcode'));

            //add upsells section
            if (get_option('yith_ctpw_enable_upsells','yes') == 'yes') {
                add_action('yith_ctpw_successful_ac', array($this, 'yith_ctpw_upsells'), 50);
            }

            //upsells shortcode to use the upsells section in any position on the page
            //you will need to disable the upsells section in the settings
            add_shortcode('ctpw_show_products', array($this,'ctpw_show_products_shortcode'));

            //social box shortcode, it can be used to loccate the box on the page where you want
            add_shortcode('yith_ctpw_social', array($this,'yith_ctpw_social_shortcode'));

            //add show user_name shortcode
            add_shortcode('yith_ctpw_customer_name', array($this,'yith_ctpw_customer_name_shortcode'));
            //add show order number shortcode
            add_shortcode('yith_ctpw_order_number', array($this,'yith_ctpw_order_number_shortcode'));
            //add show customer email shortcode
            add_shortcode('yith_ctpw_customer_email', array($this,'yith_ctpw_customer_email_shortcode'));

            /* get PDF class instance */
            //APPLY_FILTER: yith_ctpw_pdf_button: enable pdf button: value can be yes or no
            $pdf_active = apply_filters( 'yith_ctpw_pdf_button', get_option('yith_ctpw_enable_pdf','no'));
            if ( class_exists('YITH_Custom_Thankyou_Page_PDF') &&  $pdf_active != 'no' ) {
                $this->YITH_PDF = YITH_Custom_Thankyou_Page_PDF::instance();
            }

            //call the parent __construct
            parent::__construct();

            //if ctpw page we return true to wc checkout filters
            if ( isset($_GET['ctpw']) && isset($_GET['order-received']) && isset($_GET['key']) ) {
                add_filter('woocommerce_is_order_received_page','__return_false', 99);
                add_filter('woocommerce_is_checkout','__return_false', 99);
                //WooCommerce Google Analytics Integration compatibility
                if ( class_exists( 'WC_Google_Analytics' ) ) {
                    update_post_meta( $_GET['order-received'], '_ga_tracked', 0 );
                }
                update_post_meta( $_GET['order-received'], '_tracked', 0 );
            }
        }

        /**
         * Redirect Function
         *
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @since 1.0.0
         *
         * @param $order
         *
         */
        public function yith_ctpw_redirect_after_purchase($order)
        {
            //setting starting pages
            $general_page = '';

            //set new version general page option to work with previous version
            //since 1.0.3
            $g = get_option('yith_ctpw_general_page_or_url');
            if (empty( $g )) update_option('yith_ctpw_general_page_or_url','ctpw_page');


            //check if selected to use the custom url or custom wordpress page for general settings
            if ( get_option('yith_ctpw_general_page_or_url') == 'ctpw_url' && get_option('yith_ctpw_general_page_url') != '' ) {
                $general_page = get_option('yith_ctpw_general_page_url'); //general thankyou url
            } else {
                $general_page = $this->ctpw_general_page; //general thankyou page
                /* WPML compatibility - get page id translated if exists for current language */
                $lang = ( isset($_GET['lang']) && $_GET['lang'] != '') ? $_GET['lang'] : '' ;
                $general_page = apply_filters( 'wpml_object_id', $general_page, 'page', true, $lang );
            }

            $single_p_thankyoupage = 0; //single product thankyou page
            $cat_thankyoupage = 0; //category thankyou page
            $payment_page = 0; //payment gateway thankyou page
            $sel_page = ''; //final selected page during the checks
            $selected_thankyou_page = 0; //the page that will be selected after all the checks

            $ctpw_priority = get_option('yith_ctpw_priority','general'); //set the priority

            //if the priority is set to General and the general page exists we get it and we don't need to make other checks
            if ( ($general_page != 0 || $general_page != '' ) && $ctpw_priority  == 'general' ) {

                //if the general page is set as external URL we redirect to it and return
                if ( get_option('yith_ctpw_general_page_or_url') == 'ctpw_url') {
                    wp_redirect($general_page);
                    return;
                }

                //making the url redirect
                $order_key = wc_clean($_GET['key']);
                $redirect = get_permalink($general_page);
                /* WPML compatibility - check if the url has just an argument like ?lang=en
                if yes the url change accordingly */
                if (  strpos($redirect, '?' ) > 0  ) {
                    $redirect .= '&';
                } else {
                    $redirect .= get_option('permalink_structure') === '' ? '&' : '?';
                }
                $redirect .= 'order-received=' . absint($order) . '&key=' . $order_key . '&ctpw=' . $general_page;

                //APPLY_FILTER: yith_ctpw_url_redirect: manage the url redirect
                $redirect = apply_filters('yith_ctpw_url_redirect', $redirect);
                wp_redirect($redirect);
                return;

            }

            //we have to check for single product custom pages or category custom pages
            //get order object to check it there's a product with the Custom Thankyou page set
            $check_order = wc_get_order(intval($order));

            //check payment gateway priority and custom thank you page
            $payment_id = $check_order->get_payment_method();
            $payment_page = 0;

            if  ( get_option('yith_ctpw_general_page_or_url_' . $payment_id ) == 'ctpw_page' ) {
                if ( get_option( 'yith_ctpw_page_for_' . $payment_id ) != 0 && get_option( 'yith_ctpw_page_for_' . $payment_id ) != '' )  {
                    $payment_page = get_option( 'yith_ctpw_page_for_' . $payment_id );
                }
            } else {
                $payment_page = get_option( 'yith_ctpw_url_for_' . $payment_id );
            }


            //check for single product thankyou page
            //we only take in consideration the first product with a custom page, and that page will be the custom thank you page
             foreach( $check_order->get_items() as $item ) {
                     $_product = apply_filters('woocommerce_order_item_product', $check_order->get_product_from_item($item), $item);
                     $sel_var_page = '';
                     //get product id by wc version
                     $pid = version_compare($this->yith_ctpw_check_woocommerce_version(), '2.7','>=') ? $_product->get_id() : $_product->id ;


                     //check if selected to use wp page or external url
                     $sel_page_url = get_post_meta($pid, "yith_ctpw_product_thankyou_page_url", true);

                     if ( $sel_page_url == 'ctpw_url' ) {
                         $sel_page = trim(get_post_meta($pid, "yith_ctpw_product_thankyou_url", true));
                     } else {
                         $sel_page = get_post_meta($pid, "yith_product_thankyou_page", true);
                     }


                     //if it is a variable product check also if we have a custom page for variations
                    $sel_var_page = $sel_var_page_p = 0;
                     if ( $_product->get_type() == 'variation' ) {
                         //get first the general product custom page or url
                         $sel_page_url_var_p = get_post_meta($_product->get_parent_id(), "yith_ctpw_product_thankyou_page_url", true);
                         if ( $sel_page_url_var_p == 'ctpw_url' ) {
                             $sel_var_page_p = trim(get_post_meta($_product->get_parent_id(), "yith_ctpw_product_thankyou_url", true));
                         } else {
                             $sel_var_page_p = get_post_meta($_product->get_parent_id(), "yith_product_thankyou_page_variation", true);
                         }

                         //then check for variation
                         $sel_page_url_var = get_post_meta($_product->get_id(), "yith_ctpw_product_thankyou_page_url", true);
                         if ( $sel_page_url_var == 'ctpw_url' ) {
                             $sel_var_page = trim(get_post_meta($_product->get_id(), "yith_ctpw_product_thankyou_url", true));
                         } else {
                             $sel_var_page = get_post_meta($_product->get_id(), "yith_product_thankyou_page_variation", true);
                         }
                     }


                     //if a custom thank you page is set for variation we save it as thank you page for the product
                     if ( $sel_var_page != '' && $sel_var_page != 0 ) {
                         $sel_page = $sel_var_page;
                     } else {
                         if ($sel_var_page_p != '' || $sel_var_page_p != 0 ) {
                             $sel_page = $sel_var_page_p;
                         }
                     }

                 //we have a product custom thank you page
                 if ( ! empty($sel_page) || $sel_page != 0 ) {
                                $single_p_thankyoupage = $sel_page;
                                break;
                 }
             }

            //check for category product thankyou page
            //we only take in consideration the first product with a custom category thankyou page
            foreach( $check_order->get_items() as $item ) {
                $_product = apply_filters('woocommerce_order_item_product', $check_order->get_product_from_item($item), $item);

                if (version_compare($this->yith_ctpw_check_woocommerce_version(), '2.7','>=') ) {
                    // this is needed to check of the current product is a variation or not
                    // because if it is a variation we cannot get the categories so we will use the parent id
                    $parent_id =  wp_get_post_parent_id( $_product->get_id() );
                    if ( $parent_id != '') {
                        $cats = get_the_terms( $parent_id, 'product_cat' );
                    } else {
                        $cats = get_the_terms( $_product->get_id(), 'product_cat' );
                    }

                } else {
                    $cats = get_the_terms( $_product->id, 'product_cat' );
                }


                //we have categories check if for each if we have a custom thank you page or url
                 if($cats){
                     for ($i = 0; $i < count($cats); $i++)
                     {

                         $cat_page_url = get_term_meta($cats[$i]->term_id,'yith_ctpw_or_url_product_cat_thankyou_page',true);
                         $cat_url = get_term_meta($cats[$i]->term_id,'yith_ctpw_url_product_cat_thankyou_page',true);
                         $cat_page = get_term_meta($cats[$i]->term_id,'yith_ctpw_product_cat_thankyou_page',true);

                         if ( $cat_page_url == 'ctpw_url' && isset($cat_url) && $cat_url != '' ) {
                             $cat_thankyoupage = $cat_url;
                             break;
                         } elseif ((isset($cat_page) && $cat_page != 0 )) {
                             $cat_thankyoupage = $cat_page;
                             break;
                         }

                         if ( $cat_thankyoupage != 0 || $cat_thankyoupage != '') { break; }

                     }//end for
                 }

                //the first category thank you page found we go out of the or cycle
                if ( $cat_thankyoupage != 0 || $cat_thankyoupage != '') { break; }

            }//end for


            // if general page is set but we don't have product or category page we don't need the priority and we use the general thank you page
            if ($general_page != 0 && empty($cat_thankyoupage) && empty($single_p_thankyoupage) ) {
                $selected_thankyou_page = $general_page;
            }
            //if custom thankyou page for category is present in the order and the priority is to category page we use this
            elseif ( $cat_thankyoupage != '' && $ctpw_priority == 'category') {
                $selected_thankyou_page = $cat_thankyoupage;
            }
            //if the priority is set to Category but there's no product with a category custom page
            //we use the product page, if this not exists we use the general one
            elseif ( ( $cat_thankyoupage == 0 || $cat_thankyoupage == '' ) && $ctpw_priority == 'category') {
                if ($single_p_thankyoupage != 0 ) $selected_thankyou_page = $single_p_thankyoupage;
                elseif ($general_page != 0 || $general_page != '' ) $selected_thankyou_page = $general_page;
            }
            //if we have both category and single product thank you page, but priority is for product we use the product one
            elseif ($cat_thankyoupage != 0 && $single_p_thankyoupage != 0 && $ctpw_priority = 'product') {
                $selected_thankyou_page = $single_p_thankyoupage;
            }
            //if we have a single product page and the priority is to product we use this
            elseif (!empty($single_p_thankyoupage) && $ctpw_priority == 'product') {
                $selected_thankyou_page = $single_p_thankyoupage;
            }

            elseif ($cat_thankyoupage == 0 && $general_page==0 && $single_p_thankyoupage != 0) {
                $selected_thankyou_page = $single_p_thankyoupage;
            }

            //if the priority is to prooduct page but no products have a custom page
            //we will use the category page or the general one
            elseif ($single_p_thankyoupage == 0 && $ctpw_priority == 'product') {
                if ( !empty($cat_thankyoupage) ) $selected_thankyou_page = $cat_thankyoupage;
                elseif ( !empty($general_page) ) $selected_thankyou_page = $general_page;
            }

            if ($general_page==0 && $single_p_thankyoupage == 0 && ($cat_thankyoupage != 0 && $cat_thankyoupage != '')){
                $selected_thankyou_page = $cat_thankyoupage;
            }

            if ( $ctpw_priority == 'payment' && $payment_page != 0) {
                $selected_thankyou_page = $payment_page;
            }
            if ($cat_thankyoupage == 0 && $general_page==0 && $single_p_thankyoupage == 0 && ($payment_page != 0 && $payment_page != '')){
                $selected_thankyou_page = $payment_page;
            }


            //if we have a selected page or an external url, redirect to it
            if ( $selected_thankyou_page != 0 || $selected_thankyou_page != '' ) {
                if ( strlen(stristr( $selected_thankyou_page, "http" )) > 0 ) {
                    wp_redirect($selected_thankyou_page);
                } else {
                    /* WPML compatibility - get page id translated if exists for current language */
                    $lang = ( isset($_GET['lang']) && $_GET['lang'] != '') ? $_GET['lang'] : '' ;
                    $selected_thankyou_page = apply_filters( 'wpml_object_id', $selected_thankyou_page, 'page', true, $lang );
                    $order_key = wc_clean($_GET['key']);
                    $redirect = get_permalink($selected_thankyou_page);
                    /* WPML compatibility - check if the url has just an argument like ?lang=en
                    if yes the url change accordingly */
                    if (  strpos($redirect, '?' ) > 0  ) {
                        $redirect .= '&';
                    } else {
                        $redirect .= get_option('permalink_structure') === '' ? '&' : '?';
                    }
                    //pass also order-received query arg to make it to work with external plugins that need it
                    $redirect .= 'order-received=' . absint($order) . '&key=' . $order_key . '&ctpw=' . $selected_thankyou_page;

                    $redirect = apply_filters('yith_ctpw_url_redirect', $redirect);
                    wp_redirect($redirect);
                }

            }

        } //end function


        /**
         * Print Styles for Order Review elements
         *
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @since 1.0.0
         * @return void
         */
        public function yith_order_parts_style() {
            $f_size = 'font-size: ' .get_option('ctpw_orderstyle_title_fontsize','20') . 'px;';
            $f_color = 'color: ' . get_option('ctpw_orderstyle_title_color','#000000'). ';';
            $f_weight = 'font-weight: ' . get_option('ctpw_social_orderstyle_title_fontweight','bold'). ';';
            ?>
            <style>
                .yith-ctpw-front h2.customer_details, .yith-ctpw-front h2.order_details_title, .yith-ctpw-front .billig_address_title h2, .yith-ctpw-front .shipping_address_title h2 { <?php echo $f_color . ' ' . $f_size . ' ' . $f_weight ?> }
            </style>
            <?php

        }
        /**
         * Social Box function
         *
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @since 1.0.0
         *
         * param wc $order
         *
         */
        public function yith_ctpw_social_box() {
            global $is_shortcode;
            $is_shortcode = false;

            //if all the social options are disabled we don't need the box
            if ( get_option('yith_ctpw_enable_fb_social_box','yes') == 'no' && get_option('yith_ctpw_enable_twitter_social_box','yes') == 'no'
                && get_option('yith_ctpw_enable_google_social_box','yes') == 'no' ) { return; }

            //load the script for the tabs
            wp_enqueue_script('yith-ctpw-tabs');

            //get custom styles info
            //main box title styles
            $f_size = 'font-size: ' .get_option('ctpw_social_box_title_fontsize','20') . 'px;';
            $f_color = 'color: ' . get_option('ctpw_social_box_title_color','#000000'). ';';
            $f_weight = 'font-weight: ' . get_option('ctpw_social_box_title_fontweight','bold'). ';';
            //social titles styles
            $s_f_color = 'color: ' . get_option('ctpw_socials_titles_color','#ffffff'). ';';
            $s_f_color_hover = 'color: ' . get_option('ctpw_socials_titles_color_hover','#6d6d6d'). ';';
            $s_f_color_active = 'color: ' . get_option('ctpw_socials_titles_color_active','#dc446e'). ';';
            $s_f_color_active_hover = 'color: ' . get_option('ctpw_socials_titles_color_active_hover','#dc446e'). ';';
            //box background
            $box_background = 'background-color: ' . get_option('ctpw_socials_box_main_background','#b3b3b3'). ';';
            $box_background_selected = 'background-color: ' . get_option('ctpw_socials_box_main_background_selected','#e7e7e7'). ';';
            //arrow box and share button background and font
            $s_arr_box = 'background-color: ' . get_option('ctpw_socials_box_arrow_box_color','#b3b3b3'). ';';
            $s_share_button = 'background-color: ' . get_option('ctpw_socials_box_button_color','#b3b3b3'). ';';
            $s_share_b_font_size = 'font-size: ' .get_option('ctpw_social_box_button_title_fontsize','15') . 'px;';
            $s_share_b_font_color = 'color: ' . get_option('ctpw_socials_box_button_fontcolor','#ffffff'). ';';

            ?>
            <style>
                #yith-ctpw-social-box > h2 { <?php echo $f_color . ' ' . $f_size . ' ' . $f_weight ?> }
                .yith-ctpw-tabs-nav__link { <?php echo $s_f_color  . ' ' . $box_background ?>}
                .yith-ctpw-tabs-nav__link:hover { <?php echo $s_f_color_hover ?> }
                .yith-ctpw-tabs-nav__link.is-active { <?php echo $s_f_color_active ?> }
                .yith-ctpw-tabs-nav__link.is-active:hover { <?php echo  $s_f_color_active_hover ?> }
                .yith-ctpw-tab, .yith-ctpw-tabs-nav__link.is-active { <?php echo  $box_background_selected ?> }
                #yith-ctpw-tab_sharing_product .ctpw_share_it { <?php echo  $s_share_button ?> }
                #yith-ctpw-tab_sharing_product .ctpw_share_it a { <?php echo $s_share_b_font_size  . ' ' . $s_share_b_font_color ?>}
                p.yith-ctwp-social_navigation { <?php echo  $s_arr_box ?> }
            </style>
            <script language="JavaScript" src="<?php echo YITH_CTPW_ASSETS_URL . 'js/yith_ctpw_social_box.js'; ?>"></script>
            <?php


            //load the social box template
            wc_get_template('yith_ctpw_social_box.php', array('is_shortcode' => $is_shortcode), '', YITH_CTPW_TEMPLATE_PATH . 'woocommerce/');


        }

        /**
         * Order Review Shortcode Function
         *
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @since 1.0.0
         *
         */
        public function yith_ctpw_header_shortcode() {

            if (isset($_GET['order-received']) && get_option('yith_ctpw_show_header','yes') == 'no' && isset($_GET['ctpw']) && $_GET['ctpw'] != '') {
                $order = wc_get_order(intval($_GET['order-received']));
                ob_start();
                wc_get_template('yith_ctpw_header.php', array('order' => $order), '', YITH_CTPW_TEMPLATE_PATH . 'woocommerce/');
                $value = ob_get_contents();
                ob_end_clean();
                return $value;
            } else {
                return '';
            }
        }

        /**
         * Order Table Shortcode Function
         *
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @since 1.0.0
         *
         */
        public function yith_ctpw_table_shortcode() {

            if (isset($_GET['order-received']) && get_option('yith_ctpw_show_order_table','yes') == 'no' && isset($_GET['ctpw']) && $_GET['ctpw'] != '' ) {
                $order = wc_get_order(intval($_GET['order-received']));
                ob_start();
                wc_get_template('yith_ctpw_table.php', array('order' => $order), '', YITH_CTPW_TEMPLATE_PATH . 'woocommerce/');
                $value = ob_get_contents();
                ob_end_clean();
                return $value;
            } else {
                return '';
            }
        }

        /**
         * Order Customer Details Shortcode Function
         *
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @since 1.0.0
         *
         */
        public function yith_ctpw_customer_details_shortcode() {
            if (isset($_GET['order-received']) && get_option('yith_ctpw_show_customer_details','yes') == 'no' && isset($_GET['ctpw']) && $_GET['ctpw'] != '' ) {
                $order = wc_get_order(intval($_GET['order-received']));
                ob_start();
                wc_get_template('yith_ctpw_customer_details.php', array('order' => $order), '', YITH_CTPW_TEMPLATE_PATH . 'woocommerce/');
                $value = ob_get_contents();
                ob_end_clean();
                return $value;
            } else {
                return '';
            }
        }

        /**
         * Show Products Shortcode
         *
         * this shortcode is used to print the upsells
         *
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @since 1.0.0
         *
         * @code from plugins\woocommerce\includes\class-wc-shortcodes.php (function product loop)
         */
        public function ctpw_show_products_shortcode($atts) {
            global $woocommerce_loop;
            //check if we are on Custom Thank you page
            if ( ! isset($_GET['ctpw']) || $_GET['ctpw'] == '' ) {
                return;
            }

            $loop_name = 'product';

            $atts = shortcode_atts( array(
                'columns' => '4',
                'orderby' => 'title',
                'order'   => 'asc',
                'ids'     => '',
                'skus'    => '',
                'products_per_page' => -1
            ), $atts );

            //if we don't have both skus or ids we don't print the upsells

            if (empty($atts['ids']) && empty($atts['skus'])) { return; }

            $query_args = array(
                'post_type'           => 'product',
                'post_status'         => 'publish',
                'ignore_sticky_posts' => 1,
                'orderby'             => $atts['orderby'],
                'order'               => $atts['order'],
                'posts_per_page'      =>  $atts['products_per_page'],
                'meta_query'          => WC()->query->get_meta_query()
            );

            if ( ! empty( $atts['skus'] ) ) {
                $query_args['meta_query'][] = array(
                    'key'     => '_sku',
                    'value'   => array_map( 'trim', explode( ',', $atts['skus'] ) ),
                    'compare' => 'IN'
                );

                // Ignore catalog visibility
                $query_args['meta_query'] = array_merge( $query_args['meta_query'], WC()->query->stock_status_meta_query() );
            }

            if ( ! empty( $atts['ids'] ) ) {
                $query_args['post__in'] = array_map( 'trim', explode( ',', $atts['ids'] ) );

                // Ignore catalog visibility
                $query_args['meta_query'] = array_merge( $query_args['meta_query'], WC()->query->stock_status_meta_query() );
            }


            //APPLY_FILTER: ctpw_shortcode_products_query: change the query for upsells products shortcode: provided values ( query args, shortcode atts, loop name )
            $products                    = new WP_Query( apply_filters( 'ctpw_shortcode_products_query', $query_args, $atts, $loop_name ) );
            $columns                     = (absint( $atts['columns'] ) > 6) ? 6 : absint( $atts['columns'] ); //limit the number peer row to 6
            $woocommerce_loop['columns'] = $columns;
            $woocommerce_loop['name']    = $loop_name;

            ob_start();

            if ( $products->have_posts() ) {
                ?>

                <?php do_action( "woocommerce_shortcode_before_{$loop_name}_loop" ); ?>

                <?php woocommerce_product_loop_start(); ?>

                <?php while ( $products->have_posts() ) : $products->the_post(); ?>

                    <?php wc_get_template_part( 'content', 'product' ); ?>

                <?php endwhile; // end of the loop. ?>

                <?php woocommerce_product_loop_end(); ?>

                <?php do_action( "woocommerce_shortcode_after_{$loop_name}_loop" ); ?>

            <?php
            } else {
                do_action( "woocommerce_shortcode_{$loop_name}_loop_no_results" );
            }

            woocommerce_reset_loop();
            wp_reset_postdata();

            //getting the title style
            $f_size = 'font-size: ' .get_option('ctpw_upsells_title_fontsize','20') . 'px;';
            $f_color = 'color: ' . get_option('ctpw_upsells_title_color','#000000'). ';';
            $f_weight = 'font-weight: ' . get_option('ctpw_upsells_title_fontweight','bold'). ';';

            //title
            $before_content = '<style>#ctpw_upsells > h2 {' . $f_color . ' ' . $f_size . ' ' . $f_weight .'} </style>';
            $before_content .= '<div id="ctpw_upsells">';
            //APPLY_FILTER ctwp_upsells_title: change the upsells section title
            $before_content .= '<h2>' . apply_filters('ctwp_upsells_title',__('You may be interested in...','yith-custom-thankyou-page-for-woocommerce')) . '</h2>';

            $after_content = '</div>';

            return $before_content . '<div class="woocommerce columns-' . $columns . '">' . ob_get_clean() . '</div>' . $after_content;

        }


        /**
         * Upsell Section function
         *
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @since 1.0.0
         *
         */
        public function yith_ctpw_upsells() {
            //get all the settings
            $items_per_page = get_option('yith_ctpw_ups_ppp','4',false);
            $columns = get_option('yith_ctpw_ups_columns','4',false);
            $orderby = get_option('yith_ctpw_ups_orderby','title',false);
            $order = get_option('yith_ctpw_ups_order','asc',false);
            $product_ids = get_option('yith_ctpw_upsells_ids');

            //if the ids are not prenset the upsells section is not printed
            if (empty($product_ids)) { return; }

                if (is_array($product_ids)) {
                    $product_ids = implode(',',$product_ids);
                }
                echo do_shortcode('[ctpw_show_products products_per_page="'.$items_per_page.'" columns="'. $columns .'" order="'.$order.'" orderby="'.$orderby.'" ids="'. $product_ids .'"]');

        }

        /**
         * Social Box Shortcode
         *
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @since 1.0.0
         *
         */
        public function yith_ctpw_social_shortcode($atts) {

            global $is_shortcode;

            //check if we are on Custom Thank you page
            //if not the shortcode will not show
            //check if we are on Custom Thank you page
            if ( ! isset($_GET['ctpw']) || $_GET['ctpw'] == '' ) {
                return;
            }

            $atts = shortcode_atts( array(
                'facebook' => 'no',
                'google' => 'no',
                'twitter' => 'no',
                'pinterest' => 'no',
                'title' => '',
            ), $atts );


            //load the script for the tabs
            wp_enqueue_script('yith-ctpw-tabs');
            ob_start();
            $social_box_info = $atts;
            $is_shortcode = true;
            //get custom styles info
            //main box title styles
            $f_size = 'font-size: ' .get_option('ctpw_social_box_title_fontsize','20') . 'px;';
            $f_color = 'color: ' . get_option('ctpw_social_box_title_color','#000000'). ';';
            $f_weight = 'font-weight: ' . get_option('ctpw_social_box_title_fontweight','bold'). ';';
            //social titles styles
            $s_f_color = 'color: ' . get_option('ctpw_socials_titles_color','#ffffff'). ';';
            $s_f_color_hover = 'color: ' . get_option('ctpw_socials_titles_color_hover','#6d6d6d'). ';';
            $s_f_color_active = 'color: ' . get_option('ctpw_socials_titles_color_active','#dc446e'). ';';
            $s_f_color_active_hover = 'color: ' . get_option('ctpw_socials_titles_color_active_hover','#dc446e'). ';';
            //box background
            $box_background = 'background-color: ' . get_option('ctpw_socials_box_main_background','#b3b3b3'). ';';
            $box_background_selected = 'background-color: ' . get_option('ctpw_socials_box_main_background_selected','#e7e7e7'). ';';
            //arrow box and share button background and font
            $s_arr_box = 'background-color: ' . get_option('ctpw_socials_box_arrow_box_color','#b3b3b3'). ';';
            $s_share_button = 'background-color: ' . get_option('ctpw_socials_box_button_color','#b3b3b3'). ';';
            $s_share_b_font_size = 'font-size: ' .get_option('ctpw_social_box_button_title_fontsize','15') . 'px;';
            $s_share_b_font_color = 'color: ' . get_option('ctpw_socials_box_button_fontcolor','#ffffff'). ';';

            ?>
            <style>
                #yith-ctpw-social-box > h2 { <?php echo $f_color . ' ' . $f_size . ' ' . $f_weight ?> }
                .yith-ctpw-tabs-nav__link { <?php echo $s_f_color  . ' ' . $box_background ?>}
                .yith-ctpw-tabs-nav__link:hover { <?php echo $s_f_color_hover ?> }
                .yith-ctpw-tabs-nav__link.is-active { <?php echo $s_f_color_active ?> }
                .yith-ctpw-tabs-nav__link.is-active:hover { <?php echo  $s_f_color_active_hover ?> }
                .yith-ctpw-tab, .yith-ctpw-tabs-nav__link.is-active { <?php echo  $box_background_selected ?> }
                #yith-ctpw-tab_sharing_product .ctpw_share_it { <?php echo  $s_share_button ?> }
                #yith-ctpw-tab_sharing_product .ctpw_share_it a { <?php echo $s_share_b_font_size  . ' ' . $s_share_b_font_color ?>}
                p.yith-ctwp-social_navigation { <?php echo  $s_arr_box ?> }
            </style>

            <script language="JavaScript" src="<?php echo YITH_CTPW_ASSETS_URL . 'js/yith_ctpw_social_box.js'; ?>"></script>
            <?php
            //load the social box template
            wc_get_template('yith_ctpw_social_box.php', array('is_shortcode' => $is_shortcode, 'social_box_info' => $social_box_info), '', YITH_CTPW_TEMPLATE_PATH . 'woocommerce/');



            return ob_get_clean();
        }

        /**
         * Billing Username or User First Name Shortcode
         *
         * Returns Billing Username of the customer
         *
         * @return string
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @since 1.0.1
         *
         */
        public function yith_ctpw_customer_name_shortcode( $atts ) {
            $atts = shortcode_atts( array(
                'name' => ''
            ), $atts );


            $username = '';

            //getting order to get products infos
            $order = wc_get_order(intval($_GET['order-received']));

            if( version_compare( $this->yith_ctw_wc_version, '2.7', "<" ) ) {
                if ( $atts['name'] == 'first_name' ) {
                    //get user first name
                    $usr = wp_get_current_user();
                    $username = $usr->first_name;
                }
                else {
                    //get billing first name
                    $username = $order->billing_first_name;
                }


            } else {
                //for wc >= 2.7
                if ( $atts['name'] == 'first_name' ) {
                    //get user first name
                    $usr = wp_get_current_user();
                    $username = $usr->first_name;
                } else {
                    //get billing first name
                    $username = $order->get_billing_first_name();
                }
            }

            //APPLY_FILTER yith_ctpw_customer_name: manage name of the user for Customer Name Shortcode: provided value ( username )
            return apply_filters('yith_ctpw_customer_name', $username);
        }

        /**
         * Customer Billing Email Shortcode
         *
         * Returns the customer Billing Email
         *
         * @return string
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @since 1.0.1
         *
         */
        public function yith_ctpw_customer_email_shortcode() {
            //getting order to get products infos
            $order = wc_get_order(intval($_GET['order-received']));

            if( version_compare( $this->yith_ctw_wc_version, '2.7', "<" ) ) {
                $customer_email =$order->billing_email;
            } else {
                $customer_email = $order->get_billing_email();
            }

            //APPLY_FILTER yith_ctpw_customer_email: manage the customer email for Customer Email Shortcode: provided value ( customer email )
            return  apply_filters('yith_ctpw_customer_email', $customer_email);
        }

        /**
         * Order Number Shortcode
         *
         * Returns the Order Number
         *
         * @return string
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @since 1.0.1
         *
         */
        public function yith_ctpw_order_number_shortcode() {

            //getting order to get products infos
            $order = wc_get_order(intval($_GET['order-received']));

            //APPLY_FILTER yith_ctpw_order_number: manage the order number for Order Number Shortcode: provided value ( order number )
            $ordernumber = apply_filters('yith_ctpw_order_number',$order->get_order_number());

            return  $ordernumber;
        }


    }//end class
}