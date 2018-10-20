<?php
/**
 * The Template for invoice
 *
 * Override this template by copying it to [your theme folder]/woocommerce/yith-pdf-invoice/invoice-template.php
 *
 * @author        Yithemes
 * @package       yith-woocommerce-pdf-invoice-premium/Templates
 * @version       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly


?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <?php
    /**
     * yith_ctpw_pdf_template_head hook
     *
     * @hooked yith_ctpw_add_pdf_styles - 10
     */
    do_action( 'yith_ctpw_pdf_template_head', $order );
    ?>
</head>

<body>
<div class="ctpw-pdf-document <?php echo $main_class; ?>">
    <?php
    /**
     * Show the header of the document
     *
     * @hooked
     */
    do_action( 'yith_ctpw_template_document_header', $order ); ?>

    <?php
    /**
     * Show the template that contains the company data
     */
    do_action( 'yith_ctpw_template_company_data', $order ); ?>

    <?php
    /**
     * Show the template for the order details
     *
     * @hooked yith_ctpw_add_pdf_order_infos - 10
     * @hooked yith_ctpw_add_pdf_order_infos_table - 15
     */
    do_action( 'yith_ctpw_template_order_content', $order ); ?>

    <?php
    /**
     * Show the template for the order notes
     */
    do_action( 'yith_ctpw_template_notes', $order ); ?>

    <?php
    /**
     * Show the template for end of the document
     *
     * @hooked yith_ctpw_pdf_footer_text - 10
     */
    do_action( 'yith_ctpw_template_footer', $order ); ?>
</div>
</body>
</html>