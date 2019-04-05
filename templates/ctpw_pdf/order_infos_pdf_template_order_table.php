<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<table class="shop_table order_details">
    <thead>
    <tr>
        <th class="product-name"><?php _e( 'Product', 'yith-custom-thankyou-page-for-woocommerce' ); ?></th>
        <th class="product-total"><?php _e( 'Total', 'yith-custom-thankyou-page-for-woocommerce' ); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    if ( sizeof( $order->get_items() ) > 0 ) {

        foreach( $order->get_items() as $item ) {
            $_product     = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );

            /* woocommerce 3.1 compatibility */
            if (version_compare( WC()->version , '3.1', '<' ) ){
                $item_meta = new WC_Order_Item_Meta($item, $_product);
            } else {
                $item_meta = new  WC_Order_Item_Product($_product);
            }

            ?>
            <tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
                <td class="product-name">
                    <?php
                    //APPLY_FILTER: yith_ctwp_show_product_thumb : set to true to show product thumbnail in order details table: value can be true or false
                    $ctpw_show_product_thumb = apply_filters('yith_ctwp_show_product_thumb_pdf',false);
                    if ( $ctpw_show_product_thumb ) {
                        $thumbsize = apply_filters('yith_ctpw_pdf_thumb_size', array(100,100));
                        $pdf_thumb_max_width = apply_filters('yith_ctpw_pdf_thumb_max_size','90');
                         echo '<img class="yith_ctpw_thumb" style="max-width: ' . $pdf_thumb_max_width . '" src="'.get_the_post_thumbnail_url($_product->get_ID(),$thumbsize) . '" />';
                    }
                    ?>
                    <?php

                    if ( $_product && ! $_product->is_visible() )
                        echo apply_filters( 'woocommerce_order_item_name', $item['name'], $item );
                    else
                        echo apply_filters( 'woocommerce_order_item_name', sprintf( '<a href="%s">%s</a>', get_permalink( $item['product_id'] ), $item['name'] ), $item );

                    echo apply_filters( 'woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf( '&times; %s', $item['qty'] ) . '</strong>', $item );

                    $formatted_meta_data = $item->get_formatted_meta_data();
                    if ( $formatted_meta_data ) {
                        echo '<ul class="yctpw-item-meta wc-item-meta">';

                        foreach ($formatted_meta_data as $data => $dobj ) {
                            echo '<li><strong class="wc-item-meta-label">'.$dobj->display_key.':</strong><p>'.$dobj->display_value.'</p></li>';
                        }
                        echo '</ul>';
                    }

                    if ( $_product && $_product->exists() && $_product->is_downloadable() && $order->is_download_permitted() ) {

                        $download_files = $order->get_item_downloads( $item );
                        $i              = 0;
                        $links          = array();

                        foreach ( $download_files as $download_id => $file ) {
                            $i++;

                            $links[] = '<small><a href="' . esc_url( $file['download_url'] ) . '">' . sprintf( __( 'Download file %s',
                                    'yith-custom-thankyou-page-for-woocommerce' ), ( count( $download_files ) > 1 ? ' ' . $i . ': ' : ': ' ) ) . esc_html( $file['name'] ) . '</a></small>';
                        }

                        echo '<br/>' . implode( '<br/>', $links );
                    }
                    ?>
                </td>
                <td class="woocommerce-table__product-total product-total">
                    <?php echo $order->get_formatted_line_subtotal( $item ); ?>
                </td>
            </tr>
            <?php
            $show_purchase_note    = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
            if ( $show_purchase_note ) { ?>
                <tr class="product-purchase-note">
                    <td colspan="2"><?php echo wpautop( do_shortcode( wp_kses_post( $_product->get_purchase_note() ) ) ); ?></td>
                </tr>

            <?php
            }

        } //end for
    }

    do_action( 'woocommerce_order_items_table', $order );
    ?>
    </tbody>
    <tfoot>
    <?php
    if ( $totals = $order->get_order_item_totals() ) foreach ( $totals as $total ) :
        ?>
        <tr>
            <th scope="row"><?php echo $total['label']; ?></th>
            <td><?php echo $total['value']; ?></td>
        </tr>
    <?php
    endforeach;


    if ( $order->get_customer_note() ) {
        ?>
        <tr class="woocommerce-table__product-customer-note product-customer-note">
            <th><?php _e( 'Note:', 'woocommerce' ); ?></th>
            <td><?php echo wptexturize( $order->get_customer_note() ); ?></td>
        </tr>
    <?php
    }

    ?>
    </tfoot>
</table>

