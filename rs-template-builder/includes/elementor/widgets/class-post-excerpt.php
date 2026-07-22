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

class Post_Excerpt extends Widget_Base {

	public function get_name(): string {
		return 'rstb-post-excerpt';
	}

	public function get_title(): string {
		return esc_html__( 'Post Excerpt', 'rs-template-builder' );
	}

	public function get_icon(): string {
		return 'eicon-post-excerpt rstb-branding-icon';
	}

	public function get_categories(): array {
		return [ 'rstb_elements' ];
	}

	public function get_keywords(): array {
		return [ 'rs template builder', 'rstheme', 'header', 'footer', 'excerpt', 'post', 'rs-template-builder' ];
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
				'label' => esc_html__( 'Post Excerpt', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'excerpt_length',
			[
				'type'        => Controls_Manager::NUMBER,
				'label'       => esc_html__( 'Excerpt Length', 'rs-template-builder' ),
				'description' => esc_html__( 'Limit the number of words shown in the excerpt using wp_trim_words().', 'rs-template-builder' ),
				'min'         => 0,
				'default'     => 50
			]
		);

		$this->add_control(
			'excerpt_line',
			[
				'type'        => Controls_Manager::NUMBER,
				'label'       => esc_html__( 'Line clamp', 'rs-template-builder' ),
				'description' => esc_html__( 'Limit the number of visible lines using CSS line-clamp.', 'rs-template-builder' ),
				'min'         => 0,
				'selectors'   => [
					'{{WRAPPER}} p' => 'display: -webkit-box; -webkit-box-orient: vertical; overflow: hidden; line-clamp: {{VALUE}}; -webkit-line-clamp: {{VALUE}}',
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
				'default'      => 'no',
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
				],
				'separator' => 'before',
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
				'label' => esc_html__( 'Post Excerpt', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} p' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} p',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'read_more_style',
			[
				'label'     => esc_html__( 'Read More', 'rs-template-builder' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_read_more' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'read_more_margin',
			[
				'label'      => esc_html__( 'Margin', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .read-more-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'read_more_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .read-more-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'read_more_typo',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .read-more-btn',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'read_more_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .read-more-btn',
			]
		);

		$this->add_responsive_control(
			'read_more_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .read-more-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'read_more_btn_tabs' );

		$this->start_controls_tab(
			'read_more_normal_style',
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
					'{{WRAPPER}} .read-more-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'read_more_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .read-more-btn',
				'exclude'  => [ 'image' ]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'read_more_shadow',
				'selector' => '{{WRAPPER}} .read-more-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'read_more_hover_style',
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
					'{{WRAPPER}} .read-more-btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'hover_read_more_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .read-more-btn:hover',
				'exclude'  => [ 'image' ]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'hover_read_more_shadow',
				'selector' => '{{WRAPPER}} .read-more-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render(): void {
		$settings          = $this->get_settings_for_display();
		$current_post_type = get_post_type();

		if ( Plugin::$instance->editor->is_edit_mode() || $current_post_type === 'rstb_template' ) {
			$dummy_text = "This is a sample paragraph designed to give you an idea of how your content will look once it's published. Please note, the final content will be displayed live on the page, giving you a full view of how it will appear to visitors. Stay tuned for the actual content once it's up and running!";

			if ( ! empty( $settings[ 'excerpt_length' ] ) ) {
				$excerpt = wp_trim_words( $dummy_text, $settings[ 'excerpt_length' ] );
			} else {
				$excerpt = $dummy_text;
			}
			?>
            <div class="rstb-post-content-dummy">
                <p><?php echo wp_kses_post( $excerpt ) ?></p>
				<?php $this->read_more_button(); ?>
            </div>
			<?php
		} else {
			if ( ! empty( $settings[ 'excerpt_length' ] ) ) {
				$excerpt = wp_trim_words( get_the_excerpt(), $settings[ 'excerpt_length' ] );
			} else {
				$excerpt = get_the_excerpt();
			}

			echo wp_kses_post( $excerpt );

			$this->read_more_button();
		}
	}

	private function read_more_button() {
		$settings = $this->get_settings_for_display();

		if ( 'yes' !== $settings[ 'show_read_more' ] ) {
			return;
		}
		?>
        <a href="<?php echo esc_url( apply_filters( 'the_permalink', get_permalink() ) ); ?>" class="read-more-btn">
			<?php if ( ! empty( $settings[ 'read_more_text' ] ) ) {
				echo esc_html( $settings[ 'read_more_text' ] );
			} ?>
			<?php if ( ! empty( $settings[ 'read_more_icon' ][ 'value' ] ) ) : ?>
                <span class="read-more-icon">
                    <?php Icons_Manager::render_icon( $settings[ 'read_more_icon' ] ); ?>
                </span>
			<?php endif; ?>
        </a>
		<?php
	}
}