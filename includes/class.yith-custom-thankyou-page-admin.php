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

if ( ! class_exists( 'YITH_Custom_Thankyou_Page_Admin' ) ) {
    /**
     * Basic Admin Class
     *
     * @since Version 1.0.0
     * @package Yithemes
     * @author Armando Liccardo <armando.liccardo@yithemes.com>
     */
    class YITH_Custom_Thankyou_Page_Admin  {
        /**
         * @var Panel object
         */
        protected $_panel = null;


        /**
         * @var Panel page
         */
        protected $_panel_page = 'yith_ctpw_panel';

        /**
         * @var bool Show the premium landing page
         */
        public $show_premium_landing = false;

        /**
         * @var string Official plugin documentation
         */
        protected $_official_documentation = 'https://docs.yithemes.com/yith-custom-thank-you-page-for-woocommerce/' ;

        /**
         * @var string Official plugin landing page
         */
        protected $_premium_landing = 'https://yithemes.com/themes/plugins/yith-custom-thank-you-page-for-woocommerce//' ;

        /**
         * @var string Official plugin landing page
         */
        protected $_premium_live = 'https://plugins.yithemes.com/yith-custom-thank-you-page-for-woocommerce' ;

        /**
         * @var string Official plugin support page
         */
        protected $_support = 'https://wordpress.org/support/plugin/yith-custom-thank-you-page-for-woocommerce';

        /**
         * Construct
         *
         * @author  Armando Liccardo <armando.liccardo@yithemes.com>
         * @since 1.0
         */
        public function __construct(){
            /* === Register Panel Settings === */
            add_action( 'admin_menu', array( $this, 'register_panel' ), 5 );

            // Add action links & row meta links
            add_filter( 'plugin_action_links_' . plugin_basename( YITH_CTPW_PATH . '/' . basename( YITH_CTPW_FILE ) ), array( $this, 'action_links' ) );
            add_filter( 'yith_show_plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 5 );

            /* === Premium Tab === */
            add_action( 'yith_ctpw_premium_tab', array( $this, 'show_premium_landing' ) );

        }

        /**
         * Add a panel under YITH Plugins tab
         *
         * @return   void
         * @since    1.0
         * @author   Armando Liccardo <armando.liccardo@yithemes.com>
         * @use     /Yit_Plugin_Panel class
         * @see      plugin-fw/lib/yit-plugin-panel.php
         */
        public function register_panel() {

            if ( ! empty( $this->_panel ) ) {
                return;
            }

            $menu_title = 'Custom Thank You Page';

            $admin_tabs = apply_filters( 'yith_ctpw_admin_tabs', array(
                    'settings'      => __( 'Settings', 'yith-custom-thankyou-page-for-woocommerce' ),
                )
            );

            if( $this->show_premium_landing ){
                $admin_tabs['premium'] = __( 'Premium Version', 'yith-custom-thankyou-page-for-woocommerce' );
            }


            $args = array(
                'create_menu_page' => true,
                'parent_slug'      => '',
                'page_title'       => $menu_title,
                'menu_title'       => $menu_title,
                'capability'       => 'manage_options',
                'parent'           => '',
                'parent_page'      => 'yith_plugin_panel',
                'page'             => $this->_panel_page,
                'admin-tabs'       => $admin_tabs,
                'options-path'     => YITH_CTPW_OPTIONS_PATH,
                'links'            => $this->get_sidebar_link()
            );


            /* === Fixed: not updated theme/old plugin framework  === */
            if ( ! class_exists( 'YIT_Plugin_Panel_WooCommerce' ) ) {
                require_once( 'plugin-fw/lib/yit-plugin-panel-wc.php' );
            }

            $this->_panel = new YIT_Plugin_Panel_WooCommerce( $args );
        }

        /**
         * Show the premium landing page
         *
         * @author Andrea Grillo <andrea.grillo@yithemes.com>
         * @since 1.0.0
         * @return void
         */
        public function show_premium_landing(){

            if( file_exists( YITH_CTPW_PATH . 'premium/premium.php' )&& $this->show_premium_landing ){
                require_once( YITH_CTPW_PATH . 'premium/premium.php' );
            }
        }

        /**
         * Get the premium landing uri
         *
         * @since   1.0.0
         * @author  Andrea Grillo <andrea.grillo@yithemes.com>
         * @return  string The premium landing link
         */
        public function get_premium_landing_uri() {
            return defined( 'YITH_REFER_ID' ) ? $this->_premium_landing . '?refer_id=' . YITH_REFER_ID : $this->_premium_landing . '?refer_id=1030585';
        }

        /**
         * Get the panel page id
         *
         * @since   1.0.0
         * @author  Andrea Grillo <andrea.grillo@yithemes.com>
         * @return  string The premium landing link
         */
        public function get_panel_page(){
            return $this->_panel_page;
        }

        /**
         * Sidebar links
         *
         * @return   array The links
         * @since    1.0.0
         * @author   Andrea Grillo <andrea.grillo@yithemes.com>
         */
        public function get_sidebar_link(){
            $links =  array(
                array(
                    'title' => __( 'Plugin documentation', 'yith-custom-thankyou-page-for-woocommerce' ),
                    'url'   => $this->_official_documentation,
                ),
                array(
                    'title' => __( 'Help Center', 'yith-custom-thankyou-page-for-woocommerce' ),
                    'url'   => 'https://support.yithemes.com/hc/en-us/categories/202568518-Plugins',
                ),
            );

            if( defined( 'YITH_CTPW_FREE_INIT' ) ){
                $links[] = array(
                    'title' => __( 'Discover the premium version', 'yith-custom-thankyou-page-for-woocommerce' ),
                    'url'   => $this->_premium_landing,
                );

                $links[] = array(
                    'title' => __( 'Free VS Premium', 'yith-custom-thankyou-page-for-woocommerce' ),
                    'url'   => 'https://yithemes.com/themes/plugins/yith-custom-thank-you-page-for-woocommerce/#tab-free_vs_premium_tab',
                );

                $links[] = array(
                    'title' => __( 'Premium live demo', 'yith-custom-thankyou-page-for-woocommerce' ),
                    'url'   => $this->_premium_live
                );

                $links[] =  array(
                    'title' => __( 'WordPress support forum', 'yith-custom-thankyou-page-for-woocommerce' ),
                    'url'   => 'https://wordpress.org/plugins/yith-custom-thank-you-page-for-woocommerce/',
                );

                $links[] =  array(
                    'title' => sprintf( '%s (%s %s)', __( 'Changelog', 'yith-custom-thankyou-page-for-woocommerce' ), __( 'current version','yith-custom-thankyou-page-for-woocommerce' ), YITH_CTPW_VERSION ),
                    'url'   => 'https://docs.yithemes.com/yith-custom-thank-you-page-for-woocommerce/changelog/changelog-free-version/',
                );
            }

            if( defined( 'YITH_CTPW_PREMIUM' ) ){
                $links[] =  array(
                    'title' => __( 'Support platform', 'yith-custom-thankyou-page-for-woocommerce' ),
                    'url'   => 'https://yithemes.com/my-account/support/dashboard/',
                );

                $links[] =  array(
                    'title' => sprintf( '%s (%s %s)', __( 'Changelog', 'yith-custom-thankyou-page-for-woocommerce' ), __( 'current version','yith-custom-thankyou-page-for-woocommerce' ), YITH_CTPW_VERSION ),
                    'url'   => 'https://docs.yithemes.com/yith-custom-thank-you-page-for-woocommerce/changelog/changelog-premium-version/',
                );
            }

            return $links;
        }

        /**
         * Add Action Links in Plugins Page
         *
         * @param array $links
         * @since 1.0.8
         * @author Andrea Grillo <andrea.grillo@yithemes.com>
         * @return array $links
         */
        public function action_links( $links ) {
            $links = yith_add_action_links( $links, $this->_panel_page, false );
            return $links;
        }

        /**
         * Add plugin links in Plugins page
         *
         * @param array $new_row_meta_args
         * @param string $plugin_meta
         * @param string $plugin_file
         * @param string $plugin_data
         * @param string $status
         * @param string $init_file
         * @since 1.0.8
         * @author Andrea Grillo <andrea.grillo@yithemes.com>
         * @return array $new_row_meta_args
         */
        public function plugin_row_meta( $new_row_meta_args, $plugin_meta, $plugin_file, $plugin_data, $status, $init_file = 'YITH_CTPW_FREE_INIT' ) {
            if ( defined( $init_file ) && constant( $init_file ) == $plugin_file ) {
                $new_row_meta_args['slug'] = YITH_CTPW_SLUG;
            }

            return $new_row_meta_args;
        }


    } //end class

}