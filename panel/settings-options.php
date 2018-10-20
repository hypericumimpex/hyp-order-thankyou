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

    'settings' => apply_filters( 'yith_ctpw_settings_options', array(

            //general
            'settings_options_start'    => array(
                'type' => 'sectionstart',
            ),

            'settings_options_title'    => array(
                'title' => _x( 'General settings', 'Panel: page title', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'  => 'title',
                'desc'  => '',
            ),

            'settings_enable_custom_thankyou_page' => array(
                'title'   => _x( 'Enable Custom Thank You Page', 'Admin option: Enable plugin', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'    => 'checkbox',
                'desc'    => _x( 'Check this option to enable the plugin features', 'Admin option description: Enable plugin',
                    'yith-custom-thankyou-page-for-woocommerce' ),
                'id'      => 'yith_ctpw_enable',
                'default' => 'yes'
            ),

            'settings_select_custom_thankyou_page_or_url' => array(
                'title'   => _x( 'Redirect to a Custom Page or External URL', 'Admin option', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'    => 'select',
                'id'      => 'yith_ctpw_general_page_or_url',
                'options' => array(
                    'ctpw_page'   => __( 'Custom Wordpress Page', 'yith-custom-thankyou-page-for-woocommerce' ),
                    'ctpw_url' => __( 'External URL', 'yith-custom-thankyou-page-for-woocommerce' ),
                ),
                'default' => 'ctpw_page',
                'class' => 'yith_ctpw_general_page_or_url',
                'css' => 'min-width:300px;',
                'desc_tip' => __('Select the General Thank You Page or External URL for all products', 'yith-custom-thankyou-page-for-woocommerce' )
            ),

            'settings_select_custom_thankyou_page' => array(
                'title'   => _x( 'Select the General Page', 'Admin option', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'    => 'yctpw_single_select_page',
                'id'      => 'yith_ctpw_general_page',
                'sort_column' => 'title',
                'class' => 'wc-enhanced-select-nostd',
                'css' => 'min-width:300px;',
                'desc_tip' => __('Select the General Thank You Page for all products'),
            ),

            'settings_select_custom_thankyou_page_url' => array(
                'title'   => _x( 'Set the Url', 'Admin option', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'    => 'text',
                'id'      => 'yith_ctpw_general_page_url',
                'class' => 'yith_ctpw_general_page_url',
                'css' => 'min-width:300px;',
                'desc_tip' => __('Set the URL to redirect'),
                'desc' => __('write full url for ex: https://yithemes.com/', 'yith-custom-thankyou-page-for-woocommerce' )
            ),


            'setting_custom_thankyou_page_custom_style' => array(
                'title'   => _x( 'Custom CSS', 'Admin option: Custom Style', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type' => 'textarea',
                'id'      => 'yith_ctpw_custom_style',
            ),

            'settings_options_end'      => array(
                'type' => 'sectionend',
            ),


        )
    ) //end settings array
);

