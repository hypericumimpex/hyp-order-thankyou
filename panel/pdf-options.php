<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */


return array(

    'pdf' => apply_filters( 'yith_ctpw_pdf_options', array(

            //general
            'ctpw_pdf_options_start'    => array(
                'type' => 'sectionstart',
            ),

            'ctpw_pdf_options_title'    => array(
                'title' => _x( 'PDF Settings ', 'Panel: page title', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'  => 'title',
                'desc'  => '',
            ),

            'ctpw_enable_pdf' => array(
                'title'   => _x( 'Enable PDF button', 'Admin option', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'      => 'yith-field',
                'yith-type' => 'checkbox',
                'desc'    => _x( 'Check this option to show the PDF button on Custom Thank You Page', 'Admin option description',
                    'yith-custom-thankyou-page-for-woocommerce' ),
                'id'      => 'yith_ctpw_enable_pdf',
                'default' => 'no'
            ),

            'ctpw_pdf_button_type' => array(
                'title'   => _x( 'Use as Shortcode', 'Admin option', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'      => 'yith-field',
                'yith-type' => 'checkbox',
                'desc'    => _x( 'If this option is checked the button will show only when you add the shortcode to the page [yith_ctpw_pdf_button]', 'Admin option description',
                    'yith-custom-thankyou-page-for-woocommerce' ),
                'id'      => 'yith_ctpw_enable_pdf_as_shortcode',
                'default' => 'no'
            ),

            'ctpw_button_label' => array(
                'title'   => _x( 'PDF Button Label', 'Admin option', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'      => 'yith-field',
                'yith-type' => 'text',
                'desc'    => _x( 'set the PDF button label', 'Admin option description',
                    'yith-custom-thankyou-page-for-woocommerce' ),
                'id'      => 'yith_ctpw_pdf_button_label',
                'default' => __('Save as PDF','yith-custom-thankyou-page-for-woocommerce')
            ),

            'ctpw_button_back_color' => array (
                'title'   => _x( 'PDF Button Background Color', 'Admin option', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'      => 'yith-field',
                'yith-type' => 'colorpicker',
                'id'        => 'yith_ctpw_pdf_button_back_color',
                'default'   => '#000000'
            ),

            'ctpw_button_back_color_hover' => array (
                'title'   => _x( 'PDF Button Background Color on Hover', 'Admin option', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'      => 'yith-field',
                'yith-type' => 'colorpicker',
                'id'        => 'yith_ctpw_pdf_button_back_color_hover',
                'default'   => '#666666'
            ),

            'ctpw_button_text_color' => array (
                'title'   => _x( 'PDF Button Text Color', 'Admin option', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'      => 'yith-field',
                'yith-type' => 'colorpicker',
                'id'        => 'yith_ctpw_pdf_button_text_color',
                'default'   => '#ffffff'
            ),

            'ctpw_button_text_color_hover' => array (
                'title'   => _x( 'PDF Button Text Color on Hover', 'Admin option', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'      => 'yith-field',
                'yith-type' => 'colorpicker',
                'id'        => 'yith_ctpw_pdf_button_text_color_hover',
                'default'   => '#ffffff'
            ),

            'ctpw_pdf_show_logo' => array(
                'title'   => _x( 'Show Logo on PDF', 'Admin option', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'      => 'yith-field',
                'yith-type' => 'checkbox',
                'desc'    => _x( 'Check this option to show a custom logo image on PDF', 'Admin option description',
                    'yith-custom-thankyou-page-for-woocommerce' ),
                'id'      => 'yith_ctpw_pdf_show_logo',
                'default' => 'no'
            ),

            'ctpw_pdf_custom_logo' => array(
                'title'   => _x( 'Logo', 'Admin option', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'      => 'yith-field',
                'yith-type' => 'upload',
                'desc'    => _x( 'Upload or select a logo image (available extensions: jpg, png)', 'Admin option description',
                    'yith-custom-thankyou-page-for-woocommerce' ),
                'id'      => 'yith_ctpw_pdf_custom_logo',
                'default' => ''
            ),

            'ctpw_pdf_custom_logo_max_width' => array(
                'title'   => _x( 'Max Logo Width', 'Admin option', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'      => 'yith-field',
                'yith-type' => 'number',
                'desc'    => _x( 'set the max size for the logo image in px', 'Admin option description',
                    'yith-custom-thankyou-page-for-woocommerce' ),
                'id'      => 'yith_ctpw_pdf_custom_logo_max_size',
                'default' => '200'
            ),

            'ctpw_pdf_show_order_header' => array(
                'title'   => _x( 'Show Order Header Table', 'Admin option', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'      => 'yith-field',
                'yith-type' => 'checkbox',
                'desc'    => _x( 'Show Order Header Table', 'Admin option description',
                    'yith-custom-thankyou-page-for-woocommerce' ),
                'id'      => 'yith_ctpw_pdf_show_order_header',
                'default' => 'yes'
            ),

            'ctpw_pdf_show_order_details_table' => array(
                'title'   => _x( 'Show Order Details Table', 'Admin option', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'      => 'yith-field',
                'yith-type' => 'checkbox',
                'desc'    => _x( 'Show Order Details Table', 'Admin option description',
                    'yith-custom-thankyou-page-for-woocommerce' ),
                'id'      => 'yith_ctpw_pdf_show_order_details_table',
                'default' => 'yes'
            ),

            'ctpw_pdf_footer' => array(
                'title'   => _x( 'Footer Text', 'Admin option', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'      => 'yith-field',
                'yith-type' => 'textarea',
                'desc'    => _x( 'Add custom message to PDF footer', 'Admin option description',
                    'yith-custom-thankyou-page-for-woocommerce' ),
                'id'      => 'yith_ctpw_pdf_footer_text',
            ),

            'ctpw_pdf_options_end'      => array(
                'type' => 'sectionend',
            ),

        )
    ) //end settings array


);