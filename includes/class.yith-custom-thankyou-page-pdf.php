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

if ( ! class_exists( 'YITH_Custom_Thankyou_Page_PDF' ) ) {
    /**
     * Print Order Details as PDF
     *
     * @class       YITH_Custom_Thankyou_Page_Premium
     * @package     YITH Custom ThankYou Page for Woocommerce
     * @author      YITH
     * @since       1.0.5
     */
    class YITH_Custom_Thankyou_Page_PDF {

        protected static $_instance = null;

        /**
         * Construct function
         *
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @since 1.0.5
         */
        public function __construct() {
            /* load mpdf lib */
            if ( ! class_exists( 'mPDF' ) ) {
                require( YITH_CTPW_LIB_DIR . 'mpdf60/mpdf.php' );
            }

            /* register the frontend script to call it in yith_ctpw_pdf_button */
            wp_register_script('yith-ctpw-front', YITH_CTPW_ASSETS_URL . 'js/yith_ctpw_front.js', array('jquery') );

            //if the user set to use the button as shortcode we not add it automatically
            //but we will register it as shortcode
            $use_as_shortcode = get_option('yith_ctpw_enable_pdf_as_shortcode','no');
            if ( $use_as_shortcode == 'no') {
                /* print the PDF button */
                add_action( 'yith_ctpw_successful_ac', array( $this, 'yith_ctpw_pdf_button'), 35 );
            } else {
                add_shortcode( 'yith_ctpw_pdf_button', array( $this, 'yith_ctpw_pdf_button_shortcode') );
            }

            /* add ajax function callback to get pdf */
            add_action( 'wp_ajax_yith_ctpw_get_pdf', array( $this, 'yith_ctpw_get_pdf'));
            add_action( 'wp_ajax_nopriv_yith_ctpw_get_pdf', array( $this, 'yith_ctpw_get_pdf'));

            //PDF document actions
            //pdf style
            add_action('yith_ctpw_pdf_template_head', array( $this, 'yith_ctpw_add_pdf_styles'), 10);

            //header logo
            if ( get_option('yith_ctpw_pdf_show_logo','no') != 'no' && get_option('yith_ctpw_pdf_custom_logo','') != '' ) {
                add_action('yith_ctpw_template_document_header', array( $this, 'yith_ctpw_add_pdf_logo'), 10  );
            }
            //order header
            if ( get_option('yith_ctpw_pdf_show_order_header') != 'no' && get_option('yith_ctpw_pdf_show_order_header') != '' ) {
                add_action('yith_ctpw_template_order_content', array( $this, 'yith_ctpw_add_pdf_order_infos_header'), 10 );
            }

            //order details table
            if ( get_option('yith_ctpw_pdf_show_order_details_table') != 'no' && get_option('yith_ctpw_pdf_show_order_details_table') != '' ) {
                add_action('yith_ctpw_template_order_content', array( $this, 'yith_ctpw_add_pdf_order_infos_table'), 15 );
            }

            //footer
            if ( trim(get_option('yith_ctpw_pdf_footer_text')) != '' ) {
                add_action('yith_ctpw_template_footer', array( $this, 'yith_ctpw_add_pdf_footer_text'), 10 );
            }


        }

        /**
         * Get Class Main Instance
         *
         * @since 1.0.5
         * @return YITH_Custom_Thankyou_Page_PDF
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Set a maximum execution time
         *
         * @param int $seconds time in seconds
         * @since 1.0.5
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         */
        private function set_time_limit( $seconds ) {
            $check_safe_mode = ini_get( 'safe_mode' );
            if ( ( ! $check_safe_mode ) || ( 'OFF' == strtoupper( $check_safe_mode ) ) ) {

                @set_time_limit( $seconds );
            }
        }

        /**
         * Load frontend script for PDF button
         *
         * @since 1.0.9
         * @return void
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         */
        public function yith_ctpw_pdf_script() {
            /*load the frotend script*/
            wp_enqueue_script('yith-ctpw-front');

            /* provide some values for the script*/
            $localize_array_datas = array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'pdf_creator' => YITH_CTPW_URL . 'includes/pdf_creator.php',
                'order_id' =>  intval($_GET['order-received']),
                'file_name' => apply_filters('yith_ctpw_pdf_file_name', 'yctpw.pdf'),
                'loading_gif' => YITH_CTPW_ASSETS_URL . 'images/preloader.gif'
            );
            wp_localize_script( 'yith-ctpw-front', 'yith_ctpw_ajax', $localize_array_datas );
        }

        /**
         * Print the PDF button on checkout success
         *
         * @since 1.0.5
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @return void
         */
        public function yith_ctpw_pdf_button() {

            $this->yith_ctpw_pdf_script();

            $style = $this->yith_ctpw_get_button_styles();

            /* printing the button */
            $button_label = get_option('yith_ctpw_pdf_button_label', __('Save as PDF','yith-custom-thankyou-page-for-woocommerce') );
            echo $style . '<button id="yith_ctwp_pdf_button">' . apply_filters('yith_ctpw_pdf_button_label', $button_label) . '</button>';

        }

        /**
         * Get pdf ajax call
         *
         * @since 1.0.5
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @return array $result json encoded
         */
        public function yith_ctpw_get_pdf( ) {
            $this->set_time_limit( 120 );

            $result = array( 'status' => false, 'file' => '');

            if ( isset($_POST['order_id']) && $_POST['order_id'] != 0 ) {
                $order = wc_get_order($_POST['order_id']);

                ob_start();
                wc_get_template( 'order_infos_pdf_template.php',
                    array(
                        'order'   => $order,
                        'main_class' => apply_filters( 'yith_ctpw_ftp_template_add_body_class', '' ),
                    ),
                    '',
                    YITH_CTPW_PDF_TEMPLATE_PATH );


                $html = ob_get_clean();
                $html = apply_filters( 'yith_ctpw_before_pdf_rendering_html', $html, $order );

                $mpdf = new mPDF();
                // write html

                $mpdf->WriteHTML( $html, 0 );

                //store the entire PDF as a string in $pdf
                $pdf = $mpdf->Output('', 'S');

                //construct the pdf filename and path
                $up_dir = wp_upload_dir();
                $folder_path = trailingslashit( $up_dir['basedir'] ) . 'ctpw_tmp/';
                $filename = 'ctpw_order_' . $order->get_id() . '.pdf';


                //check if the pdf temp folder path exists, if not create it
                if ( ! is_dir($folder_path) ) {
                    mkdir($folder_path);
                }

                //write the pdf
                if ( file_put_contents( $folder_path . $filename, $pdf ) ) {
                    $result['status'] = true;
                    $result['file'] = $folder_path . $filename;
                }

            }

            echo json_encode($result);

            wp_die();
        }

        /**
         * Get Pdf Button Css from plugin settings
         *
         * @param
         * @since 1.0.9
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @return string css codes
         */
        public function yith_ctpw_get_button_styles() {
            $style = '<style>';
            $back_color = get_option('yith_ctpw_pdf_button_back_color', true);
            $back_color_hover = get_option('yith_ctpw_pdf_button_back_color_hover', true);
            $text_color = get_option('yith_ctpw_pdf_button_text_color', true);
            $text_color_hover = get_option('yith_ctpw_pdf_button_text_color_hover', true);

            $style .= "#yith_ctwp_pdf_button {";
            if ( $back_color != 'none' && $back_color != '' ) {
                $style .= 'background-color: ' . $back_color . '; ';
            }

            if ( $text_color != 'none' && $text_color != '' ) {
                $style .= 'color: ' . $text_color . ';';
            }

            $style .= "}";
            $style .= ' #yith_ctwp_pdf_button:hover {';

            if ( $back_color_hover != 'none' && $back_color_hover != '' ) {
                $style .= 'background-color: ' . $back_color_hover . ';';
            }

            if ( $text_color_hover != 'none' && $text_color_hover != '' ) {
                $style .= 'color: ' . $text_color_hover . ';';
            }

            $style .= "}";
            $style .= '</style>';

            return $style;
        }

        /*
         * Shortcode to print PDF button
         *
         * @since 1.0.8
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @return void
         */
        public function yith_ctpw_pdf_button_shortcode() {
            /*load the frotend script*/
            $this->yith_ctpw_pdf_script();

            $style = $this->yith_ctpw_get_button_styles();

            /* printing the button */
            $button_label = get_option('yith_ctpw_pdf_button_label', __('Save as PDF','yith-custom-thankyou-page-for-woocommerce') );
            return $style . '<button id="yith_ctwp_pdf_button">' . apply_filters('yith_ctpw_pdf_button_label', $button_label) . '</button>';
        }

        /**
         * Add styles to pdf template
         *
         * @since 1.0.5
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @return void
         */
        public function yith_ctpw_add_pdf_styles() {
            ob_start();
            echo '<style>';
            wc_get_template( 'ctpw_pdf_styles.css', '' , '', YITH_CTPW_PDF_TEMPLATE_PATH );
            echo '</style>';
            echo ob_get_clean();
        }

        /**
         * Add logo to pdf header
         *
         * @since 1.0.5
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @return void
         */
        public function yith_ctpw_add_pdf_logo() {
            $logo_url = get_option('yith_ctpw_pdf_custom_logo','');
            if ( empty( $logo_url ) ) { return; }

            $logo_max_width = apply_filters('yith_ctpw_pdf_logo_max_width', get_option('yith_ctpw_pdf_custom_logo_max_size' , '90'));

            ob_start();
            ?>

            <header><div id="main_logo"><img src="<?php echo $logo_url; ?>" style="max-width: <?php echo $logo_max_width ?>px;" /></div></header>

            <?php
            echo ob_get_clean();
        }

        /**
         * Add header order part to pdf template
         *
         * @param $order wc order
         * @since 1.0.5
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @return void
         */
        public function yith_ctpw_add_pdf_order_infos_header( $order ) {

            ob_start();
            wc_get_template( 'order_infos_pdf_template_order_header.php',
            array(
            'order'   => $order,
            ),
            '',
            YITH_CTPW_PDF_TEMPLATE_PATH );

            echo ob_get_clean();
        }
        /**
         * Add order table to pdf template
         *
         * @param $order wc order
         * @since 1.0.5
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @return void
         */
        public function yith_ctpw_add_pdf_order_infos_table ( $order ) {
            ob_start();
            wc_get_template( 'order_infos_pdf_template_order_table.php',
                array(
                    'order'   => $order,
                ),
                '',
                YITH_CTPW_PDF_TEMPLATE_PATH );

            echo ob_get_clean();
        }

        /**
         * Add footer text from plugin options to pdf template
         *
         * @param $order wc order
         * @since 1.0.5
         * @author Armando Liccardo <armando.liccardo@yithemes.com>
         * @return void
         */
        public function yith_ctpw_add_pdf_footer_text( $order ) {
            echo '<div id="footer_text">';
            echo trim(get_option('yith_ctpw_pdf_footer_text'));
            echo '</div>';
        }

    } //end class

} //end if class_exists