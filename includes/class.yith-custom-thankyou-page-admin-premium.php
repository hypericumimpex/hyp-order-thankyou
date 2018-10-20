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

/**
 *
 * @class      YITH_Custom_Thankyou_Page_Admin
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author     Armando Liccardo <armando.liccardo@yithemes.com>
 *
 */

if ( ! class_exists( 'YITH_Custom_Thankyou_Page_Admin_Premium' ) ) {
    /**
     * Class YITH_Custom_Thankyou_Page_Admin_Premium
     *
     * @author Armando Liccardo <armando.liccardo@yithemes.com>
     */
    class YITH_Custom_Thankyou_Page_Admin_Premium extends YITH_Custom_Thankyou_Page_Admin {
        /* wc version */
        public $yith_ctw_wc_version = '';

        /**
         * Construct
         *
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @since  1.0.0
         */
        public function __construct() {
            $this->yith_ctw_wc_version = $this->yith_ctpw_check_woocommerce_version();
            $this->show_premium_landing = false;

            // register plugin to licence/update system
            add_action( 'wp_loaded', array( $this, 'register_plugin_for_activation' ), 99 );
            add_action( 'admin_init', array( $this, 'register_plugin_for_updates' ) );

            /* === Premium Options === */
             add_filter( 'yith_ctpw_settings_options', array( $this, 'settings_options' ) );
             add_filter( 'yith_ctpw_admin_tabs', array( $this, 'socialbox_options'));
             add_filter( 'yith_ctpw_admin_tabs', array( $this, 'upsells_options'));
             add_filter( 'yith_ctpw_admin_tabs', array( $this, 'pdf_options'));
             add_filter( 'yith_ctpw_admin_tabs', array( $this, 'payment_gateways_options'));

            // add custom page select in main Settings Panel
            add_action('woocommerce_admin_field_yctpw_single_select_page',array($this,'add_settings_single_select_page'));
            // add payment gateways list in Payment Gateways Tab
             add_filter('yith_ctpw_payment_gateways_fields',array($this, 'yith_ctpw_add_payment_gateways_fields'));
            // add custom page select in Payment Gateways Panel
            add_action('woocommerce_admin_field_yctpw_single_select_page_payments',array($this,'yctpw_single_select_page_payments'));
            // load search for products field in Upsells tab
            add_action('woocommerce_admin_field_ctpw_product_select',array($this,'ctpw_product_select_field'),10,1);

            //add option to Single Product Tabs
            add_action( 'woocommerce_product_data_tabs', array( $this, 'yit_ctpw_add_panel_tab' ) );
            add_action( 'woocommerce_product_data_panels', array($this,'yit_ctpw_add_panel_tab_content') );
            add_action('woocommerce_process_product_meta', array($this, 'yit_ctpw_add_panel_tab_save_data'), 10, 2);

            // Add Custom Thank you page field to product Variation Settings
            add_action( 'woocommerce_product_after_variable_attributes', array($this, 'yith_ctpw_variation_settings_fields' ), 10, 3 );
            // Save Custom Thank you page field for product Variation
            add_action( 'woocommerce_save_product_variation', array($this, 'yith_ctpw_save_variation_settings_fields' ), 10, 2 );

            //add custom thank you page option field in woocommerce product categories
            add_action( 'product_cat_add_form_fields', array( $this, 'yit_ctpw_taxonomy_add_new_meta_field'), 10, 2 );
            add_action( 'product_cat_edit_form_fields', array( $this, 'yit_ctpw_taxonomy_edit_meta_field'), 10, 2 );
            add_action( 'edited_product_cat',  array( $this, 'yit_ctpw_save_taxonomy_custom_meta'), 10, 2 );
            add_action( 'create_product_cat',  array( $this, 'yit_ctpw_save_taxonomy_custom_meta'), 10, 2 );

            parent::__construct();

            //load the admin js only on this plugin page
            add_action('admin_enqueue_scripts', array($this,'yith_ctpw_load_admin_js') );

        }

        /**
         * Load Admin Script only in Plugin settings page
         *
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @since 1.0.0
         * @param string $hook
         */
        public function yith_ctpw_load_admin_js($hook){
            //load script and css file only where needed
           if ( $hook == 'yith-plugins_page_yith_ctpw_panel' || ($hook = 'term.php') && isset( $_GET['taxonomy'] ) && $_GET['taxonomy'] == 'product_cat' || ($hook = 'edit-tags.php') && isset( $_GET['taxonomy'] ) && $_GET['taxonomy'] == 'product_cat' || ($hook = 'post.php') ) {
               wp_enqueue_script('yctpw-admin', YITH_CTPW_ASSETS_URL . 'js/yith_ctpw_admin.js', array('jquery'));
               wp_register_style('yctpw-admin-style',YITH_CTPW_ASSETS_URL . 'css/admin_style.css');
               wp_enqueue_style('yctpw-admin-style');
           }
        }

        /**
         * Check woocommerce version Function
         *
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @since 1.0.0
         * @return string
         *
         */
        public function yith_ctpw_check_woocommerce_version() {
            global $woocommerce;
            if( version_compare( $woocommerce->version, '2.7.0', ">=" ) ) {
                return '2.7';
            }
            else {
                return '2.6';
            }

        }

        /**
         *  Add premium settings
         *
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @param array $old
         * @return array
         * @since 1.0.0
         */
        public function settings_options( $old ){
            $new = array(

                'settings_priority_start'    => array(
                    'type' => 'sectionstart',
                ),

                'settings_priority_options_title'    => array(
                    'title' => _x( 'Priority', 'Panel: page title', 'yith-custom-thankyou-page-for-woocommerce' ),
                    'type'  => 'title',
                    'desc'  => '',
                ),

                'settings_general_priority_thankyou_page' => array(
                    'title'   => _x( 'Select General Priority to', 'Admin option: General Priority', 'yith-custom-thankyou-page-for-woocommerce' ),
                    'type'    => 'select',
                    'desc'    => _x( 'Choose which custom Thank You page must have priority over others (General, Category or Product)',
                        'Admin option description: General Priority', 'yith-custom-thankyou-page-for-woocommerce' ),
                    'id'      => 'yith_ctpw_priority',
                    'options' => array(
                        'general' => 'General',
                        'category' => 'Product Category',
                        'product' => 'Product',
                        'payment' => 'Payment Method'
                    ),
                    'css' => 'max-width:200px;',
                    'default' => 'general',
                    'desc_tip' => _x('If you have set different custom Thank You pages for products in the cart, select which settings should have
                    priority over others (the custom Thank You page associated with single products or categories or the general one).
                    If you set it to Products and there are more products with different Thank You pages, the one associated with the first
                    product in the list will be shown.','yith-custom-thankyou-page-for-woocommerce')
                ),

                'settings_priority_end'      => array(
                    'type' => 'sectionend',
                ),

                //layout
                'settings_layout_options_start'    => array(
                    'type' => 'sectionstart',
                ),

                'settings_layout_options_title'    => array(
                    'title' => _x( 'Order Details', 'Section title in Settings', 'yith-custom-thankyou-page-for-woocommerce' ),
                    'type'  => 'title',
                    'desc'  => '',
                ),

                'settings_custom_thankyou_page_show_header' => array(
                    'title'   => _x( 'Show header section', 'Admin option', 'yith-custom-thankyou-page-for-woocommerce' ),
                    'type'    => 'checkbox',
                    'desc'    => _x( 'This section shows a main order details', 'Admin option description',
                        'yith-custom-thankyou-page-for-woocommerce' ),
                    'id'      => 'yith_ctpw_show_header',
                    'default' => 'yes'
                ),

                'settings_custom_thankyou_page_show_order_table' => array(
                    'title'   => _x( 'Show order table', 'Admin option', 'yith-custom-thankyou-page-for-woocommerce' ),
                    'type'    => 'checkbox',
                    'desc'    => _x( 'This section shows all order details', 'Admin option description',
                        'yith-custom-thankyou-page-for-woocommerce' ),
                    'id'      => 'yith_ctpw_show_order_table',
                    'default' => 'yes'
                ),

                'settings_custom_thankyou_page_show_customer_details' => array(
                    'title'   => _x( 'Show customer details', 'Admin option',
                        'yith-custom-thankyou-page-for-woocommerce' ),
                    'type'    => 'checkbox',
                    'desc'    => _x( 'This section shows customer details', 'Admin option description',
                        'yith-custom-thankyou-page-for-woocommerce' ),
                    'id'      => 'yith_ctpw_show_customer_details',
                    'default' => 'yes'
                ),



                'ctpw_styles_options_orderstyle_title_color' => array(
                    'title' => __('Title font color','yith-custom-thankyou-page-for-woocommerce'),
                    'id' => 'ctpw_orderstyle_title_color',
                    'type' => 'color',
                    'default' => '#000000',
                ),

                'ctpw_styles_options_orderstyle_title_font_size' => array(
                    'title'              => __( 'Title font size', 'yith-custom-thankyou-page-for-woocommerce' ),
                    'type'              => 'number',
                    'default'           => 20,
                    'id'                => 'ctpw_orderstyle_title_fontsize',
                    'custom_attributes' => array(
                        'min'      => 10,
                        'max'      => 50,
                    )
                ),

                'ctpw_styles_options_orderstyle_title_font_weight' => array(
                    'title'              => __( 'Title font weight', 'yith-custom-thankyou-page-for-woocommerce' ),
                    'type'              => 'select',
                    'default'           => 'bold',
                    'id'                => 'ctpw_social_orderstyle_title_fontweight',
                    'options'           => array (
                        'lighter' => 'Lighter',
                        'normal' => 'Normal',
                        'bold' => 'Bold',
                        'bolder' => 'Bolder'
                    )
                ),

                'settings_layout_options_end'      => array(
                    'type' => 'sectionend',
                ),

                //Uninstall
                'settings_uninstall_options_start'    => array(
                    'type' => 'sectionstart',
                ),

                'settings_uninstall_options_title'    => array(
                    'title' => _x( 'Uninstall', 'Section title in Settings', 'yith-custom-thankyou-page-for-woocommerce' ),
                    'type'  => 'title',
                    'desc'  => '',
                ),

                'settings_uninstall_remove_options' => array(
                    'title'   => __( 'Delete plugin options on Uninstall', 'yith-custom-thankyou-page-for-woocommerce' ),
                    'type'      => 'checkbox',
                    'desc'    => __( 'check this in order to delete plugin options saved in database on plugin uninstall', 'yith-custom-thankyou-page-for-woocommerce' ),
                    'id'      => 'yith_ctpw_uninstall_remove_options',
                    'default' => 'no'
                ),

                'settings_uninstall_options_end'      => array(
                    'type' => 'sectionend',
                ),

            );

            return array_merge( $old, $new );
        }

        /**
         * Add Custom Single Page Select in Settings Panel
         *
         * @param array field $args field arguments from settings panel
         * @return void
         * @since 1.0.6
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         */
        public function add_settings_single_select_page ( $args ) {

            //get previous saved custom page id
            $ctpw_selected_page_id = (get_option( 'yith_ctpw_general_page',true)) ? get_option( 'yith_ctpw_general_page',true) : 0;

            ?>
            <tr valign="top" class="single_select_page">
                <th scope="row" class="titledesc">
                    <label><?php echo esc_html( $args['title'] ); ?> <?php echo $args['desc_tip']; // WPCS: XSS ok. ?></label>
                </th>
                <td class="forminp">
                    <?php
                    //get woocommerce pages ids to exclude them from list
                    $avoid_pages = apply_filters('yctpw_avoid_pages', array(get_option( 'woocommerce_checkout_page_id' ), get_option( 'woocommerce_cart_page_id' ), get_option( 'woocommerce_shop_page_id' ), get_option( 'woocommerce_myaccount_page_id' ) ));
                    //args to get pages
                    $ddargs = array(
                        'name'             => $args['id'],
                        'id'               => $args['id'],
                        'sort_order' => 'asc',
                        'sort_column' => 'post_title',
                        'hierarchical' => 1,
                        'exclude' => $avoid_pages,
                        'show_option_none' => ' ',
                        'class'            => $args['class'],
                        'echo'             => false,
                        'selected'         => $ctpw_selected_page_id,
                        'post_status'      => 'publish',
                        'include' => '',
                        'meta_key' => '',
                        'meta_value' => '',
                        'authors' => '',
                        'child_of' => 0,
                        'parent' => -1,
                        'exclude_tree' => '',
                        'number' => '',
                        'offset' => 0,
                        'post_type' => 'page',
                    );
                    //print the select
                     echo str_replace( ' id=', " data-placeholder='" . esc_attr__( 'Select a page&hellip;', 'woocommerce' ) . "' style='" . $args['css'] . "' class='" . $args['class'] . "' id=", wp_dropdown_pages( $ddargs ) ); // WPCS: XSS ok. ?> <?php echo $args['desc_tip']; // WPCS: XSS ok.

                    ?>
                </td>
            </tr>
            <?php
            if (wp_script_is( 'select2', $list = 'registered' ) && wp_script_is( 'select2', $list = 'enqueued' ) ) {
                ?>
                <script type="text/javascript">
                    jQuery('#yith_product_thankyou_page').select2();

                </script>
            <?php }

        }

        /**
         * Add payment gateways list in Payment Gateways Tab
         *
         * @param array field $args field arguments from settings panel
         * @return $fields
         * @since 1.0.9
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         */
        public function yith_ctpw_add_payment_gateways_fields( $fields ) {
            $installed_payment_methods = WC()->payment_gateways->payment_gateways();

            $payment_gateways_fields = array(
                    //general
                     'ctpw_payment_gateways_options_start'    => array(
                            'type' => 'sectionstart',
                     ),

                    'ctpw_payment_gateways_options_title'    => array(
                            'title' => _x( 'Payment Methods ', 'Panel: page title', 'yith-custom-thankyou-page-for-woocommerce' ),
                            'type'  => 'title',
                            'desc'  => '',
                        ),

                    'ctpw_payment_gateways_options_end' => array(
                        'type' => 'sectionend',
                    )
            );


            foreach ( $installed_payment_methods as $paymentg ) {

                $payment_gateways_fields['ctpw_payment_gateways_start_' . $paymentg->id] = array(
                    'type' => 'sectionstart',
                );

                $payment_gateways_fields['ctpw_payment_gateways_start_' . $paymentg->id . '_title'] = array(
                    'title' => $paymentg->id,
                    'type'  => 'title',
                    'desc'  => '',
                );

                $payment_gateways_fields['ctpw_payment_gateways_page_url_' . $paymentg->id] = array (
                    'title'   => $paymentg->title,
                    'type'    => 'select',
                    'id'      => 'yith_ctpw_general_page_or_url_' . $paymentg->id,
                    'options' => array(
                        'ctpw_page'   => __( 'Custom Wordpress Page', 'yith-custom-thankyou-page-for-woocommerce' ),
                        'ctpw_url' => __( 'External URL', 'yith-custom-thankyou-page-for-woocommerce' ),
                    ),
                    'default' => 'ctpw_page',
                    'class' => 'yith_ctpw_general_page_or_url',
                    'custom_attributes' => array(
                        'ctpw_pg_id' => $paymentg->id
                    ),
                    'css' => 'min-width:300px;',
                    'desc_tip' => __('Select the General Thank You Page or External URL', 'yith-custom-thankyou-page-for-woocommerce' )
                );

                $payment_gateways_fields['ctpw_payment_gateways_thankyou_page_' . $paymentg->id] = array(
                    'type'    => 'yctpw_single_select_page_payments',
                    'id'      => 'yith_ctpw_page_for_' . $paymentg->id,
                    'sort_column' => 'title',
                    'class' => 'wc-enhanced-select-nostd',
                    'css' => 'min-width:300px;',

                );

                $payment_gateways_fields['ctpw_payment_gateways_thankyou_url_' . $paymentg->id] = array(
                    'type'    => 'text',
                    'id'      => 'yith_ctpw_url_for_' . $paymentg->id,
                    'class' => 'yith_ctpw_general_page_url',
                    'css' => 'min-width:300px;',
                    'desc' => __('write full url for ex: https://yithemes.com/', 'yith-custom-thankyou-page-for-woocommerce' )
                );

                $payment_gateways_fields['ctpw_payment_gateways_ends_' . $paymentg->id] = array(
                    'type' => 'sectionend',
                );
            }



            return array_merge( $fields, $payment_gateways_fields);
        }

        /**
         * Add Custom Page Select field in Payments Gateways Panel
         *
         * @param array field $args field arguments from settings panel
         * @return void
         * @since 1.0.9
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         */
        public function yctpw_single_select_page_payments ( $args ) {

            //get previous saved custom page id
            $ctpw_selected_page_id = (get_option( $args['id'],true)) ? get_option( $args['id'],true) : 0;

            ?>
            <tr valign="top" class="yith_payment_gateways_select_page">
                <th scope="row" class="titledesc">
                    <label><?php echo esc_html( $args['title'] ); ?> <?php echo $args['desc_tip']; // WPCS: XSS ok. ?></label>
                </th>
                <td class="forminp">
                    <?php
                    //get woocommerce pages ids to exclude them from list
                    $avoid_pages = apply_filters('yctpw_avoid_pages', array(get_option( 'woocommerce_checkout_page_id' ), get_option( 'woocommerce_cart_page_id' ), get_option( 'woocommerce_shop_page_id' ), get_option( 'woocommerce_myaccount_page_id' ) ));
                    //args to get pages
                    $ddargs = array(
                        'name'             => $args['id'],
                        'id'               => $args['id'],
                        'sort_order' => 'asc',
                        'sort_column' => 'post_title',
                        'hierarchical' => 1,
                        'exclude' => $avoid_pages,
                        'show_option_none' => ' ',
                        'class'            => $args['class'],
                        'echo'             => false,
                        'selected'         => $ctpw_selected_page_id,
                        'post_status'      => 'publish',
                        'include' => '',
                        'meta_key' => '',
                        'meta_value' => '',
                        'authors' => '',
                        'child_of' => 0,
                        'parent' => -1,
                        'exclude_tree' => '',
                        'number' => '',
                        'offset' => 0,
                        'post_type' => 'page',
                    );
                    //print the select
                    echo str_replace( ' id=', " data-placeholder='" . esc_attr__( 'Select a page&hellip;', 'woocommerce' ) . "' style='" . $args['css'] . "' class='" . $args['class'] . "' id=", wp_dropdown_pages( $ddargs ) ); // WPCS: XSS ok. ?> <?php echo $args['desc_tip']; // WPCS: XSS ok.

                    ?>
                </td>
            </tr>
            <?php
            if (wp_script_is( 'select2', $list = 'registered' ) && wp_script_is( 'select2', $list = 'enqueued' ) ) {
                ?>
                <script type="text/javascript">
                    jQuery('#yith_product_thankyou_page').select2();

                </script>
            <?php }

        }


        /**
         *  Add social box settings panel
         *
         * @param array $panels
         * @return array
         * @since 1.0.0
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         */
        public function socialbox_options( $panels ){
            $socialbox_p = array( 'socialbox' => __( 'Social Box', 'yith-custom-thankyou-page-for-woocommerce' ) );
            $panels = array_merge( $panels, $socialbox_p);
            return $panels;
        }

        /**
         * Add upsells settings panel
         *
         * @param array $panels
         * @return array
         * @since 1.0.0
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         */
        public function upsells_options( $panels){
            $upsells_p = array( 'upsells' => __( 'UpSells', 'yith-custom-thankyou-page-for-woocommerce' ) );
            $panels = array_merge( $panels, $upsells_p);
            return $panels;
        }

        /**
         * Add pdf settings panel
         *
         * @param array $panels
         * @return array
         * @since 1.0.5
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         */
        public function pdf_options( $panels){
            $pdf_p = array( 'pdf' => __( 'PDF', 'yith-custom-thankyou-page-for-woocommerce' ) );
            $panels = array_merge( $panels, $pdf_p);
            return $panels;
        }
        /**
         * Add Payment Gateways settings panel
         *
         * @param array $panels
         * @return array
         * @since 1.0.8
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         */
        public function payment_gateways_options( $panels){
            $pdf_p = array( 'payment_gateways' => __( 'Payment Methods', 'yith-custom-thankyou-page-for-woocommerce' ) );
            $panels = array_merge( $panels, $pdf_p);
            return $panels;
        }

        /**
         * Add Select 2 search for products field in Upsells Settings Tab
         *
         * @param array $value
         * @since   1.0.0
         * @author  Armando Liccardo <armando.liccardo@yithemes.com>
         */
        public function ctpw_product_select_field($value) {

            //if woocommerce select2 script is not loaded show a normal field
            if (wp_script_is( 'select2', $list = 'registered' ) && wp_script_is( 'select2', $list = 'enqueued' ) ) {
                if ( is_array(get_option('yith_ctpw_upsells_ids')) ) {
                    $product_id_to_string =  implode(',', get_option('yith_ctpw_upsells_ids'));
                } else {
                    $product_id_to_string = get_option('yith_ctpw_upsells_ids');
                }
                $product_ids[] = explode(',', $product_id_to_string );
                 $json_ids    = array();

                foreach ( $product_ids[0] as $product_id ) {
                    $product_id = absint($product_id);
                    $product = wc_get_product( $product_id );

                    if ( is_object( $product ) ) {
                        $json_ids[ $product_id ] = wp_kses_post( html_entity_decode( $product->get_formatted_name(), ENT_QUOTES, get_bloginfo( 'charset' ) ) );
                    }
                }

                if ($this->yith_ctw_wc_version == '2.7'){
                    $data_selected = $json_ids;
                    $p_values = array_keys( $json_ids );
                } else {
                    $data_selected = esc_attr( json_encode( $json_ids ) );
                    $p_values = implode( ',', array_keys( $json_ids ) );
                }
                ?>
                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>

                    </th>
                    <td>
                        <div class="data_search_wrapper">
                            <div id="product_data_search" class="panel">
                                <div class="options_group">
                                    <?php
                                    $ctpw_select2_args = array(
                                        'class' => 'wc-product-search',
                                        'id' => $value['id'],
                                        'name' => $value['id'],
                                        'data-placeholder' => __( 'Search for a product&hellip;', 'yith-custom-thankyou-page-for-woocommerce' ),
                                        'data-action' => 'woocommerce_json_search_products',
                                        'data-multiple' => true,
                                        'data-selected' => $data_selected,
                                        'value' => $p_values,
                                        'style' => 'min-width: 150px'
                                    );
                                    yit_add_select2_fields($ctpw_select2_args); ?>

                            </div>
                        </div>
                    </td></tr>
            <?php
            } else {?>
                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>

                    </th>
                    <td>
                        <div class="data_search_wrapper">
                            <div id="product_data_search" class="panel woocommerce_options_panel">
                                <div class="options_group">
                                    <input type="text"
                                           class="wc-product-search"
                                           style="width: 50%;"
                                           id="<?php echo $value['id']; ?>"
                                           name="<?php echo $value['id']; ?>"
                                           value="<?php echo get_option('yith_ctpw_upsells_ids'); ?>" />
                                    <?php echo wc_help_tip( __( 'Type product IDs separated by comma.',
                                        'yith-custom-thankyou-page-for-woocommerce' ) ); ?>
                                </div>
                            </div>
                        </div>
                    </td></tr>
            <?php
            }
        }

         /**
         * Register plugins for activation tab
         *
         * @return void
         * @since 1.0.0
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         */
        public function register_plugin_for_activation() {
            if( ! class_exists( 'YIT_Plugin_Licence' ) ){
                require_once 'plugin-fw/licence/lib/yit-licence.php';
                require_once 'plugin-fw/licence/lib/yit-plugin-licence.php';
            }

            YIT_Plugin_Licence()->register( YITH_CTPW_INIT, YITH_CTPW_SECRETKEY, YITH_CTPW_SLUG );
        }

        /**
         * Register plugins for update tab
         *
         * @return void
         * @since 1.0.0
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         */
        public function register_plugin_for_updates() {
            if( ! class_exists( 'YIT_Plugin_Licence' ) ){
                require_once( YITH_CTPW_PATH . 'plugin-fw/lib/yit-upgrade.php' );
            }

            YIT_Upgrade()->register( YITH_CTPW_SLUG, YITH_CTPW_INIT );
        }

        /**
         * Woocommerce Single Product Data Tab
         *
         * add Custom Thank you page option in Product Data Tab
         *
         * @since   1.0.0
         * @author  Armando Liccardo <armando.liccardo@yithemes.com>
         * @param   array (of product data tabs)
         * @return  array (of product data tabs)
         *
         */
        public function yit_ctpw_add_panel_tab($product_data_tabs) {
            global $post;
            $_product =  wc_get_product( $post->ID );
            /* if it is a grouped product not add the tab */
            if ($_product->get_type() != 'grouped') {
                $product_data_tabs['yith_ctpw_tab'] = array(
                    'label' => __('Custom Thank You Page', 'yith-custom-thankyou-page-for-woocommerce'),
                    'target' => 'ctpw_tab_data',
                    'class' => array('yith_ctpw_tab_class'),
                );
            }
            return $product_data_tabs;
        }

        /**
         * Woocommerce Single Product Data Panel
         *
         * add Custom Thank you page option in Product Data Panel
         *
         * @since   1.0.0
         * @author  Armando Liccardo <armando.liccardo@yithemes.com>
         * @return  void
         *
         */
        public function yit_ctpw_add_panel_tab_content() {
            global $post;
            $_product =  wc_get_product( $post->ID );

            /* if it is a grouped product not add the tab panel */
            if ($_product->get_type() == 'grouped') return;

            //select page or custom url field

            //get previous custom url if exists
            $ctpw_url_or_page = (get_post_meta( $post->ID, 'yith_ctpw_product_thankyou_page_url',true)) ? get_post_meta( $post->ID, 'yith_ctpw_product_thankyou_page_url',true) : '';
            ?>
            <div id="ctpw_tab_data" class="panel woocommerce_options_panel">

                <?php wp_nonce_field('yith_ctwp_nonce','yith_ctwp_nonce'); ?>

                <p class="form-field yith_product_thankyou_page_field yith_ctpw_product_thankyou_page">
                    <label for="yith_ctpw_product_thankyou_page_url"><?php _e( 'Custom Thank You Page or Url', 'yith-custom-thankyou-page-for-woocommerce' );?></label>
                    <select style="min-width: 250px;" name="yith_ctpw_product_thankyou_page_url" id="yith_ctpw_product_thankyou_page_url" class="yith_ctpw_product_thankyou_page_url">
                        <option value="ctpw_page" <?php echo ($ctpw_url_or_page=='ctpw_page') ? 'selected' : ''; ?>><?php _e( 'Custom Wordpress Page', 'yith-custom-thankyou-page-for-woocommerce' ) ?></option>
                        <option value="ctpw_url" <?php echo ($ctpw_url_or_page=='ctpw_url') ? 'selected="selected"' : ''; ?>><?php _e( 'Custom URL', 'yith-custom-thankyou-page-for-woocommerce' ) ?></option>
                    </select>
                </p>
                <?php
                    // custom url field
                    //get previous custom url if exists
                    $ctpw_custom_url = (get_post_meta( $post->ID, 'yith_ctpw_product_thankyou_url',true)) ? get_post_meta( $post->ID, 'yith_ctpw_product_thankyou_url',true) : '';

                ?>
                <p class="form-field yith_product_thankyou_page_field yith_ctpw_product_thankyou_page  yith_ctpw_product_thankyou_page_url">
                     <label for="yith_ctpw_product_thankyou_url"><?php _e( 'Custom URL', 'yith-custom-thankyou-page-for-woocommerce' );?></label>
                    <input type="text" name="yith_ctpw_product_thankyou_url" id="yith_ctpw_product_thankyou_url" class="yith_ctpw_product_thankyou_url" placeholder="<?php _e('write here the full url (http://...)','yith-custom-thankyou-page-for-woocommerce');?>" <?php echo ( !empty($ctpw_custom_url) ) ? 'value="'.$ctpw_custom_url.'"' : ''; ?>/>

                </p>
            <?php

                //get previous saved custom page id
                $ctpw_selected_page_id = (get_post_meta( $post->ID, 'yith_product_thankyou_page',true)) ? get_post_meta( $post->ID, 'yith_product_thankyou_page',true) : 0;

                $pages = array('0'=>'none');
                $pages = $pages + yith_ctpw_list_all_pages();

                woocommerce_wp_select(
                    array(
                        'id'=>"yith_product_thankyou_page",
                        'wrapper_class' => 'yith_ctpw_product_thankyou_page yith_ctpw_product_thankyou_page_id',
                        'label'         => __( 'Custom Thank You page', 'yith-custom-thankyou-page-for-woocommerce' ),
                        'description'   => '',
                        'value'       =>  $ctpw_selected_page_id,
                        'desc_tip'      => false,
                        'options' => $pages,
                    )
                );
                if (wp_script_is( 'select2', $list = 'registered' ) && wp_script_is( 'select2', $list = 'enqueued' ) ) {
                    ?>
                    <script type="text/javascript">
                        jQuery('#yith_product_thankyou_page').select2();
                       // jQuery('#yith_ctpw_product_thankyou_page_url').select2();
                    </script>
                <?php }
                ?>
            </div>
        <?php
        }

        /**
         * yit_ctpw_add_panel_tab_save_data
         *
         * Save Custom Thank you page/url option for product
         *
         * @since   1.0.0
         * @author  Armando Liccardo <armando.liccardo@yithemes.com>
         * @return  void
         *
         */
        //save custom thankyoupage id for current product
        public function yit_ctpw_add_panel_tab_save_data($post_id) {
            global $post;
            $_product =  wc_get_product( $post->ID );
            /* if it is a grouped product there's no tab added so no need to save data */
            if ($_product->get_type() == 'grouped') return;
            /* verify nonce field */
            if (isset($_POST['yith_ctwp_nonce']) && wp_verify_nonce( $_POST['yith_ctwp_nonce'], 'yith_ctwp_nonce')) {
                //save the options
                $ctpw_url_or_page_id = isset( $_POST['yith_ctpw_product_thankyou_page_url'] ) ? $_POST['yith_ctpw_product_thankyou_page_url'] : '';
                $ctwp_url = isset( $_POST['yith_ctpw_product_thankyou_url'] ) ? $_POST['yith_ctpw_product_thankyou_url'] : '';
                $ctpw_selected_page_id = isset( $_POST['yith_product_thankyou_page'] ) ? $_POST['yith_product_thankyou_page'] : '0';
                update_post_meta( $post_id, 'yith_product_thankyou_page', $ctpw_selected_page_id );
                update_post_meta( $post_id, 'yith_ctpw_product_thankyou_url', $ctwp_url );
                update_post_meta( $post_id, 'yith_ctpw_product_thankyou_page_url', $ctpw_url_or_page_id );
            }

        }

        /**
         * Create new Custom Thank you page field for Product variations
         *
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @since 1.0.1
         * @return void
         */
        function yith_ctpw_variation_settings_fields( $loop, $variation_data, $variation ) {
            //get previous custom url if exists
            $ctpw_url_or_page = (get_post_meta( $variation->ID, 'yith_ctpw_product_thankyou_page_url',true)) ? get_post_meta( $variation->ID, 'yith_ctpw_product_thankyou_page_url',true) : '';

            ?>
            <div class="yith_ctpw item_<?php echo $loop ?>" ctpw_item="item_<?php echo $loop ?>">
                    <hr />
                  <label for="yith_ctpw_product_thankyou_page_url"><?php _e( 'Custom Thank You Page or Url', 'yith-custom-thankyou-page-for-woocommerce' );?></label>
                  <select style="min-width: 250px;" name="yith_ctpw_product_thankyou_page_url[<?php echo $loop; ?>]" id="yith_ctpw_product_thankyou_page_url[<?php echo $loop; ?>]" class="yith_ctpw_product_thankyou_page_url">
                      <option value="ctpw_page" <?php echo ($ctpw_url_or_page=='ctpw_page') ? 'selected' : ''; ?>><?php _e( 'Custom Wordpress Page', 'yith-custom-thankyou-page-for-woocommerce' ) ?></option>
                      <option value="ctpw_url" <?php echo ($ctpw_url_or_page=='ctpw_url') ? 'selected="selected"' : ''; ?>><?php _e( 'Custom URL', 'yith-custom-thankyou-page-for-woocommerce' ) ?></option>
                  </select>

                  <?php
                  $ctpw_selected_page_var_id = (get_post_meta( $variation->ID, 'yith_product_thankyou_page_variation',true)) ? get_post_meta( $variation->ID, 'yith_product_thankyou_page_variation',true) : 0;

                  $pages = array('0'=>'none');
                  $pages = $pages + yith_ctpw_list_all_pages();

                  woocommerce_wp_select(
                      array(
                          'id'=>'yith_product_thankyou_page_variation['.$loop.']',
                          'wrapper_class' => 'yith_ctpw_product_thankyou_page',
                          'label'         => __( 'Custom Thank You page', 'yith-custom-thankyou-page-for-woocommerce' ),
                          'description'   => __( 'Select the custom Thank You page!', 'yith-custom-thankyou-page-for-woocommerce' ),
                          'value'       =>  $ctpw_selected_page_var_id,
                          'desc_tip'      => false,
                          'options' => $pages,
                      )
                  );
                  ?>

                    <?php
                    // custom url field
                    //get previous custom url if exists
                    $ctpw_custom_url = (get_post_meta( $variation->ID, 'yith_ctpw_product_thankyou_url',true)) ? get_post_meta( $variation->ID, 'yith_ctpw_product_thankyou_url',true) : '';

                    woocommerce_wp_text_input(
                        array(
                            'id'=>'yith_ctpw_product_thankyou_url['.$loop.']',
                            'wrapper_class' => 'yith_ctpw_product_thankyou_url',
                            'label'         => __( 'Custom URL', 'yith-custom-thankyou-page-for-woocommerce'),
                            'value'       =>  $ctpw_custom_url,
                            'desc_tip'      => false,
                            'placeholder' => __( 'Write full URL ex: http://...', 'yith-custom-thankyou-page-for-woocommerce' ),
                        )
                    );
                    ?>
                <?php /* add wp_nonce */
                wp_nonce_field('yith_ctwp_nonce','yith_ctwp_nonce');
                ?>

            </div>

        <?php
        }

        /**
         * Save Custom Thank yuo page for variations
         *
         * @author @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @since 1.0.1
         * @return void
         */
        function yith_ctpw_save_variation_settings_fields( $variation_id, $i )
        {
            if (isset($_POST['yith_ctwp_nonce']) && wp_verify_nonce( $_POST['yith_ctwp_nonce'], 'yith_ctwp_nonce')) {
                    update_post_meta($variation_id, 'yith_ctpw_product_thankyou_page_url', stripslashes($_POST['yith_ctpw_product_thankyou_page_url'][$i]));
                    update_post_meta($variation_id, 'yith_product_thankyou_page_variation', stripslashes($_POST['yith_product_thankyou_page_variation'][$i]));
                    update_post_meta($variation_id, 'yith_ctpw_product_thankyou_url', stripslashes($_POST['yith_ctpw_product_thankyou_url'][$i]));
            }

        }

        /**
         * yit_ctpw_taxonomy_add_new_meta_field
         *
         * adding Custom Thank you Page options in a new product category page screen
         *
         * @return void
         * @since 1.0.0
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         */
        function yit_ctpw_taxonomy_add_new_meta_field() {
            //preparing pages array
            $pages = array('0'=>'none');
            $pages = $pages + yith_ctpw_list_all_pages();

            //print the fields
            ?>
            <div class="form-field yith_ctpw_cat yith_ctpw_or_url">

                <?php wp_nonce_field('yith_ctwp_cat_nonce','yith_ctwp_cat_nonce'); ?>

                <label for="yith_ctpw_product_cat_thankyou_page"><?php _e( 'Custom Thank You Page or Url', 'yith-custom-thankyou-page-for-woocommerce' );?></label>
                <select style="min-width: 250px;" name="yith_ctpw_or_url_product_cat_thankyou_page" id="yith_ctpw_or_url_product_cat_thankyou_page" class="yith_ctpw_or_url_product_cat_thankyou_page">
                    <option value="ctpw_page"><?php _e( 'Custom Wordpress Page', 'yith-custom-thankyou-page-for-woocommerce' ) ?></option>
                    <option value="ctpw_url"><?php _e( 'Custom URL', 'yith-custom-thankyou-page-for-woocommerce' ) ?></option>
                </select>
            </div>
            <div class="form-field yith_ctpw_cat yith_ctpw_page">
                <div>
                    <label for="yith_ctpw_product_cat_thankyou_page"><?php _e( 'Custom Thank You Page', 'yith-custom-thankyou-page-for-woocommerce' );?></label>
                    <select style="min-width: 250px;" name="yith_ctpw_product_cat_thankyou_page" id="yith_ctpw_product_cat_thankyou_page" class="yith_ctpw_product_cat_thankyou_page">
                            <?php
                                   foreach($pages as $p_index => $p_name) {
                                       echo '<option value="'. $p_index .'" >' . $p_name . '</option>';
                                   }
                            ?>
                          </select>
                    <?php
                    //if wp is loading select2 js we add the style to the field
                    if (wp_script_is( 'select2', $list = 'registered' ) && wp_script_is( 'select2', $list = 'enqueued' ) ) {
                        ?>
                        <script type="text/javascript">
                            jQuery('#yith_ctpw_product_cat_thankyou_page').select2();
                        </script>
                    <?php } ?>
                </div>
            </div>
            <div class="form-field yith_ctpw_cat yith_ctpw_url">
                <div>
                    <label for="yith_ctpw_url_product_cat_thankyou_page"><?php _e( 'Custom URL', 'yith-custom-thankyou-page-for-woocommerce' );?></label>
                    <input type="text" name="yith_ctpw_url_product_cat_thankyou_page" id="yith_ctpw_url_product_cat_thankyou_page" class="yith_ctpw_url_product_cat_thankyou_page" placeholder="<?php _e('write here the full url (http://...)','yith-custom-thankyou-page-for-woocommerce');?>" />

                </div>
            </div>
            <?php
        }

        /**
         *
         * adding Custom Thank you Page options in the product category editing page
         *
         * @param array $term
         * @return void
         * @since 1.0.0
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         */
        function yit_ctpw_taxonomy_edit_meta_field($term) {

            // put the term ID into a variable
            $ctpw_cat_id = $term->term_id;

            // retrieve the existing values for this meta fields
            $ctpw_or_url = get_term_meta($ctpw_cat_id,'yith_ctpw_or_url_product_cat_thankyou_page',true);
            $actual_ctpw = get_term_meta($ctpw_cat_id,'yith_ctpw_product_cat_thankyou_page',true);
            $actual_url = get_term_meta($ctpw_cat_id,'yith_ctpw_url_product_cat_thankyou_page',true);


            $ctpw_selected_page_id = ($actual_ctpw) ? $actual_ctpw : 0;

            //preparing the $pages array
            $pages = array('0'=>'none');
            $pages = $pages + yith_ctpw_list_all_pages();

            //print the fields
            ?>
            <tr class="form-field yith_ctpw_cat yith_ctpw_or_url">
                <th scope="row" valign="top"><label for="yith_ctpw_or_url_product_cat_thankyou_page"><?php _e( 'Custom Thank You Page or Url', 'yith-custom-thankyou-page-for-woocommerce' ); ?></label></th>
                <td>
                    <select style="min-width: 250px;" name="yith_ctpw_or_url_product_cat_thankyou_page" id="yith_ctpw_or_url_product_cat_thankyou_page" class="yith_ctpw_or_url_product_cat_thankyou_page">
                        <option value="ctpw_page" <?php echo ($ctpw_or_url == 'ctpw_page') ? 'selected' : ''; ?>><?php _e( 'Custom Wordpress Page', 'yith-custom-thankyou-page-for-woocommerce' ) ?></option>
                        <option value="ctpw_url"  <?php echo ($ctpw_or_url == 'ctpw_url') ? 'selected' : ''; ?>><?php _e( 'Custom URL', 'yith-custom-thankyou-page-for-woocommerce' ) ?></option>
                    </select>
                    <?php wp_nonce_field('yith_ctwp_cat_nonce','yith_ctwp_cat_nonce'); ?>
                </td>
            </tr>
            <tr class="form-field yith_ctpw_cat yith_ctpw_cat_page">
                <th scope="row" valign="top"><label for="yith_ctpw_product_cat_thankyou_page"><?php _e( 'Custom Thank You Page', 'yith-custom-thankyou-page-for-woocommerce' ); ?></label></th>
                <td>
                    <select style="min-width: 250px;" name="yith_ctpw_product_cat_thankyou_page" id="yith_ctpw_product_cat_thankyou_page" class="yith_ctpw_product_cat_thankyou_page">
                        <?php
                               foreach($pages as $p_index => $p_name) {
                                   $selected =  ($ctpw_selected_page_id == $p_index) ? 'selected' : '';
                                   echo '<option value="'. $p_index .'" ' . $selected . ' >' . $p_name . '</option>';
                               }
                        ?>
                      </select>
                    <?php
                    //if wp is loading select2 js we add the style to the field
                    if (wp_script_is( 'select2', $list = 'registered' ) && wp_script_is( 'select2', $list = 'enqueued' ) ) {
                        ?>
                        <script type="text/javascript">
                            jQuery('#yith_ctpw_product_cat_thankyou_page').select2();
                        </script>
                    <?php }

                        ?>

                    </td>
            </tr>
            <tr class="form-field yith_ctpw_cat yith_ctpw_cat_url">
            <th scope="row" valign="top"><label for="yith_ctpw_url_product_cat_thankyou_page"><?php _e( 'Custom Thank You Url', 'yith-custom-thankyou-page-for-woocommerce' ); ?></label></th>
            <td>
                <input type="text" name="yith_ctpw_url_product_cat_thankyou_page" id="yith_ctpw_url_product_cat_thankyou_page" class="yith_ctpw_url_product_cat_thankyou_page" placeholder="<?php _e('write here the full url (http://...)','yith-custom-thankyou-page-for-woocommerce');?>" <?php echo ($actual_url) ? 'value="'. $actual_url.'"' : ''; ?> />
            </td>
            </tr>
        <?php
        }

        /**
         * Save Custom Thank you Page option for Product Category
         *
         * Save the Custom Thank you Page ID or URL for current Product Category
         *
         * @param $term_id
         * @return void
         * @since 1.0.0
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         */
         function yit_ctpw_save_taxonomy_custom_meta( $term_id ) {

             /* verify nonce field then save the options */
             if ( isset( $_POST['yith_ctwp_cat_nonce'] ) && wp_verify_nonce( $_POST['yith_ctwp_cat_nonce'], 'yith_ctwp_cat_nonce') ) {

                 if ( isset( $_POST['yith_ctpw_or_url_product_cat_thankyou_page'] ) ) {
                     update_term_meta(
                         $term_id,
                         'yith_ctpw_or_url_product_cat_thankyou_page',
                         $_POST['yith_ctpw_or_url_product_cat_thankyou_page']
                     );
                 }
                 if ( isset( $_POST['yith_ctpw_product_cat_thankyou_page'] ) ) {
                    update_term_meta(
                        $term_id,
                        'yith_ctpw_product_cat_thankyou_page',
                       $_POST['yith_ctpw_product_cat_thankyou_page']
                    );
                 }
                 if ( isset( $_POST['yith_ctpw_url_product_cat_thankyou_page'] ) ) {
                     update_term_meta(
                         $term_id,
                         'yith_ctpw_url_product_cat_thankyou_page',
                         trim($_POST['yith_ctpw_url_product_cat_thankyou_page'])
                     );
                 }
             }

        }

        public function plugin_row_meta( $new_row_meta_args, $plugin_meta, $plugin_file, $plugin_data, $status, $init_file = 'YITH_CTPW_INIT' ) {
            $new_row_meta_args = parent::plugin_row_meta( $new_row_meta_args, $plugin_meta, $plugin_file, $plugin_data, $status, $init_file );

            if ( defined( $init_file ) && constant( $init_file ) == $plugin_file ){
                $new_row_meta_args['is_premium'] = true;
            }

            return $new_row_meta_args;
        }

        public function action_links( $links ) {
            $links = yith_add_action_links( $links, $this->_panel_page, true );
            return $links;
        }


    } //end class
}