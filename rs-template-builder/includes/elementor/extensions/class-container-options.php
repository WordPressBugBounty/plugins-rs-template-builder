<?php

namespace RsTemplateBuilder\Elementor\Extensions;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Container_Options {

	public function __construct() {
		add_action( 'elementor/element/container/section_layout/after_section_end', [ $this, 'register_options' ] );
	}

	public function register_options( $element ): void {
		$element->start_controls_section(
			'section_rstb_size_position',
			[
				'label' => sprintf( '<i class="rstb-branding-ex-icon"></i> %s', __( 'RS Size & Position', 'rs-template-builder' ) ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_responsive_control(
			'rstb_con_height',
			[
				'label'      => esc_html__( 'Height', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vh', 'em', 'custom' ],
				'range'      => [
					'px' => [
						'max' => 1500,
					],
				],
				'selectors'  => [
					'{{WRAPPER}}' => 'height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$element->add_responsive_control(
			'rstb_con_max_height',
			[
				'label'      => esc_html__( 'Max Height', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vh', 'em', 'custom' ],
				'range'      => [
					'px' => [
						'max' => 1500,
					],
				],
				'selectors'  => [
					'{{WRAPPER}}' => 'max-height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$overflow_options = [
			''        => esc_html__( 'Default', 'rs-template-builder' ),
			'visible' => esc_html__( 'Visible', 'rs-template-builder' ),
			'hidden'  => esc_html__( 'Hidden', 'rs-template-builder' ),
			'auto'    => esc_html__( 'Auto', 'rs-template-builder' ),
			'scroll'  => esc_html__( 'Scroll', 'rs-template-builder' ),
		];

		$element->add_responsive_control(
			'rstb_con_overflow_x',
			[
				'label'     => esc_html__( 'Overflow X', 'rs-template-builder' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $overflow_options,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}}' => 'overflow-x: {{VALUE}}',
				],
			]
		);

		$element->add_responsive_control(
			'rstb_con_overflow_y',
			[
				'label'       => esc_html__( 'Overflow Y', 'rs-template-builder' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => $overflow_options,
				'default'     => '',
				'selectors'   => [
					'{{WRAPPER}}' => 'overflow-y: {{VALUE}}',
				],
				'description' => esc_html__( 'Set a Height or Max Height with Overflow Y "Auto" to make the content scrollable.', 'rs-template-builder' ),
			]
		);

		$element->add_responsive_control(
			'rstb_con_position',
			[
				'label'     => esc_html__( 'Position', 'rs-template-builder' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''         => esc_html__( 'Default', 'rs-template-builder' ),
					'relative' => esc_html__( 'Relative', 'rs-template-builder' ),
					'absolute' => esc_html__( 'Absolute', 'rs-template-builder' ),
					'fixed'    => esc_html__( 'Fixed', 'rs-template-builder' ),
					'sticky'   => esc_html__( 'Sticky', 'rs-template-builder' ),
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}}' => 'position: {{VALUE}}',
				],
			]
		);

		$offsets = [
			'top'    => esc_html__( 'Top', 'rs-template-builder' ),
			'right'  => esc_html__( 'Right', 'rs-template-builder' ),
			'bottom' => esc_html__( 'Bottom', 'rs-template-builder' ),
			'left'   => esc_html__( 'Left', 'rs-template-builder' ),
		];

		foreach ( $offsets as $side => $label ) {
			$element->add_responsive_control(
				'rstb_con_pos_' . $side,
				[
					'label'      => $label,
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%', 'vw', 'vh', 'custom' ],
					'range'      => [
						'px' => [
							'min' => -1000,
							'max' => 1000,
						],
						'%'  => [
							'min' => -100,
							'max' => 100,
						],
					],
					'selectors'  => [
						'{{WRAPPER}}' => $side . ': {{SIZE}}{{UNIT}}',
					],
				]
			);
		}

		$element->end_controls_section();
	}
}

new Container_Options();
