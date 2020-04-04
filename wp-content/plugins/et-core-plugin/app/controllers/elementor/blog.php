<?php
namespace ETC\App\Controllers\Elementor;

use ETC\App\Traits\Elementor;
use ETC\App\Controllers\Shortcodes\Blog as Blog_Shortcode;

/**
 * Blog widget.
 *
 * @since      2.1.3
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Blog extends \Elementor\Widget_Base {

	use Elementor;

	/**
	 * Get widget name.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'et_blog';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Blog', 'xstore-core' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-post';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'blog' ];
	}

    /**
     * Get widget categories.
     *
     * @since 2.1.3
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
    	return ['eight_theme'];
    }

	/**
	 * Register Blog widget controls.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'settings',
			[
				'label' => __( 'Blog Settings', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'post_type',
			[
				'label' 		=>	__( 'Data source', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'description' 	=>	__( 'Select content type for your grid.', 'xstore-core' ),
				'options' 		=>	[
					'post'	=>	esc_html__( 'Post', 'xstore-core' ),
					'ids'	=>	esc_html__( 'List of IDs', 'xstore-core' ),
				],
			]
		);

		$this->add_control(
			'include',
			[
				'label' 		=>	__( 'Include only', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT2,
				'label_block'	=> 'true',
				'description' 	=>	__( 'Add posts, pages, etc. by title.', 'xstore-core' ),
				'multiple' 		=>	true,
				'options' 		=>	Elementor::get_post_pages(),
				'condition' 	=>	['post_type' => 'ids'],
				'show_label'	=> true
			]
		);

		$this->add_control(
			'taxonomies',
			[
				'label' 		=>	__( 'Narrow data source', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT2,
				'label_block'	=> 'true',
				'description' 	=>	__( 'Enter categories, tags or custom taxonomies', 'xstore-core' ),
				'multiple' 		=>	true,
				'options' 		=>	Elementor::get_terms( get_object_taxonomies( 'post' ) ),
				'condition' 	=>	['post_type' => 'post'],
			]
		);

		$this->add_control(
			'items_per_page',
			[
				'label' 		=>	__( 'Items per page', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'description' 	=>	__( 'Number of items to show per page', 'xstore-core' ),
				'default'	 	=>	'10',
			]
		);

		$this->add_control(
			'items_limit',
			[
				'label' 		=>	__( 'Limit', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'default'	 	=>	'10',
			]
		);

		$this->add_control(
			'size',
			[
				'label' 		=>	__( 'Images size', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'description' 	=>	__( 'Enter image size (Ex.: "medium", "large" etc.) or enter size in pixels (Ex.: 200x100 (WxH))', 'xstore-core' ),
			]
		);

		$this->add_control(
			'blog_hover',
			[
				'label' 		=>	__( 'Blog hover effect', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'zoom'		=>	esc_html__( 'Zoom', 'xstore-core' ),
					'default'	=>	esc_html__( 'Default', 'xstore-core' ),
					'animated'	=>	esc_html__( 'Animated', 'xstore-core' ),
				],
			]
		);

		$this->add_control(
			'blog_align',
			[
				'label' 		=>	__( 'Blog align', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'left'		=>	esc_html__('Left', 'xstore-core'), 
					'center'	=>	esc_html__('Center', 'xstore-core'), 
					'right'		=>	esc_html__('Right', 'xstore-core'), 
				],
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' 	=>	__( 'Order by', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::SELECT,
				'description' 	=>	sprintf( esc_html__( 'Select how to sort retrieved products. More at %s.', 'xstore-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
				'multiple' 	=>	true,
				'options' 	=>	array(
					'date'				=>	esc_html__( 'Date', 'xstore-core' ),
					'ID'				=>	esc_html__( 'Order by post ID', 'xstore-core' ),
					'author'			=>	esc_html__( 'Author', 'xstore-core' ),
					'title'				=>	esc_html__( 'Title', 'xstore-core' ),
					'modified'			=>	esc_html__( 'Last modified date', 'xstore-core' ),
					'parent'			=>	esc_html__( 'Post/page parent ID', 'xstore-core' ),
					'comment_count'		=>	esc_html__( 'Number of comments', 'xstore-core' ),
					'menu_order'		=>	esc_html__( 'Menu order/Page Order', 'xstore-core' ),
					'meta_value'		=>	esc_html__( 'Meta value', 'xstore-core' ),
					'meta_value_num'	=>	esc_html__( 'Meta value number', 'xstore-core' ),
					'rand'				=>	esc_html__( 'Random order', 'xstore-core' ),
				),
				'default' 	=>	'',
			]
		);

		$this->add_control(
			'order',
			[
				'label' 	=>	__( 'Order way', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::SELECT,
				'description' 	=> sprintf( esc_html__( 'Designates the ascending or descending order. More at %s.', 'xstore-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
				'multiple' 	=>	true,
				'options' 	=>	array(
					'ASC' 	=> esc_html__( 'Ascending', 'xstore-core' ),
					'DESC' 	=> esc_html__( 'Descending', 'xstore-core' ),
				),
				'default' 	=>	'',
				'condition' =>	['post_type' => 'post'],
			]
		);

		$this->add_control(
			'meta_key',
			[
				'label' 		=>	__( 'Meta key', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'description' 	=>	__( 'Input meta key for grid ordering', 'xstore-core' ),
				'condition' 	=>	['orderby' => array( 'meta_value', 'meta_value_num' )],
			]
		);

		$this->add_control(
			'exclude',
			[
				'label' 		=>	__( 'Exclude', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT2,
				'label_block'	=> 'true',
				'description' 	=>	__( 'Exclude posts, pages, etc. by title.', 'xstore-core' ),
				'multiple' 		=>	true,
				'options' 		=>	Elementor::get_post_pages(),
				'condition' 	=>	['post_type' => 'post'],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render Blog widget output on the frontend.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$settings['include']	=	!empty( $settings['include'] ) ? implode( ',', $settings['include'] ) : '';
		$settings['exclude']	=	!empty( $settings['exclude'] ) ? implode( ',', $settings['exclude'] ) : '';

		$atts = array(
			'post_type'			=>	$settings['post_type'],
			'include'			=>	$settings['include'],
			'taxonomies'		=>	$settings['taxonomies'],
			'items_per_page'	=>	$settings['items_per_page'],
			'items_limit'		=>	$settings['items_limit'],
			'blog_hover'		=>	$settings['blog_hover'],
			'blog_align'		=>	$settings['blog_align'],
			'size'				=>	$settings['size'],
			'orderby'			=>	$settings['orderby'],
			'order'				=>	$settings['order'],
			'meta_key'			=>	$settings['meta_key'],
			'exclude'			=>	$settings['exclude'],
		);


		$Blog = Blog_Shortcode::get_instance();
		echo $Blog->blog_shortcode($atts,'');

	}

}
