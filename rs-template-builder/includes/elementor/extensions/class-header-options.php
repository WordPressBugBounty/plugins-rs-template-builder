<?php

namespace RsTemplateBuilder\Elementor\Extensions;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Header_Options {

	public function __construct() {
		add_action( 'elementor/element/container/section_layout/after_section_end', [ $this, 'register_header_options' ] );
	}

	public function register_header_options( $element ): void {
		$element->start_controls_section(
			'section_rstb_header_options',
			[
				'label' => sprintf( '<i class="rstb-branding-ex-icon"></i> %s', __( 'RS Header Settings', 'rs-template-builder' ) ),
				'tab'   => Controls_Manager::TAB_LAYOUT,
			]
		);

		$element->add_control(
			'header_position',
			[
				'label'        => esc_html__( 'Select Position', 'rs-template-builder' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => [
					''                 => esc_html__( 'Default', 'rs-template-builder' ),
					'rstb-transparent' => esc_html__( 'Transparent', 'rs-template-builder' ),
				],
				'default'      => '',
				'prefix_class' => '',
				'description'  => esc_html__( 'Choose "Transparent" to make the header position absolute on the page, allowing content to appear behind it.', 'rs-template-builder' ),
			]
		);

		$element->add_control(
			'header_sticky',
			[
				'label'        => esc_html__( 'Header Sticky', 'rs-template-builder' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => [
					''               => esc_html__( 'No', 'rs-template-builder' ),
					'rstb-sticky'    => esc_html__( 'Always Sticky', 'rs-template-builder' ),
					'rstb-sticky-up' => esc_html__( 'Sticky on Scroll Up', 'rs-template-builder' ),
				],
				'default'      => '',
				'prefix_class' => '',
				'description'  => esc_html__( 'Make this a sticky header. "Always Sticky" keeps the header visible when scrolling down, and "Sticky on Scroll Up" makes it appear only when scrolling up.', 'rs-template-builder' ),
			]
		);

		$element->end_controls_section();
	}
}

new Header_Options();