<?php

namespace RsTemplateBuilder\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Widget_Base;

class Offcanvas extends Widget_Base {

	public function get_name(): string {
		return 'rstb-offcanvas';
	}

	public function get_title(): string {
		return esc_html__( 'Offcanvas', 'rs-template-builder' );
	}

	public function get_icon(): string {
		return 'eicon-nav-menu rstb-branding-icon';
	}

	public function get_categories(): array {
		return [ 'rstb_elements' ];
	}

	public function get_keywords(): array {
		return [ 'rs template builder', 'rstheme', 'header', 'footer', 'offcanvas', 'toggle', 'rs-template-builder' ];
	}

	protected function is_dynamic_content(): bool {
		return true;
	}

	public function has_widget_inner_wrapper(): bool {
		return ! Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls(): void {
		$this->start_controls_section(
			'section_widget_content',
			[
				'label' => esc_html__( 'General', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'content_type',
			[
				'label'       => esc_html__( 'Content Type', 'rs-template-builder' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'options'     => [
					'text_blocks' => esc_html__( 'Text Blocks', 'rs-template-builder' ),
					'template'    => esc_html__( 'Template', 'rs-template-builder' ),
				],
				'default'     => 'text_blocks',
				'description' => esc_html__( 'Choose the type of content you want to display: either a text blocks or a template. You can use the offcanvas template from your admin panel with full Elementor controls.', 'rs-template-builder' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'rs-template-builder' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'Text Block Title', 'rs-template-builder' ),
				'placeholder' => esc_html__( 'Enter block title', 'rs-template-builder' ),
			]
		);

		$repeater->add_control(
			'desc',
			[
				'label'       => esc_html__( 'Description', 'rs-template-builder' ),
				'type'        => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default'     => esc_html__( 'This is a text block description area where you can add detailed information about your content. Use this space to describe the purpose, context, or any important details related to this section.', 'rs-template-builder' ),
				'placeholder' => esc_html__( 'Enter block description', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'text_blocks',
			[
				'label'       => esc_html__( 'Text Blocks', 'rs-template-builder' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'title' => esc_html__( 'Text Block Title', 'rs-template-builder' ),
						'desc'  => esc_html__( 'This is a text block description area where you can add detailed information about your content. Use this space to describe the purpose, context, or any important details related to this section.', 'rs-template-builder' ),
					],
					[
						'title' => esc_html__( 'Text Block Title', 'rs-template-builder' ),
						'desc'  => esc_html__( 'This is a text block description area where you can add detailed information about your content. Use this space to describe the purpose, context, or any important details related to this section.', 'rs-template-builder' ),
					],
					[
						'title' => esc_html__( 'Text Block Title', 'rs-template-builder' ),
						'desc'  => esc_html__( 'This is a text block description area where you can add detailed information about your content. Use this space to describe the purpose, context, or any important details related to this section.', 'rs-template-builder' ),
					],
				],
				'title_field' => '{{{ title }}}',
				'condition'   => [
					'content_type' => 'text_blocks'
				]
			]
		);

		$this->add_control(
			'template_id',
			[
				'label'     => esc_html__( 'Select Template', 'rs-template-builder' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_builder_template(),
				'default'   => '',
				'condition' => [
					'content_type' => 'template'
				]
			]
		);

		$this->add_control(
			'toggle_btn_heading',
			[
				'label'     => esc_html__( 'Toggle Button', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'toggle_btn_text',
			[
				'label'       => esc_html__( 'Button Text', 'rs-template-builder' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => false,
				'placeholder' => esc_html__( 'Click Here', 'rs-template-builder' ),
				'ai'          => false
			]
		);

		$this->add_control(
			'toggle_icon_type',
			[
				'label'       => esc_html__( 'Toggle Icon', 'rs-template-builder' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'options'     => [
					'default' => esc_html__( 'Default', 'rs-template-builder' ),
					'custom'  => esc_html__( 'Custom', 'rs-template-builder' ),
					'none'    => esc_html__( 'None', 'rs-template-builder' ),
				],
				'default'     => 'default',
			]
		);

		$this->add_control(
			'toggle_custom_icon',
			[
				'label'       => esc_html__( 'Select Icon', 'rs-template-builder' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => [
					'value'   => 'fas fa-bars',
					'library' => 'fa-solid',
				],
				'skin'        => 'inline',
				'label_block' => false,
				'condition'   => [
					'toggle_icon_type' => 'custom'
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
				'separator'   => 'before',
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
					'close_icon_type' => 'custom'
				]
			]
		);

		$this->add_control(
			'offcanvas_position',
			[
				'label'       => esc_html__( 'Position', 'rs-template-builder' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'options'     => [
					'left'  => esc_html__( 'Left', 'rs-template-builder' ),
					'right' => esc_html__( 'Right', 'rs-template-builder' ),
				],
				'default'     => 'right',
				'separator'   => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_style',
			[
				'label' => esc_html__( 'Toggle Button', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'toggle_alignment',
			[
				'label'       => esc_html__( 'Alignment', 'rs-template-builder' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'rs-template-builder' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'     => [
						'title' => esc_html__( 'Center', 'rs-template-builder' ),
						'icon'  => 'eicon-h-align-center',
					],
					'flex-end'   => [
						'title' => esc_html__( 'Right', 'rs-template-builder' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'selectors'   => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-toggle-wrap' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'toggle_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'toggle_typography',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-toggle',
			]
		);

		$this->add_control(
			'toggle_width_height_pop',
			[
				'label'        => esc_html__( 'Width/Height', 'rs-template-builder' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => esc_html__( 'None', 'rs-template-builder' ),
				'label_on'     => esc_html__( 'Custom', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'toggle_width',
			[
				'label'      => esc_html__( 'Width', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-toggle' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'toggle_width_height_pop' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'toggle_height',
			[
				'label'      => esc_html__( 'Height', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-toggle' => 'height: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'toggle_width_height_pop' => 'yes'
				]
			]
		);

		$this->end_popover();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'toggle_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-toggle',
			]
		);

		$this->add_responsive_control(
			'toggle_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-toggle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'toggle_style_tabs' );

		$this->start_controls_tab(
			'toggle_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'toggle_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-toggle' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-toggle' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'toggle_box_shadow',
				'selector' => '{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-toggle',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'toggle_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'toggle_hover_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-toggle:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_hover_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-toggle:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'toggle_hover_box_shadow',
				'selector' => '{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-toggle:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'toggle_icon_heading',
			[
				'label'     => esc_html__( 'Toggle Icon', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'toggle_icon_position',
			[
				'label'     => esc_html__( 'Icon Position', 'rs-template-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'row-reverse' => [
						'title' => esc_html__( 'Start', 'rs-template-builder' ),
						'icon'  => "eicon-h-align-left",
					],
					'row'         => [
						'title' => esc_html__( 'End', 'rs-template-builder' ),
						'icon'  => "eicon-h-align-right",
					],
				],
				'selectors' => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-toggle' => 'flex-direction: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'toggle_icon_space',
			[
				'label'      => esc_html__( 'Icon Space', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-toggle' => 'gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'toggle_icon_width_height_pop',
			[
				'label'        => esc_html__( 'Size', 'rs-template-builder' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => esc_html__( 'None', 'rs-template-builder' ),
				'label_on'     => esc_html__( 'Custom', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'toggle_icon_size',
			[
				'label'      => esc_html__( 'Icon Size', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-toggle .toggle-btn-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'toggle_icon_width_height_pop' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'toggle_icon_width',
			[
				'label'      => esc_html__( 'Width', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-toggle .toggle-btn-icon' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'toggle_icon_width_height_pop' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'toggle_icon_height',
			[
				'label'      => esc_html__( 'Height', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-toggle .toggle-btn-icon' => 'height: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'toggle_icon_width_height_pop' => 'yes'
				]
			]
		);

		$this->end_popover();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'toggle_icon_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-toggle .toggle-btn-icon',
			]
		);

		$this->add_responsive_control(
			'toggle_icon_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-toggle .toggle-btn-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'toggle_icon_style_tabs' );

		$this->start_controls_tab(
			'toggle_icon_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'toggle_icon_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-toggle .toggle-btn-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_icon_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-toggle .toggle-btn-icon' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'toggle_icon_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'toggle_icon_color_h',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-toggle:hover .toggle-btn-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_icon_bg_color_h',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-toggle:hover .toggle-btn-icon' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_icon_border_color_h',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-toggle:hover .toggle-btn-icon' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'toggle_icon_border_border!' => ''
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_canvas_style',
			[
				'label' => esc_html__( 'Offcanvas', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-container' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'content_type' => 'text_blocks'
				]
			]
		);

		$this->add_responsive_control(
			'container_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'container_bg_colo',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-container' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'overly_color',
			[
				'label'     => esc_html__( 'Overly Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-overly' => 'background: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_text_blocks_style',
			[
				'label'     => esc_html__( 'Text Block', 'rs-template-builder' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'content_type' => 'text_blocks'
				]
			]
		);

		$this->add_control(
			'title_heading',
			[
				'label' => esc_html__( 'Title', 'rs-template-builder' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typo',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .offcanvas-text-blocks .block-title',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .offcanvas-text-blocks .block-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'title_gap',
			[
				'label'      => esc_html__( 'Bottom Gap', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .offcanvas-text-blocks .block-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'desc_heading',
			[
				'label'     => esc_html__( 'Description', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'desc_typo',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .offcanvas-text-blocks .block-desc',
			]
		);

		$this->add_control(
			'desc_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .offcanvas-text-blocks .block-desc' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_close_style',
			[
				'label' => esc_html__( 'Close Icon', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'close_icon_font_size',
			[
				'label'      => esc_html__( 'Font Size', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-close' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'close_icon_width',
			[
				'label'      => esc_html__( 'Width', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-close' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'close_icon_height',
			[
				'label'      => esc_html__( 'Height', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-close' => 'height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'close_icon_position',
			[
				'label'        => esc_html__( 'Position', 'rs-template-builder' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => esc_html__( 'None', 'rs-template-builder' ),
				'label_on'     => esc_html__( 'Custom', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => [
					'direction' => 'vertical'
				]
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'close_icon_p_l',
			[
				'label'      => esc_html__( 'Left', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-close' => 'left: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'close_icon_position' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'close_icon_p_r',
			[
				'label'      => esc_html__( 'Right', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-close' => 'right: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'close_icon_position' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'close_icon_p_t',
			[
				'label'      => esc_html__( 'Top', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-close' => 'top: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'close_icon_position' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'close_icon_p_b',
			[
				'label'      => esc_html__( 'Bottom', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-close' => 'bottom: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'close_icon_position' => 'yes'
				]
			]
		);

		$this->end_popover();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'close_icon_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-close',
			]
		);

		$this->add_responsive_control(
			'close_icon_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-close' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'close_icon_style_tabs' );

		$this->start_controls_tab(
			'close_icon_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'close_icon_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-close' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'close_icon_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-close' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'close_icon_shadow',
				'selector' => '{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-close',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'close_icon_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'close_icon_hover_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-close:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'close_icon_hover_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-close:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'close_icon_hover_shadow',
				'selector' => '{{WRAPPER}} .rstb-offcanvas-wrap .offcanvas-close:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render(): void {
		$settings = $this->get_settings_for_display();
		$style    = '';
		if ( 'template' === $settings[ 'content_type' ] && ! empty( $settings[ 'template_id' ] ) ) {
			$tb_settings  = get_post_meta( $settings[ 'template_id' ], '_rstb_settings', true );
			$canvas_width = $tb_settings[ 'offcanvas_width' ] ?? '450px';

			if ( ! empty( $canvas_width ) ) {
				$style .= 'style=width:' . $canvas_width;
			}
		}

		$panel_class = $settings[ 'offcanvas_position' ];

		if ( is_rtl() ) {
			$panel_class .= ' rtl-enable';
		}
		?>
        <div class="rstb-offcanvas-wrap">
            <div class="offcanvas-toggle-wrap">
                <button class="offcanvas-toggle">
					<?php if ( ! empty( $settings[ 'toggle_btn_text' ] ) ) {
						echo '<span class="toggle-btn-text">' . esc_html( $settings[ 'toggle_btn_text' ] ) . '</span>';
					}

					if ( 'none' !== $settings[ 'toggle_icon_type' ] ) : ?>
                        <span class="toggle-btn-icon">
                            <?php if ( 'custom' === $settings[ 'toggle_icon_type' ] ) :
	                            Icons_Manager::render_icon( $settings[ 'toggle_custom_icon' ] );
                            else : ?>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M3 4H21V6H3V4ZM9 11H21V13H9V11ZM3 18H21V20H3V18Z"></path>
                                </svg>
                            <?php endif; ?>
                        </span>
					<?php endif; ?>
                </button>
            </div>
            <div class="rstb-offcanvas-panel position-<?php echo esc_attr( $panel_class ) ?>" data-lenis-prevent>
                <div class="offcanvas-overly"></div>
                <div class="offcanvas-container" <?php echo esc_attr( $style ); ?>>
                    <button class="offcanvas-close">
						<?php if ( 'custom' === $settings[ 'close_icon_type' ] ) : ?>
							<?php Icons_Manager::render_icon( $settings[ 'close_custom_icon' ] ); ?>
						<?php else : ?>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M10.5859 12L2.79297 4.20706L4.20718 2.79285L12.0001 10.5857L19.793 2.79285L21.2072 4.20706L13.4143 12L21.2072 19.7928L19.793 21.2071L12.0001 13.4142L4.20718 21.2071L2.79297 19.7928L10.5859 12Z"></path>
                            </svg>
						<?php endif; ?>
                    </button>
					<?php if ( 'template' === $settings[ 'content_type' ] ) : ?>
						<?php if ( ! empty( $settings[ 'template_id' ] ) ) {
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Displaying with Elementor content rendering
							echo Plugin::$instance->frontend->get_builder_content_for_display( $settings[ 'template_id' ] );
						} ?>
					<?php else : ?>
                        <div class="offcanvas-text-blocks">
							<?php foreach ( $settings[ 'text_blocks' ] as $block ) : ?>
                                <div class="offcanvas-text-block">
									<?php if ( ! empty( $block[ 'title' ] ) ) : ?>
                                        <h4 class="block-title"><?php echo esc_html( $block[ 'title' ] ) ?></h4>
									<?php endif; ?>
									<?php if ( ! empty( $block[ 'desc' ] ) ) : ?>
                                        <p class="block-desc"><?php echo esc_html( $block[ 'desc' ] ) ?></p>
									<?php endif; ?>
                                </div>
							<?php endforeach; ?>
                        </div>
					<?php endif; ?>
                </div>
            </div>
        </div>
		<?php
	}

	private function get_builder_template(): array {
		$args = [
			'post_type'      => 'rstb_template',
			'posts_per_page' => - 1,
			'post_status'    => 'publish',
			'tax_query'      => [
				[
					'taxonomy' => 'rstb_template_type',
					'field'    => 'slug',
					'terms'    => 'offcanvas',
				],
			],
		];

		$items = [];

		$posts = get_posts( $args );

		foreach ( $posts as $post ) {
			$items[ $post->ID ] = $post->post_title;
		}

		return [ '' => __( '-- Select Template --', 'rs-template-builder' ) ] + $items;
	}
}