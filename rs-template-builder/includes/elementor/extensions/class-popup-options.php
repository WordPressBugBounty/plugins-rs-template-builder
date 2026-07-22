<?php

namespace RsTemplateBuilder\Elementor\Extensions;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use RsTemplateBuilder\Helpers\Utils;

defined( 'ABSPATH' ) || exit;

class Popup_Options {

	public function __construct() {
		add_action( 'elementor/documents/register_controls', [ $this, 'register_options' ] );
	}

	public function register_options( $document ) {
		$document_id = $document->get_main_id();

		if ( 'rstb_template' !== get_post_type( $document_id ) || 'popup' !== Utils::get_template_type( $document_id ) ) {
			return;
		}

		$selector_close   = '#rstb-popup-' . esc_attr( $document_id ) . ' .popup-container .popup-close';
		$selector_close_h = '#rstb-popup-' . esc_attr( $document_id ) . ' .popup-container .popup-close:hover';
		$selector_overly  = '#rstb-popup-' . esc_attr( $document_id ) . ' .popup-overly';

		$document->start_controls_section(
			'section_rstb_p_close_style',
			[
				'label' => sprintf( '<i class="rstb-branding-ex-icon"></i> %s', __( 'Popup Close Button', 'rs-template-builder' ) ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			]
		);

		$document->add_responsive_control(
			'rstb_p_close_w',
			[
				'label'      => esc_html__( 'Width', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					$selector_close => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$document->add_responsive_control(
			'rstb_p_close_h',
			[
				'label'      => esc_html__( 'Height', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					$selector_close => 'height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$document->add_responsive_control(
			'rstb_p_close_fs',
			[
				'label'      => esc_html__( 'Icon Size', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					$selector_close => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$document->add_control(
			'rstb_p_close_pos',
			[
				'label'        => esc_html__( 'Position', 'rs-template-builder' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => esc_html__( 'None', 'rs-template-builder' ),
				'label_on'     => esc_html__( 'Custom', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$document->start_popover();

		$document->add_responsive_control(
			'rstb_p_close_pos_l',
			[
				'label'      => esc_html__( 'Left', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					$selector_close => 'left: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'rstb_p_close_pos' => 'yes'
				]
			]
		);

		$document->add_responsive_control(
			'rstb_p_close_pos_r',
			[
				'label'      => esc_html__( 'Right', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					$selector_close => 'right: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'rstb_p_close_pos' => 'yes'
				]
			]
		);

		$document->add_responsive_control(
			'rstb_p_close_pos_t',
			[
				'label'      => esc_html__( 'Top', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					$selector_close => 'top: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'rstb_p_close_pos' => 'yes'
				]
			]
		);

		$document->add_responsive_control(
			'rstb_p_close_pos_b',
			[
				'label'      => esc_html__( 'Bottom', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					$selector_close => 'bottom: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'rstb_p_close_pos' => 'yes'
				]
			]
		);

		$document->end_popover();

		$document->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'rstb_p_close_b',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => $selector_close,
			]
		);

		$document->add_responsive_control(
			'rstb_p_close_b_r',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					$selector_close => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$document->start_controls_tabs( 'rstb_p_close_s_tabs' );

		$document->start_controls_tab(
			'rstb_p_close_nt',
			[
				'label' => esc_html__( 'Normal', 'rs-template-builder' ),
			]
		);

		$document->add_control(
			'rstb_p_close_c',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					$selector_close => 'color: {{VALUE}}',
				],
			]
		);

		$document->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'rstb_p_close_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => $selector_close,
				'exclude'  => [ 'image' ]
			]
		);

		$document->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'rstb_p_close_bs',
				'selector' => $selector_close,
			]
		);

		$document->end_controls_tab();

		$document->start_controls_tab(
			'rstb_p_close_ht',
			[
				'label' => esc_html__( 'Hover', 'rs-template-builder' ),
			]
		);

		$document->add_control(
			'rstb_p_close_c_h',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					$selector_close_h => 'color: {{VALUE}}',
				],
			]
		);

		$document->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'rstb_p_close_bg_h',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => $selector_close_h,
				'exclude'  => [ 'image' ]
			]
		);

		$document->add_control(
			'rstb_p_close_bc_h',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					$selector_close_h => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'rstb_p_close_b_border!' => ''
				]
			]
		);

		$document->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'rstb_p_close_bs_h',
				'selector' => $selector_close_h,
			]
		);

		$document->end_controls_tab();

		$document->end_controls_tabs();

		$document->end_controls_section();

		$document->start_controls_section(
			'section_rstb_overly_style',
			[
				'label' => sprintf( '<i class="rstb-branding-ex-icon"></i> %s', __( 'Popup Overly', 'rs-template-builder' ) ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			]
		);

		$document->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'rstb_p_overly_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => $selector_overly,
			]
		);

		$document->end_controls_section();
	}
}

new Popup_Options();