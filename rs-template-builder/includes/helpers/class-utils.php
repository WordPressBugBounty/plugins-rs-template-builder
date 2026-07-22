<?php

namespace RsTemplateBuilder\Helpers;

defined( 'ABSPATH' ) || exit;

class Utils {

	public static function get_elementor_content( $content_id ): string {
		$content = '';

		if ( ! empty( $content_id ) && \class_exists( '\Elementor\Plugin' ) ) {
			$elementor_instance = \Elementor\Plugin::instance();
			$content            = $elementor_instance->frontend->get_builder_content_for_display( $content_id );
		}

		return $content;
	}

	public static function get_popup_data( $id, $always_on = false ): array {
		$tb_settings = get_post_meta( $id, '_rstb_settings', true );

		$wrapper_class = $always_on ? 'rstb-template-popup show-popup always-on' : 'rstb-template-popup';

		$pp_position   = $tb_settings[ 'pp_position' ] ?? '';
		$wrapper_style = '';

		switch ( $pp_position ) {
			case 'top-center':
				$wrapper_style = 'align-items: flex-start; justify-content: center;';
				break;
			case 'top-left':
				$wrapper_style = 'align-items: flex-start; justify-content: flex-start;';
				break;
			case 'top-right':
				$wrapper_style = 'align-items: flex-start; justify-content: flex-end;';
				break;
			case 'left-center':
				$wrapper_style = 'align-items: center; justify-content: flex-start;';
				break;
			case 'center-center':
				$wrapper_style = 'align-items: center; justify-content: center;';
				break;
			case 'right-center':
				$wrapper_style = 'align-items: center; justify-content: flex-end;';
				break;
			case 'bottom-center':
				$wrapper_style = 'align-items: flex-end; justify-content: center;';
				break;
			case 'bottom-left':
				$wrapper_style = 'align-items: flex-end; justify-content: flex-start;';
				break;
			case 'bottom-right':
				$wrapper_style = 'align-items: flex-end; justify-content: flex-end;';
				break;
		}

		$container_style = '';

		if ( 'custom' === ( $tb_settings[ 'pp_width' ] ?? '' ) && ! empty( $tb_settings[ 'pp_custom_width' ] ) ) {
			$container_style .= 'width:' . $tb_settings[ 'pp_custom_width' ] . 'px;';
		} elseif ( 'full' === ( $tb_settings[ 'pp_width' ] ?? '' ) ) {
			$container_style .= 'width:100vw;';
		} else {
			$container_style .= 'width:auto;';
		}

		if ( 'custom' === ( $tb_settings[ 'pp_height' ] ?? '' ) && ! empty( $tb_settings[ 'pp_custom_height' ] ) ) {
			$container_style .= 'height:' . $tb_settings[ 'pp_custom_height' ] . 'px;';
		} elseif ( 'full' === ( $tb_settings[ 'pp_height' ] ?? '' ) ) {
			$container_style .= 'height:100vh;';
		} else {
			$container_style .= 'height:auto;';
		}

		$data_settings = [
			'id'              => $id,
			'modified'        => get_post_modified_time( 'U', false, $id ),
			'show_frequency'  => $tb_settings[ 'pp_show_frequency' ] ?? 'every-time',
			'repeat_interval' => (int) ( $tb_settings[ 'pp_repeat_int' ] ?? 0 ),
			'limit_display'   => (int) ( $tb_settings[ 'pp_limit_display' ] ?? 0 ),
			'reset_on_update' => (bool) ( $tb_settings[ 'pp_reset_on_update' ] ?? false ),
			'trigger'         => [
				'on_load'          => (bool) ( $tb_settings[ 'pp_page_load' ] ?? false ),
				'load_delay'       => (int) ( $tb_settings[ 'pp_page_delay' ] ?? 0 ),
				'on_scroll'        => (bool) ( $tb_settings[ 'pp_page_scroll' ] ?? false ),
				'scroll_amount'    => (int) ( $tb_settings[ 'pp_scroll_amount' ] ?? 0 ),
				'on_element'       => (bool) ( $tb_settings[ 'pp_scroll_el' ] ?? false ),
				'element_selector' => $tb_settings[ 'pp_scroll_el_sel' ] ?? '',
				'on_click'         => (bool) ( $tb_settings[ 'pp_click_el' ] ?? false ),
				'click_selector'   => $tb_settings[ 'pp_click_el_sel' ] ?? '',
			],
		];

		return [
			'wrapper_class'   => $wrapper_class,
			'wrapper_style'   => $wrapper_style,
			'container_style' => $container_style,
			'data_settings'   => $data_settings,
			'show_overly'     => $tb_settings[ 'pp_overly' ] ?? true,
			'close_btn'       => $tb_settings[ 'pp_close_btn' ] ?? true,
		];
	}

	public static function get_supported_template_types(): array {
		return [
			'header',
			'footer',
			'page_title',
			'mega_menu',
			'popup',
			'offcanvas',
			'archive',
			'single_post',
			'404',
		];
	}

