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

    'upsells' => apply_filters( 'yith_ctpw_upsells_options', array(

            //general
            'ctpw_upsells_options_start'    => array(
                'type' => 'sectionstart',
            ),

            'ctpw_upsells_options_title'    => array(
                'title' => _x( 'UpSells Settings ', 'Panel: page title', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'  => 'title',
                'desc'  => '',
            ),

            'ctpw_upsells_enable_upsells' => array(
                'title'   => _x( 'Enable UpSelling section', 'Admin option', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'    => 'checkbox',
                'desc'    => _x( 'Check this option to show the up-selling section', 'Admin option description',
                    'yith-custom-thankyou-page-for-woocommerce' ),
                'id'      => 'yith_ctpw_enable_upsells',
                'default' => 'yes'
            ),

            'ctpw_upsells_options_columns' => array(
                'title'   => _x( 'Products per row', 'Admin option', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'    => 'select',
                'options' => apply_filters('yith_ctpw_ups_columns_options',array(2 => '2',3 => '3',4 => '4', 5 => '5', 6=> '6')),
                'id'      => 'yith_ctpw_ups_columns',
                'default' => 4
            ),

            'ctpw_upsells_options_products_per_page' => array(
                'title'   => _x( 'Products per page', 'Admin option', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'    => 'number',
                'id'      => 'yith_ctpw_ups_ppp',
                'default' => 4,
            ),

            'ctpw_upsells_options_orderby' => array(
                'title'   => _x( 'Order by', 'Admin option', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'    => 'select',
                'options' => array(
                    'title' => __('title','yith-custom-thankyou-page-for-woocommerce'),
                    'rand' =>  __('random','yith-custom-thankyou-page-for-woocommerce'),
                    'date' =>  __('date','yith-custom-thankyou-page-for-woocommerce'),
                ),
                'id'      => 'yith_ctpw_ups_orderby',
                'default' => 4
            ),

            'ctpw_upsells_options_order' => array(
                'title'   => _x( 'Order', 'Admin option', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'    => 'select',
                'options' => array(
                    'asc' => 'asc',
                    'desc' => 'desc'
                    ),
                'id'      => 'yith_ctpw_ups_order',
                'default' => 4
            ),

            'ctpw_upsells_options_ids' => array(
                'title'   => _x( 'Select products', 'Admin option', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'    => 'ctpw_product_select',
                'id'      => 'yith_ctpw_upsells_ids',
            ),

            'ctpw_upsells_options_end'      => array(
                'type' => 'sectionend',
            ),

            //upsells title style
            'ctpw_cstyles_options_upsells_start'    => array(
                'type' => 'sectionstart',
            ),

            'ctpw_cstyles_options_upsells_title'    => array(
                'title' => _x( 'Style ', 'Section title', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'  => 'title',
                'desc'  => '',
            ),

            'ctpw_styles_options_upsells_title_color' => array(
                'title' => __('Title font color','yith-custom-thankyou-page-for-woocommerce'),
                'id' => 'ctpw_upsells_title_color',
                'type' => 'color',
                'default' => '#000000',

            ),

            'ctpw_styles_options_upsells_title_font_size' => array(
                'title'              => __( 'Title font size', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'              => 'number',
                'default'           => 20,
                'id'                => 'ctpw_upsells_title_fontsize',
                'custom_attributes' => array(
                    'min'      => 10,
                    'max'      => 50,
                )
            ),

            'ctpw_styles_options_upsells_title_font_weight' => array(
                'title'              => __( 'Title font weight', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'              => 'select',
                'default'           => 'bold',
                'id'                => 'ctpw_upsells_title_fontweight',
                'options'           => array (
                    'lighter' => 'Lighter',
                    'normal' => 'Normal',
                    'bold' => 'Bold',
                    'bolder' => 'Bolder'
                )
            ),

            'ctpw_cstyles_options_upsells_end'      => array(
                'type' => 'sectionend',
            ),


        )
    ) //end settings array
);

