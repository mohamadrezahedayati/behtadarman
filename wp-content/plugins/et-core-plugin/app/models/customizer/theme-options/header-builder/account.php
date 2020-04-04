<?php 
	/**
	 * The template created for displaying header account options 
	 *
	 * @version 1.0.5
	 * @since 1.4.0
  	 * last changes in 2.0.0
	 */ 

	// section account
	Kirki::add_section( 'account', array(
	    'title'          => esc_html__( 'Account', 'xstore-core' ),
	    'panel' => 'header-builder',
	    'icon' => 'dashicons-admin-users'
		) );

		// content separator
		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'custom',
			'settings'    => 'account_content_separator',
			'section'     => 'account',
			'default'     => $separators['content'],
			'priority'    => 10,
		) );

		// account_style
		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'radio-image',
			'settings'    => 'account_style_et-desktop',
			'label'       => $strings['label']['style'],
			'section'     => 'account',
			'default'     => 'type1',
			'choices'     => et_b_element_styles('account'),
			'transport' => 'postMessage',
			'partial_refresh' => array(
				'account_style_et-desktop' => array(
					'selector'  => '.et_b_header-account.et_element-top-level',
					'render_callback' => 'header_account_callback'
				),
			),
			'js_vars'     => array(
				array(
					'element'  => '.et_b_header-account.et_element-top-level',
					'function' => 'toggleClass',
					'class' => 'account-type1',
					'value' => 'type1'
				),
				array(
					'element'  => '.et_b_header-account.et_element-top-level',
					'function' => 'toggleClass',
					'class' => 'account-type2',
					'value' => 'type2'
				),
				array(
					'element'  => '.et_b_header-account.et_element-top-level',
					'function' => 'toggleClass',
					'class' => 'account-type3',
					'value' => 'type3'
				),
			),
		) );

		// account_icon
		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'radio-image',
			'settings'    => 'account_icon_et-desktop',
			'label'       => $strings['label']['icon'],
			'description' => $strings['description']['icons_style'],
			'section'     => 'account',
			'default'     => 'type1',
			'choices'     => array(
				'type1'   => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/account/Account-1.svg',
				'custom'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-custom.svg',
				'none'    => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-none.svg' 
			),
			'transport' => 'postMessage',
			'partial_refresh' => array(
				'account_icon_et-desktop' => array(
					'selector'        => '.header-wrapper .et_b_header-account > a .et_b-icon',
					'render_callback' => function() {
						global $et_account_icons;
						$type = get_theme_mod('account_icon_et-desktop');
						if ( $type == 'custom' ) {
							return get_theme_mod('account_icon_custom_et-desktop');
						}
						return $et_account_icons['light'][$type];
					},
				),
			),
		) );

		// account_icon
		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'radio-image',
			'settings'    => 'account_icon_et-mobile',
			'label'       => $strings['label']['icon'],
			'description' => $strings['description']['icons_style'],
			'section'     => 'account',
			'default'     => 'type1',
			'choices'     => array(
				'type1'   => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/account/Account-1.svg',
				'custom'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-custom.svg',
				'none'    => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-none.svg' 
			),
			'transport' => 'postMessage',
			'partial_refresh' => array(
				'account_icon_et-mobile' => array(
					'selector'        => '.mobile-header-wrapper .et_b_header-account > a .et_b-icon',
					'render_callback' => function() {
						global $et_account_icons;
						$type = get_theme_mod('account_icon_et-mobile');
						if ( $type == 'custom' ) {
							return get_theme_mod('account_icon_custom_et-mobile');
						}
						return $et_account_icons['light'][$type];
					},
				),
			),
		) );

		// account_icon_custom
		Kirki::add_field( 'et_kirki_options', array (
			'type'     => 'code',
			'settings' => 'account_icon_custom_et-desktop',
			'label'    => $strings['label']['custom_icon_svg'],
			'section'  => 'account',
			'default'  => '',
			'choices'  => array(
				'language' => 'html'
			),
			'active_callback' => array(
				array(
					'setting'  => 'account_icon_et-desktop',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport' => 'postMessage',
			'partial_refresh' => array(
				'account_icon_custom_et-desktop' => array(
					'selector'        => '.header-wrapper .et_b_header-account > a .et_b-icon',
					'render_callback' => function() {
						$icon = get_theme_mod('account_icon_custom_et-desktop');
						return $icon;
					},
				),
			),
		) );

		// account_icon_custom
		Kirki::add_field( 'et_kirki_options', array (
			'type'     => 'textarea',
			'settings' => 'account_icon_custom_et-mobile',
			'label'    => $strings['label']['custom_icon_svg'],
			'section'  => 'account',
			'default'  => '',
			'active_callback' => array(
				array(
					'setting'  => 'account_icon_et-mobile',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport' => 'postMessage',
			'partial_refresh' => array(
				'account_icon_custom_et-mobile' => array(
					'selector'        => '.mobile-header-wrapper .et_b_header-account > a .et_b-icon',
					'render_callback' => function() {
						$icon = get_theme_mod('account_icon_custom_et-mobile');
						return $icon;
					},
				),
			),
		) );

		// account_icon_zoom 
		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'slider',
			'settings'    => 'account_icon_zoom_et-desktop',
			'label'       => $strings['label']['icons_zoom'],
			'section'     => 'account',
			'default'     => 1.3,
			'choices'     => array(
				'min'  => '.7',
				'max'  => '3',
				'step' => '.1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'account_icon_et-desktop',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
			'transport' => 'auto',
			'output' => array(
				array(
					'element' => '.et_b_header-account.et_element-top-level > a svg',
					'property' => 'width',
					'units' => 'em'
				),
				array(
					'element' => '.et_b_header-account.et_element-top-level > a svg',
					'property' => 'height',
					'units' => 'em'
				)
			)
		) );

		// account_icon_zoom 
		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'slider',
			'settings'    => 'account_icon_zoom_et-mobile',
			'label'       => $strings['label']['icons_zoom'],
			'section'     => 'account',
			'default'     => 1.4,
			'choices'     => array(
				'min'  => '.7',
				'max'  => '3',
				'step' => '.1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'account_icon_et-mobile',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
			'transport' => 'auto',
			'output' => array(
				array(
					'element' => '.mobile-header-wrapper .et_b_header-account.et_element-top-level > a svg',
					'property' => 'width',
					'units' => 'em'
				),
				array(
					'element' => '.mobile-header-wrapper .et_b_header-account.et_element-top-level > a svg',
					'property' => 'height',
					'units' => 'em'
				)
			)
		) );

		// account_content_type
		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'radio-buttonset',
			'settings'    => 'account_content_type_et-desktop',
			'label'       => esc_html__( 'Content type', 'xstore-core' ),
			'description' => esc_html__( 'Popup will apply only for unlogged in users. To use dropdown option, please set up WooCommerce plugin', 'xstore-core' ),
			'section'     => 'account',
			'default'     => 'dropdown',
			'choices'     => array(
				'dropdown' => esc_html__( 'Dropdown', 'xstore-core' ),
				'none' => esc_html__( 'None', 'xstore-core' ),
			),
			'transport' => 'postMessage',
			'partial_refresh' => array(
				'account_content_type_et-desktop' => array(
					'selector'  => '.et_b_header-account.et_element-top-level',
					'render_callback' => 'header_account_callback'
				),
			),
		) );

		// advanced separator
		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'custom',
			'settings'    => 'account_label_separator',
			'section'     => 'account',
			'default'     => '<div style="'.$sep_style.'"><span class="dashicons dashicons-admin-users"></span> <span style="padding-left: 3px;">' . esc_html__( 'Title', 'xstore-core' ) . '</span></div>',
			'priority'    => 10,
		) );

		// account_label
		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'toggle',
			'settings'    => 'account_label_et-desktop',
			'label'       => $strings['label']['show_title'],
			'section'     => 'account',
			'default'     => 1,
			'transport' => 'postMessage',
			'js_vars'     => array(
				array(
					'element'  => '.et_b_header-account.et_element-top-level > a .et-element-label',
					'function' => 'toggleClass',
					'class' => 'dt-hide',
					'value' => false
				),
			),
		) );

		// account_label
		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'toggle',
			'settings'    => 'account_label_et-mobile',
			'label'       => $strings['label']['show_title'],
			'section'     => 'account',
			'default'     => 0,
			'transport' => 'postMessage',
			'js_vars'     => array(
				array(
					'element'  => '.et_b_header-account.et_element-top-level > a .et-element-label',
					'function' => 'toggleClass',
					'class' => 'mob-hide',
					'value' => false
				),
			),
		) );

		// account_label_username
		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'toggle',
			'settings'    => 'account_label_username',
			'label'       => esc_html__( 'Show username as title', 'xstore-core' ),
			'section'     => 'account',
			'default'     => "0",
			'active_callback' => function() {
				if ( get_theme_mod( 'account_label_et-desktop', false ) || get_theme_mod( 'account_label_et-mobile', false ) ) {
					return true;
				}
				return false;
			},
			'transport' => 'postMessage',
			'partial_refresh' => array(
				'account_label_username' => array(
					'selector'  => '.et_b_header-account > a .et-element-label',
					'render_callback' => function() {
						$element_options = array();
						if ( get_theme_mod( 'account_label_username' ) ) {
							$element_options['current_user_et-desktop'] = wp_get_current_user();
							$element_options['account_label_text'] = $element_options['current_user_et-desktop']->user_login;
						}
						else {
							$element_options['account_logged_in_text'] = get_theme_mod( 'account_logged_in_text' );
							$element_options['account_label_text'] = ( $element_options['account_logged_in_text'] != '' ) ? $element_options['account_logged_in_text'] : esc_html__('My account', 'xstore-core');
						}
						return $element_options['account_label_text'];
					},
				),
			),
		) );

		// account_text
		Kirki::add_field( 'et_kirki_options', array (
			'type'     => 'text',
			'settings' => 'account_text',
			'label'    => esc_html__( 'Sign in text', 'xstore-core' ),
			'section'  => 'account',
			'default'  => esc_html__( 'Sign in', 'xstore-core' ),
			'transport' => 'postMessage',
		) );

		// account_logged_in_text
		Kirki::add_field( 'et_kirki_options', array (
			'type'     => 'text',
			'settings' => 'account_logged_in_text',
			'label'    => esc_html__( 'My account text', 'xstore-core' ),
			'section'  => 'account',
			'default'  => esc_html__( 'My account', 'xstore-core' ),
			'transport' => 'postMessage',
			'partial_refresh' => array(
				'account_logged_in_text' => array(
					'selector'  => '.et_b_header-account > a .et-element-label',
					'render_callback' => function() {
						$element_options = array();
						if ( get_theme_mod( 'account_label_username' ) ) {
							$element_options['current_user_et-desktop'] = wp_get_current_user();
							$element_options['account_label_text'] = $element_options['current_user_et-desktop']->user_login;
						}
						else {
							$element_options['account_logged_in_text'] = get_theme_mod( 'account_logged_in_text' );
							$element_options['account_label_text'] = ( $element_options['account_logged_in_text'] != '' ) ? $element_options['account_logged_in_text'] : esc_html__('My account', 'xstore-core');
						}
						return $element_options['account_label_text'];
					},
				),
			),
		) );

		// style separator
		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'custom',
			'settings'    => 'account_style_separator',
			'section'     => 'account',
			'default'     => $separators['style'],
			'priority'    => 10,
		) );

		// account_content_alignment
		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'radio-buttonset',
			'settings'    => 'account_content_alignment_et-desktop',
			'label'       => $strings['label']['alignment'],
			'section'     => 'account',
			'default'     => 'start',
			'choices'     => $choices['alignment'],
			'transport' => 'postMessage',
			'js_vars'     => array(
				array(
					'element'  => '.et_b_header-account.et_element-top-level > a',
					'function' => 'toggleClass',
					'class' => 'justify-content-start',
					'value' => 'start'
				),
				array(
					'element'  => '.et_b_header-account.et_element-top-level > a',
					'function' => 'toggleClass',
					'class' => 'justify-content-center',
					'value' => 'center'
				),
				array(
					'element'  => '.et_b_header-account.et_element-top-level > a',
					'function' => 'toggleClass',
					'class' => 'justify-content-end',
					'value' => 'end'
				),
			),
		) );

		// account_content_alignment
		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'radio-buttonset',
			'settings'    => 'account_content_alignment_et-mobile',
			'label'       => $strings['label']['alignment'],
			'section'     => 'account',
			'default'     => 'start',
			'choices'     => $choices['alignment'],
			'transport' => 'postMessage',
			'js_vars'     => array(
				array(
					'element'  => '.et_b_header-account.et_element-top-level > a',
					'function' => 'toggleClass',
					'class' => 'mob-justify-content-start',
					'value' => 'start'
				),
				array(
					'element'  => '.et_b_header-account.et_element-top-level > a',
					'function' => 'toggleClass',
					'class' => 'mob-justify-content-center',
					'value' => 'center'
				),
				array(
					'element'  => '.et_b_header-account.et_element-top-level > a',
					'function' => 'toggleClass',
					'class' => 'mob-justify-content-end',
					'value' => 'end'
				),
			),
		) );

		// account_background
		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'select',
			'settings'    => 'account_background_et-desktop',
			'label'       => $strings['label']['colors'],
			'section'     => 'account',
			'default'     => 'current',
			'choices'     => $choices['colors'],
			'output'      => array(
				array(
					'element' => '.et_b_header-account.et_element-top-level > a',
					'property' => 'color',
					'value_pattern' => 'var(--$-color)'
				),
			),
		) );

		// account_background_custom
		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'color',
			'settings'    => 'account_background_custom_et-desktop',
			'label'       => $strings['label']['bg_color'],
			'section'     => 'account',
			'default' 	  => '#ffffff',
			'choices' 	  => array (
				'alpha' => true
			),
			'active_callback' => array(
				array(
					'setting'  => 'account_background_et-desktop',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport' => 'auto',
			'output' => array(
				array(
					'element' => '.et_b_header-account.et_element-top-level > a',
					'property' => 'background-color',
				)
			),
		) );

		// account_color
		Kirki::add_field( 'et_kirki_options', array(
			'settings'    => 'account_color_et-desktop',
			'label'       => $strings['label']['wcag_color'],
			'description' => $strings['description']['wcag_color'],
			'type'        => 'kirki-wcag-tc',
			'section'     => 'account',
			'default'     => '#000000',
			'choices'     => array(
				'setting' => 'account_background_custom_et-desktop',
				// 'maxHueDiff'          => 60,   // Optional.
				// 'stepHue'             => 15,   // Optional.
				// 'maxSaturation'       => 0.5,  // Optional.
				// 'stepSaturation'      => 0.1,  // Optional.
				// 'stepLightness'       => 0.05, // Optional.
				// 'precissionThreshold' => 6,    // Optional.
				// 'contrastThreshold'   => 4.5   // Optional.	
				'show'    => array(
					// 'auto'        => false,
					// 'custom'      => false,
					'recommended' => false,
				),	
			),
			'active_callback' => array(
				array(
					'setting'  => 'account_background_et-desktop',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport' => 'auto',
			'output'	  => array(
				array(
					'element' => '.et_b_header-account.et_element-top-level > a',
					'property' => 'color'
				)
			),
		) );

		// account_border_radius
		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'slider',
			'settings'    => 'account_border_radius_et-desktop',
			'label'       => $strings['label']['border_radius'],
			'section'     => 'account',
			'default'     => 0,
			'choices'     => array(
				'min'  => '0',
				'max'  => '100',
				'step' => '1',
			),
			'transport' => 'auto',
			'output' => array(
				array(
					'element' => '.et_b_header-account.et_element-top-level > a',
					'property' => 'border-radius',
					'units' => 'px'
				)
			)
		) );

		// account_box_model
		Kirki::add_field( 'et_kirki_options', array(
			'settings'    => 'account_box_model_et-desktop',
			'label'       => $strings['label']['computed_box'],
			'description' => esc_html__( 'You can select the margin, border-width and padding for account element.', 'xstore-core' ),
			'type'        => 'kirki-box-model',
			'section'     => 'account',
			'default'     => array(
				'margin-top'          => '0px',
				'margin-right'        => '0px',
				'margin-bottom'       => '0px',
				'margin-left'         => '0px',
				'border-top-width'    => '0px',
				'border-right-width'  => '0px',
				'border-bottom-width' => '0px',
				'border-left-width'   => '0px',
				'padding-top'         => '5px',
				'padding-right'       => '0px',
				'padding-bottom'      => '5px',
				'padding-left'        => '0px',
			),
			'output'      => array(
				array(
					'element' => '.et_b_header-account.et_element-top-level > a',
				),
			),
			'transport' => 'postMessage',
	        'js_vars'   => box_model_output('.et_b_header-account.et_element-top-level > a')
		) );

		// account_box_model
		Kirki::add_field( 'et_kirki_options', array(
			'settings'    => 'account_box_model_et-mobile',
			'label'       => $strings['label']['computed_box'],
			'description' => esc_html__( 'You can select the margin, border-width and padding for account element.', 'xstore-core' ),
			'type'        => 'kirki-box-model',
			'section'     => 'account',
			'default'     => $box_models['empty'],
			'output'      => array(
				array(
					'element' => '.mobile-header-wrapper .et_b_header-account.et_element-top-level > a',
				),
			),
			'transport' => 'postMessage',
	        'js_vars'   => box_model_output('.mobile-header-wrapper .et_b_header-account.et_element-top-level > a')
		) );

		// account_border
		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'select',
			'settings'    => 'account_border_et-desktop',
			'label'       => $strings['label']['border_style'],
			'section'     => 'account',
			'default'     => 'solid',
			'choices'     => $choices['border_style'],
			'transport' => 'auto',
			'output'      => array(
				array(
					'element' => '.et_b_header-account.et_element-top-level > a',
					'property' => 'border-style',
				),
			),
		) );

		// account_border_color_custom
		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'color',
			'settings'    => 'account_border_color_custom_et-desktop',
			'label'       => $strings['label']['border_color'],
			'description' => $strings['description']['border_color'],
			'section'     => 'account',
			'default' 	  => '#e1e1e1',
			'choices' 	  => array (
				'alpha' => true
			),
			'transport' => 'auto',
			'output'      => array(
				array(
					'element' => '.et_b_header-account.et_element-top-level > a',
					'property' => 'border-color',
				),
			),
		) );

		// content separator 
		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'custom',
			'settings'    => 'account_content_dropdown_separator',
			'section'     => 'account',
			'default'     => '<div style="'.$sep_style.'"><span class="dashicons dashicons-images-alt"></span> <span style="padding-left: 3px;">' . esc_html__( 'Dropdown', 'xstore-core' ) . '</span></div>',
			'priority'    => 10,
			'active_callback' => array(
				array(
					'setting'  => 'account_content_type_et-desktop',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
		) );

		// account_zoom
		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'slider',
			'settings'    => 'account_zoom_et-desktop',
			'label'       => esc_html__( 'Dropdown Content size (%)', 'xstore-core' ),
			'section'     => 'account',
			'default'     => 100,
			'choices'     => array(
				'min'  => '10',
				'max'  => '200',
				'step' => '1',
			),
			'transport' => 'auto',
			'output'      => array(
				array(
					'element' => '.et_b_header-account.et_element-top-level .et-mini-content, .et_b_header-account.et_element-top-level-popup',
					'property' => '--content-zoom',
					'value_pattern' => 'calc($em * .01)'
				),
			),
		) );

		// account_dropdown_position
		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'radio-buttonset',
			'settings'    => 'account_dropdown_position_et-desktop',
			'label'       => esc_html__( 'Dropdown position', 'xstore-core' ),
			'section'     => 'account',
			'default'     => 'right',
			'multiple'    => 1,
			'choices'     => $choices['dropdown_position'],
			'active_callback' => array(
				array(
					'setting'  => 'account_content_type_et-desktop',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
			'transport' => 'postMessage',
			'js_vars'     => array(
				array(
					'element'  => '.et_b_header-account',
					'function' => 'toggleClass',
					'class' => 'et-content-right',
					'value' => 'right'
				),
				array(
					'element'  => '.et_b_header-account',
					'function' => 'toggleClass',
					'class' => 'et-content-left',
					'value' => 'left'
				),
			),
		) );

		// account_dropdown_position_custom
		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'slider',
			'settings'    => 'account_dropdown_position_custom_et-desktop',
			'label'       => esc_html__( 'Dropdown offset', 'xstore-core' ),
			'section'     => 'account',
			'default'     => 0,
			'choices'     => array(
				'min'  => '-300',
				'max'  => '300',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'account_content_type_et-desktop',
					'operator' => '!=',
					'value'    => 'none',
				),
				array(
					'setting'  => 'account_dropdown_position_et-desktop',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport' => 'auto',
			'output'      => array(
				array(
					'element' => '.et_b_header-account.et_element-top-level.et-content-dropdown .et-mini-content',
					'property' => 'left',
					'units' => 'px'
				),
			),
		) );

		// account_dropdown_background_custom
		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'color',
			'settings'    => 'account_dropdown_background_custom_et-desktop',
			'label'       => esc_html__( 'Dropdown Background', 'xstore-core' ),
			'section'     => 'account',
			'choices' 	  => array (
				'alpha' => true
			),
			'default' => '#ffffff',
			'transport' => 'auto',
			'output'      => array(
				array(
					'element' => '.et_b_header-account.et_element-top-level .et-mini-content',
					'property' => 'background-color',
				),
			),
		) );

		// account_dropdown_color 
		Kirki::add_field( 'et_kirki_options', array(
			'settings'    => 'account_dropdown_color_et-desktop',
			'label'       => esc_html__( 'WCAG Dropdown Color', 'xstore-core' ),
			'description' => $strings['description']['wcag_color'],
			'type'        => 'kirki-wcag-tc',
			'section'     => 'account',
			'default'     => '#000000',
			'choices'     => array(
				'setting' => 'account_dropdown_background_custom_et-desktop',
				// 'maxHueDiff'          => 60,   // Optional.
				// 'stepHue'             => 15,   // Optional.
				// 'maxSaturation'       => 0.5,  // Optional.
				// 'stepSaturation'      => 0.1,  // Optional.
				// 'stepLightness'       => 0.05, // Optional.
				// 'precissionThreshold' => 6,    // Optional.
				// 'contrastThreshold'   => 4.5   // Optional.	
				'show'    => array(
					// 'auto'        => false,
					// 'custom'      => false,
					'recommended' => false,
				),	
			),
			'transport' => 'auto',
			'output'	  => array(
				array(
					'element' => '.et_b_header-account.et_element-top-level .et-mini-content',
					'property' => 'color'
				)
			),
		) );

		// account_content_box_model
		Kirki::add_field( 'et_kirki_options', array(
			'settings'    => 'account_content_box_model_et-desktop',
			'label'       => esc_html__( 'Dropdown Computed box', 'xstore-core' ),
			'description' => esc_html__( 'You can select the margin, border-width and padding for dropdown element.', 'xstore-core' ),
			'type'        => 'kirki-box-model',
			'section'     => 'account',
			'default'     => array(
				'margin-top'          => '0px',
				'margin-right'        => '0px',
				'margin-bottom'       => '0px',
				'margin-left'         => '0px',
				'border-top-width'    => '0px',
				'border-right-width'  => '0px',
				'border-bottom-width' => '0px',
				'border-left-width'   => '0px',
				'padding-top'         => '20px',
				'padding-right'       => '30px',
				'padding-bottom'      => '30px',
				'padding-left'        => '30px',
			),
			'active_callback' => array(
				array(
					'setting'  => 'account_content_type_et-desktop',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
			'transport' => 'auto',
			'output'      => array(
				array(
					'element' => '.et_b_header-account.et_element-top-level .et-mini-content',
				),
			),
		) );

		// account_content_border
		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'select',
			'settings'    => 'account_content_border_et-desktop',
			'label'       => esc_html__( 'Dropdown Border style', 'xstore-core' ),
			'section'     => 'account',
			'default'     => 'solid',
			'choices'     => $choices['border_style'],
			'active_callback' => array(
				array(
					'setting'  => 'account_content_type_et-desktop',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
			'transport' => 'auto',
			'output'      => array(
				array(
					'element' => '.et_b_header-account.et_element-top-level .et-mini-content',
					'property' => 'border-style',
				),
			),
		) );

		// account_border_color_custom
		Kirki::add_field( 'et_kirki_options', array(
			'type'        => 'color',
			'settings'    => 'account_content_border_color_custom_et-desktop',
			'label'       => esc_html__( 'Dropdown Border color', 'xstore-core' ),
			'description' => $strings['description']['border_color'],
			'section'     => 'account',
			'default' 	  => '#e1e1e1',
			'choices' 	  => array (
				'alpha' => true
			),
			'active_callback' => array(
				array(
					'setting'  => 'account_content_type_et-desktop',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
			'transport' => 'auto',
			'output'      => array(
				array(
					'element' => '.et_b_header-account.et_element-top-level .et-mini-content',
					'property' => 'border-color',
				),
			),
		) );
?>