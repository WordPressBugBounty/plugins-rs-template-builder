<?php

namespace RsTemplateBuilder\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;
use RsTemplateBuilder\Helpers\Nav_Walker;

class Nav_Menu extends Widget_Base {

	public function get_name(): string {
		return 'rstb-nav-menu';
	}

	public function get_title(): string {
		return esc_html__( 'Nav Menu', 'rs-template-builder' );
	}

	public function get_icon(): string {
		return 'eicon-nav-menu rstb-branding-icon';
	}

	public function get_categories(): array {
		return [ 'rstb_elements' ];
	}

	public function get_keywords(): array {
		return [ 'rs template builder', 'rstheme', 'header', 'footer', 'menu', 'navigation', 'rs-template-builder' ];
	}

	protected function is_dynamic_content(): bool {
		return true;
	}

	public function has_widget_inner_wrapper(): bool {
		return false;
	}

	protected function register_controls(): void {
		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'Menu', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'selected_menu',
			[
				'label'   => esc_html__( 'Select Menu', 'rs-template-builder' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_menus_list(),
				'default' => ''
			]
		);

		$this->add_control(
			'layout_heading',
			[
				'label'     => esc_html__( 'Layout', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'direction',
			[
				'label'       => esc_html__( 'Direction', 'rs-template-builder' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'options'     => [
					'horizontal' => esc_html__( 'Horizontal', 'rs-template-builder' ),
					'vertical'   => esc_html__( 'Vertical', 'rs-template-builder' ),
				],
				'default'     => 'horizontal',
			]
		);

		$this->add_responsive_control(
			'horizontal_alignment',
			[
				'label'       => esc_html__( 'Item Alignment', 'rs-template-builder' ),
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
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu' => 'justify-content: {{VALUE}};',
				],
				'condition'   => [
					'direction' => 'horizontal'
				]
			]
		);

		$this->add_responsive_control(
			'vertical_alignment',
			[
				'label'       => esc_html__( 'Item Alignment', 'rs-template-builder' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => [
					'start'  => [
						'title' => esc_html__( 'Start', 'rs-template-builder' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'rs-template-builder' ),
						'icon'  => 'eicon-h-align-center',
					],
					'end'    => [
						'title' => esc_html__( 'End', 'rs-template-builder' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'selectors'   => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu .menu-item-link' => 'text-align: {{VALUE}};',
				],
				'condition'   => [
					'direction' => 'vertical'
				]
			]
		);

		$this->add_control(
			'show_vertical_divider',
			[
				'label'        => esc_html__( 'Show Divider', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'No', 'rs-template-builder' ),
				'return_value' => 'has-vertical-divider',
				'default'      => 'has-vertical-divider',
				'condition'    => [
					'direction' => 'vertical'
				]
			]
		);

		$this->add_control(
			'submenu_icon_type',
			[
				'label'       => esc_html__( 'Submenu Icon', 'rs-template-builder' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'options'     => [
					'default' => esc_html__( 'Default', 'rs-template-builder' ),
					'custom'  => esc_html__( 'Custom', 'rs-template-builder' ),
				],
				'default'     => 'default',
				'separator'   => 'before'
			]
		);

		$this->add_control(
			'submenu_icon',
			[
				'label'       => esc_html__( 'Expand Icon', 'rs-template-builder' ),
				'type'        => Controls_Manager::ICONS,
				'label_block' => false,
				'skin'        => 'inline',
				'condition'   => [
					'submenu_icon_type' => 'custom'
				]
			]
		);

		$this->add_control(
			'submenu_icon_two',
			[
				'label'       => esc_html__( 'Collapse Icon', 'rs-template-builder' ),
				'type'        => Controls_Manager::ICONS,
				'label_block' => false,
				'skin'        => 'inline',
				'condition'   => [
					'submenu_icon_type' => 'custom'
				]
			]
		);

		$this->add_control(
			'show_menu_prefix_icon',
			[
				'label'        => esc_html__( 'Menu Item Icon', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'No', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before'
			]
		);

		$this->add_control(
			'menu_prefix_icon',
			[
				'label'       => esc_html__( 'Select Icon', 'rs-template-builder' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => [
					'value'   => 'fas fa-circle',
					'library' => 'fa-solid',
				],
				'label_block' => false,
				'skin'        => 'inline',
				'condition'   => [
					'show_menu_prefix_icon' => 'yes'
				]
			]
		);

		$this->add_control(
			'menu_prefix_icon_style',
			[
				'label'        => esc_html__( 'Item Style', 'rs-template-builder' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => esc_html__( 'None', 'rs-template-builder' ),
				'label_on'     => esc_html__( 'Custom', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => [
					'show_menu_prefix_icon' => 'yes'
				]
			]
		);

		$this->start_popover();

		$this->add_control(
			'menu_prefix_icon_p_v',
			[
				'label'       => esc_html__( 'Position', 'rs-template-builder' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'options'     => [
					''  => esc_html__( 'Before', 'rs-template-builder' ),
					'3' => esc_html__( 'After', 'rs-template-builder' ),
				],
				'selectors'   => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu .menu-item-link .menu-prefix-icon' => 'order: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'menu_prefix_icon_size',
			[
				'label'      => esc_html__( 'Icon Size', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu .menu-prefix-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'show_menu_prefix_icon'  => 'yes',
					'menu_prefix_icon_style' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'menu_prefix_icon_space',
			[
				'label'      => esc_html__( 'Space Right', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu .menu-prefix-icon' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'show_menu_prefix_icon'  => 'yes',
					'menu_prefix_icon_style' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'menu_prefix_icon_space_left',
			[
				'label'      => esc_html__( 'Space Left', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu .menu-prefix-icon' => 'margin-left: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'show_menu_prefix_icon'  => 'yes',
					'menu_prefix_icon_style' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'menu_prefix_icon_v_p',
			[
				'label'      => esc_html__( 'Vertical Position', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu .menu-prefix-icon' => 'top: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'show_menu_prefix_icon'  => 'yes',
					'menu_prefix_icon_style' => 'yes'
				]
			]
		);

		$this->add_control(
			'menu_prefix_icon_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu .menu-prefix-icon' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_menu_prefix_icon'  => 'yes',
					'menu_prefix_icon_style' => 'yes'
				]
			]
		);

		$this->add_control(
			'menu_prefix_icon_color_hover',
			[
				'label'     => esc_html__( 'Color(Hover)', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu .menu-item-link:hover .menu-prefix-icon' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_menu_prefix_icon'  => 'yes',
					'menu_prefix_icon_style' => 'yes'
				]
			]
		);

		$this->add_control(
			'menu_prefix_icon_color_active',
			[
				'label'     => esc_html__( 'Color(Active)', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu .current-menu-parent > .menu-item-link .menu-prefix-icon, {{WRAPPER}} > .rstb-nav-menu > .primary-menu > .current-menu-item > .menu-item-link .menu-prefix-icon' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_menu_prefix_icon'  => 'yes',
					'menu_prefix_icon_style' => 'yes'
				]
			]
		);

		$this->end_popover();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_mobile_panel',
			[
				'label' => esc_html__( 'Mobile Menu(Responsive)', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'mobile_selected_menu',
			[
				'label'   => esc_html__( 'Select Menu', 'rs-template-builder' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_menus_list(),
				'default' => ''
			]
		);

		$this->add_control(
			'breakpoint',
			[
				'label'   => esc_html__( 'Breakpoint', 'rs-template-builder' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'breakpoint-all'  => esc_html__( 'All Screen', 'rs-template-builder' ),
					'breakpoint-xl'   => esc_html__( 'Large (< 1200px)', 'rs-template-builder' ),
					'breakpoint-lg'   => esc_html__( 'Tablet (< 1025px)', 'rs-template-builder' ),
					'breakpoint-md'   => esc_html__( 'Mobile (< 768px)', 'rs-template-builder' ),
					'breakpoint-none' => esc_html__( 'None', 'rs-template-builder' ),
				],
				'default' => 'breakpoint-lg',
			]
		);

		$this->add_control(
			'mobile_menu_style',
			[
				'label'       => esc_html__( 'Menu Style', 'rs-template-builder' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'options'     => [
					'classic'   => esc_html__( 'Classic', 'rs-template-builder' ),
					'offcanvas' => esc_html__( 'Offcanvas', 'rs-template-builder' ),
				],
				'default'     => 'offcanvas',
				'separator'   => 'before'
			]
		);

		$this->add_control(
			'offcanvas_position',
			[
				'label'       => esc_html__( 'Offcanvas Position', 'rs-template-builder' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'options'     => [
					'left'  => esc_html__( 'Left', 'rs-template-builder' ),
					'right' => esc_html__( 'Right', 'rs-template-builder' ),
				],
				'default'     => 'right',
				'condition'   => [
					'mobile_menu_style' => 'offcanvas',
				]
			]
		);

		$this->add_responsive_control(
			'mobile_menu_alignment',
			[
				'label'       => esc_html__( 'Item Alignment', 'rs-template-builder' ),
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
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .menu-item-link' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'mobile_menu_divider',
			[
				'label'        => esc_html__( 'Show Divider', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'No', 'rs-template-builder' ),
				'return_value' => 'has-vertical-divider',
				'default'      => 'has-vertical-divider',
			]
		);

		$this->add_control(
			'mobile_submenu_icon_type',
			[
				'label'       => esc_html__( 'Submenu Icon Type', 'rs-template-builder' ),
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
			'mobile_submenu_icon',
			[
				'label'       => esc_html__( 'Expand Icon', 'rs-template-builder' ),
				'type'        => Controls_Manager::ICONS,
				'label_block' => false,
				'skin'        => 'inline',
				'condition'   => [
					'mobile_submenu_icon_type' => 'custom'
				]
			]
		);

		$this->add_control(
			'mobile_submenu_icon_two',
			[
				'label'       => esc_html__( 'Collapse Icon', 'rs-template-builder' ),
				'type'        => Controls_Manager::ICONS,
				'label_block' => false,
				'skin'        => 'inline',
				'condition'   => [
					'mobile_submenu_icon_type' => 'custom'
				]
			]
		);

		$this->add_control(
			'toggle_icon_heading',
			[
				'label'     => esc_html__( 'Toggle Icon', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'toggle_icon_source',
			[
				'label'       => esc_html__( 'Icon Type', 'rs-template-builder' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'options'     => [
					'default' => esc_html__( 'Default', 'rs-template-builder' ),
					'custom'  => esc_html__( 'Custom', 'rs-template-builder' ),
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
					'toggle_icon_source' => 'custom'
				]
			]
		);

		$this->add_responsive_control(
			'toggle_icon_alignment',
			[
				'label'       => esc_html__( 'Alignment', 'rs-template-builder' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => [
					'left'   => [
						'title' => esc_html__( 'Left', 'rs-template-builder' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'rs-template-builder' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'rs-template-builder' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'selectors'   => [
					'{{WRAPPER}} .rstb-nav-menu .menu-toggler-wrap' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'close_heading',
			[
				'label'     => esc_html__( 'Close Icon', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'close_icon_source',
			[
				'label'       => esc_html__( 'Icon Type', 'rs-template-builder' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'options'     => [
					'default' => esc_html__( 'Default', 'rs-template-builder' ),
					'custom'  => esc_html__( 'Custom', 'rs-template-builder' ),
				],
				'default'     => 'default',
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
					'close_icon_source' => 'custom'
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_mobile_logo',
			[
				'label'     => esc_html__( 'Mobile Logo', 'rs-template-builder' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'mobile_menu_style' => 'offcanvas'
				]
			]
		);

		$this->add_control(
			'show_mobile_logo',
			[
				'label'        => esc_html__( 'Show a mobile logo', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'No', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					'mobile_menu_style' => 'offcanvas'
				]
			]
		);

		$this->add_control(
			'mobile_logo',
			[
				'label'     => esc_html__( 'Mobile Logo', 'rs-template-builder' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'mobile_menu_style' => 'offcanvas',
					'show_mobile_logo'  => 'yes',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_menu_li_style',
			[
				'label' => esc_html__( 'Menu Items', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'menu_item_heading',
			[
				'label' => esc_html__( 'Menu Item (li)', 'rs-template-builder' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'item_margin',
			[
				'label'      => esc_html__( 'Margin', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'first_item_style',
			[
				'label'        => esc_html__( 'First Item', 'rs-template-builder' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => esc_html__( 'None', 'rs-template-builder' ),
				'label_on'     => esc_html__( 'Custom', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'first_item_margin',
			[
				'label'      => esc_html__( 'Margin', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item:first-child' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'first_item_style' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'first_item_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item:first-child' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'first_item_style' => 'yes',
				]
			]
		);

		$this->end_popover();

		$this->add_control(
			'last_item_style',
			[
				'label'        => esc_html__( 'Last Item', 'rs-template-builder' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => esc_html__( 'None', 'rs-template-builder' ),
				'label_on'     => esc_html__( 'Custom', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'last_item_margin',
			[
				'label'      => esc_html__( 'Margin', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item:last-child' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'last_item_style' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'last_item_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item:last-child' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'last_item_style' => 'yes',
				]
			]
		);

		$this->end_popover();

		$this->add_responsive_control(
			'vertical_divider_width',
			[
				'label'     => esc_html__( 'Vertical Divider Width', 'rs-template-builder' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => [
					'{{WRAPPER}} .has-vertical-divider .primary-menu .menu-item, {{WRAPPER}} .has-vertical-divider .primary-menu .sub-menu .menu-item:first-child' => 'border-width: {{VALUE}}px',
				],
				'separator' => 'before',
				'condition' => [
					'direction'             => 'vertical',
					'show_vertical_divider' => 'has-vertical-divider'
				]
			]
		);

		$this->add_control(
			'vertical_divider_color',
			[
				'label'     => esc_html__( 'Vertical Divider Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .has-vertical-divider .primary-menu .menu-item, {{WRAPPER}} .has-vertical-divider .primary-menu .sub-menu .menu-item:first-child' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'direction'             => 'vertical',
					'show_vertical_divider' => 'has-vertical-divider'
				]
			]
		);

		$this->add_control(
			'menu_item_a_heading',
			[
				'label'     => esc_html__( 'Menu Link (a)', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'link_margin',
			[
				'label'      => esc_html__( 'Margin', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item > .menu-item-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'link_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item > .menu-item-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'link_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item > .menu-item-link',
			]
		);

		$this->add_responsive_control(
			'link_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item > .menu-item-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'link_typography',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item > .menu-item-link',
			]
		);

		$this->start_controls_tabs( 'link_style_tabs' );

		$this->start_controls_tab(
			'link_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'link_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item > .menu-item-link' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'link_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item > .menu-item-link' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'link_box_shadow',
				'selector' => '{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item > .menu-item-link',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'link_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'link_hover_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item > .menu-item-link:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'link_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item > .menu-item-link:hover' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'link_border_border!' => ''
				]
			]
		);

		$this->add_control(
			'link_hover_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item > .menu-item-link:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'link_hover_box_shadow',
				'selector' => '{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item > .menu-item-link:hover',
			]
		);

		$this->add_responsive_control(
			'link_hover_p_l',
			[
				'label'      => esc_html__( 'Padding Start (For Effect)', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu .menu-item .menu-item-link:hover' => 'padding-inline-start: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'direction' => 'vertical'
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'link_active_tab',
			[
				'label' => esc_html__( 'Active', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'link_active_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu .current-menu-parent > .menu-item-link, {{WRAPPER}} > .rstb-nav-menu > .primary-menu > .current-menu-item > .menu-item-link' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'link_active_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu .current-menu-parent > .menu-item-link, {{WRAPPER}} > .rstb-nav-menu > .primary-menu > .current-menu-item > .menu-item-link' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'link_border_border!' => ''
				]
			]
		);

		$this->add_control(
			'link_active_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu .current-menu-parent > .menu-item-link, {{WRAPPER}} > .rstb-nav-menu > .primary-menu > .current-menu-item > .menu-item-link' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'link_active_box_shadow',
				'selector' => '{{WRAPPER}} > .rstb-nav-menu > .primary-menu .current-menu-parent > .menu-item-link, {{WRAPPER}} > .rstb-nav-menu > .primary-menu > .current-menu-item > .menu-item-link',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'menu_link_first_item',
			[
				'label'        => esc_html__( 'First Item', 'rs-template-builder' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => esc_html__( 'None', 'rs-template-builder' ),
				'label_on'     => esc_html__( 'Custom', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before'
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'first_menu_link_margin',
			[
				'label'      => esc_html__( 'Margin', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item:first-child > .menu-item-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'menu_link_first_item' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'first_menu_link_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item:first-child > .menu-item-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'menu_link_first_item' => 'yes',
				]
			]
		);

		$this->add_control(
			'first_menu_link_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item:first-child > .menu-item-link' => 'color: {{VALUE}}',
				],
				'condition' => [
					'menu_link_first_item' => 'yes',
				]
			]
		);

		$this->add_control(
			'first_menu_link_bg',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item:first-child > .menu-item-link' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'menu_link_first_item' => 'yes',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'first_menu_link_border',
				'label'     => esc_html__( 'Border', 'rs-template-builder' ),
				'selector'  => '{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item:first-child > .menu-item-link',
				'condition' => [
					'menu_link_first_item' => 'yes',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'first_menu_link_shadow',
				'selector'  => '{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item:first-child > .menu-item-link',
				'condition' => [
					'menu_link_first_item' => 'yes',
				]
			]
		);

		$this->end_popover();

		$this->add_control(
			'menu_link_last_item',
			[
				'label'        => esc_html__( 'Last Item', 'rs-template-builder' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => esc_html__( 'None', 'rs-template-builder' ),
				'label_on'     => esc_html__( 'Custom', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'last_menu_link_margin',
			[
				'label'      => esc_html__( 'Margin', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item:last-child > .menu-item-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'menu_link_last_item' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'last_menu_link_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item:last-child > .menu-item-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'menu_link_last_item' => 'yes',
				]
			]
		);

		$this->add_control(
			'last_menu_link_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item:last-child > .menu-item-link' => 'color: {{VALUE}}',
				],
				'condition' => [
					'menu_link_last_item' => 'yes',
				]
			]
		);

		$this->add_control(
			'last_menu_link_bg',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item:last-child > .menu-item-link' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'menu_link_last_item' => 'yes',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'last_menu_link_border',
				'label'     => esc_html__( 'Border', 'rs-template-builder' ),
				'selector'  => '{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item:last-child > .menu-item-link',
				'condition' => [
					'menu_link_last_item' => 'yes',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'last_menu_link_shadow',
				'selector'  => '{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item:last-child > .menu-item-link',
				'condition' => [
					'menu_link_last_item' => 'yes',
				]
			]
		);

		$this->end_popover();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_submenu_style',
			[
				'label' => esc_html__( 'Submenu', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'submenu_width',
			[
				'label'      => esc_html__( 'Width', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					]
				],
				'selectors'  => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu .sub-menu' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'submenu_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} > .rstb-nav-menu > .primary-menu .sub-menu',
				'exclude'  => [ 'image' ]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'submenu_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} > .rstb-nav-menu > .primary-menu .sub-menu',
			]
		);

		$this->add_responsive_control(
			'submenu_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu .sub-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'submenu_box_shadow',
				'selector' => '{{WRAPPER}} > .rstb-nav-menu > .primary-menu .sub-menu',
			]
		);

		$this->add_responsive_control(
			'submenu_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu .sub-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'submenu_divider_width',
			[
				'label'     => esc_html__( 'Item Divider Width', 'rs-template-builder' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu .sub-menu .menu-item:not(:last-child)' => 'border-width: {{VALUE}}px',
				],
				'condition' => [
					'direction' => 'horizontal'
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'submenu_divider_color',
			[
				'label'     => esc_html__( 'Item Divider Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu .sub-menu .menu-item:not(:last-child)' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'direction' => 'horizontal'
				]
			]
		);

		$this->add_responsive_control(
			'submenu_indent',
			[
				'label'      => esc_html__( 'Indent', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu .sub-menu' => 'padding-left: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'direction' => 'vertical'
				]
			]
		);

		$this->add_control(
			'submenu_link_heading',
			[
				'label'     => esc_html__( 'Submenu Link', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'submenu_link_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu .sub-menu .menu-item-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'submenu_link_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} > .rstb-nav-menu > .primary-menu .sub-menu .menu-item-link',
			]
		);

		$this->add_responsive_control(
			'submenu_link_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu .sub-menu .menu-item-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'submenu_link_typography',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} > .rstb-nav-menu > .primary-menu .sub-menu .menu-item-link',
			]
		);

		$this->start_controls_tabs( 'submenu_link_style_tabs' );

		$this->start_controls_tab(
			'submenu_link_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'submenu_link_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu .sub-menu .menu-item-link' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submenu_link_bg',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu .sub-menu .menu-item-link' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'submenu_link_box_shadow',
				'selector' => '{{WRAPPER}} > .rstb-nav-menu > .primary-menu .sub-menu .menu-item-link',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'submenu_link_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'submenu_link_hover_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu .sub-menu .menu-item-link:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submenu_link_hover_bg',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu .sub-menu .menu-item-link:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submenu_link_hover_border',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu .sub-menu .menu-item-link:hover' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'submenu_link_border_border!' => ''
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'submenu_link_hover_box_shadow',
				'selector' => '{{WRAPPER}} > .rstb-nav-menu > .primary-menu .sub-menu .menu-item-link:hover',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'submenu_link_active_tab',
			[
				'label' => esc_html__( 'Active', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'submenu_link_active_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu .sub-menu .current-menu-parent > .menu-item-link, {{WRAPPER}} > .rstb-nav-menu > .primary-menu .sub-menu .current-menu-item > .menu-item-link' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submenu_link_active_bg',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu .sub-menu .current-menu-parent > .menu-item-link, {{WRAPPER}} > .rstb-nav-menu > .primary-menu .sub-menu .current-menu-item > .menu-item-link' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submenu_link_active_border',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu .sub-menu .current-menu-parent > .menu-item-link, {{WRAPPER}} > .rstb-nav-menu > .primary-menu .sub-menu .current-menu-item > .menu-item-link' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'submenu_link_border_border!' => ''
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'submenu_link_active_box_shadow',
				'selector' => '{{WRAPPER}} > .rstb-nav-menu > .primary-menu .sub-menu .current-menu-parent > .menu-item-link, {{WRAPPER}} > .rstb-nav-menu > .primary-menu .sub-menu .current-menu-item > .menu-item-link',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'submenu_icon_style',
			[
				'label' => esc_html__( 'Submenu Icon', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'submenu_icon_top_level_h',
			[
				'label'     => esc_html__( 'Top Level', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [
					'direction' => 'horizontal'
				]
			]
		);

		$this->add_control(
			'submenu_icon_area_size_top_l',
			[
				'label'        => esc_html__( 'Area Size', 'rs-template-builder' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => esc_html__( 'None', 'rs-template-builder' ),
				'label_on'     => esc_html__( 'Custom', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => [
					'direction' => 'horizontal'
				]
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'submenu_icon_width_top_l',
			[
				'label'      => esc_html__( 'Width', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item > .menu-item-link > .sub-menu-icon' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'direction'                    => 'horizontal',
					'submenu_icon_area_size_top_l' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'submenu_icon_height_top_l',
			[
				'label'      => esc_html__( 'Height', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item > .menu-item-link > .sub-menu-icon' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'direction'                    => 'horizontal',
					'submenu_icon_area_size_top_l' => 'yes'
				]
			]
		);

		$this->end_popover();

		$this->add_responsive_control(
			'submenu_icon_font_size_top_l',
			[
				'label'      => esc_html__( 'Icon Size', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item > .menu-item-link > .sub-menu-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'direction' => 'horizontal'
				]
			]
		);

		$this->add_responsive_control(
			'submenu_icon_space_top_l',
			[
				'label'      => esc_html__( 'Icon Space', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item > .menu-item-link > .sub-menu-icon' => 'margin-inline-start: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'direction' => 'horizontal'
				]
			]
		);

		$this->add_control(
			'submen_icon_rotate_top_l_p',
			[
				'label'        => esc_html__( 'Icon Rotate', 'rs-template-builder' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => esc_html__( 'None', 'rs-template-builder' ),
				'label_on'     => esc_html__( 'Custom', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => [
					'direction' => 'horizontal'
				]
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'submen_icon_rotate_top_l',
			[
				'label'     => esc_html__( 'Expand Icon', 'rs-template-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => - 360,
						'max'  => 360,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item > .menu-item-link > .sub-menu-icon' => '--icon-rotate: {{SIZE}}deg;',
				],
				'condition' => [
					'submen_icon_rotate_top_l_p' => 'yes',
					'direction'                  => 'horizontal'
				]
			]
		);

		$this->add_responsive_control(
			'submen_icon_rotate_2_top_l',
			[
				'label'     => esc_html__( 'Collapse Icon', 'rs-template-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => - 360,
						'max'  => 360,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item > .menu-item-link > .sub-menu-icon' => '--icon-rotate-2: {{SIZE}}deg;',
				],
				'condition' => [
					'submen_icon_rotate_top_l_p' => 'yes',
					'direction'                  => 'horizontal'
				]
			]
		);

		$this->end_popover();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'submenu_icon_border_top_l',
				'label'     => esc_html__( 'Border', 'rs-template-builder' ),
				'selector'  => '{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item > .menu-item-link > .sub-menu-icon',
				'condition' => [
					'direction' => 'horizontal'
				],
				'exclude'   => [ 'color' ]
			]
		);

		$this->add_responsive_control(
			'submenu_icon_border_radius_top_l',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item > .menu-item-link > .sub-menu-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'direction' => 'horizontal'
				]
			]
		);

		$this->start_controls_tabs( 'submenu_icon_tab_top_l', [
			'condition' => [
				'direction' => 'horizontal'
			]
		] );

		$this->start_controls_tab(
			'submenu_icon_tab_top_l_normal',
			[
				'label' => esc_html__( 'Normal', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'submen_icon_color_top_l',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item > .menu-item-link > .sub-menu-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submen_icon_bg_color_top_l',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item > .menu-item-link > .sub-menu-icon' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submen_icon_b_color_top_l',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item > .menu-item-link > .sub-menu-icon' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'submenu_icon_tab_top_l_hover',
			[
				'label' => esc_html__( 'Hover', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'submen_icon_color_top_l_h',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item > .menu-item-link:hover > .sub-menu-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submen_icon_bg_color_top_l_h',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item > .menu-item-link:hover > .sub-menu-icon' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submen_icon_b_color_top_l_h',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .menu-item > .menu-item-link:hover > .sub-menu-icon' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'submenu_icon_tab_top_l_active',
			[
				'label' => esc_html__( 'Active', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'submen_icon_color_top_l_a',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .current-menu-parent > .menu-item-link > .sub-menu-icon,
					{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .current-menu-item > .menu-item-link > .sub-menu-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submen_icon_bg_color_top_l_a',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .current-menu-parent > .menu-item-link > .sub-menu-icon,
					{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .current-menu-item > .menu-item-link > .sub-menu-icon' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submen_icon_b_color_top_l_a',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .current-menu-parent > .menu-item-link > .sub-menu-icon,
					{{WRAPPER}} > .rstb-nav-menu > .primary-menu > .current-menu-item > .menu-item-link > .sub-menu-icon' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'submenu_icon_dropdown_level_h',
			[
				'label'     => esc_html__( 'Dropdown Level', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'direction' => 'horizontal'
				]
			]
		);

		$this->add_control(
			'submenu_icon_area_size',
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
			'submenu_icon_width',
			[
				'label'      => esc_html__( 'Width', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .nav-horizontal > .primary-menu .sub-menu .sub-menu-icon, {{WRAPPER}} > .nav-vertical > .primary-menu .sub-menu-icon' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'submenu_icon_area_size' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'submenu_icon_height',
			[
				'label'      => esc_html__( 'Height', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .nav-horizontal > .primary-menu .sub-menu .sub-menu-icon, {{WRAPPER}} > .nav-vertical > .primary-menu .sub-menu-icon' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'submenu_icon_area_size' => 'yes'
				]
			]
		);

		$this->end_popover();

		$this->add_responsive_control(
			'submenu_icon_font_size',
			[
				'label'      => esc_html__( 'Icon Size', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .nav-horizontal > .primary-menu .sub-menu .sub-menu-icon, {{WRAPPER}} > .nav-vertical > .primary-menu .sub-menu-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'submen_icon_rotate_p',
			[
				'label'        => esc_html__( 'Icon Rotate', 'rs-template-builder' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => esc_html__( 'None', 'rs-template-builder' ),
				'label_on'     => esc_html__( 'Custom', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'submen_icon_rotate_1',
			[
				'label'     => esc_html__( 'Expand Icon', 'rs-template-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => - 360,
						'max'  => 360,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} > .nav-horizontal > .primary-menu .sub-menu-icon, 
					{{WRAPPER}} > .nav-vertical > .primary-menu .sub-menu-icon' => '--icon-rotate: {{SIZE}}deg;',
				],
				'condition' => [
					'submen_icon_rotate_p' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'submen_icon_rotate_3',
			[
				'label'     => esc_html__( 'Collapse Icon', 'rs-template-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => - 360,
						'max'  => 360,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} > .nav-horizontal > .primary-menu .sub-menu-icon, 
					{{WRAPPER}} > .nav-vertical > .primary-menu .sub-menu-icon' => '--icon-rotate-2: {{SIZE}}deg;',
				],
				'condition' => [
					'submen_icon_rotate_p' => 'yes',
				]
			]
		);

		$this->end_popover();

		$this->add_control(
			'submenu_icon_position',
			[
				'label'        => esc_html__( 'Position (Vertical)', 'rs-template-builder' ),
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
			'submenu_icon_p_l',
			[
				'label'      => esc_html__( 'Left', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .nav-horizontal > .primary-menu .sub-menu .sub-menu-icon, {{WRAPPER}} > .nav-vertical > .primary-menu .sub-menu-icon' => 'left: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'submenu_icon_position' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'submenu_icon_p_r',
			[
				'label'      => esc_html__( 'Right', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .nav-horizontal > .primary-menu .sub-menu .sub-menu-icon, {{WRAPPER}} > .nav-vertical > .primary-menu .sub-menu-icon' => 'right: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'submenu_icon_position' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'submenu_icon_p_t',
			[
				'label'      => esc_html__( 'Top', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .nav-horizontal > .primary-menu .sub-menu .sub-menu-icon, {{WRAPPER}} > .nav-vertical > .primary-menu .sub-menu-icon' => 'top: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'submenu_icon_position' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'submenu_icon_p_b',
			[
				'label'      => esc_html__( 'Bottom', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .nav-horizontal > .primary-menu .sub-menu .sub-menu-icon, {{WRAPPER}} > .nav-vertical > .primary-menu .sub-menu-icon' => 'bottom: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'submenu_icon_position' => 'yes'
				]
			]
		);

		$this->end_popover();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'submenu_icon_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} > .nav-horizontal > .primary-menu .sub-menu .sub-menu-icon, {{WRAPPER}} > .nav-vertical > .primary-menu .sub-menu-icon',
				'exclude'  => [ 'color' ]
			]
		);

		$this->add_responsive_control(
			'submenu_icon_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} > .nav-horizontal > .primary-menu .sub-menu .sub-menu-icon, {{WRAPPER}} > .nav-vertical > .primary-menu .sub-menu-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'submenu_icon_style_tab' );

		$this->start_controls_tab(
			'submenu_icon_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'submen_icon_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .nav-horizontal > .primary-menu .sub-menu .sub-menu-icon, {{WRAPPER}} > .nav-vertical > .primary-menu .sub-menu-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submen_icon_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .nav-horizontal > .primary-menu .sub-menu .sub-menu-icon, {{WRAPPER}} > .nav-vertical > .primary-menu .sub-menu-icon' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submen_icon_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .nav-horizontal > .primary-menu .sub-menu .sub-menu-icon, {{WRAPPER}} > .nav-vertical > .primary-menu .sub-menu-icon' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'submenu_icon_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'submen_icon_color_h',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .nav-horizontal > .primary-menu .sub-menu .menu-item-link:hover .sub-menu-icon, 
					{{WRAPPER}} > .nav-vertical > .primary-menu .sub-menu-icon:hover' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'submen_icon_bg_color_h',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .nav-horizontal > .primary-menu .sub-menu .menu-item-link:hover .sub-menu-icon, 
					{{WRAPPER}} > .nav-vertical > .primary-menu .sub-menu-icon:hover' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'submen_icon_border_color_h',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .nav-horizontal > .primary-menu .sub-menu .menu-item-link:hover .sub-menu-icon, 
					{{WRAPPER}} > .nav-vertical > .primary-menu .sub-menu-icon:hover' => 'border-color: {{VALUE}} !important;',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'submenu_icon_active_tab',
			[
				'label' => esc_html__( 'Active', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'submen_icon_color_a',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .nav-horizontal > .primary-menu .sub-menu .current-menu-parent .sub-menu-icon,
					{{WRAPPER}} > .nav-horizontal > .primary-menu .sub-menu .current-menu-item .sub-menu-icon, 
					{{WRAPPER}} > .nav-vertical > .primary-menu .current-menu-parent .sub-menu-icon, 
					{{WRAPPER}} > .nav-vertical > .primary-menu .current-menu-item .sub-menu-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submen_icon_bg_color_a',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .nav-horizontal > .primary-menu .sub-menu .current-menu-parent .sub-menu-icon,
					{{WRAPPER}} > .nav-horizontal > .primary-menu .sub-menu .current-menu-item .sub-menu-icon, 
					{{WRAPPER}} > .nav-vertical > .primary-menu .current-menu-parent .sub-menu-icon, 
					{{WRAPPER}} > .nav-vertical > .primary-menu .current-menu-item .sub-menu-icon' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submen_icon_border_color_a',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} > .nav-horizontal > .primary-menu .sub-menu .current-menu-parent .sub-menu-icon,
					{{WRAPPER}} > .nav-horizontal > .primary-menu .sub-menu .current-menu-item .sub-menu-icon, 
					{{WRAPPER}} > .nav-vertical > .primary-menu .current-menu-parent .sub-menu-icon, 
					{{WRAPPER}} > .nav-vertical > .primary-menu .current-menu-item .sub-menu-icon' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_panel_style',
			[
				'label' => esc_html__( 'Mobile Panel', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'panel_width',
			[
				'label'      => esc_html__( 'Max Width', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-panel-content' => 'max-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'panel_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-panel-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'panel_bg_color',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .rstb-nav-menu .mobile-panel-content',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'panel_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .rstb-nav-menu .mobile-panel-content',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'panel_box_shadow',
				'selector' => '{{WRAPPER}} .rstb-nav-menu .mobile-panel-content',
			]
		);

		$this->add_control(
			'panel_overly_color',
			[
				'label'     => esc_html__( 'Overly Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-panel-overly' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'mobile_menu_style' => 'offcanvas'
				]
			]
		);

		$this->add_responsive_control(
			'mobile_divider_width',
			[
				'label'     => esc_html__( 'Vertical Divider Width', 'rs-template-builder' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu.has-vertical-divider .menu-item, {{WRAPPER}} .rstb-nav-menu .mobile-menu.has-vertical-divider .menu-item:first-child' => 'border-width: {{VALUE}}px',
				],
				'separator' => 'before',
				'condition' => [
					'mobile_menu_divider' => 'has-vertical-divider'
				]
			]
		);

		$this->add_control(
			'mobile_divider_color',
			[
				'label'     => esc_html__( 'Vertical Divider Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu.has-vertical-divider .menu-item, {{WRAPPER}} .rstb-nav-menu .mobile-menu.has-vertical-divider .menu-item:first-child' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'mobile_menu_divider' => 'has-vertical-divider'
				]
			]
		);

		$this->add_control(
			'panel_menu_link',
			[
				'label'     => esc_html__( 'Menu Link', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'panel_menu_link_margin',
			[
				'label'      => esc_html__( 'Margin', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .menu-item-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'panel_menu_link_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .menu-item-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'panel_menu_link_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .rstb-nav-menu .mobile-menu .menu-item-link',
			]
		);

		$this->add_responsive_control(
			'panel_menu_link_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .menu-item-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'panel_submenu_indent',
			[
				'label'      => esc_html__( 'Submenu Indent', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .sub-menu' => 'padding-left: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'panel_link_typography',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .rstb-nav-menu .mobile-menu .menu-item-link',
			]
		);

		$this->start_controls_tabs( 'panel_menu_link_style_tabs' );

		$this->start_controls_tab(
			'panel_menu_link_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'panel_link_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .menu-item-link' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'panel_link_bg',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .menu-item-link' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'panel_menu_link_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'panel_link_hover_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .menu-item-link:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'panel_link_hover_bg',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .menu-item-link:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'panel_link_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .menu-item-link:hover' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'panel_menu_link_border_border!' => ''
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'panel_menu_link_active_tab',
			[
				'label' => esc_html__( 'Active', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'panel_link_active_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .current-menu-parent > .menu-item-link, {{WRAPPER}} .rstb-nav-menu .mobile-menu > .current-menu-item > .menu-item-link' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'panel_link_active_bg',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .current-menu-parent > .menu-item-link, {{WRAPPER}} .rstb-nav-menu .mobile-menu > .current-menu-item > .menu-item-link' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'panel_link_active_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .current-menu-parent > .menu-item-link, {{WRAPPER}} .rstb-nav-menu .mobile-menu > .current-menu-item > .menu-item-link' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'panel_menu_link_border_border!' => ''
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'mobile_submenu_icon_heading',
			[
				'label'     => esc_html__( 'Submenu Icon', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'mobile_submenu_icon_area_size',
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
			'mobile_submenu_icon_width',
			[
				'label'      => esc_html__( 'Width', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER} .rstb-nav-menu .mobile-menu .sub-menu-icon' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'mobile_submenu_icon_area_size' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'mobile_submenu_icon_height',
			[
				'label'      => esc_html__( 'Height', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .sub-menu-icon' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'mobile_submenu_icon_area_size' => 'yes'
				]
			]
		);

		$this->end_popover();

		$this->add_responsive_control(
			'mobile_submenu_icon_font_size',
			[
				'label'      => esc_html__( 'Icon Size', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .sub-menu-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'mobile_submenu_icon_rotate',
			[
				'label'        => esc_html__( 'Icon Rotate', 'rs-template-builder' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => esc_html__( 'None', 'rs-template-builder' ),
				'label_on'     => esc_html__( 'Custom', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'mobile_submen_icon_rotate_1',
			[
				'label'     => esc_html__( 'Expand Icon', 'rs-template-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => - 360,
						'max'  => 360,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .sub-menu-icon' => '--icon-rotate: {{SIZE}}deg;',
				],
				'condition' => [
					'mobile_submenu_icon_rotate' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'mobile_submen_icon_rotate_3',
			[
				'label'     => esc_html__( 'Collapse Icon', 'rs-template-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => - 360,
						'max'  => 360,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .sub-menu-icon' => '--icon-rotate-2: {{SIZE}}deg;',
				],
				'condition' => [
					'mobile_submenu_icon_rotate' => 'yes'
				]
			]
		);

		$this->end_popover();

		$this->add_control(
			'mobile_submenu_icon_position',
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

		$this->add_responsive_control(
			'mobile_submenu_icon_p_l',
			[
				'label'      => esc_html__( 'Left', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .sub-menu-icon' => 'left: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'mobile_submenu_icon_position' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'mobile_submenu_icon_p_r',
			[
				'label'      => esc_html__( 'Right', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .sub-menu-icon' => 'right: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'mobile_submenu_icon_position' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'mobile_submenu_icon_p_t',
			[
				'label'      => esc_html__( 'Top', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .sub-menu-icon' => 'top: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'mobile_submenu_icon_position' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'mobile_submenu_icon_p_b',
			[
				'label'      => esc_html__( 'Bottom', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .sub-menu-icon' => 'bottom: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'mobile_submenu_icon_position' => 'yes'
				]
			]
		);

		$this->end_popover();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'mobile_submenu_icon_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .rstb-nav-menu .mobile-menu .sub-menu-icon',
				'exclude'  => [ 'color' ]
			]
		);

		$this->add_responsive_control(
			'mobile_submenu_icon_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .sub-menu-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'mobile_submenu_icon_style' );

		$this->start_controls_tab(
			'mobile_submenu_icon_normal',
			[
				'label' => esc_html__( 'Normal', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'mobile_submen_icon_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .sub-menu-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'mobile_submen_icon_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .sub-menu-icon' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'mobile_submen_icon_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .sub-menu-icon' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'mobile_submenu_icon_hover',
			[
				'label' => esc_html__( 'Hover', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'mobile_submen_icon_color_h',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .sub-menu-icon:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'mobile_submen_icon_bg_color_h',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .sub-menu-icon:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'mobile_submen_icon_border_color_h',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .sub-menu-icon:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'mobile_submenu_icon_active',
			[
				'label' => esc_html__( 'Active', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'mobile_submen_icon_color_a',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .current-menu-parent .sub-menu-icon, 
					{{WRAPPER}} .rstb-nav-menu .mobile-menu .current-menu-item .sub-menu-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'mobile_submen_icon_bg_color_a',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .current-menu-parent .sub-menu-icon, 
					{{WRAPPER}} .rstb-nav-menu .mobile-menu .current-menu-item .sub-menu-icon' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'mobile_submen_icon_border_color_a',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-menu .current-menu-parent .sub-menu-icon, 
					{{WRAPPER}} .rstb-nav-menu .mobile-menu .current-menu-item .sub-menu-icon' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'panel_toggle_close_style',
			[
				'label' => esc_html__( 'Toggle/Close Icon', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'toggle_style_heading',
			[
				'label' => esc_html__( 'Toggle', 'rs-template-builder' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'toggle_font_size',
			[
				'label'      => esc_html__( 'Font Size', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-nav-menu .menu-toggler' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'toggle_icon_source' => 'custom'
				]
			]
		);

		$this->add_responsive_control(
			'toggle_width',
			[
				'label'      => esc_html__( 'Width', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-nav-menu .menu-toggler' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'toggle_height',
			[
				'label'      => esc_html__( 'Height', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-nav-menu .menu-toggler' => 'height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'toggle_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .rstb-nav-menu .menu-toggler',
			]
		);

		$this->add_responsive_control(
			'toggle_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-nav-menu .menu-toggler' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .rstb-nav-menu .menu-toggler' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-nav-menu .menu-toggler' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'toggle_box_shadow',
				'selector' => '{{WRAPPER}} .rstb-nav-menu .menu-toggler',
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
					'{{WRAPPER}} .rstb-nav-menu .menu-toggler:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_hover_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-nav-menu .menu-toggler:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'toggle_hover_box_shadow',
				'selector' => '{{WRAPPER}} .rstb-nav-menu .menu-toggler:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'close_icon_style_heading',
			[
				'label'     => esc_html__( 'Close(Offcanvas)', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'close_icon_size',
			[
				'label'      => esc_html__( 'Font Size', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-panel-close' => 'font-size: {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}} .rstb-nav-menu .mobile-panel-close' => 'width: {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}} .rstb-nav-menu .mobile-panel-close' => 'height: {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}} .rstb-nav-menu .mobile-panel-close' => 'left: {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}} .rstb-nav-menu .mobile-panel-close' => 'right: {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}} .rstb-nav-menu .mobile-panel-close' => 'top: {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}} .rstb-nav-menu .mobile-panel-close' => 'bottom: {{SIZE}}{{UNIT}}',
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
				'selector' => '{{WRAPPER}} .rstb-nav-menu .mobile-panel-close',
			]
		);

		$this->add_responsive_control(
			'close_icon_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-panel-close' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'close_icon_style_tab' );

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
					'{{WRAPPER}} .rstb-nav-menu .mobile-panel-close' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'close_icon_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-panel-close' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'close_icon_box_shadow',
				'selector' => '{{WRAPPER}} .rstb-nav-menu .mobile-panel-close',
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
			'close_icon_color_hover',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-panel-close:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'close_icon_bg_color_hover',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-panel-close:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'close_icon_box_shadow_hover',
				'selector' => '{{WRAPPER}} .rstb-nav-menu .mobile-panel-close:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_mobile_logo_s',
			[
				'label'     => esc_html__( 'Mobile Logo', 'rs-template-builder' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'mobile_menu_style' => 'offcanvas',
					'show_mobile_logo'  => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'mobile_logo_margin',
			[
				'label'      => esc_html__( 'Margin', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-panel-logo' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'mobile_logo_width',
			[
				'label'      => esc_html__( 'Width', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px', 'vw' ],
				'range'      => [
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-panel-logo img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'mobile_logo_height',
			[
				'label'      => esc_html__( 'Height', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vw', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-panel-logo img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'mobile_logo_max_width',
			[
				'label'      => esc_html__( 'Max Width', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px', 'vw', 'custom' ],
				'range'      => [
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-panel-logo img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'mobile_logo_object_fit',
			[
				'label'     => esc_html__( 'Object Fit', 'rs-template-builder' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''        => esc_html__( 'Default', 'rs-template-builder' ),
					'fill'    => esc_html__( 'Fill', 'rs-template-builder' ),
					'cover'   => esc_html__( 'Cover', 'rs-template-builder' ),
					'contain' => esc_html__( 'Contain', 'rs-template-builder' ),
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-panel-logo img' => 'object-fit: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'mobile_logo_object_position',
			[
				'label'     => esc_html__( 'Object Position', 'rs-template-builder' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''       => esc_html__( 'Default', 'rs-template-builder' ),
					'top'    => esc_html__( 'Top', 'rs-template-builder' ),
					'bottom' => esc_html__( 'Bottom', 'rs-template-builder' ),
					'left'   => esc_html__( 'left', 'rs-template-builder' ),
					'right'  => esc_html__( 'Right', 'rs-template-builder' ),
					'center' => esc_html__( 'Center', 'rs-template-builder' ),
				],
				'selectors' => [
					'{{WRAPPER}} .rstb-nav-menu .mobile-panel-logo img' => 'object-position: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render(): void {
		$settings = $this->get_settings_for_display();
		$this->add_render_attribute( 'wrapper', 'class', [
			'rstb-nav-menu',
			'nav-' . $settings[ 'direction' ],
			'nav-' . $settings[ 'breakpoint' ],
		] );
		$this->add_render_attribute( 'wrapper', 'class', $settings[ 'show_vertical_divider' ] );
		?>
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php
			$menu_args = [
				'container'   => false,
				'menu_class'  => 'primary-menu',
				'fallback_cb' => false,
				'depth'       => 4,
				'walker'      => new Nav_Walker()
			];

			if ( 'custom' === $settings[ 'submenu_icon_type' ] && ! empty( $settings[ 'submenu_icon' ][ 'value' ] ) ) {
				$menu_args[ 'submenu_icon' ]     = Icons_Manager::try_get_icon_html( $settings[ 'submenu_icon' ] );
				$menu_args[ 'submenu_icon_two' ] = Icons_Manager::try_get_icon_html( $settings[ 'submenu_icon_two' ] );
			}

			if ( 'yes' === $settings[ 'show_menu_prefix_icon' ] && ! empty( $settings[ 'menu_prefix_icon' ][ 'value' ] ) ) {
				$menu_args[ 'link_before' ] = '<span class="menu-prefix-icon">' . Icons_Manager::try_get_icon_html( $settings[ 'menu_prefix_icon' ] ) . '</span>';
			}

			if ( ! empty( $settings[ 'selected_menu' ] ) && wp_get_nav_menu_object( $settings[ 'selected_menu' ] ) ) {
				$menu_args[ 'menu' ] = $settings[ 'selected_menu' ];

				wp_nav_menu( $menu_args );
			}
			?>
			<?php if ( 'breakpoint-none' !== $settings[ 'breakpoint' ] ) {
				$this->render_toggle_icon();
				$this->render_mobile_menu();
			} ?>
        </div>
		<?php
	}

	protected function render_mobile_menu(): void {
		$settings    = $this->get_settings_for_display();
		$panel_class = 'mobile-panel-wrapper panel-' . $settings[ 'mobile_menu_style' ];
		if ( 'offcanvas' === $settings[ 'mobile_menu_style' ] ) {
			$panel_class .= ' position-' . $settings[ 'offcanvas_position' ];
		}
		?>
        <div class="<?php echo esc_attr( $panel_class ) ?>">
			<?php if ( 'offcanvas' === $settings[ 'mobile_menu_style' ] ) {
				echo '<div class="mobile-panel-overly"></div>';
			} ?>
            <div class="mobile-panel-content">
				<?php
				if ( 'offcanvas' === $settings[ 'mobile_menu_style' ] ) {
					$this->render_close_icon();

					if ( 'yes' === $settings[ 'show_mobile_logo' ] && ! empty( $settings[ 'mobile_logo' ][ 'url' ] ) ) {
						echo '<div class="mobile-panel-logo">' . wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'full', 'mobile_logo' ) ) . '</div>';
					}
				}

				$panel_args = [
					'container'   => false,
					'menu_class'  => 'mobile-menu ' . $settings[ 'mobile_menu_divider' ],
					'fallback_cb' => false,
					'depth'       => 4,
					'walker'      => new Nav_Walker()
				];

				if ( 'custom' === $settings[ 'mobile_submenu_icon_type' ] && ! empty( $settings[ 'mobile_submenu_icon' ][ 'value' ] ) ) {
					$panel_args[ 'submenu_icon' ]     = Icons_Manager::try_get_icon_html( $settings[ 'mobile_submenu_icon' ] );
					$panel_args[ 'submenu_icon_two' ] = Icons_Manager::try_get_icon_html( $settings[ 'mobile_submenu_icon_two' ] );
				}

				if ( ! empty( $settings[ 'mobile_selected_menu' ] ) && wp_get_nav_menu_object( $settings[ 'mobile_selected_menu' ] ) ) {
					$panel_args[ 'menu' ] = $settings[ 'mobile_selected_menu' ];

					wp_nav_menu( $panel_args );
				}
				?>
            </div>
        </div>
		<?php
	}

	protected function render_toggle_icon(): void {
		$settings = $this->get_settings_for_display();
		?>
        <div class="menu-toggler-wrap">
            <button class="menu-toggler">
                <span class="open-icon">
                    <?php if ( 'default' === $settings[ 'toggle_icon_source' ] ) : ?>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3 4H21V6H3V4ZM3 11H21V13H3V11ZM3 18H21V20H3V18Z"></path></svg>
                    <?php else : ?>
	                    <?php Icons_Manager::render_icon( $settings[ 'toggle_custom_icon' ] ); ?>
                    <?php endif; ?>
                </span>
                <span class="close-icon">
                    <?php if ( 'default' === $settings[ 'close_icon_source' ] ) : ?>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M11.9997 10.5865L16.9495 5.63672L18.3637 7.05093L13.4139 12.0007L18.3637 16.9504L16.9495 18.3646L11.9997 13.4149L7.04996 18.3646L5.63574 16.9504L10.5855 12.0007L5.63574 7.05093L7.04996 5.63672L11.9997 10.5865Z"></path>
                        </svg>
                    <?php else : ?>
	                    <?php Icons_Manager::render_icon( $settings[ 'close_custom_icon' ] ); ?>
                    <?php endif; ?>
                </span>
            </button>
        </div>
		<?php
	}

	protected function render_close_icon(): void {
		$settings = $this->get_settings_for_display();
		?>
        <button class="mobile-panel-close">
			<?php if ( 'default' === $settings[ 'close_icon_source' ] ) : ?>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M11.9997 10.5865L16.9495 5.63672L18.3637 7.05093L13.4139 12.0007L18.3637 16.9504L16.9495 18.3646L11.9997 13.4149L7.04996 18.3646L5.63574 16.9504L10.5855 12.0007L5.63574 7.05093L7.04996 5.63672L11.9997 10.5865Z"></path>
                </svg>
			<?php else : ?>
				<?php Icons_Manager::render_icon( $settings[ 'close_custom_icon' ] ); ?>
			<?php endif; ?>
        </button>
		<?php
	}

	private function get_menus_list(): array {
		$nav_menus = [];
		$terms     = get_terms( 'nav_menu' );

		foreach ( $terms as $term ) {
			$nav_menus[ $term->name ] = $term->name;
		}

		return [ '' => __( '-- Select Menu --', 'rs-template-builder' ) ] + $nav_menus;
	}
}