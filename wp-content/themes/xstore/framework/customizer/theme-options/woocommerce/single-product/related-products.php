<?php  
	/**
	 * The template created for displaying shop related products options 
	 *
	 * @version 0.0.1
	 * @since 6.0.0
	 */
	
	// section shop-related-products
	Kirki::add_section( 'shop-related-products', array(
	    'title'          => esc_html__( 'Related Products', 'xstore' ),
	    'panel' => 'single-product-page',
	    'icon' => 'dashicons-networking'
		) );

		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'toggle',
			'settings'    => 'show_related',
			'label'       => esc_html__( 'Display related products', 'xstore' ),
			'description' => esc_html__( 'Turn on to display related products on  the single product page.', 'xstore' ),
			'section'     => 'shop-related-products',
			'default'     => 1,
		) );

		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'select',
			'settings'    => 'related_type',
			'label'       => esc_html__('Related products type', 'xstore'),
			'description' => esc_html__( 'Choose the design type of the related products.', 'xstore' ),
			'section'     => 'shop-related-products',
			'default'     => 'slider',
			'choices'     => array(
				'slider' => esc_html__('Slider', 'xstore'),
                'grid'   => esc_html__('Grid', 'xstore'),
			),
			'active_callback' => array(
				array(
					'setting'  => 'show_related',
					'operator' => '==',
					'value'    => true,
				),
			)
		) );

		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'dimensions',
			'settings'    => 'related_slides',
			'label'       => esc_html__('Related products per view', 'xstore'),
			'description' => esc_html__( 'Controls the number of related products per view for every device.', 'xstore' ),
			'section'     => 'shop-related-products',
			'default'     => array(
				'padding-top'  => '',
				'padding-right'  => '',
				'padding-bottom' => '',
				'padding-left' => '',
			),
			'choices'     => array(
				'labels' => array(
					'padding-top'  => esc_html__( 'Large', 'xstore' ),
					'padding-right'  => esc_html__( 'Notebook', 'xstore' ),
					'padding-bottom' => esc_html__( 'Tablet landscape', 'xstore' ),
					'padding-left' => esc_html__( 'Tablet portrait', 'xstore' ),
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'show_related',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'related_type',
					'operator' => '==',
					'value'    => 'slider',
				),
			)
		) );

		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'select',
			'settings'    => 'related_columns',
			'label'       => esc_html__( 'Related products columns', 'xstore' ),
			'description' => esc_html__( 'Controls the number of columns of the related products.', 'xstore' ),
			'section'     => 'shop-related-products',
			'default'     => 4,
			'choices'     => array(
				'2' => 2,
                '3' => 3,
                '4' => 4,
                '5' => 5,
                '6' => 6,
			),
			'active_callback' => array(
				array(
					'setting'  => 'show_related',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'related_type',
					'operator' => '==',
					'value'    => 'grid',
				),
			)
		) );

		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'slider',
			'settings'    => 'related_limit',
			'label'       => esc_html__( 'Related products limit', 'xstore' ),
			'description' => esc_html__( 'Limits the number of the related products.', 'xstore' ),
			'section'     => 'shop-related-products',
			'default'     => 10,
			'choices'     => array(
				'min'  => 1,
				'max'  => 30,
				'step' => 1,
			),
			'active_callback' => array(
				array(
					'setting'  => 'show_related',
					'operator' => '==',
					'value'    => true,
				),
			)
		) );
?>