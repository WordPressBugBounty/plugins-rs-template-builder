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
use Elementor\Repeater;
use Elementor\Widget_Base;

class Post_Meta extends Widget_Base {

	public function get_name(): string {
		return 'rstb-post-meta';
	}

	public function get_title(): string {
		return esc_html__( 'Post Meta', 'rs-template-builder' );
	}

	public function get_icon(): string {
		return 'eicon-meta-data rstb-branding-icon';
	}

	public function get_categories(): array {
		return [ 'rstb_elements' ];
	}

	public function get_keywords(): array {
		return [ 'rs template builder', 'rstheme', 'header', 'footer', 'meta', 'post', 'rs-template-builder' ];
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
		$repeater = new Repeater();

		$repeater->add_control(
			'type',
			[
				'label'   => esc_html__( 'Type', 'rs-template-builder' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'author'   => esc_html__( 'Author', 'rs-template-builder' ),
					'date'     => esc_html__( 'Date', 'rs-template-builder' ),
					'comments' => esc_html__( 'Comments', 'rs-template-builder' ),
					'category' => esc_html__( 'Category', 'rs-template-builder' ),
					'tags'     => esc_html__( 'Tags', 'rs-template-builder' ),
				],
			]
		);

		$repeater->add_control(
			'date_format',
			[
				'label'     => esc_html__( 'Date Format', 'rs-template-builder' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '0',
				'options'   => [
					'0'      => _x( 'June 1, 2024 (F j, Y)', 'Date Format', 'rs-template-builder' ),
					'1'      => '06/01/2024 (d/m/Y)',
					'2'      => '2024-01-06 (Y-m-d)',
					'3'      => '01/06/2024 (m/d/Y)',
					'custom' => esc_html__( 'Custom', 'rs-template-builder' ),
				],
				'condition' => [
					'type' => 'date',
				],
			]
		);

		$repeater->add_control(
			'custom_date_format',
			[
				'label'       => esc_html__( 'Custom Date Format', 'rs-template-builder' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'F j, Y',
				'condition'   => [
					'type'        => 'date',
					'date_format' => 'custom',
				],
				'description' => sprintf(
				/* translators: %s: Allowed data letters (see: http://php.net/manual/en/function.date.php). */
					esc_html__( 'Use the letters: %s', 'rs-template-builder' ),
					'l D d j S F m M n Y y'
				),
			]
		);

		$repeater->add_control(
			'text_prefix',
			[
				'label' => esc_html__( 'Before', 'rs-template-builder' ),
				'type'  => Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'show_avatar',
			[
				'label'     => esc_html__( 'Avatar', 'rs-template-builder' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'type' => 'author',
				],
			]
		);

		$repeater->add_responsive_control(
			'avatar_size',
			[
				'label'     => esc_html__( 'Size', 'rs-template-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .meta-icon' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'show_avatar' => 'yes',
					'type'        => 'author',
				],
			]
		);
		$repeater->add_responsive_control(
			'avatar_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .meta-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
				'condition'  => [
					'show_avatar' => 'yes',
					'type'        => 'author',
				],
			]
		);
		$repeater->add_control(
			'string_comments',
			[
				'label'       => esc_html__( 'Label', 'rs-template-builder' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Comments', 'rs-template-builder' ),
				'condition'   => [
					'type' => 'comments',
				],
			]
		);

		$repeater->add_control(
			'string_no_comments',
			[
				'label'       => esc_html__( 'No Comments', 'rs-template-builder' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'No Comments', 'rs-template-builder' ),
				'condition'   => [
					'type' => 'comments',
				],
			]
		);

		$repeater->add_control(
			'cat_tags_first_only',
			[
				'label'        => esc_html__( 'Show First Only', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'True', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'False', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					'type' => [ 'category', 'tags' ],
				],
			]
		);
		$repeater->add_control(
			'cat_tags_separator',
			[
				'label'       => esc_html__( 'Separator', 'rs-template-builder' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( ', ', 'rs-template-builder' ),
				'default'     => __( ', ', 'rs-template-builder' ),
				'label_block' => true,
				'condition'   => [
					'type' => [ 'category', 'tags' ],
				],
			]
		);

		$repeater->add_control(
			'link',
			[
				'label'   => esc_html__( 'Link', 'rs-template-builder' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$repeater->add_control(
			'selected_icon',
			[
				'label'       => esc_html__( 'Choose Icon', 'rs-template-builder' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'condition'   => [
					'show_avatar!' => 'yes',
				],
			]
		);

		$this->add_control(
			'meta_lists',
			[
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [],
				'title_field' => '<span style="text-transform: capitalize;">{{{ type }}}</span>',
			]
		);

		$this->end_controls_section();

		// Container Style Start
		$this->start_controls_section(
			'section_g_style',
			[
				'label' => esc_html__( 'Container Style', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'g_flex_v_align',
			[
				'label'     => esc_html__( 'Vertical Align', 'rs-template-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start' => [ 'title' => esc_html__( 'Top', 'rs-template-builder' ), 'icon' => 'eicon-align-start-v' ],
					'center'     => [ 'title' => esc_html__( 'Middle', 'rs-template-builder' ), 'icon' => 'eicon-align-center-v' ],
					'flex-end'   => [ 'title' => esc_html__( 'Bottom', 'rs-template-builder' ), 'icon' => 'eicon-align-end-v' ],
				],
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-meta' => 'align-items: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'g_flex_h_align',
			[
				'label'     => esc_html__( 'Horizontal Align', 'rs-template-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start'    => [ 'title' => esc_html__( 'Start', 'rs-template-builder' ), 'icon' => 'eicon-align-start-h' ],
					'center'        => [ 'title' => esc_html__( 'Center', 'rs-template-builder' ), 'icon' => 'eicon-align-center-h' ],
					'flex-end'      => [ 'title' => esc_html__( 'End', 'rs-template-builder' ), 'icon' => 'eicon-align-end-h' ],
					'space-between' => [ 'title' => esc_html__( 'Space Between', 'rs-template-builder' ), 'icon' => 'eicon-justify-space-between-h' ],
				],
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-meta' => 'justify-content: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'g_flex_dir',
			[
				'label'     => esc_html__( 'Column Direction', 'rs-template-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'row'            => [ 'title' => esc_html__( 'Row', 'rs-template-builder' ), 'icon' => 'eicon-justify-start-h' ],
					'row-reverse'    => [ 'title' => esc_html__( 'Row Reverse', 'rs-template-builder' ), 'icon' => 'eicon-wrap' ],
					'column'         => [ 'title' => esc_html__( 'Column', 'rs-template-builder' ), 'icon' => 'eicon-justify-start-v' ],
					'column-reverse' => [ 'title' => esc_html__( 'Column Reverse', 'rs-template-builder' ), 'icon' => 'eicon-wrap' ],
				],
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-meta' => 'flex-direction: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'g_flex_wrap',
			[
				'label'     => esc_html__( 'Flex Wrap', 'rs-template-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'nowrap' => [ 'title' => esc_html__( 'No Wrap', 'rs-template-builder' ), 'icon' => 'eicon-nowrap' ],
					'wrap'   => [ 'title' => esc_html__( 'Wrap', 'rs-template-builder' ), 'icon' => 'eicon-wrap' ],
				],
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-meta' => 'flex-wrap: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'g_flex_gap',
			[
				'label'      => esc_html__( 'Gap Between', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 1000 ],
					'%'  => [ 'min' => 0, 'max' => 100 ],
				],
				'selectors'  => [
					'{{WRAPPER}} .rstb-post-meta' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

		// Item Box Style Start
		$this->start_controls_section(
			'section_item_box_style',
			[
				'label' => esc_html__( 'Item Box', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'item_box_flex_v_align',
			[
				'label'     => esc_html__( 'Vertical Align', 'rs-template-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start' => [ 'title' => esc_html__( 'Top', 'rs-template-builder' ), 'icon' => 'eicon-align-start-v' ],
					'center'     => [ 'title' => esc_html__( 'Middle', 'rs-template-builder' ), 'icon' => 'eicon-align-center-v' ],
					'flex-end'   => [ 'title' => esc_html__( 'Bottom', 'rs-template-builder' ), 'icon' => 'eicon-align-end-v' ],
				],
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-meta .post-meta' => 'align-items: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'item_box_flex_h_align',
			[
				'label'     => esc_html__( 'Horizontal Align', 'rs-template-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start'    => [ 'title' => esc_html__( 'Start', 'rs-template-builder' ), 'icon' => 'eicon-align-start-h' ],
					'center'        => [ 'title' => esc_html__( 'Center', 'rs-template-builder' ), 'icon' => 'eicon-align-center-h' ],
					'flex-end'      => [ 'title' => esc_html__( 'End', 'rs-template-builder' ), 'icon' => 'eicon-align-end-h' ],
					'space-between' => [ 'title' => esc_html__( 'Space Between', 'rs-template-builder' ), 'icon' => 'eicon-justify-space-between-h' ],
				],
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-meta .post-meta' => 'justify-content: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'item_box_flex_dir',
			[
				'label'     => esc_html__( 'Column Direction', 'rs-template-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'row'            => [ 'title' => esc_html__( 'Row', 'rs-template-builder' ), 'icon' => 'eicon-justify-start-h' ],
					'row-reverse'    => [ 'title' => esc_html__( 'Row Reverse', 'rs-template-builder' ), 'icon' => 'eicon-wrap' ],
					'column'         => [ 'title' => esc_html__( 'Column', 'rs-template-builder' ), 'icon' => 'eicon-justify-start-v' ],
					'column-reverse' => [ 'title' => esc_html__( 'Column Reverse', 'rs-template-builder' ), 'icon' => 'eicon-wrap' ],
				],
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-meta .post-meta' => 'flex-direction: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'item_box_flex_wrap',
			[
				'label'     => esc_html__( 'Flex Wrap', 'rs-template-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'nowrap' => [ 'title' => esc_html__( 'No Wrap', 'rs-template-builder' ), 'icon' => 'eicon-nowrap' ],
					'wrap'   => [ 'title' => esc_html__( 'Wrap', 'rs-template-builder' ), 'icon' => 'eicon-wrap' ],
				],
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-meta .post-meta' => 'flex-wrap: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'item_box_flex_gap',
			[
				'label'      => esc_html__( 'Gap Between', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 1000 ],
					'%'  => [ 'min' => 0, 'max' => 100 ],
				],
				'selectors'  => [
					'{{WRAPPER}} .rstb-post-meta .post-meta' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'item_box_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-post-meta .post-meta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'item_box_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-post-meta .post-meta' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'item_box_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .rstb-post-meta .post-meta',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'item_box_border',
				'selector' => '{{WRAPPER}} .rstb-post-meta .post-meta',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'item_box_box_shadow',
				'selector' => '{{WRAPPER}} .rstb-post-meta .post-meta',
			]
		);
		$this->end_controls_section();

		// Text Style Start
		$this->start_controls_section(
			'section_text_icon_style',
			[
				'label' => esc_html__( 'Text & Icon Style', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'text_ctrl_heading',
			[
				'label' => esc_html__( 'Text Control', 'rs-template-builder' ),
				'type'  => Controls_Manager::HEADING,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} .rstb-post-meta .post-meta',
			]
		);
		$this->add_control(
			'text_color',
			[
				'label'     => esc_html__( 'Text Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-meta .post-meta' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'text_link_color',
			[
				'label'     => esc_html__( 'Link Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-meta .post-meta a,
						{{WRAPPER}} .rstb-post-meta a.post-meta' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'text_link_color_hover',
			[
				'label'     => esc_html__( 'Link Color (Hover)', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-meta .post-meta a:hover,
						{{WRAPPER}} .rstb-post-meta a.post-meta:hover' => 'color: {{VALUE}};',
				],
			]
		);

		// Icon
		$this->add_control(
			'icon_ctrl_heading',
			[
				'label'     => esc_html__( 'Icon Control', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'icon_size',
			[
				'label'      => esc_html__( 'Size', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rstb-post-meta .post-meta svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rstb-post-meta .post-meta i'   => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-meta .post-meta svg path' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .rstb-post-meta .post-meta i'        => 'color: {{VALUE}};',
				],
			]
		);

		// Prefix
		$this->add_control(
			'prefix_ctrl_heading',
			[
				'label'     => esc_html__( 'Prefix Control', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'prefix_typography',
				'selector' => '{{WRAPPER}} .rstb-post-meta .post-meta .meta-prefix',
			]
		);
		$this->add_control(
			'prefix_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-meta .post-meta .meta-prefix' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'prefix_margin',
			[
				'label'      => esc_html__( 'Margin', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-post-meta .post-meta .meta-prefix' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

		// Tags & Category Item Start
		$this->start_controls_section(
			'section_tags_category_style',
			[
				'label' => esc_html__( 'Tags & Category Item Style', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tag_cat_typography',
				'selector' => '{{WRAPPER}} .rstb-post-meta .post-meta .inner-pills-wrapper .inner-pill-item',
			]
		);
		$this->add_responsive_control(
			'tag_cat_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-post-meta .post-meta .inner-pills-wrapper .inner-pill-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'tag_cat_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-post-meta .post-meta .inner-pills-wrapper .inner-pill-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'tag_cat_margin',
			[
				'label'      => esc_html__( 'Margin', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-post-meta .post-meta .inner-pills-wrapper .inner-pill-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs( 'tag_cat_style_tabs' );
		$this->start_controls_tab(
			'tag_cat_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'rs-template-builder' ),
			]
		);
		$this->add_control(
			'tag_cat_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-meta .post-meta .inner-pills-wrapper .inner-pill-item' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'tag_cat_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .rstb-post-meta .post-meta .inner-pills-wrapper .inner-pill-item',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'tag_cat_border',
				'selector' => '{{WRAPPER}} .rstb-post-meta .post-meta .inner-pills-wrapper .inner-pill-item',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'tag_cat_box_shadow',
				'selector' => '{{WRAPPER}} .rstb-post-meta .post-meta .inner-pills-wrapper .inner-pill-item',
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tag_cat_style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'rs-template-builder' ),
			]
		);
		$this->add_control(
			'tag_cat_color_hover',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-meta .post-meta .inner-pills-wrapper .inner-pill-item:hover' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'tag_cat_background_hover',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .rstb-post-meta .post-meta .inner-pills-wrapper .inner-pill-item:hover',
			]
		);
		$this->add_control(
			'tag_cat_border_color_hover',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-meta .post-meta .inner-pills-wrapper .inner-pill-item:hover' => 'border-color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'tag_cat_box_shadow_hover',
				'selector' => '{{WRAPPER}} .rstb-post-meta .post-meta .inner-pills-wrapper .inner-pill-item:hover',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$meta_items = $settings[ 'meta_lists' ];

		if ( empty( $meta_items ) ) {
			return;
		}

		$post_id = get_the_ID();

		// $array = [ 'author', 'date', 'comments', 'category', 'tags', ];
		?>
        <div class="rstb-post-meta">
			<?php foreach ( $meta_items as $item ) {
				$this->render_item( $item );
			} ?>
        </div>
		<?php
	}

	public function render_item( $item ) {
		$item_data = [
			'text'   => '',
			'link'   => '',
			'avatar' => '',
			'prefix' => '',
		];
		switch ( $item[ 'type' ] ) {
			case 'author':
				$item_data = array_merge( $item_data, $this->get_post_author( $item ) );
				break;
			case 'date':
				$item_data = array_merge( $item_data, $this->get_post_date( $item ) );
				break;
			case 'comments':
				$item_data = array_merge( $item_data, $this->get_post_comment( $item ) );
				break;
			case 'category':
				$item_data = array_merge( $item_data, $this->get_post_category( $item ) );
				break;
			case 'tags':
				$item_data = array_merge( $item_data, $this->get_post_tags( $item ) );
				break;
			default :
				break;
		}

		$prefix = ! empty( $item_data[ 'prefix' ] ) ? '<span class="meta-prefix">' . esc_html( $item_data[ 'prefix' ] ) . '</span>' : '';
		if ( 'category' === $item[ 'type' ] || 'tags' === $item[ 'type' ] ) {
			if ( ! empty( $item_data[ 'text' ] ) ) {
				echo '<div class="post-meta post-meta-' . esc_attr( $item[ 'type' ] ) . ' elementor-repeater-item-' . esc_attr( $item[ '_id' ] ) . '">';
				if ( ! empty( $item[ 'selected_icon' ][ 'value' ] ) ) {
					echo '<span class="meta-icon">';
					Icons_Manager::render_icon( $item[ 'selected_icon' ], [ 'aria-hidden' => 'true' ] );
					echo '</span>';
				}
				echo wp_kses_post( $prefix ) . ' ' . '<span class="inner-pills-wrapper">' . wp_kses_post( $item_data[ 'text' ] ) . '</span>';
				echo '</div>';
			}
		} else {
			if ( ! empty( $item_data[ 'link' ] ) ) {
				echo '<a href="' . esc_url( $item_data[ 'link' ] ) . '" class="post-meta post-meta-' . esc_attr( $item[ 'type' ] ) . ' elementor-repeater-item-' . esc_attr( $item[ '_id' ] ) . '">';
			} else {
				echo '<span class="post-meta post-meta-' . esc_attr( $item[ 'type' ] ) . ' elementor-repeater-item-' . esc_attr( $item[ '_id' ] ) . '">';
			}
			if ( ( 'yes' !== $item[ 'show_avatar' ] ) && ! empty( $item[ 'selected_icon' ][ 'value' ] ) ) {
				echo '<span class="meta-icon">';
				Icons_Manager::render_icon( $item[ 'selected_icon' ], [ 'aria-hidden' => 'true' ] );
				echo '</span>';
			}
			if ( ( 'yes' === $item[ 'show_avatar' ] ) && ! empty( $item_data[ 'avatar' ] ) ) {
				echo '<span class="meta-icon">';
				echo wp_kses_post( $item_data[ 'avatar' ] );
				echo '</span>';
			}
			echo wp_kses_post( $prefix ) . ' ' . esc_html( $item_data[ 'text' ] );
			if ( ! empty( $item_data[ 'link' ] ) ) {
				echo '</a>';
			} else {
				echo '</span>';
			}
		}
	}

	private function get_post_author( $item ): array {
		$user_id = get_post_field( 'post_author', get_the_ID() );

		return [
			'text'   => get_the_author_meta( 'display_name', $user_id ),
			'link'   => 'yes' === $item[ 'link' ] ? get_author_posts_url( $user_id ) : '',
			'avatar' => 'yes' === $item[ 'show_avatar' ] ? get_avatar( $user_id ) : '',
			'prefix' => $item[ 'text_prefix' ],
		];
	}

	private function get_post_date( $item ): array {
		$formats = [
			'0'      => 'F j, Y',
			'1'      => 'd/m/Y',
			'2'      => 'Y-m-d',
			'3'      => 'm/d/Y',
			'custom' => '',
		];

		$date_format = $formats[ '0' ];

		if ( isset( $item[ 'date_format' ] ) ) {
			if ( $item[ 'date_format' ] === 'custom' && ! empty( $item[ 'custom_date_format' ] ) ) {
				$date_format = $item[ 'custom_date_format' ];
			} elseif ( isset( $formats[ $item[ 'date_format' ] ] ) ) {
				$date_format = $formats[ $item[ 'date_format' ] ];
			}
		}

		$date_text = get_the_date( $date_format );

		$year  = get_the_date( 'Y' );
		$month = get_the_date( 'm' );
		$day   = get_the_date( 'd' );
		$link  = get_day_link( $year, $month, $day );

		return [
			'text'   => $date_text,
			'link'   => 'yes' === $item[ 'link' ] ? esc_url( $link ) : '',
			'prefix' => ! empty( $item[ 'text_prefix' ] ) ? $item[ 'text_prefix' ] : '',
		];
	}

	private function get_post_comment( $item ): array {
		$comments_number   = (int) get_comments_number();
		$comments_label    = ! empty( $item[ 'string_comments' ] ) ? $item[ 'string_comments' ] : '';
		$no_comments_label = ! empty( $item[ 'string_no_comments' ] ) ? $item[ 'string_no_comments' ] : 'No Comments';

		if ( ! empty( $comments_label ) ) {
			$comment_text = $comments_number . ' ' . $comments_label;
		} else {
			/* translators: %s: Number of comments. */
			$comment_text = sprintf( _n( '%s Comment', '%s Comments', $comments_number, 'rs-template-builder' ), number_format_i18n( $comments_number ) );
		}
		if ( 0 === $comments_number ) {
			$comment_text = $no_comments_label;
		}

		$link = ( ! post_password_required() && comments_open() ) ? get_comments_link() : '';

		return [
			'text'   => $comment_text,
			'link'   => 'yes' === $item[ 'link' ] ? esc_url( $link ) : '',
			'prefix' => ! empty( $item[ 'text_prefix' ] ) ? $item[ 'text_prefix' ] : '',
		];
	}

	private function get_post_category( $item ): array {
		if ( Plugin::$instance->editor->is_edit_mode() ) {
			return [
				'text'   => '<span class="inner-pill-item">Elementor</span>',
				'prefix' => ! empty( $item[ 'text_prefix' ] ) ? $item[ 'text_prefix' ] : '',
			];
		}

		$categories = get_the_category();
		if ( empty( $categories ) ) {
			return [];
		}

		$cat_item = [];

		foreach ( $categories as $category ) {
			if ( 'yes' === $item[ 'link' ] ) {
				$cat_item[] = sprintf(
					'<a href="%s" class="inner-pill-item">%s</a>',
					esc_url( get_category_link( $category->term_id ) ),
					esc_html( $category->name )
				);
			} else {
				$cat_item[] = sprintf(
					'<span class="inner-pill-item">%s</span>',
					esc_html( $category->name )
				);
			}
		}

		$catSeparator = ! empty( $item[ 'cat_tags_separator' ] ) ? $item[ 'cat_tags_separator' ] : '';
		if ( $item[ 'cat_tags_first_only' ] === 'yes' ) {
			$category_text = $cat_item[ 0 ];
		} else {
			$category_text = implode( $catSeparator, $cat_item );
		}

		return [
			'text'   => $category_text,
			'prefix' => ! empty( $item[ 'text_prefix' ] ) ? $item[ 'text_prefix' ] : '',
		];
	}

	private function get_post_tags( $item ): array {
		if ( Plugin::$instance->editor->is_edit_mode() ) {
			return [
				'text'   => '<span class="inner-pill-item">Dummy Tag</span>',
				'prefix' => ! empty( $item[ 'text_prefix' ] ) ? $item[ 'text_prefix' ] : '',
			];
		}

		$tags = get_the_tags();
		if ( empty( $tags ) || ! is_array( $tags ) ) {
			return [];
		}

		$tag_item = [];

		foreach ( $tags as $tag ) {
			if ( 'yes' === $item[ 'link' ] ) {
				$tag_item[] = sprintf(
					'<a href="%s" class="inner-pill-item">%s</a>',
					esc_url( get_tag_link( $tag->term_id ) ),
					esc_html( $tag->name )
				);
			} else {
				$tag_item[] = sprintf(
					'<span class="inner-pill-item">%s</span>',
					esc_html( $tag->name )
				);
			}
		}

		$tagsSeparator = ! empty( $item[ 'cat_tags_separator' ] ) ? $item[ 'cat_tags_separator' ] : '';
		if ( $item[ 'cat_tags_first_only' ] === 'yes' ) {
			$tag_text = $tag_item[ 0 ];
		} else {
			$tag_text = implode( $tagsSeparator, $tag_item );
		}

		return [
			'text'   => $tag_text,
			'prefix' => ! empty( $item[ 'text_prefix' ] ) ? $item[ 'text_prefix' ] : '',
		];
	}
}