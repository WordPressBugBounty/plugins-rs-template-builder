<?php

namespace RsTemplateBuilder\Elementor\Extensions;

use Elementor\Controls_Manager;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

class Container_Attributes {

	/**
	 * Attributes Elementor manages itself; overriding them breaks the editor or frontend handlers.
	 */
	private const RESERVED = [ 'id', 'class', 'href', 'data-id', 'data-element_type', 'data-settings', 'data-model-cid', 'data-e-type' ];

	public function __construct() {
		add_action( 'elementor/element/container/section_layout/after_section_end', [ $this, 'register_options' ], 20 );
		add_action( 'elementor/frontend/container/before_render', [ $this, 'render_attributes' ] );
	}

	public function register_options( $element ): void {
		$element->start_controls_section(
			'section_rstb_attributes',
			[
				'label' => sprintf( '<i class="rstb-branding-ex-icon"></i> %s', __( 'RS Attributes', 'rs-template-builder' ) ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'key',
			[
				'label'       => esc_html__( 'Name', 'rs-template-builder' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => 'data-foo',
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'value',
			[
				'label'       => esc_html__( 'Value', 'rs-template-builder' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [ 'active' => true ],
				'label_block' => true,
			]
		);

		$element->add_control(
			'rstb_attributes',
			[
				'label'         => esc_html__( 'Attributes', 'rs-template-builder' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'title_field'   => '{{{ key }}}',
				'prevent_empty' => false,
				'description'   => esc_html__( 'Rendered on the container wrapper. Event handlers (on*), id, class and href are not allowed.', 'rs-template-builder' ),
			]
		);

		$element->end_controls_section();
	}

	public function render_attributes( $element ): void {
		$attributes = $element->get_settings_for_display( 'rstb_attributes' );

		if ( empty( $attributes ) || ! is_array( $attributes ) ) {
			return;
		}

		foreach ( $attributes as $attribute ) {
			$key = strtolower( trim( $attribute['key'] ?? '' ) );

			if ( ! preg_match( '/^[a-z_:@][-a-z0-9_:.@]*$/', $key ) ) {
				continue;
			}

			if ( 0 === strpos( $key, 'on' ) || in_array( $key, self::RESERVED, true ) ) {
				continue;
			}

			$element->add_render_attribute( '_wrapper', $key, (string) ( $attribute['value'] ?? '' ) );
		}
	}
}

new Container_Attributes();
