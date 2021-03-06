<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
 * Products shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/VC
 */
class Products extends VC {

    function hooks() {

        $this->register_vc_products();

        add_filter( 'vc_autocomplete_etheme_products_ids_callback', array( 'Vc_Vendor_Woocommerce', 'productIdAutocompleteSuggester',), 10, 1 ); // Get suggestion(find). Must return an array
        add_filter( 'vc_autocomplete_etheme_products_ids_render', array( 'Vc_Vendor_Woocommerce', 'productIdAutocompleteRender',), 10, 1 ); // Render exact product. Must return an array (label,value)
        
        //For param: ID default value filter
        add_filter( 'vc_form_fields_render_field_product_id_param_value', array( 'Vc_Vendor_Woocommerce', 'productIdDefaultValue', ), 10, 4 ); // Defines default value for param if not provided. Takes from other param value.

        //Filters For autocomplete param:
        //For suggestion: vc_autocomplete_[shortcode_name]_[param_name]_callback
        add_filter( 'vc_autocomplete_etheme_products_ids_callback', array( 'Vc_Vendor_Woocommerce', 'productIdAutocompleteSuggester',), 10, 1 ); // Get suggestion(find). Must return an array
        add_filter( 'vc_autocomplete_etheme_products_ids_render', array( 'Vc_Vendor_Woocommerce', 'productIdAutocompleteRender',), 10, 1 ); // Render exact product. Must return an array (label,value)
        //For param: ID default value filter
        add_filter( 'vc_form_fields_render_field_products_ids_param_value', array( 'Vc_Vendor_Woocommerce','productsIdsDefaultValue', ), 10, 4 ); // Defines default value for param if not provided. Takes from other param value.

        // Narrow data taxonomies
        add_filter( 'vc_autocomplete_etheme_products_taxonomies_callback', 'vc_autocomplete_taxonomies_field_search', 10, 1 );
        add_filter( 'vc_autocomplete_etheme_products_taxonomies_render', 'vc_autocomplete_taxonomies_field_render', 10, 1 );

    }

