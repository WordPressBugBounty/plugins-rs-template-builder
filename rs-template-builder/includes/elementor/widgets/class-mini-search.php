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

class Mini_Search extends Widget_Base {

	public function get_name(): string {
		return 'rstb-mini-search';
	}

	public function get_title(): string {
		return esc_html__( 'Mini Search', 'rs-template-builder' );
	}

	public function get_icon(): string {
		return 'eicon-search rstb-branding-icon';
	}

	public function get_categories(): array {
		return [ 'rstb_elements' ];
	}

	public function get_keywords(): array {
		return [ 'rs template builder', 'rstheme', 'header', 'footer', 'search', 'rs-template-builder' ];
	}

	protected function is_dynamic_content(): bool {
		return false;
	}

	public function has_widget_inner_wrapper(): bool {
		return ! Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls(): void {
		$this->start_controls_section(
			'section_widget_content',
			[
				'label' => esc_html__( 'Widget', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'search_type',
			[
				'label'       => esc_html__( 'Type', 'rs-template-builder' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'options'     => [
					'btn-toggle'  => esc_html__( 'Button Toggle', 'rs-template-builder' ),
					'search-form' => esc_html__( 'Search From', 'rs-template-builder' ),
				],
				'default'     => 'btn-toggle',
			]
		);

		$this->add_control(
			'open_icon',
			[
				'label'       => esc_html__( 'Open Icon', 'rs-template-builder' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => [
					'value'   => 'fas fa-search',
					'library' => 'fa-solid',
				],
				'label_block' => false,
				'skin'        => 'inline',
				'condition'   => [
					'search_type' => 'btn-toggle'
				],
			]
		);

		$this->add_control(
			'close_icon',
			[
				'label'       => esc_html__( 'Close Icon', 'rs-template-builder' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => [
					'value'   => 'fas fa-times',
					'library' => 'fa-solid',
				],
				'label_block' => false,
				'skin'        => 'inline',
				'condition'   => [
					'search_type' => 'btn-toggle'
				],
			]
		);

		$this->add_control(
			'search_form_heading',
			[
				'label'     => esc_html__( 'Search Form', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'placeholder_text',
			[
				'label'       => esc_html__( 'Placeholder Text', 'rs-template-builder' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'Search Keyword...', 'rs-template-builder' ),
				'placeholder' => esc_html__( 'Enter placeholder', 'rs-template-builder' ),
				'ai'          => false
			]
		);

		$this->add_control(
			'submit_icon',
			[
				'label'       => esc_html__( 'Submit Icon', 'rs-template-builder' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => [
					'value'   => 'fas fa-search',
					'library' => 'fa-solid',
				],
				'label_block' => false,
				'skin'        => 'inline'
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_search_area_style',
			[
				'label' => esc_html__( 'Search Form', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'input_alignment',
			[
				'label'       => esc_html__( 'Text Alignment', 'rs-template-builder' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => [
					'left'   => [
						'title' => esc_html__( 'Left', 'rs-template-builder' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'rs-template-builder' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'rs-template-builder' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'toggle'      => true,
				'selectors'   => [
					'{{WRAPPER}} .search-form-area input' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'search_form_area_width',
			[
				'label'      => esc_html__( 'Width', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 2000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					]
				],
				'selectors'  => [
					'{{WRAPPER}} .search-form-area' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'search_area_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .search-form-area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'search_area_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .search-form-area' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'search_area_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .search-form-area',
			]
		);

		$this->add_responsive_control(
			'search_area_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .search-form-area' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'search_area_shadow',
				'selector' => '{{WRAPPER}} .search-form-area',
			]
		);

		$this->add_control(
			'search_area_position',
			[
				'label'        => esc_html__( 'Position', 'rs-template-builder' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => esc_html__( 'None', 'rs-template-builder' ),
				'label_on'     => esc_html__( 'Custom', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => [
					'search_type' => 'btn-toggle'
				],
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'search_area_p_l',
			[
				'label'      => esc_html__( 'Left', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .search-form-area' => 'left: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'search_area_position' => 'yes',
					'search_type'          => 'btn-toggle'
				]
			]
		);

		$this->add_responsive_control(
			'search_area_p_r',
			[
				'label'      => esc_html__( 'Right', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .search-form-area' => 'right: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'search_area_position' => 'yes',
					'search_type'          => 'btn-toggle'
				]
			]
		);

		$this->add_responsive_control(
			'search_area_p_t',
			[
				'label'      => esc_html__( 'Top', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .search-form-area' => 'top: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'search_area_position' => 'yes',
					'search_type'          => 'btn-toggle'
				]
			]
		);

		$this->add_responsive_control(
			'search_area_p_b',
			[
				'label'      => esc_html__( 'Bottom', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .search-form-area' => 'Bottom: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'search_area_position' => 'yes',
					'search_type'          => 'btn-toggle'
				]
			]
		);

		$this->end_popover();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_input_style',
			[
				'label' => esc_html__( 'Search Input', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'input_typo',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .search-form-area input',
			]
		);

		$this->add_responsive_control(
			'input_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .search-form-area input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'input_width',
			[
				'label'      => esc_html__( 'Width', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .search-form-area input' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'input_height',
			[
				'label'      => esc_html__( 'Height', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .search-form-area input' => 'height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'input_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .search-form-area input',
			]
		);

		$this->add_responsive_control(
			'input_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .search-form-area input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'input_style_tab' );

		$this->start_controls_tab(
			'input_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'input_color',
			[
				'label'     => esc_html__( 'Text', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .search-form-area input' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'placeholder_color',
			[
				'label'     => esc_html__( 'Placeholder', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .search-form-area input' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'input_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .search-form-area input' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'input_focus_tab',
			[
				'label' => esc_html__( 'Focus', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'focus_input_color',
			[
				'label'     => esc_html__( 'Text', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .search-form-area input:focus' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'focus_input_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .search-form-area input:focus' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_submit_btn_style',
			[
				'label' => esc_html__( 'Submit Button', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'submit_btn_position',
			[
				'label'        => esc_html__( 'Position', 'rs-template-builder' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => esc_html__( 'None', 'rs-template-builder' ),
				'label_on'     => esc_html__( 'Custom', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->start_popover();

		$this->add_control(
			'submit_bnt_p',
			[
				'label'       => esc_html__( 'Position', 'rs-template-builder' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'options'     => [
					''         => esc_html__( 'Default', 'rs-template-builder' ),
					'relative' => esc_html__( 'Relative', 'rs-template-builder' ),
					'absolute' => esc_html__( 'Absolute', 'rs-template-builder' ),
					'static'   => esc_html__( 'Static', 'rs-template-builder' ),
				],
				'default'     => '',
				'selectors'   => [
					'{{WRAPPER}} .search-form-area .submit-btn' => 'position: {{VALUE}}',
				],
				'condition'   => [
					'submit_btn_position' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'submit_btn_p_l',
			[
				'label'      => esc_html__( 'Left', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .search-form-area .submit-btn' => 'left: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'submit_btn_position' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'submit_btn_p_r',
			[
				'label'      => esc_html__( 'Right', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .search-form-area .submit-btn' => 'right: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'submit_btn_position' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'submit_btn_p_t',
			[
				'label'      => esc_html__( 'Top', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .search-form-area .submit-btn' => 'top: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'submit_btn_position' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'submit_btn_p_b',
			[
				'label'      => esc_html__( 'Bottom', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .search-form-area .submit-btn' => 'Bottom: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'submit_btn_position' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'submit_btn_p_t_x',
			[
				'label'      => esc_html__( 'Translate X', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .search-form-area .submit-btn' => '--translate-x: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'submit_btn_position' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'submit_btn_p_t_y',
			[
				'label'      => esc_html__( 'Translate Y', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .search-form-area .submit-btn' => '--translate-y: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'submit_btn_position' => 'yes'
				]
			]
		);

		$this->end_popover();

		$this->add_responsive_control(
			'submit_btn_font_size',
			[
				'label'      => esc_html__( 'Icon Size', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .search-form-area .submit-btn' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'submit_btn_width',
			[
				'label'      => esc_html__( 'Width', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .search-form-area .submit-btn' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'submit_btn_height',
			[
				'label'      => esc_html__( 'Height', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .search-form-area .submit-btn' => 'height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'submit_btn_margin',
			[
				'label'      => esc_html__( 'Margin', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .search-form-area .submit-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'submit_btn_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .search-form-area .submit-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'submit_btn_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .search-form-area .submit-btn',
			]
		);

		$this->add_responsive_control(
			'submit_btn_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .search-form-area .submit-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'submit_btn_style_tabs' );

		$this->start_controls_tab(
			'submit_btn_normal',
			[
				'label' => esc_html__( 'Normal', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'submit_btn_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .search-form-area .submit-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submit_btn_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .search-form-area .submit-btn' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'submit_btn_hover',
			[
				'label' => esc_html__( 'Hover', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'submit_btn_hover_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .search-form-area .submit-btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submit_btn_hover_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .search-form-area .submit-btn:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_style',
			[
				'label'     => esc_html__( 'Toggle Button', 'rs-template-builder' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'search_type' => 'btn-toggle'
				],
			]
		);

		$this->add_responsive_control(
			'search_btn_alignment',
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
				'toggle'      => true,
				'selectors'   => [
					'{{WRAPPER}} .rstb-mini-search' => 'align-items: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'search_btn_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .search-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'search_btn_font_size',
			[
				'label'      => esc_html__( 'Icon Size', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .search-btn' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'search_btn_width',
			[
				'label'      => esc_html__( 'Width', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .search-btn' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'search_btn_height',
			[
				'label'      => esc_html__( 'Height', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .search-btn' => 'height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'search_btn_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .search-btn',
			]
		);

		$this->add_responsive_control(
			'search_btn_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .search-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'search_btn_style_tabs' );

		$this->start_controls_tab(
			'search_btn_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'search_btn_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .search-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'search_btn_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .search-btn' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'search_btn_shadow',
				'selector' => '{{WRAPPER}} .search-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'search_btn_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'hover_search_btn_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-mini-search .search-btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hover_search_btn_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-mini-search .search-btn:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'hover_search_btn_shadow',
				'selector' => '{{WRAPPER}} .rstb-mini-search .search-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'search_btn_active_tab',
			[
				'label' => esc_html__( 'Active', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'active_search_btn_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .search-btn.search-open' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'active_search_btn_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .search-btn.search-open' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'active_search_btn_shadow',
				'selector' => '{{WRAPPER}} .search-btn.search-open',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render(): void {
		$settings = $this->get_settings_for_display();
		?>
        <div class="rstb-mini-search type-<?php echo esc_attr( $settings[ 'search_type' ] ) ?>">
			<?php if ( 'btn-toggle' === $settings[ 'search_type' ] ) : ?>
                <button class="search-btn">
					<?php if ( ! empty( $settings[ 'open_icon' ][ 'value' ] ) ) : ?>
                        <span class="open-icon"><?php Icons_Manager::render_icon( $settings[ 'open_icon' ] ); ?></span>
					<?php endif; ?>
					<?php if ( ! empty( $settings[ 'close_icon' ][ 'value' ] ) ) : ?>
                        <span class="close-icon"><?php Icons_Manager::render_icon( $settings[ 'close_icon' ] ); ?></span>
					<?php endif; ?>
                </button>
			<?php endif; ?>
            <div class="search-form-area">
                <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <input
                            type="search"
                            class="search-field"
                            placeholder="<?php echo esc_html( $settings[ 'placeholder_text' ] ); ?>"
                            value="<?php echo get_search_query() ?>"
                            name="s"
                    />
                    <button type="submit" class="submit-btn">
						<?php Icons_Manager::render_icon( $settings[ 'submit_icon' ] ); ?>
                    </button>
                </form>
            </div>
        </div>
		<?php
	}
}