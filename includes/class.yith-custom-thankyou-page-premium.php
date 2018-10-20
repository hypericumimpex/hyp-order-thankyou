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

if ( ! class_exists( 'YITH_Custom_Thankyou_Page_Premium' ) ) {
    /**
     * Main Plugin Class Premium
     *
     * @since  Version 1.0.0
     * @author Armando Liccardo <armando.liccardo@yithemes.com>
     */
    class YITH_Custom_Thankyou_Page_Premium extends YITH_Custom_Thankyou_Page {

        /**
         * Construct
         *
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @since 1.0.0
         */
        public function __construct(){
            /* === Premium Initializzation === */
            add_filter( 'yith_ctpw_require_class', array( $this, 'load_premium_classes' ) );

            parent::__construct();
        }

        /**
         * Main plugin Instance
         *
         * @return YITH_Custom_Thankyou_Page_Premium
         * @author Andrea Grillo <andrea.grillo@yithemes.com>
         * @since 1.0.0
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Class Initializzation
         *
         * Instance the admin or frontend classes
         *
         * @author Andrea Grillo <andrea.grillo@yithemes.com>
         * @since  1.0.0
         * @return void
         * @access protected
         */
        public function init() {
            if ( is_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_REQUEST['context'] ) && $_REQUEST['context'] == 'frontend' ) ) {
                $this->admin = new YITH_Custom_Thankyou_Page_Admin_Premium();
            }

            elseif( $this->is_plugin_enabled ) {
                $this->frontend = new YITH_Custom_Thankyou_Page_Frontend_Premium();
            }

            if ( defined( 'DOING_AJAX' )  ) {
                $this->frontend = new YITH_Custom_Thankyou_Page_Frontend_Premium();
            }
        }

        /**
         * Add premium files to Require array
         *
         * @param $require The require files array
         *
         * @return Array
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @since 1.0.0
         *
         */
        public function load_premium_classes( $require ){
            $require['admin'][]     = 'includes/class.yith-custom-thankyou-page-admin-premium.php';
            $require['frontend'][]  = 'includes/class.yith-custom-thankyou-page-frontend-premium.php';
         //   $require['common'][]    = 'includes/functions.yith-ctpw-premium.php';
            return $require;
        }

    } //end class
}