	public static function get_template_type( $post_id ): string {
		$type = wp_get_post_terms( $post_id, 'rstb_template_type', [ 'fields' => 'slugs' ] );

		if ( ! is_wp_error( $type ) && isset( $type[ 0 ] ) ) {
			return $type[ 0 ];
		} else {
			return '';
		}
	}

	public static function get_supported_post_types(): array {
		$post_types = get_post_types( [ 'public' => true ], 'objects' );;

		unset( $post_types[ 'attachment' ] );
		unset( $post_types[ 'elementor_library' ] );
		unset( $post_types[ 'e-landing-page' ] );
		unset( $post_types[ 'e-floating-buttons' ] );
		unset( $post_types[ 'elementor-rshf' ] );
		unset( $post_types[ 'rstb_template' ] );

		return $post_types;
	}

	public static function get_criteria_options(): array {
		$basic_pages = [
			[ 'value' => 'basic-global', 'label' => __( 'Entire Website', 'rs-template-builder' ) ],
			[ 'value' => 'basic-singulars', 'label' => __( 'All Singulars', 'rs-template-builder' ) ],
			[ 'value' => 'basic-archives', 'label' => __( 'All Archives', 'rs-template-builder' ) ],
		];

		$special_pages = [
			[ 'value' => 'special-404', 'label' => __( '404 Page', 'rs-template-builder' ) ],
			[ 'value' => 'special-search', 'label' => __( 'Search Page', 'rs-template-builder' ) ],
			[ 'value' => 'special-blog', 'label' => __( 'Blog / Posts Page', 'rs-template-builder' ) ],
			[ 'value' => 'special-front', 'label' => __( 'Front Page', 'rs-template-builder' ) ],
			[ 'value' => 'special-date', 'label' => __( 'Date Archive', 'rs-template-builder' ) ],
			[ 'value' => 'special-author', 'label' => __( 'Author Archive', 'rs-template-builder' ) ],
		];

		if ( class_exists( 'WooCommerce' ) ) {
			$special_pages[] = [
				'value' => 'special-woo-shop',
				'label' => __( 'WooCommerce Shop Page', 'rs-template-builder' ),
			];
		}

		$criteria = [
			[
				'label'   => __( 'Basic', 'rs-template-builder' ),
				'options' => $basic_pages,
			],
			[
				'label'   => __( 'Special Pages', 'rs-template-builder' ),
				'options' => $special_pages
			]
		];

		foreach ( self::get_supported_post_types() as $post_type ) {
			$post_type_options = [
				'label'   => $post_type->label,
				'options' => [
					[
						'value' => $post_type->name . '|all',
						/* translators: %s: Post type label (plural). */
						'label' => sprintf( __( 'All %s', 'rs-template-builder' ), $post_type->label ),
					],
					[
						'value' => $post_type->name . '|all|archive',
						/* translators: %s: Post type label (plural). */
						'label' => sprintf( __( 'All %s Archive', 'rs-template-builder' ), $post_type->label ),
					]
				]
			];

			$taxonomies = array_filter(
				get_object_taxonomies( $post_type->name, 'objects' ),
				fn( $tax ) => $tax->public && $tax->name !== 'post_format'
			);

			foreach ( $taxonomies as $taxonomy ) {
				$post_type_options[ 'options' ][] = [
					'value' => $post_type->name . '|all|taxarchive|' . $taxonomy->name,
					/* translators: %s: Taxonomy label (plural). */
					'label' => sprintf( __( 'All %s Archive', 'rs-template-builder' ), $taxonomy->label ),
				];
			}

			$criteria[] = $post_type_options;
		}

		$criteria[] = [
			'label'   => __( 'Specific Target', 'rs-template-builder' ),
			'options' => [
				[
					'value' => 'specific',
					'label' => __( 'Specific Pages, Posts, Taxonomies etc.', 'rs-template-builder' ),
				],
			],
		];

		return $criteria;
	}

