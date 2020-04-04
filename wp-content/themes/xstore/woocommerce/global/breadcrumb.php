<?php
/**
 * Shop breadcrumb
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 * @see         woocommerce_breadcrumb()
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $wp_query;

$l = etheme_page_config();
$current = isset( $current ) ? $current : wc_get_loop_prop( 'current_page' );
$delimeter = '<span class="delimeter"><i class="et-icon et-right-arrow"></i></span>';
$product_name_single = !get_option( 'etheme_single_product_builder', false ) && etheme_get_option('product_name_signle');
$product_name_single = apply_filters('product_name_single', $product_name_single);
$breadcrumb_stretch = 'default';
$breadcrumb_stretch = apply_filters('woocommerce_breadcrumb_stretch', $breadcrumb_stretch);
$return_to_previous = etheme_get_option('return_to_previous');
$return_to_previous = apply_filters('return_to_previous', $return_to_previous);
$breadcrumb_params = apply_filters('breadcrumb_params', array(
	'type' => $l['breadcrumb'],
	'effect' => $l['bc_effect'],
	'color' => $l['bc_color']
));

$class = array();
$class[] = 'bc-type-'.$breadcrumb_params['type'];
$class[] = 'bc-effect-'.$breadcrumb_params['effect'];
$class[] = 'bc-color-'.$breadcrumb_params['color'];

if ( $breadcrumb_stretch != 'default' ) 
	$class[] = 'vc_row wpb_row';

if ($l['breadcrumb'] !== 'disable' && !$l['slider']): ?>
	<div class="page-heading <?php echo implode(' ', $class); ?>" <?php if ( $breadcrumb_stretch != 'default' ) : ?> data-vc-full-width="true" data-vc-full-width-init="false" <?php endif; if ( $breadcrumb_stretch == 'full_width_content') :?>data-vc-stretch-content="true"<?php endif;?>>
		<div class="<?php if ( $breadcrumb_stretch != 'full_width_content' ) echo 'container '; ?>page-heading-inner">
			<div class="row">
				<div class="col-md-12 a-center">
					
					<?php do_action('etheme_before_breadcrumbs'); ?>

					<?php if ( $breadcrumb ) : ?>

						<?php echo wp_specialchars_decode($wrap_before); ?>

						<?php foreach ( $breadcrumb as $key => $crumb ) : ?>

							<?php echo wp_specialchars_decode($before); ?>

							<?php if ( ! empty( $crumb[1] ) && sizeof( $breadcrumb ) !== $key + 1 ) : ?>
								<?php echo '<a href="' . esc_url( $crumb[1] ) . '">' . esc_html( $crumb[0] ) . '</a>'; ?>
							<?php elseif ( ($l['breadcrumb'] == 'default' && !is_shop()) || ( (is_product_category() || is_shop()) && $current > 1) ) : ?>
								<?php echo '<span class="span-title">'.esc_html( $crumb[0] ).'</span>'; ?>
							<?php endif; ?>

							<?php echo wp_specialchars_decode($after); ?>

							<?php if ( sizeof( $breadcrumb ) !== $key + 1 ) : ?>
								<?php echo apply_filters('woocommerce_breadcrumb_delimiter', $delimeter); ?>
							<?php endif; ?>

						<?php endforeach; ?>

	                    <?php if( $product_name_single && is_single() && ! is_attachment() ): ?>
	                    	<h1 class="title">
	                        	<?php echo get_the_title(); ?>
	                        </h1>
	                    <?php elseif( ! is_single()): ?>
	                    	<h1 class="title">
	                        	<?php woocommerce_page_title(); ?>
	                        </h1>
	                    <?php endif; ?>

					<?php echo wp_specialchars_decode($wrap_after); ?>

					<?php endif; ?>
					
					<?php if( $return_to_previous ) etheme_back_to_page(); ?>
				</div>
			</div>
		</div>
	</div>

	<?php if ( $breadcrumb_stretch != 'default' ) { ?>
	<div class="vc_row-full-width vc_clearfix"></div>
	<?php }
endif; ?>
<?php if($l['slider']): ?>
	<div class="page-heading-slider">
		<?php  echo do_shortcode('[rev_slider_vc alias="'.$l['slider'].'"]'); ?>
	</div>
<?php endif; ?>