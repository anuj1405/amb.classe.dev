<?php
/**
 * Additional Information tab
 * 
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly
	exit;
}

global $product;

$heading = apply_filters( 'woocommerce_product_additional_information_heading', __( 'Additionalasasas Information', 'oxygen' ) );
?>

<?php if ( $heading ): ?>
<?php /*	<h4 class="tab-title"><?php echo $heading; ?></h4>*/ ?>
<?php endif; ?>

<?php $product->list_attributes(); ?>