    function register_vc_products() {

        $strings = $this->etheme_vc_shortcodes_strings();
        $order_by_values = array(
            '',
            esc_html__( 'Date', 'xstore-core' ) => 'date',
            esc_html__( 'ID', 'xstore-core' ) => 'ID',
            esc_html__( 'As IDs provided order', 'xstore-core' ) => 'post__in',
            esc_html__( 'Author', 'xstore-core' ) => 'author',
            esc_html__( 'Title', 'xstore-core' ) => 'title',
            esc_html__( 'Modified', 'xstore-core' ) => 'modified',
            esc_html__( 'Random', 'xstore-core' ) => 'rand',
            esc_html__( 'Comment count', 'xstore-core' ) => 'comment_count',
            esc_html__( 'Menu order', 'xstore-core' ) => 'menu_order',
            esc_html__( 'Price', 'xstore-core' ) => 'price',

        );

        $content_product_args = array(
            'posts_per_page'   => -1,
            'offset'           => 0,
            'category'         => '',
            'category_name'    => '',
            'orderby'          => 'date',
            'order'            => 'DESC',
            'include'          => '',
            'exclude'          => '',
            'meta_key'         => '',
            'meta_value'       => '',
            'post_type'        => 'vc_grid_item',
            'post_mime_type'   => '',
            'post_parent'      => '',
            'author'       => '',
            'author_name'      => '',
            'post_status'      => 'publish',
            'suppress_filters' => true 
        );

        $content_product_args = get_posts( $content_product_args );
        $product_templates = array();
        foreach ($content_product_args as $key) {
            $product_templates[$key->post_title] = $key->ID;
        }

        $counter = 0;
        $params = array(
            'name' => 'Products',
            'base' => 'etheme_products',
            'icon' => ETHEME_CODE_IMAGES . 'vc/Products.png',
            'description' => esc_html__('Display slider or grid of the products', 'xstore-core'),
            'category' => $strings['category'],
            'params' => array_merge(array(
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Content', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Title', 'xstore-core'),
                    'param_name' => 'title'
                ),
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Layout', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
                ),
                array(
                    'type' => 'xstore_button_set',
                    'heading' => esc_html__('Display Type', 'xstore-core'), 
                    'param_name' => 'type',
                    'value' => array(
                        esc_html__('Slider', 'xstore-core') => 'slider',
                        esc_html__('Grid', 'xstore-core') => 'grid',
                        esc_html__('List', 'xstore-core') => 'list',
                        esc_html__('Full screen', 'xstore-core') => 'full-screen',
                    )
                ),
                array(
                    'type' => 'xstore_button_set',
                    'heading' => esc_html__('Product layout for this slider', 'xstore-core'),
                    'param_name' => 'style',
                    'dependency' => array(
                        'element' => 'type', 
                        'value' => 'slider'
                    ),
                    'value' => array( 
                        esc_html__('Grid', 'xstore-core') => 'default', 
                        esc_html__('List', 'xstore-core') => 'advanced'
                    ),
                    'edit_field_class' => 'vc_col-sm-6 vc_column',
                ),
                array(
                    'type' => 'xstore_button_set',
                    'heading' => esc_html__('Remove space between slides', 'xstore-core'),
                    'param_name' => 'no_spacing',
                    'dependency' => array(
                        'element' => 'type', 
                        'value' => 'slider'
                    ),
                    'value' => array(
                        'No' => false,
                        'Yes' => 'yes',
                    ),
                    'edit_field_class' => 'vc_col-sm-6 vc_column',
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => esc_html__('Columns', 'xstore-core'),
                    'param_name' => 'columns', // notice @omid - you can try to do it as this type http://prntscr.com/qf90ae if elementor has such field type 
                    'dependency' => array('element' => 'type', 'value' => array('grid', 'list')),
                    'value' => array(
                        '',
                        1,
                        2,
                        3,
                        4,
                        5,
                        6
                    )
                ),
                array(
                    'type' => 'xstore_button_set',
                    'heading' => esc_html__('Navigation', 'xstore-core'),
                    'param_name' => 'navigation',
                    'dependency' => array( 
                        'element' => 'type', 
                        'value' => 'grid' 
                    ),
                    'value' => array(
                        esc_html__( 'Off', 'xstore-core' ) => 'off',
                        esc_html__( 'Load More button', 'xstore-core' ) => 'btn',
                        esc_html__( 'Lazy loading', 'xstore-core' ) => 'lazy',
                    )
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Products per view', 'xstore-core'),
                    'param_name' => 'per_iteration',
                    'hint' => sprintf( esc_html__( 'Number of products to show per view and after every loading', 'xstore-core' ) ),
                    'dependency' => array( 
                        'element' => 'navigation', 
                        'value' => array( 'btn', 'lazy' ) 
                    ),
                ),
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Product content settings', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++,
                    'dependency' => array(
                        'element' => 'type', 
                        'value' => array('grid', 'list', 'slider')
                    ),
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => esc_html__('Product View', 'xstore-core'),
                    'param_name' => 'product_view', // @omid radio image type 
                    'dependency' => array(
                        'element' => 'type', 
                        'value' => array('grid', 'list', 'slider')
                    ),
                    'value' => array( '',
                        esc_html__('Inherit', 'xstore-core') => '',
                        esc_html__('Default', 'xstore-core') => 'default',
                        esc_html__('Buttons on hover middle', 'xstore-core') => 'mask3',
                        esc_html__('Buttons on hover bottom', 'xstore-core') => 'mask',
                        esc_html__('Buttons on hover right', 'xstore-core') => 'mask2',
                        esc_html__('Information mask', 'xstore-core') => 'info',
                        esc_html__('Booking', 'xstore-core') => 'booking',
                        esc_html__('Light', 'xstore-core')   => 'light',
                        esc_html__('Custom', 'xstore-core') => 'custom',
                        esc_html__('Disable', 'xstore-core') => 'Disable',
                    ),
                    'edit_field_class' => 'vc_col-sm-6 vc_column',
                ),
                array(
                    'type' => 'xstore_button_set',
                    'heading' => esc_html__('Product View Color', 'xstore-core'),
                    'param_name' => 'product_view_color', // radio-button if there is this field type 
                    'dependency' => array(
                        'element' => 'type', 
                        'value' => array('grid', 'list', 'slider')
                    ),
                    'value' => array( 
                        esc_html__( 'Default', 'xstore-core' ) => '',
                        esc_html__( 'White', 'xstore-core' ) => 'white',
                        esc_html__( 'Dark', 'xstore-core' ) => 'dark',
                    ),
                    'edit_field_class' => 'vc_col-sm-6 vc_column',
                ),
                array (
                    'type' => 'dropdown',
                    'heading' => esc_html__('Custom product templates', 'xstore-core'),
                    'param_name' => 'custom_template',
                    'dependency' => array(
                        'element' => 'product_view', 
                        'value' => 'custom'
                    ),
                    'value' => $product_templates
                ),
                array (
                    'type' => 'xstore_button_set',
                    'param_name' => 'product_img_hover',
                    'heading' => esc_html__( 'Image hover effect', 'xstore-core' ),
                    'value' => array(
                        esc_html__( 'Default', 'xstore-core' ) => '',
                        esc_html__( 'Disable', 'xstore-core' ) => 'disable',
                        esc_html__( 'Swap', 'xstore-core' ) => 'swap',
                        esc_html__( 'Images Slider', 'xstore-core' ) => 'slider',
                    ),
                    'dependency' => array (
                        'element' => 'product_view', 
                        'value_not_equal_to' => 'custom'
                    )
                ),
                array(
                    'type' => 'xstore_button_set',
                    'heading' => esc_html__('Show sale counter', 'xstore-core'),
                    'param_name' => 'show_counter',
                    'value' => array(
                        'No' => false,
                        'Yes' => 'yes',
                    ),
                    'edit_field_class' => 'vc_col-sm-6 vc_column',
                ),
                array(
                    'type' => 'xstore_button_set',
                    'heading' => esc_html__('Show in stock count', 'xstore-core'),
                    'param_name' => 'show_stock',
                    'value' => array(
                        'No' => false,
                        'Yes' => 'yes',
                    ),
                    'edit_field_class' => 'vc_col-sm-6 vc_column',
                ),
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Advanced', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => $strings['heading']['ajax'],
                    'param_name' => 'ajax',
                    //'dependency' => array( 'element' => 'navigation', 'value' => array( 'off', 'btn', 'lazy' ) ),
                ),
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Data settings', 'xstore-core' ),
                    'group' => esc_html__( 'Products Data', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => esc_html__('Products type', 'xstore-core'),
                    'group' => esc_html__( 'Products Data', 'xstore-core' ),
                    'param_name' => 'products',
                    'value' => array( 
                        esc_html__('All', 'xstore-core') => '', 
                        esc_html__('Featured', 'xstore-core') => 'featured', 
                        esc_html__('Sale', 'xstore-core') => 'sale', 
                        esc_html__('Recently viewed', 'xstore-core') => 'recently_viewed', 
                        esc_html__('Bestsellings', 'xstore-core') => 'bestsellings'
                    )
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => $strings['heading']['orderby'],
                    'group' => esc_html__( 'Products Data', 'xstore-core' ),
                    'param_name' => 'orderby',
                    'value' => $order_by_values,
                    'hint' => sprintf( esc_html__( 'Select how to sort retrieved products. More at %s. Default by Title', 'xstore-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
                    'edit_field_class' => 'vc_col-sm-4 vc_column',
                ),
                array(
                    'type' => 'xstore_button_set',
                    'heading' => $strings['heading']['order'],
                    'group' => esc_html__( 'Products Data', 'xstore-core' ),
                    'param_name' => 'order',
                    'value' => $strings['value']['order'],
                    'hint' => sprintf( esc_html__( 'Designates the ascending or descending order. More at %s. Default by ASC', 'xstore-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
                    'edit_field_class' => 'vc_col-sm-4 vc_column',
                ),
                array(
                    'type' => 'xstore_button_set',
                    'heading' => esc_html__('Hide out of stock products', 'xstore-core'),
                    'hint' => esc_html__( 'Show/hide out of stock products', 'xstore-core' ),
                    'group' => esc_html__( 'Products Data', 'xstore-core' ),
                    'param_name' => 'hide_out_stock',
                    'value' => array(
                        'No' => false,
                        'Yes' => 'yes',
                    ),
                    'edit_field_class' => 'vc_col-sm-4 vc_column',
                ),
                array(
                    'type' => 'autocomplete',
                    'heading' => esc_html__( 'Products', 'xstore-core' ),
                    'group' => esc_html__( 'Products Data', 'xstore-core' ),
                    'param_name' => 'ids',
                    'settings' => array(
                        'multiple' => true,
                        'sortable' => true,
                        'groups'   => true,
                        'unique_values' => true,
                    ),
                    'save_always' => true,
                    'hint' => esc_html__( 'Enter List of Products.', 'xstore-core' ),
                ),
                array(
                    'type' => 'autocomplete',
                    'heading' => esc_html__( 'Categories or tags', 'xstore-core' ),
                    'group' => esc_html__( 'Products Data', 'xstore-core' ),
                    'param_name' => 'taxonomies',
                    'settings' => array(
                        'multiple' => true,
                        // is multiple values allowed? default false
                        // 'sortable' => true, // is values are sortable? default false
                        'min_length' => 1,
                        // min length to start search -> default 2
                        // 'no_hide' => true, // In UI after select doesn't hide an select list, default false
                        'groups' => true,
                        // In UI show results grouped by groups, default false
                        'unique_values' => true,
                        // In UI show results except selected. NB! You should manually check values in backend, default false
                        'display_inline' => true,
                        // In UI show results inline view, default false (each value in own line)
                        'delay' => 500,
                        // delay for search. default 500
                        'auto_focus' => true,
                        // auto focus input, default true
                    ),
                    'param_holder_class' => 'vc_not-for-custom',
                    'hint' => esc_html__( 'Enter categories, tags or custom taxonomies.', 'xstore-core' ),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Limit', 'xstore-core'),
                    'group' => esc_html__( 'Products Data', 'xstore-core' ),
                    'param_name' => 'limit',
                    'hint' => sprintf( esc_html__( 'Use "-1" to show all products.', 'xstore-core' ) )
                ),
            ), etheme_get_slider_params(),
            array(
                array(
                  'type' => 'css_editor',
                  'heading' => esc_html__( 'CSS box', 'xstore-core' ),
                  'param_name' => 'css',
                  'group' => esc_html__( 'Design', 'xstore-core' )
                ),
            )
            ),
        );

        vc_map($params);
    }
}
