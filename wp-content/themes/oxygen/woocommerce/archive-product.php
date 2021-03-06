<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

# start: modified by Arlind Nushi
if(get_data('shop_quickview')):

	wp_enqueue_script('cbp-grid-gallery');
	wp_enqueue_style('cbp-grid-gallery');
	
endif;
# end: modified by Arlind Nushi

get_header( 'shop' ); ?>

	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>

		<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
		<!--<div class="row">
			<div class="col-lg-12">
			
			    <div class="white-block block-pad log-in">
			        <h1><?php woocommerce_page_title(); ?></h1>
					<div class=" hidden-xs" style="margin-top: -33px;"> <img src="http://shop.ambclasse.com/wp-content/uploads/2016/09/Men_Banner_2.jpg" style="    border-bottom: 8px solid #b0976d;"> </div>
			    </div>
			</div>
		</div>-->
		<?php endif; ?> 

		<?php do_action( 'woocommerce_archive_description' ); ?>

		<?php if ( have_posts() ) : ?>

			<?php
				/**
				 * woocommerce_before_shop_loop hook
				 *
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );
			?>

			<?php woocommerce_product_loop_start(); ?>

				<?php woocommerce_product_subcategories(); ?>
				
				<div class="clear"></div>

				<?php $i = 1; while ( have_posts() ) : the_post(); ?>

					<?php wc_get_template_part( 'content', 'product' ); ?>

					<?php
					# start: modified by Arlind Nushi
					echo $i % SHOPCOLUMNS == 0 ? '<div class="clear"></div>' : '';
					# end: modified by Arlind Nushi
					?>
					
				<?php $i++; endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>

			<?php
				/**
				 * woocommerce_after_shop_loop hook
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
				
			?>

		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

			<?php wc_get_template( 'loop/no-products-found.php' ); ?>

		<?php endif; ?>

	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>

	<?php
		/**
		 * woocommerce_sidebar hook
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		#do_action( 'woocommerce_sidebar' );
	?>

<?php get_footer( 'shop' ); ?>