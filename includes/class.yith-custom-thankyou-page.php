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

if ( ! class_exists( 'YITH_Custom_Thankyou_Page' ) ) {

    /**
     * Main Plugin Class
     *
     * @var string
     * @since 1.0
     * @author Armando Liccardo <armando.liccardo@yithemes.com>
     */
    class YITH_Custom_Thankyou_Page {

        /**
         * Plugin Version
         *
         * @var string plugin version
         * @since 1.0.0
         */
        public $version = YITH_CTPW_VERSION;

        /**
         * Main Instance
         *
         * @var YITH_Custom_Thankyou_Page
         * @since 1.0.0
         * @access protected
         */
        protected static $_instance = null;

        /**
         * Main Admin Instance
         *
         * @var YITH_Custom_Thankyou_Page_Admin
         * @since 1.0.0
         */
        public $admin = null;

        /**
         * Main Frontpage Instance
         *
         * @var YITH_Custom_Thankyou_Page_Frontend
         * @since 1.0
         */
        public $frontend = null;

        /**
         * check if the plugin is activated or not
         *
         * @var bool
         * @since 1.0
         */
        public $is_plugin_enabled = false;


        /**
         * Construct
         *
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @since 1.0.0
         */
        public function __construct(){

            $this->is_plugin_enabled = 'yes' == get_option( 'yith_ctpw_enable', 'no' );

            /* === Require Main Files === */
            $require = apply_filters( 'yith_ctpw_require_class',
                array(
                    'common'    => array(
                        'includes/functions.yith-ctpw-common.php'
                    ),
                    'admin'     => array(
                        'includes/class.yith-custom-thankyou-page-admin.php',
                      //  'includes/functions.yith-update.php' da verificare
                    ),
                    'frontend'  => array(
                        'includes/class.yith-custom-thankyou-page-frontend.php',
                        'includes/class.yith-custom-thankyou-page-pdf.php'
                    ),
                )
            );

            $this->_require( $require );

            /* === Load Plugin Framework === */
            add_action( 'plugins_loaded', array( $this, 'plugin_fw_loader' ), 15 );
            add_filter( 'body_class', array( $this, 'body_class' ) );

            /* == Plugins Init === */
            add_action( 'init', array( $this, 'init' ) );
        }

        /**
         * Main plugin Instance
         *
         * @return YITH_Custom_Thankyou_Page Main instance
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Add the main classes file
         *
         * Include the admin and frontend classes
         *
         * @param $main_classes array The required classes file path
         *
         * @author Andrea Grillo <andrea.grillo@yithemes.com>
         * @since  1.0
         *
         * @return void
         * @access protected
         */
        protected function _require( $main_classes ) {
            foreach ( $main_classes as $section => $classes ) {
                foreach ( $classes as $class ) {
                    if ( 'common' == $section  || ( 'frontend' == $section && ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) || ( 'admin' == $section && is_admin() ) && file_exists( YITH_CTPW_PATH . $class ) ) {
                        require_once( YITH_CTPW_PATH . $class );
                    }
                }
            }
        }

        /**
         * Load plugin framework
         *
         * @author Andrea Grillo <andrea.grillo@yithemes.com>
         * @since  1.0
         * @return void
         */
        public function plugin_fw_loader() {
            if ( !defined( 'YIT_CORE_PLUGIN' ) ) {
                global $plugin_fw_data;
                if ( !empty( $plugin_fw_data ) ) {
                    $plugin_fw_file = array_shift( $plugin_fw_data );
                    require_once( $plugin_fw_file );
                }
            }
        }

        /**
         * Class Initialization
         *
         * Instance the admin or frontend classes
         *
         * @author Andrea Grillo <andrea.grillo@yithemes.com>
         * @since  1.0
         * @return void
         * @access protected
         */
        public function init() {

            if ( is_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_REQUEST['context'] ) && $_REQUEST['context'] == 'frontend' ) ) {
                $this->admin = new YITH_Custom_Thankyou_Page_Admin();
            }

            elseif( $this->is_plugin_enabled ) {
                $this->frontend = new YITH_Custom_Thankyou_Page_Frontend();
            }
        }

        /**
         * Add a body class(es)
         *
         * @param $classes The classes array
         *
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @since 1.0
         * @return array
         */
        public function body_class( $classes ){
            $classes[] = 'yith-ctpw';
            return $classes;
        }
    }


    }
