<?php

namespace RsTemplateBuilder\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Utils;
use Elementor\Widget_Base;

class Copyright extends Widget_Base {

	public function get_name(): string {
		return 'rstb-copyright';
	}

	public function get_title(): string {
		return esc_html__( 'Copyright', 'rs-template-builder' );
	}

	public function get_icon(): string {
		return 'eicon-info rstb-branding-icon';
	}

	public function get_categories(): array {
		return [ 'rstb_elements' ];
	}

	public function get_keywords(): array {
		return [ 'rs template builder', 'rstheme', 'header', 'footer', 'copyright', 'rs-template-builder' ];
	}

	protected function is_dynamic_content(): bool {
		return false;
	}

	public function has_widget_inner_wrapper(): bool {
		return ! Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls(): void {
		// ==== Content ====
		$this->start_controls_section(
			'section_copyright',
			[
				'label' => esc_html__( 'Copyright', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'copyright_text',
			[
				'label'       => __( 'Copyright Text', 'rs-template-builder' ),
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => false,
				'default'     => __( 'Copyright © [rstb_current_year] [rstb_site_title]', 'rs-template-builder' ),
				'description' => sprintf(
					'%1$s %2$s %3$s %4$s',
					esc_html__( 'You can use placeholders:', 'rs-template-builder' ),
					'<br><code>[rstb_current_year]</code> → ' . esc_html__( 'displays the current year', 'rs-template-builder' ),
					'<br><code>[rstb_site_title]</code> → ' . esc_html__( 'displays your site title', 'rs-template-builder' ),
					''
				),
			]
		);

		$this->add_control(
			'html_tag',
			[
				'label'   => esc_html__( 'HTML Tag', 'rs-template-builder' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'p'    => 'p',
					'span' => 'span',
					'div'  => 'div',
				],
				'default' => 'p',
			]
		);

		$this->end_controls_section();

		// ==== Style ====
		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Style', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'text_align',
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
				'toggle'    => false,
				'selectors' => [
					'{{WRAPPER}} .rstb-copyright' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .rstb-copyright',
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-copyright' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'border',
				'selector' => '{{WRAPPER}} .rstb-copyright',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-copyright' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => esc_html__( 'Text Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-copyright' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'link_color',
			[
				'label'     => esc_html__( 'Link Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-copyright a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'link_hover_color',
			[
				'label'     => esc_html__( 'Link Color(Hover)', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-copyright a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .rstb-copyright',
			]
		);

		$this->end_controls_section();
	}


	protected function render(): void {
		$settings = $this->get_settings_for_display();
		$tag      = ! empty( $settings[ 'html_tag' ] ) ? $settings[ 'html_tag' ] : 'p';
		$text     = ! empty( $settings[ 'copyright_text' ] ) ? $settings[ 'copyright_text' ] : '';

		$replacements = [
			'[rstb_current_year]' => gmdate( 'Y' ),
			'[rstb_site_title]'   => get_bloginfo( 'name' ),
		];
		$text         = strtr( $text, $replacements );

		$this->add_render_attribute( 'wrapper', 'class', 'rstb-copyright' );

		printf(
			'<%1$s %2$s>%3$s</%1$s>',
			esc_attr( $tag ),
			$this->get_render_attribute_string( 'wrapper' ),
			wp_kses_post( $text )
		);
	}
}