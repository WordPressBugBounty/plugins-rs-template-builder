<?php

namespace RsTemplateBuilder\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Utils;
use Elementor\Widget_Base;

class Post_Featured_Img extends Widget_Base {

	public function get_name(): string {
		return 'rstb-post-featured-img';
	}

	public function get_title(): string {
		return esc_html__( 'Post Featured Image', 'rs-template-builder' );
	}

	public function get_icon(): string {
		return 'eicon-featured-image rstb-branding-icon';
	}

	public function get_categories(): array {
		return [ 'rstb_elements' ];
	}

	public function get_keywords(): array {
		return [ 'rstheme', 'header', 'footer', 'featured', 'post', 'rs-template-builder' ];
	}

	protected function is_dynamic_content(): bool {
		return true;
	}

	public function has_widget_inner_wrapper(): bool {
		return ! Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls(): void {
		$this->start_controls_section(
			'section_featured_img',
			[
				'label' => esc_html__( 'Featured Image', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'post_feature_image',
				'default'   => 'full',
				'separator' => 'none',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => esc_html__( 'Alignment', 'rs-template-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
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
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'link_to',
			[
				'label'   => esc_html__( 'Link', 'rs-template-builder' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'rs-template-builder' ),
					'file' => esc_html__( 'Media File', 'rs-template-builder' ),
					'post' => esc_html__( 'Post Details', 'rs-template-builder' ),
				],
			]
		);

		$this->add_control(
			'caption_source',
			[
				'label'   => esc_html__( 'Caption', 'rs-template-builder' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'none'        => esc_html__( 'None', 'rs-template-builder' ),
					'att_caption' => esc_html__( 'Attachment Caption', 'rs-template-builder' ),
					'post_title'  => esc_html__( 'Post Title', 'rs-template-builder' ),
				],
				'default' => 'none',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_img_style',
			[
				'label' => esc_html__( 'Image', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
					'{{WRAPPER}} img' => 'width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} img' => 'height: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} img' => 'object-fit: {{VALUE}};',
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
					'{{WRAPPER}} img' => 'object-position: {{VALUE}};',
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
				'name'     => 'img_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} img',
			]
		);

		$this->add_responsive_control(
			'img_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'img_box_shadow',
				'selector' => '{{WRAPPER}} img',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_caption',
			[
				'label'     => esc_html__( 'Caption', 'rs-template-builder' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'caption_source!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'caption_align',
			[
				'label'                => esc_html__( 'Alignment', 'rs-template-builder' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => [
					'start'   => [
						'title' => esc_html__( 'Start', 'rs-template-builder' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => esc_html__( 'Center', 'rs-template-builder' ),
						'icon'  => 'eicon-text-align-center',
					],
					'end'     => [
						'title' => esc_html__( 'End', 'rs-template-builder' ),
						'icon'  => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'rs-template-builder' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'classes'              => 'elementor-control-start-end',
				'selectors_dictionary' => [
					'left'  => is_rtl() ? 'end' : 'start',
					'right' => is_rtl() ? 'start' : 'end',
				],
				'default'              => '',
				'selectors'            => [
					'{{WRAPPER}} .rstb-feature-img figcaption' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'caption_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .rstb-feature-img figcaption' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'caption_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-feature-img figcaption' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'caption_typography',
				'selector' => '{{WRAPPER}} .rstb-feature-img figcaption',
			]
		);

		$this->add_responsive_control(
			'caption_margin',
			[
				'label'      => esc_html__( 'Margin', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-feature-img figcaption' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'caption_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-feature-img figcaption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'caption_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .rstb-feature-img figcaption',
			]
		);

		$this->add_responsive_control(
			'caption_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-feature-img figcaption' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$link    = $this->get_link_url( $settings );
		$caption = $this->get_caption( $settings );

		$wrapper_tag = $caption ? 'figure' : 'div';

		echo '<' . $wrapper_tag . ' class="rstb-feature-img">';

		if ( $this->is_edit_preview_mode() ) {
			if ( $link ) {
				echo '<a href="#">';
			}
			echo '<img src="' . esc_url( Utils::get_placeholder_image_src() ) . '" alt="place holder image">';
			if ( $link ) {
				echo '</a>';
			}
		} else {
			if ( has_post_thumbnail() ) {
				if ( $link ) {
					echo '<a href="' . esc_url( $link ) . '">';
				}
				if ( $settings[ 'post_feature_image_size' ] === 'custom' ) {
					the_post_thumbnail( [
						$settings[ 'post_feature_image_custom_dimension' ][ 'width' ],
						$settings[ 'post_feature_image_custom_dimension' ][ 'height' ]
					] );
				} else {
					the_post_thumbnail( $settings[ 'post_feature_image_size' ] );
				}
				if ( $link ) {
					echo '</a>';
				}
			}
		}
		if ( $caption ) {
			echo '<figcaption>' . wp_kses_post( $caption ) . '</figcaption>';
		}
		echo '</' . $wrapper_tag . '>';
	}

	protected function get_link_url( $settings ) {
		if ( 'none' === $settings[ 'link_to' ] ) {
			return false;
		}

		if ( 'file' === $settings[ 'link_to' ] ) {
			return get_the_post_thumbnail_url();
		}

		if ( 'post' === $settings[ 'link_to' ] ) {
			return get_permalink();
		}

		return false;
	}

	protected function get_caption( $settings ) {
		$caption = '';

		if ( 'none' === $settings[ 'caption_source' ] ) {
			return $caption;
		}

		if ( $this->is_edit_preview_mode() ) {
			return __( 'Sample caption for the featured image. This appears only in the editor preview.', 'rs-template-builder' );
		}

		switch ( $settings[ 'caption_source' ] ) {
			case 'att_caption':
				$caption = get_the_post_thumbnail_caption();
				break;
			case 'post_title':
				$caption = get_the_title();
				break;
		}

		return $caption;
	}

	private function is_edit_preview_mode(): bool {
		$current_post_type = get_post_type();

		if ( Plugin::$instance->editor->is_edit_mode() || $current_post_type === 'rstb_template' ) {
			return true;
		}

		return false;
	}
}