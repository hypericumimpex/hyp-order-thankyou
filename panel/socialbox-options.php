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

    'socialbox' => apply_filters( 'yith_ctpw_socialbox_options', array(

            //general
            'socialbox_options_start'    => array(
                'type' => 'sectionstart',
            ),

            'socialbox_options_title'    => array(
                'title' => _x( 'Social Box Settings', 'Section title in Settings', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'  => 'title',
                'desc'  => '',
            ),

            'socialbox_enable_socialbox' => array(
                'title'   => _x( 'Enable Social Box', 'Admin option', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'    => 'checkbox',
                'desc'    => _x( 'Check this option to show the social sharing section', 'Admin option description',
                    'yith-custom-thankyou-page-for-woocommerce' ),
                'id'      => 'yith_ctpw_enable_social_box',
                'default' => 'yes'
            ),

            'socialbox_enable_fb_socialbox' => array(
                'title'   => _x( 'Enable Facebook', 'Admin option', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'    => 'checkbox',
                'desc'    => _x( 'Check this option to show Facebook sharing button', 'Admin option description',
                    'yith-custom-thankyou-page-for-woocommerce' ),
                'id'      => 'yith_ctpw_enable_fb_social_box',
                'default' => 'yes'
            ),

            'socialbox_enable_twitter_socialbox' => array(
                'title'   => _x( 'Enable Twitter', 'Admin option', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'    => 'checkbox',
                'desc'    => _x( 'Check this option to show Twitter sharing button', 'Admin option description',
                    'yith-custom-thankyou-page-for-woocommerce' ),
                'id'      => 'yith_ctpw_enable_twitter_social_box',
                'default' => 'yes'
            ),

            'socialbox_enable_google_socialbox' => array(
                'title'   => _x( 'Enable Google Plus', 'Admin option', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'    => 'checkbox',
                'desc'    => _x( 'Check this option to show Google sharing button', 'Admin option description',
                    'yith-custom-thankyou-page-for-woocommerce' ),
                'id'      => 'yith_ctpw_enable_google_social_box',
                'default' => 'yes'
            ),

            'socialbox_enable_pinterest_socialbox' => array(
                'title'   => _x( 'Enable Pinterest', 'Admin option', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'    => 'checkbox',
                'desc'    => _x( 'Check this option to show the Pinterest sharing button', 'Admin option description',
                    'yith-custom-thankyou-page-for-woocommerce' ),
                'id'      => 'yith_ctpw_enable_pinterest_social_box',
                'default' => 'yes'
            ),

            'socialbox_options_end'      => array(
                'type' => 'sectionend',
            ),


            //url shortener settings
            'ctpw_shorturl_options_sbox_start'    => array(
                'type' => 'sectionstart',
            ),

            'ctpw_shorturl_options_sbox_title'    => array(
                'title' => _x( 'URL Shortening Settings ', 'Panel: page title', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'  => 'title',
                'desc'  => '',
            ),

            'ctpw_shorturl_options_sbox_service_select'       => array(
                'name'    => __( 'URL shortening service', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'    => 'select',
                'id'      => 'ctpw_url_shortening',
                'options' => array(
                    'none'   => __( 'None', 'yith-custom-thankyou-page-for-woocommerce' ),
                    'google' => __( 'Google', 'yith-custom-thankyou-page-for-woocommerce' ),
                    'bitly'  => __( 'Bitly', 'yith-custom-thankyou-page-for-woocommerce' ),
                ),
                'default' => 'none',
                'description' => 'select what service to use to shorten the urls'
            ),

            'ctpw_google_api_key'       => array(
                    'name'              => __( 'Google API Key', 'yith-custom-thankyou-page-for-woocommerce' ),
                    'type'              => 'text',
                    'id'                => 'ctpw_google_api_key',
                    'css'               => 'width: 50%',
                    'custom_attributes' => array(
                        'required' => 'required'
                    )
            ),

            'ctpw_bitly_api_key'       => array(
                        'name'              => __( 'Bitly API Key', 'yith-custom-thankyou-page-for-woocommerce' ),
                        'type'              => 'text',
                        'id'                => 'ctpw_bitly_access_token',
                        'css'               => 'width: 50%',
                        'custom_attributes' => array(
                            'required' => 'required'
                        )

            ),

            'ctpw_shorturl_options_sbox_end'      => array(
                'type' => 'sectionend',
            ),

            //box title style
            'ctpw_cstyles_options_sbox_start'    => array(
                'type' => 'sectionstart',
            ),

            'ctpw_cstyles_options_sbox_title'    => array(
                'title' => _x( 'Social Box Style ', 'Panel: page title', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'  => 'title',
                'desc'  => '',
            ),

            'ctpw_styles_options_sbox_title_color' => array(
                'title' => __('Box title font color','yith-custom-thankyou-page-for-woocommerce'),
                'id' => 'ctpw_social_box_title_color',
                'type' => 'color',
                'default' => '#000000',

            ),

            'ctpw_styles_options_sbox_title_font_size' => array(
                'title'              => __( 'Box title font size', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'              => 'number',
                'default'           => 20,
                'id'                => 'ctpw_social_box_title_fontsize',
                'custom_attributes' => array(
                    'min'      => 10,
                    'max'      => 50,
                )
            ),

            'ctpw_styles_options_sbox_title_font_weight' => array(
                'title'              => __( 'Box title font weight', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'              => 'select',
                'default'           => 'bold',
                'id'                => 'ctpw_social_box_title_fontweight',
                'options'           => array (
                    'lighter' => 'Lighter',
                    'normal' => 'Normal',
                    'bold' => 'Bold',
                    'bolder' => 'Bolder'
                )
            ),

            'ctpw_styles_options_sbox_socials_titles_color' => array(
                'title' => __('Social font color','yith-custom-thankyou-page-for-woocommerce'),
                'id' => 'ctpw_socials_titles_color',
                'type' => 'color',
                'default' => '#ffffff',

            ),

            'ctpw_styles_options_sbox_socials_titles_color_hover' => array(
                'title' => __('Social font hover color','yith-custom-thankyou-page-for-woocommerce'),
                'id' => 'ctpw_socials_titles_color_hover',
                'type' => 'color',
                'default' => '#6d6d6d',

            ),

            'ctpw_styles_options_sbox_socials_titles_color_active' => array(
                'title' => __('Social font active color','yith-custom-thankyou-page-for-woocommerce'),
                'id' => 'ctpw_socials_titles_color_active',
                'type' => 'color',
                'default' => '#dc446e',

            ),
            'ctpw_styles_options_sbox_socials_titles_color_active_hover' => array(
                'title' => __('Social font active color on hover','yith-custom-thankyou-page-for-woocommerce'),
                'id' => 'ctpw_socials_titles_color_active_hover',
                'type' => 'color',
                'default' => '#dc446e',

            ),

            'ctpw_styles_options_sbox_socials_main_background_selected' => array(
                'title' => __('Selected tab background color','yith-custom-thankyou-page-for-woocommerce'),
                'id' => 'ctpw_socials_box_main_background_selected',
                'type' => 'color',
                'default' => '#e7e7e7',

            ),

            'ctpw_styles_options_sbox_socials_main_background' => array(
                'title' => __('Tab main background color','yith-custom-thankyou-page-for-woocommerce'),
                'id' => 'ctpw_socials_box_main_background',
                'type' => 'color',
                'default' => '#b3b3b3',

            ),

            'ctpw_styles_options_sbox_socials_arrow_box_color' => array(
                'title' => __('Arrow box color','yith-custom-thankyou-page-for-woocommerce'),
                'id' => 'ctpw_socials_box_arrow_box_color',
                'type' => 'color',
                'default' => '#b3b3b3',

            ),

            'ctpw_styles_options_sbox_socials_button_color' => array(
                'title' => __('Sharing button color','yith-custom-thankyou-page-for-woocommerce'),
                'id' => 'ctpw_socials_box_button_color',
                'type' => 'color',
                'default' => '#b3b3b3',

            ),

            'ctpw_styles_options_sbox_share_button_title_font_size' => array(
                'title'              => __( 'Sharing button font size', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'              => 'number',
                'default'           => 15,
                'id'                => 'ctpw_social_box_button_title_fontsize',
                'custom_attributes' => array(
                    'min'      => 10,
                    'max'      => 50,
                )
            ),

            'ctpw_styles_options_sbox_socials_button_font_color' => array(
                'title' => __('Sharing button font color','yith-custom-thankyou-page-for-woocommerce'),
                'id' => 'ctpw_socials_box_button_fontcolor',
                'type' => 'color',
                'default' => '#ffffff',

            ),

            'ctpw_cstyles_options_sbox_end'      => array(
                'type' => 'sectionend',
            ),

        )
    ) //end settings array
);
