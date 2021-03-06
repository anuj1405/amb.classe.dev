<?php
/**
 * Review Comments Template
 *
 * Closing li is left out on purpose!.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/review.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$rating   = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
$verified = wc_review_is_from_verified_owner( $comment->comment_ID );

?>
<li itemprop="review" itemscope itemtype="http://schema.org/Review" <?php comment_class( 'comment author_post' ); ?> id="li-comment-<?php comment_ID() ?>">

	<div id="comment-<?php comment_ID(); ?>" class="comment_container comment-entry">
		
		<div class="comment-inner-body">
			
			<div class="comment-thumb">
				<div class="author_img">
					<?php echo get_avatar( $comment, apply_filters( 'woocommerce_review_gravatar_size', '60' ), '' ); ?>
				</div>
			</div>
			
			<div class="comment-details">
				
				<div class="author_about_part">
					
					<?php if ( $rating && get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) : ?>
						
						<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="star-rating" title="<?php echo sprintf( __( 'Rated %d out of 5', 'oxygen' ), $rating ) ?>">
						
							<div class="rating filled-<?php echo absint($rating); ?>">
								<span class="glyphicon glyphicon-star star-1"></span>
								<span class="glyphicon glyphicon-star star-2"></span>
								<span class="glyphicon glyphicon-star star-3"></span>
								<span class="glyphicon glyphicon-star star-4"></span>
								<span class="glyphicon glyphicon-star star-5"></span>
							</div>
							
						</div>
		
					<?php endif; ?>
					
					<time itemprop="datePublished" datetime="<?php echo get_comment_date( 'c' ); ?>"><?php echo get_comment_date( __( get_option( 'date_format' ), 'oxygen' ) ); ?></time>
					
					<?php if ( $comment->comment_approved == '0' ) : ?>
		
						<p class="meta"><em><?php _e( 'Your comment is awaiting approval', 'oxygen' ); ?></em></p>
		
					<?php else : ?>
		
						<p class="meta">
							<strong itemprop="author"><?php comment_author(); ?></strong> <?php
		
								if ( get_option( 'woocommerce_review_rating_verification_label' ) === 'yes' )
									if ( $verified )
										echo '<em class="verified">(' . __( 'verified owner', 'oxygen' ) . ')</em> ';
		
							?> <?php _e('Says: ', 'oxygen'); ?>
						</p>
		
					<?php endif; ?>
					
					<?php do_action( 'woocommerce_review_before_comment_text', $comment ); ?>
						<div itemprop="description" class="description comment-content"><?php comment_text(); ?></div>
					<?php do_action( 'woocommerce_review_after_comment_text', $comment ); ?>
					
				</div>
				
			</div>

		</div>
	</div>
