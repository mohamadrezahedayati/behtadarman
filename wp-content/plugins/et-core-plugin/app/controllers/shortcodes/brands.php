<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Brands shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models/Admin
 */
class Brands extends Shortcodes {

    public function hooks() {}

    function brands_shortcode($atts) {

        if ( etheme_woocommerce_notice() ) return;

        if ( ! etheme_get_option( 'enable_brands' ) ) 
            return '<div class="woocommerce-info">'.esc_html__('Enable brands in Theme options -> Shop elements -> Brands to use this element', 'xstore-core').'</div>';
        
        $atts = shortcode_atts( array(
            'number'     => 12,
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => 0,
            'ids'        => '',
            'large' => 4,
            'notebook' => 3,
            'tablet_land' => 2,
            'tablet_portrait' => 2,
            'mobile' => 1,
            'slider_autoplay' => false,
            'slider_speed' => 300,
            'slider_interval' => 1000,
            'slider_loop' => false,
            'slider_stop_on_hover' => false,
            'pagination_type' => 'hide',
            'hide_fo' => '',
            'default_color' => '#e1e1e1',
            'active_color' => '#222',
            'hide_buttons' => false,
            'hide_buttons_for'   => '',
            'per_move' => 1,
            'class'      => '',
            'is_preview' => false
        ), $atts );

        $options = array();

        if ( $atts['orderby'] == 'ids_order' )
            $atts['orderby'] = 'include';

        $options['terms_args'] = array(
            'orderby'    => $atts['orderby'],
            'order'      => $atts['order'],
            'pad_counts' => true,
            'include'    => $atts['ids'],
            'number' => $atts['number'],
            'hide_empty' => $atts['hide_empty']
        );

        $options['product_brands'] = get_terms( 'brand', $options['terms_args'] );

        $options['box_id'] = rand(1000,10000);

        if ( $atts['orderby'] == 'name' )
            $options['product_brands'] = et_force_name_sort( $options['product_brands'], $atts['order'] );

        $atts['class'] .= ' brands-carousel-'.$options['box_id'];

        if ( $atts['slider_stop_on_hover'] ) 
            $atts['class'] .= ' stop-on-hover';

        $atts['class'] .= ( $atts['pagination_type'] == 'lines' ) ? ' swiper-pagination-lines' : '';

        $options['selectors'] = array();
        
        $options['selectors']['slider'] = '.brands-carousel-' . $options['box_id'];
        $options['selectors']['pagination'] = $options['selectors']['slider'] . ' .swiper-pagination-bullet';
        $options['selectors']['pagination_hover'] = $options['selectors']['pagination'].':hover';
        $options['selectors']['pagination_hover'] .= ', ' . $options['selectors']['pagination'] . '-active';

        // create css data for selectors
        $options['css'] = array(
            $options['selectors']['pagination'] => array(),
            $options['selectors']['pagination_hover'] => array(),
        );

        // create output css 
        $options['output_css'] = array();

        if ($atts['pagination_type'] != 'hide') {
            $options['css'][$options['selectors']['pagination']][] = 'background-color:'.$atts['default_color'];
            $options['css'][$options['selectors']['pagination_hover']][] = 'background-color:'.$atts['active_color'];

            $options['output_css'][] = $options['selectors']['pagination'] . '{'.implode(';', $options['css'][$options['selectors']['pagination']]).'}';
            $options['output_css'][] = $options['selectors']['pagination'] . '{'.implode(';', $options['css'][$options['selectors']['pagination']]).'}';
        }

        ob_start();

        if ( is_array( $options['product_brands'] ) && count( $options['product_brands'] ) > 0 ) {

            $options['wrapper_attr'] = array(
                'data-breakpoints="1"',
                'data-xs-slides="' . esc_js( $atts['mobile'] ) . '"',
                'data-sm-slides="' . esc_js( $atts['tablet_land'] ) . '"',
                'data-md-slides="' . esc_js( $atts['notebook'] ) . '"',
                'data-lt-slides="' . esc_js( $atts['large'] ) . '"',
                'data-slides-per-view="' . esc_js( $atts['large'] ) . '"',
                'data-space="30"',
                'data-autoplay="' . esc_attr( $atts['slider_autoplay'] ) . '"',
                'data-slides-per-group="' . esc_attr( $atts['per_move'] ) . '"',
            );

            if ( $atts['slider_speed'] )
                $options['wrapper_attr'][] = 'data-speed="'.$atts['slider_speed'].'"'; 

            if ( $atts['slider_loop'] )
                $options['wrapper_attr'][] = 'data-loop="true"'; 

            if ( count( $options['output_css'] ) ) {
                $atts['class'] .= ' etheme-css';
                $options['wrapper_attr'][] = 'data-css="' . implode(' ', $options['output_css']) . '"'; 
            }

            ?>

            <div class="swiper-entry brands-carousel">

                <div class="swiper-container <?php echo esc_attr($atts['class']); ?>" <?php echo implode(' ', $options['wrapper_attr']); ?>>

                    <div class="swiper-wrapper">

                    <?php
                        foreach ( $options['product_brands'] as $brand ) :

                        $local_options = array();
                        $local_options['thumb_id']   = absint( get_term_meta( $brand->term_id, 'thumbnail_id', true ) );

                        if ( $atts['hide_empty'] && 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) && etheme_stock_taxonomy( $brand->term_id, 'brand' ) < 1 ) continue;

                        ?>

                        <div class="swiper-slide">

                            <div class="categories-mask">

                                <?php if( $local_options['thumb_id'] ) : 

                                        $local_options['image'] = wp_get_attachment_image_src( $local_options['thumb_id'], 'medium' );
                                        $local_options['image_src'] = $local_options['image'][0];

                                    ?>

                                    <a href="<?php echo esc_url( get_term_link( $brand ) ); ?>" title="<?php echo sprintf(__('View all products from %s', 'xstore-core'), $brand->name); ?>">

                                        <img class="swiper-lazy" data-src="<?php echo esc_url($local_options['image_src']); ?>" title="<?php echo esc_attr( $brand->name ); ?>"/>

                                        <?php etheme_loader(true, 'swiper-lazy-preloader'); ?>

                                    </a>

                                <?php else: ?>

                                    <h3>
                                        <a href="<?php echo esc_url( get_term_link( $brand ) ); ?>" title="<?php echo sprintf(__('View all products from %s', 'xstore-core'), $brand->name); ?>">
                                            <?php echo esc_html($brand->name); ?>
                                        </a>
                                    </h3>

                                <?php endif; ?>

                            </div><?php // .categories-mask ?>

                        </div><?php // .swiper-slide ?>

                        <?php 

                        unset($local_options);
                        endforeach; 

                        ?>

                    </div><?php // .swiper-wrapper ?>

                    <?php if ($atts['pagination_type'] != 'hide') { 
                           $options['pagination_class'] = '';
                            if ( $atts['hide_fo'] == 'desktop' ) 
                                $options['pagination_class'] = ' dt-hide';
                            elseif ( $atts['hide_fo'] == 'mobile' ) 
                                $options['pagination_class'] = ' mob-hide';
                            ?>
                        <div class="swiper-pagination<?php esc_attr($options['pagination_class']); ?>"></div>
                    <?php } ?>

                </div>

                <?php 
                    if ( !$atts['hide_buttons'] || ( $atts['hide_buttons'] && $atts['hide_buttons_for'] != '' ) ) {
                        $options['nav_class'] = '';
                        if ( $atts['hide_buttons_for'] == 'desktop' ) 
                            $options['nav_class'] = ' dt-hide';
                        elseif ( $atts['hide_buttons_for'] == 'mobile' ) 
                            $options['nav_class'] = ' mob-hide';
                        ?>
                        <div class="swiper-custom-left swiper-nav <?php echo esc_attr($options['nav_class']); ?>"></div>
                        <div class="swiper-custom-right swiper-nav <?php echo esc_attr($options['nav_class']); ?>"></div>
                <?php } ?>
            </div>

        <?php } 

        else 
            echo '<div class="woocommerce-info">'.esc_html__('No brands are available', 'xstore-core').'</div>';

        if ( $atts['is_preview'] ) 
            echo parent::initPreviewJs();

        unset($atts);
        unset($options);

        return ob_get_clean();
    }

}
