<?php

namespace RsTemplateBuilder\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Widget_Base;

class Post_Navigation extends Widget_Base {

	public function get_name(): string {
		return 'rstb-post-navigation';
	}

	public function get_title(): string {
		return esc_html__( 'Post Navigation', 'rs-template-builder' );
	}

	public function get_icon(): string {
		return 'eicon-post-navigation rstb-branding-icon';
	}

	public function get_categories(): array {
		return [ 'rstb_elements' ];
	}

	public function get_keywords(): array {
		return [ 'rs template builder', 'rstheme', 'excerpt', 'post', 'rs-template-builder' ];
	}

	protected function is_dynamic_content(): bool {
		return true;
	}

	public function has_widget_inner_wrapper(): bool {
		return ! Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls(): void {
		$this->start_controls_section(
			'section_post_navigation',
			[
				'label' => __( 'Post Navigation', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_title',
			[
				'label'        => esc_html__( 'Post Title', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'Hide', 'rs-template-builder' ),
				'default'      => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'custom_label',
			[
				'label'        => esc_html__( 'Custom Label', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'Hide', 'rs-template-builder' ),
				'default'      => '',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'prev_label',
			[
				'label'     => esc_html__( 'Previous Label', 'rs-template-builder' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Previous', 'rs-template-builder' ),
				'condition' => [
					'custom_label' => 'yes',
				],
				'ai'        => false
			]
		);

		$this->add_control(
			'next_label',
			[
				'label'     => esc_html__( 'Next Label', 'rs-template-builder' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Next', 'rs-template-builder' ),
				'condition' => [
					'custom_label' => 'yes',
				],
				'ai'        => false
			]
		);

		$this->add_control(
			'show_date',
			[
				'label'        => esc_html__( 'Post Date', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'Hide', 'rs-template-builder' ),
				'default'      => 'yes',
				'return_value' => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'show_thumbnail',
			[
				'label'        => esc_html__( 'Post Thumbnail', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'Hide', 'rs-template-builder' ),
				'default'      => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'show_icons',
			[
				'label'        => esc_html__( 'Show Icon', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'No', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'prev_icon',
			[
				'label'       => esc_html__( 'Prev Icon', 'rs-template-builder' ),
				'label_block' => false,
				'type'        => Controls_Manager::ICONS,
				'default'     => [
					'value'   => 'fas fa-chevron-left',
					'library' => 'fa-solid',
				],
				'skin'        => 'inline',
				'condition'   => [
					'show_icons' => 'yes',
				]
			]
		);

		$this->add_control(
			'next_icon',
			[
				'label'       => esc_html__( 'Next Icon', 'rs-template-builder' ),
				'label_block' => false,
				'type'        => Controls_Manager::ICONS,
				'default'     => [
					'value'   => 'fas fa-chevron-right',
					'library' => 'fa-solid',
				],
				'skin'        => 'inline',
				'condition'   => [
					'show_icons' => 'yes',
				]
			]
		);

		$this->add_control(
			'show_divider',
			[
				'label'        => esc_html__( 'Show Divider', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'No', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
			]
		);

		// Filter out post type without taxonomies
		$post_type_options    = [];
		$post_type_taxonomies = [];
		foreach ( $this->get_public_post_types() as $post_type => $post_type_label ) {
			$taxonomies = $this->get_taxonomies( [ 'object_type' => $post_type ], false );
			if ( empty( $taxonomies ) ) {
				continue;
			}

			$post_type_options[ $post_type ]    = $post_type_label;
			$post_type_taxonomies[ $post_type ] = [];
			foreach ( $taxonomies as $taxonomy ) {
				$post_type_taxonomies[ $post_type ][ $taxonomy->name ] = $taxonomy->label;
			}
		}

		$this->add_control(
			'in_same_term',
			[
				'label'       => esc_html__( 'In same Term', 'rs-template-builder' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => $post_type_options,
				'default'     => '',
				'multiple'    => true,
				'label_block' => true,
				'separator'   => 'before',
				'description' => esc_html__( 'Indicates whether next post must be within the same taxonomy term as the current post, this lets you set a taxonomy per each post type', 'rs-template-builder' ),
			]
		);

		foreach ( $post_type_options as $post_type => $post_type_label ) {
			$this->add_control(
				$post_type . '_taxonomy',
				[
					'label'     => $post_type_label . ' ' . esc_html__( 'Taxonomy', 'rs-template-builder' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => $post_type_taxonomies[ $post_type ],
					'default'   => '',
					'condition' => [
						'in_same_term' => $post_type,
					],
				]
			);
		}

		$this->end_controls_section();

		$this->start_controls_section(
			'section_nav_area_style',
			[
				'label' => esc_html__( 'Nav Area', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'nav_area_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-post-navigation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'nav_area_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .rstb-post-navigation',
			]
		);

		$this->add_responsive_control(
			'nav_area_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-post-navigation' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'nav_area_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .rstb-post-navigation',
				'exclude'  => [ 'image' ]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'nav_area_shadow',
				'selector' => '{{WRAPPER}} .rstb-post-navigation',
			]
		);

		$this->add_responsive_control(
			'nav_items_gap',
			[
				'label'      => esc_html__( 'Item Gaps', 'rs-template-builder' ),
				'type'       => Controls_Manager::GAPS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-post-navigation' => 'row-gap: {{ROW}}{{UNIT}}; column-gap: {{COLUMN}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'divider_heading',
			[
				'label'     => esc_html__( 'Divider', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_divider' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'divider_size_x',
			[
				'label'      => esc_html__( 'Size X', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-post-navigation' => '--divider-size-x: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'divider_size_y',
			[
				'label'      => esc_html__( 'Size Y', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-post-navigation' => '--divider-size-y: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-nav-divider' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_item_style',
			[
				'label' => esc_html__( 'Post Item', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-nav-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_content_gap',
			[
				'label'      => esc_html__( 'Content Gaps', 'rs-template-builder' ),
				'type'       => Controls_Manager::GAPS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-nav-item' => 'row-gap: {{ROW}}{{UNIT}}; column-gap: {{COLUMN}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'item_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .post-nav-item',
			]
		);

		$this->add_responsive_control(
			'item_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-nav-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'item_style_tabs' );

		$this->start_controls_tab(
			'item_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'rs-template-builder' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'item_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .post-nav-item',
				'exclude'  => [ 'image' ]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'item_box_shadow',
				'selector' => '{{WRAPPER}} .post-nav-item',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'item_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'rs-template-builder' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'hover_item_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .post-nav-item:hover',
				'exclude'  => [ 'image' ]
			]
		);

		$this->add_control(
			'hover_item_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-nav-item:hover' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'item_border_border!' => ''
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'hover_item_box_shadow',
				'selector' => '{{WRAPPER}} .post-nav-item:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Content', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'date_heading',
			[
				'label' => esc_html__( 'Date', 'rs-template-builder' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'date_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-date' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hover_date_color',
			[
				'label'     => esc_html__( 'Color(Hover)', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-nav-item:hover .post-date' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'date_typography',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .post-date',
			]
		);

		$this->add_responsive_control(
			'date_space',
			[
				'label'      => esc_html__( 'Space Bottom', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-date' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'custom_label_heading',
			[
				'label' => esc_html__( 'Custom Label', 'rs-template-builder' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'custom_label_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-custom-label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hover_custom_label_color',
			[
				'label'     => esc_html__( 'Color(Hover)', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-nav-item:hover .post-custom-label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'custom_label_typography',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .post-custom-label',
			]
		);

		$this->add_responsive_control(
			'custom_label_space',
			[
				'label'      => esc_html__( 'Space Bottom', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-custom-label' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'post_title_heading',
			[
				'label' => esc_html__( 'Post Title', 'rs-template-builder' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'post_title_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hover_post_title_color',
			[
				'label'     => esc_html__( 'Color(Hover)', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-nav-item:hover .post-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'post_title_typography',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .post-title',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_thumb_style',
			[
				'label'     => esc_html__( 'Thumbnail', 'rs-template-builder' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_thumbnail' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'img_width',
			[
				'label'      => esc_html__( 'Width', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-thumb img' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'img_height',
			[
				'label'      => esc_html__( 'Height', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-thumb img' => 'height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'img_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .post-thumb img',
			]
		);

		$this->add_responsive_control(
			'img_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'img_style_tabs' );

		$this->start_controls_tab(
			'img_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'rs-template-builder' ),
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'img_box_shadow',
				'selector' => '{{WRAPPER}} .post-thumb img',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'img_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'img_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-nav-item:hover .post-thumb img' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'img_border_border!' => ''
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'hover_img_box_shadow',
				'selector' => '{{WRAPPER}} .post-nav-item:hover .post-thumb img',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon_style',
			[
				'label'     => esc_html__( 'Icon', 'rs-template-builder' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_icons' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'icon_width',
			[
				'label'      => esc_html__( 'Width', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-nav-icon' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'icon_height',
			[
				'label'      => esc_html__( 'Height', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-nav-icon' => 'height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'icon_font_size',
			[
				'label'      => esc_html__( 'Font Size', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-nav-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'icon_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .post-nav-icon',
			]
		);

		$this->add_responsive_control(
			'ico_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-nav-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'icon_style_tabs' );

		$this->start_controls_tab(
			'icon_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-nav-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'icon_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .post-nav-icon',
				'exclude'  => [ 'image' ]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'icon_box_shadow',
				'selector' => '{{WRAPPER}} .post-nav-icon',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'hover_icon_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-nav-item:hover .post-nav-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'hover_icon_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .post-nav-item:hover .post-nav-icon',
				'exclude'  => [ 'image' ]
			]
		);

		$this->add_control(
			'icon_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-nav-item:hover .post-nav-icon' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'icon_border_border!' => ''
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'hover_icon_box_shadow',
				'selector' => '{{WRAPPER}} .post-nav-item:hover .post-nav-icon',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render(): void {
		$settings = $this->get_settings_for_display();

		$in_same_term = false;
		$taxonomy     = 'category';
		$post_type    = get_post_type( get_queried_object_id() );

		if ( ! empty( $settings[ 'in_same_term' ] ) && is_array( $settings[ 'in_same_term' ] ) && in_array( $post_type, $settings[ 'in_same_term' ] ) ) {
			if ( isset( $settings[ $post_type . '_taxonomy' ] ) ) {
				$in_same_term = true;
				$taxonomy     = $settings[ $post_type . '_taxonomy' ];
			}
		}

		$prev = get_previous_post( $in_same_term, '', $taxonomy );
		$next = get_next_post( $in_same_term, '', $taxonomy );
		?>
        <div class="rstb-post-navigation">
            <div class="post-nav-item-wrap">
				<?php $this->render_nav_item( $prev, 'prev', $settings ); ?>
            </div>
			<?php
			if ( 'yes' === $settings[ 'show_divider' ] ) {
				echo '<span class="post-nav-divider"></span>';
			}
			?>
            <div class="post-nav-item-wrap">
				<?php $this->render_nav_item( $next, 'next', $settings ); ?>
            </div>
        </div>
		<?php
	}

	protected function render_nav_item( $post, $type, $settings ): void {
		if ( empty( $post ) ) {
			return;
		}
		$is_prev      = ( 'prev' === $type );
		$item_class   = $is_prev ? 'prev-post' : 'next-post';
		$icon_setting = $is_prev ? ( $settings[ 'prev_icon' ] ?? '' ) : ( $settings[ 'next_icon' ] ?? '' );
		$custom_label = $is_prev ? ( $settings[ 'prev_label' ] ?? '' ) : ( $settings[ 'next_label' ] ?? '' );
		$permalink    = get_permalink( $post->ID );
		?>
        <div class="post-nav-item <?php echo esc_attr( $item_class ); ?>">
			<?php if ( 'yes' === ( $settings[ 'show_icons' ] ?? '' ) && ! empty( $icon_setting ) ) : ?>
                <a href="<?php echo esc_url( $permalink ); ?>" class="post-nav-icon" aria-hidden="true">
					<?php Icons_Manager::render_icon( $icon_setting ); ?>
                </a>
			<?php endif; ?>

			<?php if ( 'yes' === ( $settings[ 'show_thumbnail' ] ?? '' ) && has_post_thumbnail( $post->ID ) ) : ?>
                <a href="<?php echo esc_url( $permalink ); ?>" class="post-thumb">
					<?php echo get_the_post_thumbnail( $post->ID, 'thumbnail' ); ?>
                </a>
			<?php endif; ?>

            <div class="post-content">
				<?php if ( 'yes' === ( $settings[ 'show_date' ] ?? '' ) ) : ?>
                    <a href="<?php echo esc_url( $permalink ); ?>" class="post-date">
						<?php echo esc_html( get_the_date( '', $post->ID ) ); ?>
                    </a>
				<?php endif; ?>

				<?php if ( 'yes' === ( $settings[ 'custom_label' ] ?? '' ) ) : ?>
                    <a href="<?php echo esc_url( $permalink ); ?>" class="post-custom-label">
						<?php echo wp_kses_post( $custom_label ); ?>
                    </a>
				<?php endif; ?>

				<?php if ( 'yes' === ( $settings[ 'show_title' ] ?? '' ) ) : ?>
                    <a href="<?php echo esc_url( $permalink ); ?>" class="post-title">
						<?php echo wp_kses_post( get_the_title( $post->ID ) ); ?>
                    </a>
				<?php endif; ?>
            </div>
        </div>
		<?php
	}

	public function get_public_post_types( $args = [] ) {
		$post_type_args = [
			// Default is the value $public.
			'show_in_nav_menus' => true,
		];

		// Keep for backwards compatibility
		if ( ! empty( $args[ 'post_type' ] ) ) {
			$post_type_args[ 'name' ] = $args[ 'post_type' ];
			unset( $args[ 'post_type' ] );
		}

		$post_type_args = wp_parse_args( $post_type_args, $args );

		$_post_types = get_post_types( $post_type_args, 'objects' );

		$post_types = [];

		foreach ( $_post_types as $post_type => $object ) {
			$post_types[ $post_type ] = $object->label;
		}

		return $post_types;
	}

	public function get_taxonomies( $args = [], $output = 'names', $operator = 'and' ) {
		global $wp_taxonomies;

		$field = ( 'names' === $output ) ? 'name' : false;

		if ( isset( $args[ 'object_type' ] ) ) {
			$object_type = (array) $args[ 'object_type' ];
			unset( $args[ 'object_type' ] );
		}

		$taxonomies = wp_filter_object_list( $wp_taxonomies, $args, $operator );

		if ( isset( $object_type ) ) {
			foreach ( $taxonomies as $tax => $tax_data ) {
				if ( ! array_intersect( $object_type, $tax_data->object_type ) ) {
					unset( $taxonomies[ $tax ] );
				}
			}
		}

		if ( $field ) {
			$taxonomies = wp_list_pluck( $taxonomies, $field );
		}

		return $taxonomies;
	}
}