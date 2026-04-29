<?php

namespace RsTemplateBuilder\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Utils;
use Elementor\Widget_Base;

class Site_Logo extends Widget_Base {

	public function get_name(): string {
		return 'rstb-site-logo';
	}

	public function get_title(): string {
		return esc_html__( 'Site Logo', 'rs-template-builder' );
	}

	public function get_icon(): string {
		return 'eicon-site-logo rstb-branding-icon';
	}

	public function get_categories(): array {
		return [ 'rstb_elements' ];
	}

	public function get_keywords(): array {
		return [ 'rs template builder', 'rstheme', 'header', 'footer', 'branding', 'logo', 'rs-template-builder' ];
	}

	protected function is_dynamic_content(): bool {
		return false;
	}

	public function has_widget_inner_wrapper(): bool {
		return ! Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls(): void {
		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'Site Logo', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'logo_type',
			[
				'label'       => esc_html__( 'Logo Type', 'rs-template-builder' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'options'     => [
					'text'       => esc_html__( 'Text', 'rs-template-builder' ),
					'image'      => esc_html__( 'Image', 'rs-template-builder' ),
					'text_image' => esc_html__( 'Text & Image', 'rs-template-builder' ),
				],
				'default'     => 'text',
			]
		);

		$this->add_control(
			'text_logo',
			[
				'label'       => esc_html__( 'Text Logo', 'rs-template-builder' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'rs-template-builder', 'rs-template-builder' ),
				'placeholder' => esc_html__( 'Enter text logo', 'rs-template-builder' ),
				'condition'   => [
					'logo_type!' => 'image'
				]
			]
		);

		$this->add_control(
			'image_logo',
			[
				'label'     => esc_html__( 'Image Logo', 'rs-template-builder' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'logo_type!' => 'text'
				]
			]
		);

		$this->add_control(
			'url_type',
			[
				'label'     => esc_html__( 'URL Type', 'rs-template-builder' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'default' => esc_html__( 'Default', 'rs-template-builder' ),
					'custom'  => esc_html__( 'Custom', 'rs-template-builder' ),
					'none'    => esc_html__( 'None', 'rs-template-builder' ),
				],
				'default'   => 'default',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'custom_url',
			[
				'label'       => esc_html__( 'Custom URL', 'rs-template-builder' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => home_url(),
				'condition'   => [
					'url_type' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'logo_horizontal_alignment',
			[
				'label'       => esc_html__( 'Horizontal Alignment', 'rs-template-builder' ),
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
					'{{WRAPPER}} .rstb-site-logo, {{WRAPPER}} .rstb-site-logo a' => 'justify-content: {{VALUE}};',
				],
				'separator'   => 'before',
			]
		);

		$this->add_responsive_control(
			'logo_vertical_alignment',
			[
				'label'       => esc_html__( 'Vertical Alignment', 'rs-template-builder' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => [
					'flex-start' => [
						'title' => esc_html__( 'Top', 'rs-template-builder' ),
						'icon'  => 'eicon-v-align-top',
					],
					'center'     => [
						'title' => esc_html__( 'Center', 'rs-template-builder' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'flex-end'   => [
						'title' => esc_html__( 'Bottom', 'rs-template-builder' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'toggle'      => true,
				'selectors'   => [
					'{{WRAPPER}} .rstb-site-logo, {{WRAPPER}} .rstb-site-logo a' => 'align-items: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'logo_img_position',
			[
				'label'       => esc_html__( 'Image Position', 'rs-template-builder' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'options'     => [
					''  => esc_html__( 'Start', 'rs-template-builder' ),
					'2' => esc_html__( 'End', 'rs-template-builder' ),
				],
				'default'     => '',
				'selectors'   => [
					'{{WRAPPER}} .rstb-site-logo img' => 'order: {{VALUE}};',
				],
				'condition'   => [
					'logo_type' => 'text_image'
				]
			]
		);

		$this->add_responsive_control(
			'text_img_gap',
			[
				'label'      => esc_html__( 'Space Between', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 200,
					]
				],
				'selectors'  => [
					'{{WRAPPER}} .rstb-site-logo, {{WRAPPER}} .rstb-site-logo a' => 'gap: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'logo_type' => 'text_image'
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_text',
			[
				'label' => esc_html__( 'Text Logo', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-site-logo' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'text_typography',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .rstb-site-logo',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_image',
			[
				'label' => esc_html__( 'Image Logo', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'img_width',
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
					'{{WRAPPER}} .rstb-site-logo img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'img_height',
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
					'{{WRAPPER}} .rstb-site-logo img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'img_max_width',
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
					'{{WRAPPER}} .rstb-site-logo img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'img_object_fit',
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
					'{{WRAPPER}} .rstb-site-logo img' => 'object-fit: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'img_object_position',
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
					'{{WRAPPER}} .rstb-site-logo img' => 'object-position: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render(): void {
		$settings = $this->get_settings_for_display();

		$url = '';

		if ( 'custom' === $settings[ 'url_type' ] && ! empty( $settings[ 'custom_url' ][ 'url' ] ) ) {
			$url = $settings[ 'custom_url' ][ 'url' ];
		} elseif ( 'default' === $settings[ 'url_type' ] ) {
			$url = home_url();
		}
		?>
        <div class="rstb-site-logo">
			<?php
			if ( ! empty( $url ) ) {
				echo '<a href="' . esc_url( $url ) . '">';
			}

			if ( 'text' !== $settings[ 'logo_type' ] && ! empty( $settings[ 'image_logo' ][ 'url' ] ) ) {
				echo '<img src="' . esc_url( $settings[ 'image_logo' ][ 'url' ] ) . '" alt="' . esc_attr( get_bloginfo() ) . '">';
			}

			if ( 'image' !== $settings[ 'logo_type' ] && ! empty( $settings[ 'text_logo' ] ) ) {
				echo esc_html( $settings[ 'text_logo' ] );
			}

			if ( ! empty( $url ) ) {
				echo '</a>';
			}
			?>
        </div>
		<?php
	}
}