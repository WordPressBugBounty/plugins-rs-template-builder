<?php

namespace RsTemplateBuilder\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Utils;
use Elementor\Widget_Base;
use WP_Query;

class Archive_Posts extends Widget_Base {

	public $query;

	public $settings = [];

	public function get_name(): string {
		return 'rstb-archive-posts';
	}

	public function get_title(): string {
		return esc_html__( 'Archive Posts', 'rs-template-builder' );
	}

	public function get_icon(): string {
		return 'eicon-archive-posts rstb-branding-icon';
	}

	public function get_categories(): array {
		return [ 'rstb_elements' ];
	}

	public function get_keywords(): array {
		return [ 'rs template builder', 'rstheme', 'header', 'footer', 'archive', 'post', 'rs-template-builder' ];
	}

	protected function is_dynamic_content(): bool {
		return true;
	}

	public function has_widget_inner_wrapper(): bool {
		return ! Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls(): void {
		$this->start_controls_section(
			'widget_content',
			[
				'label' => esc_html__( 'General', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'gird_column',
			[
				'label'     => esc_html__( 'Grid Column', 'rs-template-builder' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'selectors' => [
					'{{WRAPPER}} .rstb-archive-posts' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));'
				]
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label'       => esc_html__( 'Title Tag', 'rs-template-builder' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => [
					'h2' => [ 'title' => 'H2', 'icon' => 'eicon-editor-h2', ],
					'h3' => [ 'title' => 'H3', 'icon' => 'eicon-editor-h3', ],
					'h4' => [ 'title' => 'H4', 'icon' => 'eicon-editor-h4', ],
					'h5' => [ 'title' => 'H5', 'icon' => 'eicon-editor-h5', ],
					'h6' => [ 'title' => 'H6', 'icon' => 'eicon-editor-h6', ],
				],
				'default'     => 'h4',
				'toggle'      => false,
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'title_length',
			[
				'type'  => Controls_Manager::NUMBER,
				'label' => esc_html__( 'Title Length', 'rs-template-builder' ),
				'min'   => 0,
			]
		);

		$this->add_control(
			'title_line',
			[
				'type'      => Controls_Manager::NUMBER,
				'label'     => esc_html__( 'Line clamp', 'rs-template-builder' ),
				'min'       => 0,
				'selectors' => [
					'{{WRAPPER}} .post-title a' => 'display: -webkit-box; -webkit-box-orient: vertical; overflow: hidden; line-clamp: {{VALUE}}; -webkit-line-clamp: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'show_excerpt',
			[
				'label'        => esc_html__( 'Show Excerpt', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'No', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'excerpt_length',
			[
				'type'      => Controls_Manager::NUMBER,
				'label'     => esc_html__( 'Excerpt Length', 'rs-template-builder' ),
				'min'       => 0,
				'condition' => [
					'show_excerpt' => 'yes'
				]
			]
		);

		$this->add_control(
			'excerpt_line',
			[
				'type'      => Controls_Manager::NUMBER,
				'label'     => esc_html__( 'Line clamp', 'rs-template-builder' ),
				'min'       => 0,
				'selectors' => [
					'{{WRAPPER}} .post-excerpt' => 'display: -webkit-box; -webkit-box-orient: vertical; overflow: hidden; line-clamp: {{VALUE}}; -webkit-line-clamp: {{VALUE}}',
				],
				'condition' => [
					'show_excerpt' => 'yes'
				]
			]
		);

		$this->add_control(
			'show_read_more',
			[
				'label'        => esc_html__( 'Show Read More', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'No', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'read_more_text',
			[
				'label'       => esc_html__( 'Read More Text', 'rs-template-builder' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => false,
				'default'     => esc_html__( 'Read More', 'rs-template-builder' ),
				'condition'   => [
					'show_read_more' => 'yes',
				]
			]
		);

		$this->add_control(
			'read_more_icon',
			[
				'label'       => esc_html__( 'Read More Icon', 'rs-template-builder' ),
				'type'        => Controls_Manager::ICONS,
				'label_block' => false,
				'skin'        => 'inline',
				'condition'   => [
					'show_read_more' => 'yes',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_post_thumbnail',
			[
				'label' => esc_html__( 'Thumbnail', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_thumbnail',
			[
				'label'        => esc_html__( 'Show Thumbnail?', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'No', 'rs-template-builder' ),
				'default'      => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->add_responsive_control(
			'thumb_position',
			[
				'label'        => esc_html__( 'Thumbnail Position', 'rs-template-builder' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [
					'left'  => [
						'title' => esc_html__( 'Left', 'rs-template-builder' ),
						'icon'  => 'eicon-h-align-left',
					],
					'top'   => [
						'title' => esc_html__( 'Top', 'rs-template-builder' ),
						'icon'  => 'eicon-v-align-top',
					],
					'right' => [
						'title' => esc_html__( 'right', 'rs-template-builder' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'      => 'top',
				'toggle'       => false,
				'prefix_class' => 'rstb%s-thumb-',
				'condition'    => [
					'show_thumbnail' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'post_thumbnail',
				'default'   => 'large',
				'exclude'   => [
					'custom',
				],
				'condition' => [
					'show_thumbnail' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_post_meta',
			[
				'label' => esc_html__( 'Post Meta', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'bt_meta',
			[
				'label'       => esc_html__( 'Before Title', 'rs-template-builder' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => [
					'author'   => esc_html__( 'Author', 'rs-template-builder' ),
					'category' => esc_html__( 'Category', 'rs-template-builder' ),
					'date'     => esc_html__( 'Date', 'rs-template-builder' ),
					'comments' => esc_html__( 'Comments', 'rs-template-builder' ),
				],
			]
		);

		$this->add_control(
			'bt_meta_first_cat',
			[
				'label'        => esc_html__( 'First Category?', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'No', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					'bt_meta' => 'category'
				]
			]
		);

		$this->add_control(
			'bt_meta_icon',
			[
				'label'        => esc_html__( 'Show Icon', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'No', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'bt_meta_divider',
			[
				'label'        => esc_html__( 'Show Divider', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'No', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'at_meta',
			[
				'label'       => esc_html__( 'After Title', 'rs-template-builder' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'separator'   => 'before',
				'options'     => [
					'author'   => esc_html__( 'Author', 'rs-template-builder' ),
					'category' => esc_html__( 'Category', 'rs-template-builder' ),
					'date'     => esc_html__( 'Date', 'rs-template-builder' ),
					'comments' => esc_html__( 'Comments', 'rs-template-builder' ),
				],
			]
		);

		$this->add_control(
			'at_meta_first_cat',
			[
				'label'        => esc_html__( 'First Category?', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'No', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					'at_meta' => 'category'
				]
			]
		);

		$this->add_control(
			'at_meta_icon',
			[
				'label'        => esc_html__( 'Show Icon?', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'No', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'at_meta_divider',
			[
				'label'        => esc_html__( 'Show Divider', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'No', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'ont_meta',
			[
				'label'       => esc_html__( 'On Thumbnail', 'rs-template-builder' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'separator'   => 'before',
				'options'     => [
					'author'   => esc_html__( 'Author', 'rs-template-builder' ),
					'category' => esc_html__( 'Category', 'rs-template-builder' ),
					'date'     => esc_html__( 'Date', 'rs-template-builder' ),
					'comments' => esc_html__( 'Comments', 'rs-template-builder' ),
				],
			]
		);

		$this->add_control(
			'ont_meta_first_cat',
			[
				'label'        => esc_html__( 'First Category?', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'No', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => [
					'ont_meta' => 'category'
				]
			]
		);

		$this->add_control(
			'ont_meta_icon',
			[
				'label'        => esc_html__( 'Show Icon?', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'No', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'ont_meta_divider',
			[
				'label'        => esc_html__( 'Show Divider', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'No', 'rs-template-builder' ),
				'return_value' => 'yes',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_archive_others',
			[
				'label' => esc_html__( 'Others', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'pagination_type',
			[
				'label'       => esc_html__( 'Pagination Type', 'rs-template-builder' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'options'     => [
					'none'    => esc_html__( 'None', 'rs-template-builder' ),
					'numbers' => esc_html__( 'Numbers', 'rs-template-builder' ),
				],
				'default'     => 'numbers',
			]
		);

		$this->add_control(
			'query_id',
			[
				'label' => esc_html__( 'Query ID', 'rs-template-builder' ),
				'type'  => Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'nothing_found_message',
			[
				'label'   => esc_html__( 'Nothing Found Message', 'rs-template-builder' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'It seems we can\'t find what you\'re looking for.', 'rs-template-builder' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_blog_item',
			[
				'label' => esc_html__( 'Post Item', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'item_gap',
			[
				'label'      => esc_html__( 'Item Gaps', 'rs-template-builder' ),
				'type'       => Controls_Manager::GAPS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-archive-posts' => 'row-gap: {{ROW}}{{UNIT}}; column-gap: {{COLUMN}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_margin',
			[
				'label'      => esc_html__( 'Margin', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-post-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .rstb-post-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'item_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .rstb-post-item',
			]
		);

		$this->add_responsive_control(
			'item_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-post-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'blog_item_style_tab' );

		$this->start_controls_tab(
			'blog_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'item_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-item' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'item_box_shadow',
				'selector' => '{{WRAPPER}} .rstb-post-item',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'blog_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'item_hover_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-item:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'item_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-item:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'item_hover_box_shadow',
				'selector' => '{{WRAPPER}} .rstb-post-item:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'text_align',
			[
				'label'     => esc_html__( 'Text Align', 'rs-template-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
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
				'selectors' => [
					'{{WRAPPER}} .rstb-post-item' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .post-meta'      => 'justify-content: {{VALUE}}',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'post_thumb_section',
			[
				'label'     => esc_html__( 'Thumbnail', 'rs-template-builder' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_thumbnail' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'thumb_gap',
			[
				'label'      => esc_html__( 'Gap', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'selectors'  => [
					'{{WRAPPER}} .rstb-archive-posts' => '--thumb-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'thumb_size',
			[
				'label'        => esc_html__( 'Thumb Size', 'rs-template-builder' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => esc_html__( 'None', 'rs-template-builder' ),
				'label_on'     => esc_html__( 'Custom', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'image_width',
			[
				'label'      => esc_html__( 'Width', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
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
					'{{WRAPPER}} .post-thumbnail' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'thumb_size' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'image_height',
			[
				'label'      => esc_html__( 'Height', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
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
					'{{WRAPPER}} .post-thumbnail' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'thumb_size' => 'yes'
				]
			]
		);

		$this->add_control(
			'img_fit',
			[
				'label'     => esc_html__( 'Object Fit', 'rs-template-builder' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''           => esc_html__( 'Default', 'rs-template-builder' ),
					'cover'      => esc_html__( 'Cover', 'rs-template-builder' ),
					'fill'       => esc_html__( 'Fill', 'rs-template-builder' ),
					'contain'    => esc_html__( 'Contain', 'rs-template-builder' ),
					'none'       => esc_html__( 'None', 'rs-template-builder' ),
					'scale-down' => esc_html__( 'Scale Down', 'rs-template-builder' ),
				],
				'selectors' => [
					'{{WRAPPER}} .post-thumbnail img' => 'object-fit: {{VALUE}};',
				],
				'condition' => [
					'thumb_size' => 'yes'
				]
			]
		);

		$this->add_control(
			'img_position',
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
					'{{WRAPPER}} .post-thumbnail img' => 'object-position: {{VALUE}};',
				],
				'condition' => [
					'thumb_size' => 'yes'
				]
			]
		);

		$this->end_popover();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'thumb_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .post-thumbnail',
			]
		);

		$this->add_responsive_control(
			'thumb_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'thumbnail_style_tab' );

		$this->start_controls_tab(
			'thumb_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'rs-template-builder' ),
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'thumb_box_shadow',
				'selector' => '{{WRAPPER}} .post-thumbnail',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'thumb_overly',
				'types'          => [ 'classic', 'gradient' ],
				'selector'       => '{{WRAPPER}} .post-thumbnail::before',
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Overly', 'rs-template-builder' ),
					],
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'thumb_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'hover_thumb_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-item:hover .post-thumbnail' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'thumb_border_border!' => ''
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'hover_thumb_box_shadow',
				'selector' => '{{WRAPPER}} .rstb-post-item:hover .post-thumbnail',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'hover_thumb_overly',
				'types'          => [ 'classic', 'gradient' ],
				'selector'       => '{{WRAPPER}} .rstb-post-item:hover .post-thumbnail::before',
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Overly', 'rs-template-builder' ),
					],
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_post_content_style',
			[
				'label' => esc_html__( 'Post Content', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_margin',
			[
				'label'      => esc_html__( 'Margin', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'content_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .post-content',
			]
		);

		$this->add_responsive_control(
			'content_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'content_style_tabs' );

		$this->start_controls_tab(
			'content_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'content_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-content' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'content_box_shadow',
				'selector' => '{{WRAPPER}} .post-content',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'content_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'hover_content_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-item:hover .post-content' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'content_border_border!' => ''
				]
			]
		);

		$this->add_control(
			'hover_content_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-item:hover .post-content' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'hover_content_box_shadow',
				'selector' => '{{WRAPPER}} .rstb-post-item:hover .post-content',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_desc_style',
			[
				'label' => esc_html__( 'Title/Excerpt/Read More', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_heading',
			[
				'label' => esc_html__( 'Title', 'rs-template-builder' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label'     => esc_html__( 'Color(Hover)', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-title:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_hover_color_2',
			[
				'label'     => esc_html__( 'Color(Item Hover)', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-item:hover .post-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typo',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .post-title',
			]
		);

		$this->add_control(
			'excerpt_heading',
			[
				'label'     => esc_html__( 'Description', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_excerpt' => 'yes'
				]
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-excerpt' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_excerpt' => 'yes'
				]
			]
		);

		$this->add_control(
			'excerpt_hover_color',
			[
				'label'     => esc_html__( 'Color(Item Hover)', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-item:hover .post-excerpt' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_excerpt' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'excerpt_space_top',
			[
				'label'      => esc_html__( 'Space Top', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-excerpt' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'show_excerpt' => 'yes'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'excerpt_typo',
				'label'     => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector'  => '{{WRAPPER}} .post-excerpt',
				'condition' => [
					'show_excerpt' => 'yes'
				]
			]
		);

		$this->add_control(
			'read_more_heading',
			[
				'label'     => esc_html__( 'Read More', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_read_more' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'read_more_spacing',
			[
				'label'      => esc_html__( 'Spacing', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-read-more' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'show_read_more' => 'yes'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'read_more_typo',
				'label'     => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector'  => '{{WRAPPER}} .post-read-more',
				'condition' => [
					'show_read_more' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'read_more_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-read-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'show_read_more' => 'yes'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'read_more_border',
				'label'     => esc_html__( 'Border', 'rs-template-builder' ),
				'selector'  => '{{WRAPPER}} .post-read-more',
				'condition' => [
					'show_read_more' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'read_more_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-read-more' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'show_read_more' => 'yes'
				]
			]
		);

		$this->start_controls_tabs(
			'read_more_style_tabs',
			[
				'condition' => [
					'show_read_more' => 'yes'
				]
			]
		);

		$this->start_controls_tab(
			'read_more_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'read_more_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-read-more' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'read_more_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-read-more' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'read_more_box_shadow',
				'selector' => '{{WRAPPER}} .post-read-more',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'read_more_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'hover_read_more_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-read-more:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hover_read_more_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-read-more:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hover_read_more_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-read-more:hover' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'read_more_border_border!' => ''
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'hover_read_more_box_shadow',
				'selector' => '{{WRAPPER}} .post-read-more:hover',
			]
		);

		$this->add_control(
			'item_hover_btn_style',
			[
				'label'     => esc_html__( 'Item Hover', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'item_hover_read_more_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-item:hover .post-read-more' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'item_hover_read_more_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-item:hover .post-read-more' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'item_read_more_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-item:hover .post-read-more' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'read_more_border_border!' => ''
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'item_hover_read_more_box_shadow',
				'selector' => '{{WRAPPER}} .rstb-post-item:hover .post-read-more',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_post_meta_style',
			[
				'label' => esc_html__( 'Post Meta', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'post_meta_style_tab' );

		$this->start_controls_tab(
			'bt_meta_style',
			[
				'label' => esc_html__( 'Before Title', 'rs-template-builder' ),
			]
		);

		$this->add_responsive_control(
			'bt_meta_margin',
			[
				'label'      => esc_html__( 'Margin', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-meta.before-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'bt_meta_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-meta.before-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'bt_meta_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .post-meta.before-title',
			]
		);

		$this->add_responsive_control(
			'bt_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-meta.before-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'bt_meta_bg_color',
			[
				'label'     => esc_html__( 'Background', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-meta.before-title' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'ih_bt_meta_bg_color',
			[
				'label'     => esc_html__( 'Background(Item Hover)', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-item:hover .post-meta.before-title' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'bt_meta_gap',
			[
				'label'      => esc_html__( 'Items Gaps', 'rs-template-builder' ),
				'type'       => Controls_Manager::GAPS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-meta.before-title' => 'row-gap: {{ROW}}{{UNIT}}; column-gap: {{COLUMN}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'bt_meta_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-meta.before-title a' => 'color: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'before_title_meta_hover_color',
			[
				'label'     => esc_html__( 'Color(Hover)', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-meta.before-title a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'bt_meta_hover_color_2',
			[
				'label'     => esc_html__( 'Color(Item Hover)', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-item:hover .post-meta.before-title a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'bt_meta_typo',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .post-meta.before-title a',
			]
		);

		$this->add_control(
			'bt_divider_heading',
			[
				'label'     => esc_html__( 'Divider', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'bt_divider_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-meta.before-title .meta-divider' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'bt_divider_size',
			[
				'label'        => esc_html__( 'Divider Size', 'rs-template-builder' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => esc_html__( 'None', 'rs-template-builder' ),
				'label_on'     => esc_html__( 'Custom', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'bt_divider_width',
			[
				'label'      => esc_html__( 'Width', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-meta.before-title .meta-divider' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'bt_divider_size' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'bt_divider_height',
			[
				'label'      => esc_html__( 'Height', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-meta.before-title .meta-divider' => 'height: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'bt_divider_size' => 'yes'
				]
			]
		);

		$this->end_popover();

		$this->add_responsive_control(
			'bt_divider_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-meta.before-title .meta-divider' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'at_meta_style',
			[
				'label' => esc_html__( 'After Title', 'rs-template-builder' ),
			]
		);

		$this->add_responsive_control(
			'at_meta_margin',
			[
				'label'      => esc_html__( 'Margin', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-meta.after-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'at_meta_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-meta.after-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'at_meta_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .post-meta.after-title',
			]
		);

		$this->add_control(
			'at_meta_bg_color',
			[
				'label'     => esc_html__( 'Background', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-meta.after-title' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'at_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-meta.after-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'ih_at_meta_bg_color',
			[
				'label'     => esc_html__( 'Background(Item Hover)', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-item:hover .post-meta.after-title' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'at_meta_gap',
			[
				'label'      => esc_html__( 'Items Gaps', 'rs-template-builder' ),
				'type'       => Controls_Manager::GAPS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-meta.after-title' => 'row-gap: {{ROW}}{{UNIT}}; column-gap: {{COLUMN}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'at_meta_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-meta.after-title a' => 'color: {{VALUE}}',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'at_meta_hover_color',
			[
				'label'     => esc_html__( 'Color(Hover)', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-meta.after-title a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'at_meta_hover_color_2',
			[
				'label'     => esc_html__( 'Color(Item Hover)', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-item:hover .post-meta.after-title a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'at_meta_typo',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .post-meta.after-title a',
			]
		);

		$this->add_control(
			'at_divider_heading',
			[
				'label'     => esc_html__( 'Divider', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'at_divider_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-meta.after-title .meta-divider' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'at_divider_size',
			[
				'label'        => esc_html__( 'Divider Size', 'rs-template-builder' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => esc_html__( 'None', 'rs-template-builder' ),
				'label_on'     => esc_html__( 'Custom', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'at_divider_width',
			[
				'label'      => esc_html__( 'Width', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-meta.after-title .meta-divider' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'at_divider_size' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'at_divider_height',
			[
				'label'      => esc_html__( 'Height', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-meta.after-title .meta-divider' => 'height: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'at_divider_size' => 'yes'
				]
			]
		);

		$this->end_popover();

		$this->add_responsive_control(
			'at_divider_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-meta.after-title .meta-divider' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'ont_meta_style',
			[
				'label' => esc_html__( 'On Thumbnail', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'ont_meta_position',
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
			'ont_meta_position_l',
			[
				'label'      => esc_html__( 'Left', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-meta.on-thumbnail' => 'left: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'ont_meta_position' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'ont_meta_position_r',
			[
				'label'      => esc_html__( 'Right', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-meta.on-thumbnail' => 'right: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'ont_meta_position' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'ont_meta_position_t',
			[
				'label'      => esc_html__( 'Top', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-meta.on-thumbnail' => 'top: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'ont_meta_position' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'ont_meta_position_b',
			[
				'label'      => esc_html__( 'Bottom', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-meta.on-thumbnail' => 'bottom: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'ont_meta_position' => 'yes'
				]
			]
		);

		$this->end_popover();

		$this->add_responsive_control(
			'ont_meta_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-meta.on-thumbnail' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'ont_meta_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .post-meta.on-thumbnail',
			]
		);

		$this->add_responsive_control(
			'ont_meta_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-meta.on-thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'ont_meta_bg_color',
			[
				'label'     => esc_html__( 'Background', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-meta.on-thumbnail' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'ih_ont_meta_bg_color',
			[
				'label'     => esc_html__( 'Background(Item Hover)', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-item:hover .post-meta.on-thumbnail' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'ont_meta_gap',
			[
				'label'      => esc_html__( 'Items Gaps', 'rs-template-builder' ),
				'type'       => Controls_Manager::GAPS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-meta.on-thumbnail' => 'row-gap: {{ROW}}{{UNIT}}; column-gap: {{COLUMN}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'ont_meta_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-meta.on-thumbnail a' => 'color: {{VALUE}}',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'ont_meta_hover_color',
			[
				'label'     => esc_html__( 'Color(Hover)', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-meta.on-thumbnail a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'ont_meta_hover_color_2',
			[
				'label'     => esc_html__( 'Color(Item Hover)', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-post-item:hover .post-meta.on-thumbnail a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'ont_meta_typo',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .post-meta.on-thumbnail a',
			]
		);

		$this->add_control(
			'ont_divider_heading',
			[
				'label'     => esc_html__( 'Divider', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'ont_divider_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-meta.on-thumbnail .meta-divider' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'ont_divider_size',
			[
				'label'        => esc_html__( 'Divider Size', 'rs-template-builder' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => esc_html__( 'None', 'rs-template-builder' ),
				'label_on'     => esc_html__( 'Custom', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'ont_divider_width',
			[
				'label'      => esc_html__( 'Width', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-meta.on-thumbnail .meta-divider' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'ont_divider_size' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'ont_divider_height',
			[
				'label'      => esc_html__( 'Height', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-meta.on-thumbnail .meta-divider' => 'height: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'ont_divider_size' => 'yes'
				]
			]
		);

		$this->end_popover();

		$this->add_responsive_control(
			'ont_divider_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-meta.on-thumbnail .meta-divider' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_noting_found_style',
			[
				'label' => esc_html__( 'Nothing Found', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'noting_found_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-no-posts-msg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'noting_found_margin',
			[
				'label'      => esc_html__( 'Margin', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-no-posts-msg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'noting_found_typo',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .rstb-no-posts-msg',
			]
		);

		$this->add_control(
			'noting_found_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-no-posts-msg' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'noting_found_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-no-posts-msg' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'noting_found_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .rstb-no-posts-msg',
			]
		);

		$this->add_responsive_control(
			'noting_found_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-no-posts-msg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_pagination_style',
			[
				'label'     => esc_html__( 'Pagination', 'rs-template-builder' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'pagination_type!' => 'none'
				]
			]
		);

		$this->add_responsive_control(
			'pagination_alignment',
			[
				'label'     => esc_html__( 'Alignment', 'rs-template-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
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
				'selectors' => [
					'{{WRAPPER}} .rstb-archive-pagination .nav-links' => 'justify-content: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_margin',
			[
				'label'      => esc_html__( 'Margin', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-archive-pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'page_num_width',
			[
				'label'      => esc_html__( 'Width', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-archive-pagination .page-numbers' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'page_num_height',
			[
				'label'      => esc_html__( 'Height', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-archive-pagination .page-numbers' => 'height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'page_num_typo',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .rstb-archive-pagination .page-numbers',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'page_num_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .rstb-archive-pagination .page-numbers',
				'exclude'  => [ 'color' ]
			]
		);

		$this->add_responsive_control(
			'page_num_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-archive-pagination .page-numbers' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'page_num_style_tab' );

		$this->start_controls_tab(
			'page_num_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'page_num_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-archive-pagination .page-numbers' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'page_num_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-archive-pagination .page-numbers' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'page_num_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-archive-pagination .page-numbers' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'page_num_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'page_num_color_hover',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-archive-pagination .page-numbers:not(.dots):hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .rstb-archive-pagination .page-numbers.current'          => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'page_num_bg_color_hover',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-archive-pagination .page-numbers:not(.dots):hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .rstb-archive-pagination .page-numbers.current'          => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'page_num_border_color_hover',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-archive-pagination .page-numbers:not(.dots):hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .rstb-archive-pagination .page-numbers.current'          => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render(): void {
		$this->settings = $this->get_settings_for_display();
		global $wp_query;
		$current_post_type = get_post_type();

		$query_vars = $wp_query->query_vars;

		if ( ! empty( $this->settings[ 'query_id' ] ) ) {
			$query_vars = apply_filters( "rstb_elementor_widgets/archive_posts/{$this->settings['query_id']}", $query_vars );
		}

		if ( $query_vars !== $wp_query->query_vars ) {
			$this->query = new WP_Query( $query_vars );
		} else {
			$this->query = $wp_query;
		}

		if ( Plugin::$instance->editor->is_edit_mode() || $current_post_type === 'rstb_template' ) {
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

			$args        = [
				'post_type'           => [ 'post' ],
				'post_status'         => [ 'publish' ],
				'paged'               => $paged,
				'offset'              => 0,
				'posts_per_page'      => 9,
				'order'               => 'DESC',
				'orderby'             => 'date',
				'ignore_sticky_posts' => false
			];
			$this->query = new WP_Query( $args );
		}

		if ( $this->query->in_the_loop ) {
			$this->render_post();
		} else {
			if ( $this->query->have_posts() ) {
				echo '<div class="rstb-archive-posts">';
				while ( $this->query->have_posts() ) {
					$this->query->the_post();

					$this->render_post();
				}
				echo '</div>';
				if ( 'numbers' === $this->settings[ 'pagination_type' ] ) {
					the_posts_pagination( [
						'class'     => 'rstb-archive-pagination',
						'prev_text' => '<i class="ri-arrow-left-line"></i>',
						'next_text' => '<i class="ri-arrow-right-line"></i>'
					] );
				}
			} else {
				echo '<div class="rstb-no-posts-msg">' . wp_kses_post( $this->settings[ 'nothing_found_message' ] ) . '</div>';
			}
		}

		wp_reset_postdata();
	}

	public function render_post() {
		$settings           = $this->settings;
		$ont_meta           = $settings[ 'ont_meta' ];
		$ont_meta_icon      = $settings[ 'ont_meta_icon' ];
		$ont_meta_first_cat = $settings[ 'ont_meta_first_cat' ];
		$ont_meta_divider   = $settings[ 'ont_meta_divider' ];

		?>
        <div class="rstb-post-item">
			<?php if ( has_post_thumbnail() && 'yes' === $settings[ 'show_thumbnail' ] ): ?>
                <div class="post-thumbnail">
					<?php
					echo get_the_post_thumbnail( get_the_ID(), $settings[ 'post_thumbnail_size' ] );

					$this->render_post_meta(
						$ont_meta,
						$ont_meta_first_cat,
						$ont_meta_icon,
						$ont_meta_divider,
						'on-thumbnail'
					);
					?>
                </div>
			<?php endif; ?>
            <div class="post-content">
				<?php
				$this->render_post_title();
				$this->render_post_excerpt();
				$this->render_post_read_more();
				?>
            </div>
        </div>
		<?php
	}

	private function render_post_title() {
		$settings  = $this->get_settings_for_display();
		$the_title = ! empty( $settings[ 'title_length' ] ) ? wp_trim_words( get_the_title(), $settings[ 'title_length' ] ) : get_the_title();

		// Before title meta
		$bt_meta           = $settings[ 'bt_meta' ];
		$bt_meta_icon      = $settings[ 'bt_meta_icon' ];
		$bt_meta_first_cat = $settings[ 'bt_meta_first_cat' ];
		$bt_meta_divider   = $settings[ 'bt_meta_divider' ];

		// After title meta
		$at_meta           = $settings[ 'at_meta' ];
		$at_meta_icon      = $settings[ 'at_meta_icon' ];
		$at_meta_first_cat = $settings[ 'at_meta_first_cat' ];
		$at_meta_divider   = $settings[ 'at_meta_divider' ];

		$this->render_post_meta(
			$bt_meta,
			$bt_meta_first_cat,
			$bt_meta_icon,
			$bt_meta_divider,
			'before-title'
		);

		printf( '<%1$s class="post-title"><a href="%2$s">%3$s</a></%1$s>',
			Utils::validate_html_tag( $settings[ 'title_tag' ] ),
			esc_url( get_the_permalink() ),
			wp_kses_post( $the_title )
		);

		$this->render_post_meta(
			$at_meta,
			$at_meta_first_cat,
			$at_meta_icon,
			$at_meta_divider,
			'after-title'
		);
	}

	private function render_post_excerpt() {
		$settings = $this->get_settings_for_display();

		if ( 'yes' !== $settings[ 'show_excerpt' ] ) {
			return;
		}

		$the_excerpt = ! empty( $settings[ 'excerpt_length' ] ) ? wp_trim_words( get_the_excerpt(), $settings[ 'excerpt_length' ] ) : get_the_excerpt();

		printf( '<p class="post-excerpt">%1$s</p>',
			esc_html( $the_excerpt )
		);
	}

	private function render_post_read_more() {
		$settings = $this->get_settings_for_display();

		if ( 'yes' !== $settings[ 'show_read_more' ] || empty( $settings[ 'read_more_text' ] ) ) {
			return;
		}
		?>
        <a href="<?php echo esc_url( get_the_permalink() ) ?>" class="post-read-more">
			<?php echo esc_html( $settings[ 'read_more_text' ] ) ?>
			<?php if ( ! empty( $settings[ 'read_more_icon' ][ 'value' ] ) ) : ?>
                <span class="read-more-icon">
                    <?php Icons_Manager::render_icon( $settings[ 'read_more_icon' ] ); ?>
                </span>
			<?php endif; ?>
        </a>
		<?php
	}

	private function render_post_meta( $items, $first_cat, $icon, $divider, $additional_class = '' ): void {
		if ( ! array( $items ) || empty( $items ) ) {
			return;
		}

		$wrapper = 'post-meta';

		if ( ! empty( $additional_class ) ) {
			$wrapper .= ' ' . $additional_class;
		}
		?>
        <div class="<?php echo esc_attr( $wrapper ) ?>">
			<?php foreach ( $items as $item ) {
				$this->render_post_meta_item( $item, $first_cat, $icon, $divider );
			} ?>
        </div>
		<?php
	}

	private function render_post_meta_item( $item, $first_cat = true, $icon = false, $divider = false ) {
		$author_id = get_post_field( 'post_author', get_the_ID() );
		if ( 'author' === $item ) : ?>
            <a href="<?php echo esc_url( get_author_posts_url( $author_id ) ) ?>" class="meta-author">
				<?php if ( $icon ) {
					echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill="none" d="M0 0h24v24H0z"></path><path d="M4 22C4 17.5817 7.58172 14 12 14C16.4183 14 20 17.5817 20 22H18C18 18.6863 15.3137 16 12 16C8.68629 16 6 18.6863 6 22H4ZM12 13C8.685 13 6 10.315 6 7C6 3.685 8.685 1 12 1C15.315 1 18 3.685 18 7C18 10.315 15.315 13 12 13ZM12 11C14.21 11 16 9.21 16 7C16 4.79 14.21 3 12 3C9.79 3 8 4.79 8 7C8 9.21 9.79 11 12 11Z"></path></svg>';
				} ?>
				<?php echo esc_html( get_the_author_meta( 'display_name', $author_id ) ) ?>
            </a>
			<?php if ( $divider ) {
				echo '<span class="meta-divider"></span>';
			} ?>
		<?php elseif ( 'category' === $item ):
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already Escaped Inside.
			echo $this->get_post_category( $first_cat, $icon );
			if ( $divider ) {
				echo '<span class="meta-divider"></span>';
			} ?>
		<?php elseif ( 'date' === $item ) : ?>
            <a href="<?php echo esc_url( get_the_permalink( get_the_ID() ) ) ?>" class="meta-date">
				<?php if ( $icon ) {
					echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill="none" d="M0 0h24v24H0z"></path><path d="M9 1V3H15V1H17V3H21C21.5523 3 22 3.44772 22 4V20C22 20.5523 21.5523 21 21 21H3C2.44772 21 2 20.5523 2 20V4C2 3.44772 2.44772 3 3 3H7V1H9ZM20 11H4V19H20V11ZM7 5H4V9H20V5H17V7H15V5H9V7H7V5Z"></path></svg>';
				} ?>
				<?php echo esc_html( get_the_date() ) ?>
            </a>
			<?php if ( $divider ) {
				echo '<span class="meta-divider"></span>';
			} ?>
		<?php elseif ( 'comments' === $item && ! post_password_required() && comments_open() ) : ?>
            <a href="<?php echo esc_url( esc_url( get_comments_link() ) ) ?>" class="meta-comments">
				<?php if ( $icon ) {
					echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill="none" d="M0 0h24v24H0z"></path><path d="M14 22.5L11.2 19H6C5.44772 19 5 18.5523 5 18V7.10256C5 6.55028 5.44772 6.10256 6 6.10256H22C22.5523 6.10256 23 6.55028 23 7.10256V18C23 18.5523 22.5523 19 22 19H16.8L14 22.5ZM15.8387 17H21V8.10256H7V17H11.2H12.1613L14 19.2984L15.8387 17ZM2 2H19V4H3V15H1V3C1 2.44772 1.44772 2 2 2Z"></path></svg>';
				} ?>
                <span class="comment-text"><?php echo esc_html__( 'Comments ', 'rs-template-builder' ) ?></span>
				<?php echo '(' . esc_html( get_comments_number() ) . ')' ?>
            </a>
			<?php if ( $divider ) {
				echo '<span class="meta-divider"></span>';
			} ?>
		<?php endif;
	}

	private function get_post_category( $first_cat = true, $icon = true ): string {
		$categories = get_the_category();

		if ( empty( $categories ) ) {
			return '';
		}

		$icon_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill="none" d="M0 0h24v24H0z"></path><path d="M10.9042 2.10025L20.8037 3.51446L22.2179 13.414L13.0255 22.6063C12.635 22.9969 12.0019 22.9969 11.6113 22.6063L1.71184 12.7069C1.32131 12.3163 1.32131 11.6832 1.71184 11.2926L10.9042 2.10025ZM11.6113 4.22157L3.83316 11.9997L12.3184 20.485L20.0966 12.7069L19.036 5.28223L11.6113 4.22157ZM13.7327 10.5855C12.9516 9.80448 12.9516 8.53815 13.7327 7.7571C14.5137 6.97606 15.78 6.97606 16.5611 7.7571C17.3421 8.53815 17.3421 9.80448 16.5611 10.5855C15.78 11.3666 14.5137 11.3666 13.7327 10.5855Z"></path></svg>';

		if ( $first_cat ) {
			$category  = $categories[ 0 ];
			$icon_html = $icon ? $icon_svg : '';

			return sprintf(
				'<a class="meta-category" href="%s">%s%s</a>',
				esc_url( get_category_link( $category->term_id ) ),
				$icon_html,
				esc_html( $category->name )
			);
		}

		$links = array_map( function ( $category ) {
			return sprintf(
				'<a class="meta-category" href="%s">%s</a>',
				esc_url( get_category_link( $category->term_id ) ),
				esc_html( $category->name )
			);
		}, $categories );

		return '<div class="meta-categories">'
		       . ( $icon ? $icon_svg : '' )
		       . implode( ' ', $links )
		       . '</div>';
	}
}