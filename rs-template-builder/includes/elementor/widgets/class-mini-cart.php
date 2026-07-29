<?php

namespace RsTemplateBuilder\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Widget_Base;

class Mini_Cart extends Widget_Base {

	public function get_name(): string {
		return 'rstb-mini-cart';
	}

	public function get_title(): string {
		return esc_html__( 'Mini Cart', 'rs-template-builder' );
	}

	public function get_icon(): string {
		return 'eicon-cart rstb-branding-icon';
	}

	public function get_categories(): array {
		return [ 'rstb_elements' ];
	}

	public function get_keywords(): array {
		return [ 'rs template builder', 'rstheme', 'header', 'footer', 'cart', 'woocommerce', 'rs-template-builder' ];
	}

	protected function is_dynamic_content(): bool {
		return true;
	}

	public function has_widget_inner_wrapper(): bool {
		return ! Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls(): void {
		$this->start_controls_section(
			'section_general_content',
			[
				'label' => esc_html__( 'General', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'icon_type',
			[
				'label'   => esc_html__( 'Cart Icon Type', 'rs-template-builder' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'rs-template-builder' ),
					'custom'  => esc_html__( 'Custom', 'rs-template-builder' ),
				],
			]
		);

		$this->add_control(
			'custom_cart_icon',
			[
				'label'       => esc_html__( 'Select Icon', 'rs-template-builder' ),
				'type'        => Controls_Manager::ICONS,
				'condition'   => [
					'icon_type' => 'custom',
				],
				'default'     => [
					'value'   => 'fas fa-shopping-cart',
					'library' => 'fa-solid',
				],
				'skin'        => 'inline',
				'label_block' => false,
			]
		);

		$this->add_control(
			'show_count',
			[
				'label'        => esc_html__( 'Show Count', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'No', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'show_subtotal',
			[
				'label'        => esc_html__( 'Show Subtotal', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'No', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'panel_heading',
			[
				'label'     => esc_html__( 'Cart Panel', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'show_panel',
			[
				'label'        => esc_html__( 'Show Panel', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'No', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'panel_title',
			[
				'label'       => esc_html__( 'Panel Title', 'rs-template-builder' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'Shopping Cart', 'rs-template-builder' ),
				'placeholder' => esc_html__( 'Enter panel title', 'rs-template-builder' ),
				'condition'   => [
					'show_panel' => 'yes',
				]
			]
		);

		$this->add_control(
			'close_icon_type',
			[
				'label'       => esc_html__( 'Close Icon', 'rs-template-builder' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'options'     => [
					'default' => esc_html__( 'Default', 'rs-template-builder' ),
					'custom'  => esc_html__( 'Custom', 'rs-template-builder' ),
				],
				'default'     => 'default',
				'condition'   => [
					'show_panel' => 'yes',
				]
			]
		);

		$this->add_control(
			'close_custom_icon',
			[
				'label'       => esc_html__( 'Select Icon', 'rs-template-builder' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => [
					'value'   => 'fas fa-times',
					'library' => 'fa-solid',
				],
				'skin'        => 'inline',
				'label_block' => false,
				'condition'   => [
					'show_panel'      => 'yes',
					'close_icon_type' => 'custom'
				]
			]
		);

		$this->add_control(
			'auto_open_cart',
			[
				'label'        => esc_html__( 'Auto-Open Cart', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'No', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
				'description'  => esc_html__( 'Automatically open the mini cart panel when a product is added.', 'rs-template-builder' ),
				'separator'    => 'before',
				'condition'    => [
					'show_panel' => 'yes',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_mini_cart',
			[
				'label' => esc_html__( 'Cart Button', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'cart_btn_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-mini-cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'cart_btn_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .rstb-mini-cart',
			]
		);

		$this->add_responsive_control(
			'cart_btn_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-mini-cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'cart_btn_style_tab' );

		$this->start_controls_tab(
			'cart_btn_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'cart_btn_bg',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-mini-cart' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'cart_btn_box_shadow',
				'selector' => '{{WRAPPER}} .rstb-mini-cart',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'cart_btn_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'hover_cart_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-mini-cart:hover' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'cart_btn_border_border!' => '',
				]
			]
		);

		$this->add_control(
			'hover_cart_btn_bg',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-mini-cart:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'hover_cart_btn_box_shadow',
				'selector' => '{{WRAPPER}} .rstb-mini-cart:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'cart_icon_heading',
			[
				'label'     => esc_html__( 'Cart Icon', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'cart_icon_size',
			[
				'label'        => esc_html__( 'Area Size', 'rs-template-builder' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => esc_html__( 'None', 'rs-template-builder' ),
				'label_on'     => esc_html__( 'Custom', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'cart_icon_width',
			[
				'label'      => esc_html__( 'Width', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .cart-icon' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'cart_icon_size' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'cart_icon_height',
			[
				'label'      => esc_html__( 'Height', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .cart-icon' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'cart_icon_size' => 'yes'
				]
			]
		);

		$this->end_popover();

		$this->add_responsive_control(
			'cart_icon_font_size',
			[
				'label'      => esc_html__( 'Font Size', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .cart-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'cart_icon_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .cart-icon',
			]
		);

		$this->add_responsive_control(
			'card_icon_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .cart-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'cart_icon_style_tabs' );

		$this->start_controls_tab(
			'cart_icon_normal',
			[
				'label' => esc_html__( 'Normal', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'cart_icon_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cart-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'cart_icon_bg',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cart-icon' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'cart_icon_shadow',
				'selector' => '{{WRAPPER}} .cart-icon',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'cart_icon_hover',
			[
				'label' => esc_html__( 'Hover', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'cart_icon_color_hover',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-mini-cart:hover .cart-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'cart_icon_border_color_1',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-mini-cart:hover .cart-icon' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'cart_icon_border_border!' => ''
				]
			]
		);

		$this->add_control(
			'cart_icon_bg_hover',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-mini-cart:hover .cart-icon' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'cart_icon_shadow_hover',
				'selector' => '{{WRAPPER}} .rstb-mini-cart:hover .cart-icon',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'cart_count_heading',
			[
				'label'     => esc_html__( 'Cart Count', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'cart_count_size',
			[
				'label'        => esc_html__( 'Area Size', 'rs-template-builder' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => esc_html__( 'None', 'rs-template-builder' ),
				'label_on'     => esc_html__( 'Custom', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'cart_count_width',
			[
				'label'      => esc_html__( 'Width', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-cart-count' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'cart_count_size' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'cart_count_height',
			[
				'label'      => esc_html__( 'Height', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-cart-count' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'cart_count_size' => 'yes'
				]
			]
		);

		$this->end_popover();

		$this->add_responsive_control(
			'cart_count_font_size',
			[
				'label'      => esc_html__( 'Font Size', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-cart-count' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'cart_count_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .rstb-cart-count',
			]
		);

		$this->add_responsive_control(
			'cart_count_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-cart-count' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'cart_count_style_tabs' );

		$this->start_controls_tab(
			'cart_count_normal',
			[
				'label' => esc_html__( 'Normal', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'cart_count_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-cart-count' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'cart_count_bg',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-cart-count' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'cart_count_shadow',
				'selector' => '{{WRAPPER}} .rstb-cart-count',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'cart_count_hover',
			[
				'label' => esc_html__( 'Hover', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'cart_count_color_hover',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-mini-cart:hover .rstb-cart-count' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'cart_count_border_color_1',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-mini-cart:hover .rstb-cart-count' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'cart_count_border_border!' => ''
				]
			]
		);

		$this->add_control(
			'cart_count_bg_hover',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-mini-cart:hover .rstb-cart-count' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'cart_count_shadow_hover',
				'selector' => '{{WRAPPER}} .rstb-mini-cart:hover .rstb-cart-count',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'cart_sub_total_heading',
			[
				'label'     => esc_html__( 'Subtotal', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'cart_subtotal_typo',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .rstb-cart-subtotal',
			]
		);

		$this->add_control(
			'cart_subtotal_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-cart-subtotal' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'cart_subtotal_color_hover',
			[
				'label'     => esc_html__( 'Color(Hover)', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-mini-cart:hover .rstb-cart-subtotal' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_panel_style',
			[
				'label'     => esc_html__( 'Panel', 'rs-template-builder' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_panel' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'container_width',
			[
				'label'      => esc_html__( 'Container Width', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 2000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
					'vw' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .cart-panel-content' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'container_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .cart-panel-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'container_bg_colo',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cart-panel-content' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'overly_color',
			[
				'label'     => esc_html__( 'Overly Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cart-panel-overly' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'panel_top_heading',
			[
				'label'     => esc_html__( 'Panel Header', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'header_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .content-top' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'header_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .content-top' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'header_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .content-top',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'header_title_typo',
				'label'    => esc_html__( 'Title Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .cart-panel-title',
			]
		);

		$this->add_control(
			'header_title_color',
			[
				'label'     => esc_html__( 'Title Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cart-panel-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'close_icon_size',
			[
				'label'      => esc_html__( 'Close Icon Size', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .cart-panel-close' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'close_icon_color',
			[
				'label'     => esc_html__( 'Close Icon Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cart-panel-close' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_panel_content_style',
			[
				'label'     => esc_html__( 'Panel Content', 'rs-template-builder' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_panel' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-cart-panel .product_list_widget' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'product_img_heading',
			[
				'label'     => esc_html__( 'Product Image', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'product_img_size',
			[
				'label'      => esc_html__( 'Width', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce-mini-cart-item img' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'product_title_heading',
			[
				'label'     => esc_html__( 'Product Title', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_title_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart-item a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'product_title_typo',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart-item a',
			]
		);

		$this->add_control(
			'product_quantity_heading',
			[
				'label'     => esc_html__( 'Product Quantity', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_quantity_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart-item .quantity' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'product_quantity_typo',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart-item .quantity',
			]
		);

		$this->add_control(
			'product_remove_icon',
			[
				'label'     => esc_html__( 'Remove icon', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_remove_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart-item .remove' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'product_remove_size',
			[
				'label'      => esc_html__( 'Size', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce-mini-cart-item .remove' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'subtotal_heading',
			[
				'label'     => esc_html__( 'Subtotal', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'subtotal_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce-mini-cart__total' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'subtotal_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart__total',
			]
		);

		$this->add_control(
			'subtotal_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart__total' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'cart_buttons_heading',
			[
				'label'     => esc_html__( 'Buttons', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_area',
			[
				'label'      => esc_html__( 'Area Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce-mini-cart__buttons' => 'button: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'btn_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce-mini-cart__buttons .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'btn_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart__buttons .button',
				'exclude'  => [ 'color' ]
			]
		);

		$this->add_responsive_control(
			'btn_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce-mini-cart__buttons .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'btn_style_tab' );

		$this->start_controls_tab(
			'btn_normal',
			[
				'label' => esc_html__( 'Normal', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'btn_color_1',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart__buttons .button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_bg_color_1',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart__buttons .button' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_border_color_1',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart__buttons .button' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_color_2',
			[
				'label'     => esc_html__( 'Checkout Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart__buttons .button.checkout' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_bg_color_2',
			[
				'label'     => esc_html__( 'Checkout Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart__buttons .button.checkout' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_border_color_2',
			[
				'label'     => esc_html__( 'Checkout Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart__buttons .button.checkout' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'btn_hover',
			[
				'label' => esc_html__( 'Hover', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'hover_btn_color_1',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart__buttons .button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hover_btn_bg_color_1',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart__buttons .button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hover_btn_border_color_1',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart__buttons .button:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hover_btn_color_2',
			[
				'label'     => esc_html__( 'Checkout Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart__buttons .button.checkout:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hover_btn_bg_color_2',
			[
				'label'     => esc_html__( 'Checkout Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart__buttons .button.checkout:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hover_btn_border_color_2',
			[
				'label'     => esc_html__( 'Checkout Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart__buttons .button.checkout:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render(): void {
		if ( null === WC()->cart ) {
			return;
		}

		$settings  = $this->get_settings_for_display();
		$icon_type = $settings[ 'icon_type' ] ?? 'default';

		$this->add_render_attribute( 'wrapper', [
			'class'          => 'rstb-mini-cart',
			'data-panel'     => 'yes' === $settings[ 'show_panel' ] ? 'true' : 'false',
			'data-auto-open' => 'yes' === $settings[ 'auto_open_cart' ] ? 'true' : 'false',
		] )
		?>
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="rstb-cart-url">
                <span class="cart-icon <?php if ( 'yes' !== $settings[ 'show_count' ] ) {
	                echo 'hide-count';
                } ?>">
                    <?php if ( 'custom' === $icon_type && ! empty( $settings[ 'custom_cart_icon' ][ 'value' ] ) ) : ?>
	                    <?php Icons_Manager::render_icon( $settings[ 'custom_cart_icon' ] ); ?>
                    <?php else : ?>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M4.00488 16V4H2.00488V2H5.00488C5.55717 2 6.00488 2.44772 6.00488 3V15H18.4433L20.4433 7H8.00488V5H21.7241C22.2764 5 22.7241 5.44772 22.7241 6C22.7241 6.08176 22.7141 6.16322 22.6942 6.24254L20.1942 16.2425C20.083 16.6877 19.683 17 19.2241 17H5.00488C4.4526 17 4.00488 16.5523 4.00488 16ZM6.00488 23C4.90031 23 4.00488 22.1046 4.00488 21C4.00488 19.8954 4.90031 19 6.00488 19C7.10945 19 8.00488 19.8954 8.00488 21C8.00488 22.1046 7.10945 23 6.00488 23ZM18.0049 23C16.9003 23 16.0049 22.1046 16.0049 21C16.0049 19.8954 16.9003 19 18.0049 19C19.1095 19 20.0049 19.8954 20.0049 21C20.0049 22.1046 19.1095 23 18.0049 23Z"></path>
                        </svg>
                    <?php endif; ?>
	                <?php if ( 'yes' === $settings[ 'show_count' ] ) : ?>
	                <?php endif; ?>
                    <span class="rstb-cart-count">
                        <?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?>
                    </span>
                </span>
				<?php if ( 'yes' === $settings[ 'show_subtotal' ] ) {
					echo '<span class="rstb-cart-subtotal">' . wp_kses_post( WC()->cart->get_cart_subtotal() ) . '<span>';
				} ?>
            </a>
			<?php if ( 'yes' === $settings[ 'show_panel' ] ) : ?>
                <div class="rstb-cart-panel">
                    <div class="cart-panel-overly"></div>
                    <div class="cart-panel-content">
                        <div class="content-top">
                            <h4 class="cart-panel-title">
								<?php echo esc_html( $settings[ 'panel_title' ] ); ?>
                            </h4>
                            <button class="cart-panel-close">
								<?php if ( 'custom' === $settings[ 'close_icon_type' ] ) : ?>
									<?php Icons_Manager::render_icon( $settings[ 'close_custom_icon' ] ); ?>
								<?php else : ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path d="M10.5859 12L2.79297 4.20706L4.20718 2.79285L12.0001 10.5857L19.793 2.79285L21.2072 4.20706L13.4143 12L21.2072 19.7928L19.793 21.2071L12.0001 13.4142L4.20718 21.2071L2.79297 19.7928L10.5859 12Z"></path>
                                    </svg>
								<?php endif; ?>
                            </button>
                        </div>
                        <div class="rstb-cart-content">
							<?php woocommerce_mini_cart(); ?>
                        </div>
                    </div>
                </div>
			<?php endif; ?>
        </div>
		<?php
	}
}