	public static function get_admin_data(): array {
		$template_types = [];
		foreach ( self::get_supported_template_types() as $type ) {
			$template_types[] = [
				'value' => $type,
				'label' => ucwords( str_replace( '_', ' ', $type ) ),
			];
		}

		return [
			'adminUrl'        => admin_url(),
			'templateTypes'   => $template_types,
			'criteriaOptions' => self::get_criteria_options(),
			'adminStrings'    => [
				"editItem"                => _x( 'Edit Template', 'Admin - RS Template Builder', 'rs-template-builder' ),
				"addItem"                 => _x( 'Add Template', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'generalError'            => _x( 'Something went wrong while saving. Please try again.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'displayOnLevel'          => _x( 'Display On', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'displayOnMsg'            => _x( 'Add a location where this template should be displayed.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'hideOnLevel'             => _x( 'Hide On', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'hideOnMsg'               => _x( 'Add a location where this template should be hidden.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'typeLevel'               => _x( 'Template Type', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'typeMsg'                 => _x( 'Select a template type.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'typeError'               => _x( 'Please select a template type.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'nameLevel'               => _x( 'Template Name', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'nameMsg'                 => _x( 'Enter a template name.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'nameError'               => _x( 'Please enter a template name.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'namePlaceholder'         => _x( 'Type a template name', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'draftLevel'              => _x( 'Save as Draft', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'draftMsg'                => _x( 'Save this template as a draft.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'postPlaceholder'         => _x( 'Search specific criteria...', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'postLoading'             => _x( 'Loading...', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'noCriteria'              => _x( 'No criteria found.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'saveEle'                 => _x( 'Save & Edit with Elementor', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'saveClo'                 => _x( 'Save & Close', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'addRule'                 => _x( 'Add Rule', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'removeRule'              => _x( 'Remove', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'customWidthLevel'        => _x( 'Custom Width', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'customHeightLevel'       => _x( 'Custom Height', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'customWidthErr'          => _x( 'Please enter a custom width.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'customHeightErr'         => _x( 'Please enter a custom height.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'mmWidthLevel'            => _x( 'Mega Menu Width', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'mmWidthMsg'              => _x( 'Select width for the mega menu template.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'mmCustomWidthMsg'        => _x( 'Enter a custom width (px) for the mega menu template.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'offCanvasLabel'          => _x( 'Offcanvas Width', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'offCanvasMsg'            => _x( 'Enter a custom width (e.g. 450px) for the offcanvas panel. The default is 450px.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'offCanvasError'          => _x( 'Please provide a valid custom width.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppWidthLevel'            => _x( 'Popup Width', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppWidthMgs'              => _x( 'Select the width for the popup template.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppCustomWidthMsg'        => _x( 'Enter a custom width (px) for the popup template.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppHeightLevel'           => _x( 'Popup Height', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppHeightMgs'             => _x( 'Select the height for the popup template.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppCustomHeightMsg'       => _x( 'Enter a custom height (px) for the popup template.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppPositionLevel'         => _x( 'Popup Position', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppPositionMsg'           => _x( 'Choose the position of the popup on the page.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppOverlyLevel'           => _x( 'Show Overlay', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppOverlyMsg'             => _x( 'Display a dark overlay over the page.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppCloseBtnLevel'         => _x( 'Show Close Button', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppCloseBtnMsg'           => _x( 'Include a close button to dismiss the popup.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppDisOptLevel'           => _x( 'Display Options', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppDisTriOptLevel'        => _x( 'Display Triggers', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppDisFreOptLevel'        => _x( 'Display Frequency', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppPageLoadLabel'         => _x( 'On Page Load', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppPageLoadMsg'           => _x( 'Show the popup when the page has finished loading.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppDelayLevel'            => _x( 'Popup Delay', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppDelayMsg'              => _x( 'Set the popup delay time (seconds).', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppPageScrollLabel'       => _x( 'On Page Scroll', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppPageScrollMsg'         => _x( 'Show the popup after scrolling a specified percentage of the page.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppScrollPercentLabel'    => _x( 'Scroll Percentage', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppScrollPercentMsg'      => _x( 'Specify the percentage of page scroll required to trigger the popup.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppScrollElLabel'         => _x( 'On Scroll to Elements', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppScrollElMsg'           => _x( 'Show the popup when a specific element enters the viewport.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppScrollElSelLabel'      => _x( 'Elements Selectors', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppScrollElSelMsg'        => _x( 'Enter CSS selectors (e.g., #my-id or .my-class) of elements that trigger the popup when visible.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppClickElLabel'          => _x( 'On Elements Click', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppClickElMsg'            => _x( 'Show the popup when a specific element is clicked.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppClickElSelLabel'       => _x( 'Elements Selectors', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppClickElSelMsg'         => _x( 'Enter CSS selectors (e.g., #my-id or .my-class) of clickable elements that will trigger the popup.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppDisplayFrequencyLabel' => _x( 'Show Frequency', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppDisplayFrequencyMsg'   => _x( 'Select how often the popup will be displayed.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppRepeatIntervalLabel'   => _x( 'Repeat Interval', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppRepeatIntervalMsg'     => _x( 'Enter the interval value in hours (e.g., 2 hours).', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppLimitDisplayLabel'     => _x( 'Limit Displays', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppLimitDisplayMsg'       => _x( 'Limit the number of times the popup can be shown to the user.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppResetOnUpdateLabel'    => _x( 'Reset On Update', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'ppResetOnUpdateMsg'      => _x( 'Reset the display frequency whenever the popup content is updated.', 'Admin - RS Template Builder', 'rs-template-builder' ),
				'pageTitleNotice'         => _x( 'The page title work independently with RSTheme and popular themes. Otherwise, it will only work if the page uses the Template Type Header.', 'Admin - RS Template Builder', 'rs-template-builder' ),
			],
		];
	}
}