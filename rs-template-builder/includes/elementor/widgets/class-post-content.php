<?php

namespace RsTemplateBuilder\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;

class Post_Content extends Widget_Base {

	public function get_name(): string {
		return 'rstb-post-content';
	}

	public function get_title(): string {
		return esc_html__( 'Post Content', 'rs-template-builder' );
	}

	public function get_icon(): string {
		return 'eicon-post-content rstb-branding-icon';
	}

	public function get_categories(): array {
		return [ 'rstb_elements' ];
	}

	public function get_keywords(): array {
		return [ 'rs template builder', 'rstheme', 'header', 'footer', 'content', 'post', 'rs-template-builder' ];
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
				'label' => esc_html__( 'Post Content', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => esc_html__( 'Alignment', 'rs-template-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'    => [
						'title' => esc_html__( 'Left', 'rs-template-builder' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => esc_html__( 'Center', 'rs-template-builder' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'   => [
						'title' => esc_html__( 'Right', 'rs-template-builder' ),
						'icon'  => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justify', 'rs-template-builder' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'editor_notice',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => '<strong>Note:</strong> The content you see in the editor is only for design preview. Actual post content will display on the live page.',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Post Content', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_style_heading',
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
					'{{WRAPPER}} h1' => 'color: {{VALUE}};',
					'{{WRAPPER}} h2' => 'color: {{VALUE}};',
					'{{WRAPPER}} h3' => 'color: {{VALUE}};',
					'{{WRAPPER}} h4' => 'color: {{VALUE}};',
					'{{WRAPPER}} h5' => 'color: {{VALUE}};',
					'{{WRAPPER}} h6' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_typography_type',
			[
				'label'        => esc_html__( 'Individual Typography?', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'No', 'rs-template-builder' ),
				'return_value' => 'individual',
				'default'      => 'global',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_typography',
				'label'     => esc_html__( 'Global Typography', 'rs-template-builder' ),
				'selector'  => '{{WRAPPER}} h1, {{WRAPPER}} h2, {{WRAPPER}} h3, {{WRAPPER}} h4, {{WRAPPER}} h5, {{WRAPPER}} h6',
				'condition' => [
					'title_typography_type!' => 'individual'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_typography_h1',
				'label'     => esc_html__( 'HTML Tag H1', 'rs-template-builder' ),
				'selector'  => '{{WRAPPER}} h1',
				'condition' => [
					'title_typography_type' => 'individual'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_typography_h2',
				'label'     => esc_html__( 'HTML Tag H2', 'rs-template-builder' ),
				'selector'  => '{{WRAPPER}} h2',
				'condition' => [
					'title_typography_type' => 'individual'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_typography_h3',
				'label'     => esc_html__( 'HTML Tag H3', 'rs-template-builder' ),
				'selector'  => '{{WRAPPER}} h3',
				'condition' => [
					'title_typography_type' => 'individual'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_typography_h4',
				'label'     => esc_html__( 'HTML Tag H4', 'rs-template-builder' ),
				'selector'  => '{{WRAPPER}} h4',
				'condition' => [
					'title_typography_type' => 'individual'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_typography_h5',
				'label'     => esc_html__( 'HTML Tag H5', 'rs-template-builder' ),
				'selector'  => '{{WRAPPER}} h5',
				'condition' => [
					'title_typography_type' => 'individual'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_typography_h6',
				'label'     => esc_html__( 'HTML Tag H6', 'rs-template-builder' ),
				'selector'  => '{{WRAPPER}} h6',
				'condition' => [
					'title_typography_type' => 'individual'
				]
			]
		);

		$this->add_control(
			'content_style_heading',
			[
				'label'     => esc_html__( 'Content', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);

		$this->add_control(
			'content_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'content_link_color',
			[
				'label'     => esc_html__( 'Link Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}}',
			]
		);

		$this->end_controls_section();
	}

	protected function render(): void {
		$current_post_type = get_post_type();

		if ( Plugin::$instance->editor->is_edit_mode() || $current_post_type === 'rstb_template' ) {
			?>
            <div class="rstb-post-content-dummy">
                <h1>Sample Post Title (H1)</h1>
                <h2>Subheading Example (H2)</h2>
                <h3>Section Heading (H3)</h3>
                <h4>Subsection Title (H4)</h4>
                <h5>Minor Heading (H5)</h5>
                <h6>Caption Heading (H6)</h6>

                <p> This is a sample paragraph designed to give you an idea of how your content will look once it's published. Please note, the final content will be displayed live on the page, giving you a full view of how it will appear to visitors. Stay tuned for the actual content once it's up and running! </p>

                <p><strong>Note:</strong> This is placeholder content for design preview only. Actual post content will appear on the live site.</p>
            </div>
			<?php
		} else {
			the_content();
		}
	}
}