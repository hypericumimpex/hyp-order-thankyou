/**
 * URL shortener Admin Script Doc Comment
 *
 * @category Script
 * @package  Yith Custom Thank You Page for Woocommerce
 * @author    Armando Liccardo
 * @license  http://www.gnu.org/licenses/gpl-3.0.txt GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * @link http://www.yithemes.com
 */

jQuery(function ($) {
		$( document ).ready(function () {
				"use strict";

                /* URL shortening select change event */
				$( '#ctpw_url_shortening' ).change(function () {

					var option = $( 'option:selected', this ).val(),
						google = $( '#ctpw_google_api_key' ),
						bitly = $( '#ctpw_bitly_access_token' );

					switch (option) {
						/* select bitly service */
						case 'bitly':
							bitly.parent().parent().show();
							bitly.prop( 'required', true );
							google.parent().parent().hide();
							google.prop( 'required', false );
							break;
						 /* select google service */
						case 'google':
							bitly.parent().parent().hide();
							bitly.prop( 'required', false );
							google.parent().parent().show();
							google.prop( 'required', true );
							break;

						default:
							bitly.parent().parent().hide();
							bitly.prop( 'required', false );
							google.parent().parent().hide();
							google.prop( 'required', false );

					}

				}).change();

            /* change event on Select Custom page or Custom Url */
            $( '#yith_ctpw_general_page_or_url' ).change(function () {
                var option = $( 'option:selected', this ).val(),
                    ctwp_page = $( '#yith_ctpw_general_page' ),
                    ctpw_url = $( '#yith_ctpw_general_page_url' );

                switch (option) {
                    case 'ctpw_page':
                        ctwp_page.parent().parent().show();
                        ctpw_url.parent().parent().hide();
                        break;
                    case 'ctpw_url':
                        ctpw_url.parent().parent().show();
                        ctwp_page.parent().parent().hide();
                        break;
                    default:
                        ctwp_page.parent().parent().hide();
                        ctpw_url.parent().parent().hide();
                }

            }).change();

            /* edit\new category select change event */
            $('#yith_ctpw_or_url_product_cat_thankyou_page').change( function() {
                var option = $( 'option:selected', this ).val(),
                    ctwp_page = $( '#yith_ctpw_product_cat_thankyou_page' ),
                    ctpw_url = $( '#yith_ctpw_url_product_cat_thankyou_page' );

                switch(option) {
                    case 'ctpw_page':
                        ctwp_page.parent().parent().show();
                        ctpw_url.parent().parent().hide();
                        break;
                    case 'ctpw_url':
                        ctpw_url.parent().parent().show();
                        ctwp_page.parent().parent().hide();
                        break;
                    default:
                        ctwp_page.parent().parent().hide();
                        ctpw_url.parent().parent().hide();
                }
            }).change();

            //product page tab
            $('#ctpw_tab_data #yith_ctpw_product_thankyou_page_url').change( function() {
                var option = $( 'option:selected', this ).val(),
                    ctwp_page = $( '#yith_product_thankyou_page' ),
                    ctpw_url = $( '#yith_ctpw_product_thankyou_url' );

                switch(option) {
                    case 'ctpw_page':
                        ctwp_page.parent().show();
                        ctpw_url.parent().hide();
                        break;
                    case 'ctpw_url':
                        ctpw_url.parent().show();
                        ctwp_page.parent().hide();
                        break;
                    default:
                        ctwp_page.parent().hide();
                        ctpw_url.parent().hide();
                }
            }).change();

            $('#woocommerce-product-data').on('woocommerce_variations_loaded', function(event) {
                //product variation options
                $('.woocommerce_variation .yith_ctpw .yith_ctpw_product_thankyou_page_url').change( function() {
                    var item_class = '.' + $(this).parent().attr('ctpw_item');
                    var option = $( 'option:selected', this ).val(),
                        ctwp_page = $( item_class + ' .yith_ctpw_product_thankyou_page' ),
                        ctpw_url = $( item_class + ' .yith_ctpw_product_thankyou_url' );

                    switch(option) {
                        case 'ctpw_page':
                            ctwp_page.show();
                            ctpw_url.hide();
                            break;
                        case 'ctpw_url':
                            ctpw_url.show();
                            ctwp_page.hide();
                            break;
                        default:
                            ctwp_page.hide();
                            ctpw_url.hide();
                    }

                }).change();
            });

            //manage field selection in Payment Gateways Tab
            $('#yith_ctpw_panel_payment_gateways .yith_ctpw_general_page_or_url').change( function() {

                    var item_name = $(this).attr('ctpw_pg_id'),
                        option = $( 'option:selected', this ).val(),
                        ctwp_page_sel = '#yith_ctpw_page_for_' + item_name,
                        ctpw_url_sel = '#yith_ctpw_url_for_' + item_name,
                        ctpw_page = $( ctwp_page_sel),
                        ctpw_url = $( ctpw_url_sel);

                    switch(option) {
                        case 'ctpw_page':
                            ctpw_page.parent().parent().show();
                            ctpw_url.parent().parent().hide();
                            break;
                        case 'ctpw_url':
                            ctpw_url.parent().parent().show();
                            ctpw_page.parent().parent().hide();
                            break;
                        default:
                            ctpw_page.parent().parent().hide();
                            ctpw_url.parent().parent().hide();
                    }

                }).change();


            //add an edit link to the selected page in Main Admin settings, product category settings, single product settings
            function get_page_edit_url( ctpw_id, where ) {
                var data = {
                    'action': 'yith_ctpw_get_edit_page_url',
                    'ctpw_id': ctpw_id
                };

                if ( where == 'main_settings' ) {
                    $('.single_select_page .yith_ctpw_edit_page_url_link').remove();

                    $.post(ajaxurl, data, function(resp) {
                        if ( resp != false ) {
                            $('tr.single_select_page td .select2 .selection').append('<a style="text-decoration: none; position: absolute; top: 6px; right: 40px;" class="yith_ctpw_edit_page_url_link" target="_blank" href="' + resp + '">Edit</a>');
                        }
                    });
                } else if ( where == 'product_cat_settings') {
                    $('tr.yith_ctpw_cat_page .yith_ctpw_edit_page_url_link').remove();
                    $.post(ajaxurl, data, function(resp) {
                        if ( resp != false ) {
                            $('tr.yith_ctpw_cat_page td').append('<a style="text-decoration: none;" class="yith_ctpw_edit_page_url_link" target="_blank" href="' + resp + '">Edit</a>');
                        }
                    });
                }
                else if ( where == 'single_p_tab_settings') {
                    $('#ctpw_tab_data .yith_ctpw_product_thankyou_page_id .yith_ctpw_edit_page_url_link').remove();
                    $.post(ajaxurl, data, function(resp) {
                        if ( resp != false ) {
                            $('#ctpw_tab_data .yith_ctpw_product_thankyou_page_id').append('<a style="text-decoration: none; margin-left: 4px;" class="yith_ctpw_edit_page_url_link" target="_blank" href="' + resp + '">Edit</a>');
                        }
                    });
                }


            }

            if ( $('select#yith_ctpw_general_page').val() != '' ) {
                get_page_edit_url($('select#yith_ctpw_general_page').val() , 'main_settings');
            }

            $('select#yith_ctpw_general_page').change( function() {
                get_page_edit_url($(this).val() , 'main_settings');
            });

            if ( $('select#yith_ctpw_product_cat_thankyou_page').val() != 0 ) {
                get_page_edit_url($('select#yith_ctpw_product_cat_thankyou_page').val() , 'product_cat_settings');
            }

            $('select#yith_ctpw_product_cat_thankyou_page').change( function() {
                get_page_edit_url($(this).val() , 'product_cat_settings');
            });

            if ( $('#ctpw_tab_data .yith_ctpw_product_thankyou_page_id select').val() != '0' ) {
                get_page_edit_url($('#ctpw_tab_data .yith_ctpw_product_thankyou_page_id select').val() , 'single_p_tab_settings');
            }

            $('#ctpw_tab_data .yith_ctpw_product_thankyou_page_id select').change( function() {
                    get_page_edit_url($(this).val() , 'single_p_tab_settings');
                });

            //end add edit page link

            //show logo checkbox event on PDF tab
            var logo_option = $('#yith_ctpw_pdf_show_logo'),
                upload_field = $('#yith_ctpw_panel_pdf tr.upload'),
                logo_max_size = $('#yith_ctpw_panel_pdf tr.number');

            logo_option.change( function() {
                if (this.checked) {
                    upload_field.show();
                    logo_max_size.show();
                } else {
                    upload_field.hide();
                    logo_max_size.hide();
                }
            }).change();

		}); //end document ready
});
