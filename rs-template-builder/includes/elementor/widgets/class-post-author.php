<?php

namespace RsTemplateBuilder\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Utils;
use Elementor\Widget_Base;

class Post_Author extends Widget_Base {

	public function get_name(): string {
		return 'rstb-post-author';
	}

	public function get_title(): string {
		return esc_html__( 'Post Author', 'rs-template-builder' );
	}

	public function get_icon(): string {
		return 'eicon-person rstb-branding-icon';
	}

	public function get_categories(): array {
		return [ 'rstb_elements' ];
	}

	public function get_keywords(): array {
		return [ 'rs template builder', 'rstheme', 'author', 'post', 'details', 'rs-template-builder' ];
	}

	protected function is_dynamic_content(): bool {
		return true;
	}

	public function has_widget_inner_wrapper(): bool {
		return ! Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls(): void {
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'General', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_name',
			[
				'label'        => esc_html__( 'Show Name', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'No', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'name_tag',
			[
				'label'     => esc_html__( 'Name Tag', 'rs-template-builder' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
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
				'default'   => 'h4',
				'condition' => [
					'show_name' => 'yes'
				]
			]
		);

		$this->add_control(
			'enable_link',
			[
				'label'        => esc_html__( 'Enable Link', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'No', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					'show_name' => 'yes'
				]
			]
		);

		$this->add_control(
			'show_biography',
			[
				'label'        => esc_html__( 'Show Biography', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'No', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'show_avatar',
			[
				'label'        => esc_html__( 'Show Avatar', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'No', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'avatar_size',
			[
				'label'     => esc_html__( 'Avatar Size', 'rs-template-builder' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'default'   => 150,
				'condition' => [
					'show_avatar' => 'yes'
				]
			]
		);

		$this->add_control(
			'show_social_profiles',
			[
				'label'        => esc_html__( 'Show Social Profiles', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'No', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'widget_notice',
			[
				'type'       => Controls_Manager::ALERT,
				'alert_type' => 'info',
				'content'    => esc_html__( 'Display current post author information. Actual post author will display on the live page.', 'rs-template-builder' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_style',
			[
				'label' => esc_html__( 'Author Box', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'box_flex_dir',
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
					'{{WRAPPER}} .rstb-author-info-box' => 'flex-direction: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_flex_v_align',
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
					'{{WRAPPER}} .rstb-author-info-box' => 'align-items: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_flex_h_align',
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
					'{{WRAPPER}} .rstb-author-info-box' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_margin',
			[
				'label'      => esc_html__( 'Margin', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-author-info-box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-author-info-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'box_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .rstb-author-info-box',
			]
		);

		$this->add_responsive_control(
			'box_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-author-info-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'card_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .rstb-author-info-box',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_box_shadow',
				'selector' => '{{WRAPPER}} .rstb-author-info-box',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_name_bio_style',
			[
				'label' => esc_html__( 'Name & Biography', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,

			]
		);

		$this->add_control(
			'name_heading',
			[
				'label'     => esc_html__( 'Name', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [
					'show_name' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'name_margin',
			[
				'label'      => esc_html__( 'Margin', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .author-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'show_name' => 'yes'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'name_typo',
				'label'     => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector'  => '{{WRAPPER}} .author-name',
				'condition' => [
					'show_name' => 'yes'
				]
			]
		);

		$this->add_control(
			'name_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .author-name' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_name' => 'yes'
				]
			]
		);

		$this->add_control(
			'author_bio_heading',
			[
				'label'     => esc_html__( 'Biography', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_biography' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'bio_margin',
			[
				'label'      => esc_html__( 'Margin', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .author-bio' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'show_biography' => 'yes'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'bio_typo',
				'label'     => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector'  => '{{WRAPPER}} .author-bio',
				'condition' => [
					'show_biography' => 'yes'
				]
			]
		);

		$this->add_control(
			'bio_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .author-bio' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_biography' => 'yes'
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_social_icons_style',
			[
				'label'     => esc_html__( 'Social Icons', 'rs-template-builder' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_social_profiles' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'social_margin',
			[
				'label'      => esc_html__( 'Margin', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .author-social-profile' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_gap',
			[
				'label'      => esc_html__( 'Item Gap', 'rs-template-builder' ),
				'type'       => Controls_Manager::GAPS,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .author-social-profile' => 'row-gap: {{ROW}}{{UNIT}}; column-gap: {{COLUMN}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_width',
			[
				'label'      => esc_html__( 'Item Width', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .author-social-profile a' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'item_height',
			[
				'label'      => esc_html__( 'Item Height', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .author-social-profile a' => 'height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'item_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .author-social-profile a',
			]
		);

		$this->add_responsive_control(
			'item_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .author-social-profile a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'item_style_tabs' );

		$this->start_controls_tab(
			'item_normal_style',
			[
				'label' => esc_html__( 'Normal', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'item_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .author-social-profile a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'item_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .author-social-profile a' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'item_box_shadow',
				'selector' => '{{WRAPPER}} .author-social-profile a',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'item_hovers_style',
			[
				'label' => esc_html__( 'Hover', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'hover_item_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .author-social-profile a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hover_item_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .author-social-profile a:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'hover_item_box_shadow',
				'selector' => '{{WRAPPER}} .author-social-profile a:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_avatar_style',
			[
				'label'     => esc_html__( 'Avatar', 'rs-template-builder' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_avatar' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'avatar_margin',
			[
				'label'      => esc_html__( 'Margin', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .author-avatar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'avatar_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .author-avatar img',
			]
		);

		$this->add_responsive_control(
			'avatar_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .author-avatar img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'avatar_box_shadow',
				'selector' => '{{WRAPPER}} .author-avatar img',
			]
		);

		$this->end_controls_section();
	}

	protected function render(): void {
		if ( is_author() ) {
			$author_id = get_queried_object_id();
		} else {
			$author_id = get_post_field( 'post_author', get_the_ID() );
		}

		if ( Plugin::$instance->editor->is_edit_mode() ) {
			$author_id = get_current_user_id();
		}

		$settings        = $this->get_settings_for_display();
		$author_name     = get_the_author_meta( 'display_name', $author_id );
		$user_posts      = get_author_posts_url( $author_id );
		$author_bio      = get_the_author_meta( 'description', $author_id );
		$avatar_size     = $settings[ 'avatar_size' ] ?? 150;
		$author_gravatar = get_avatar( $author_id, $avatar_size );
		$social_profiles = get_the_author_meta( '_rstb_social_profiles', $author_id );

		$rstb_social_icons = [
			'facebook'  => 'ri-facebook-fill',
			'twitter'   => 'ri-twitter-x-fill',
			'instagram' => 'ri-instagram-line',
			'linkedin'  => 'ri-linkedin-fill',
			'youtube'   => 'ri-youtube-line',
			'tiktok'    => 'ri-tiktok-fill',
			'pinterest' => 'ri-pinterest-line',
			'github'    => 'ri-github-line',
			'website'   => 'ri-global-line',
			'whatsapp'  => 'ri-whatsapp-line',
			'telegram'  => 'ri-telegram-2-fill',
		];
		?>
        <div class="rstb-author-info-box">
			<?php if ( 'yes' === $settings[ 'show_avatar' ] ) : ?>
                <div class="author-avatar">
					<?php echo wp_kses_post( $author_gravatar ); ?>
                </div>
			<?php endif; ?>
            <div class="author-desc">
				<?php
				if ( 'yes' === $settings[ 'show_name' ] ) {
					if ( 'yes' === $settings[ 'enable_link' ] ) {
						printf( '<%1$s class="author-name"><a href="%2$s">%3$s</a></%1$s>',
							Utils::validate_html_tag( $settings[ 'name_tag' ] ),
							esc_url( $user_posts ),
							esc_html( $author_name ),
						);
					} else {
						printf( '<%1$s class="author-name">%2$s</%1$s>',
							Utils::validate_html_tag( $settings[ 'name_tag' ] ),
							esc_html( $author_name ),
						);
					}
				}
				if ( 'yes' === $settings[ 'show_biography' ] ) {
					printf( '<p class="author-bio">%1$s</p>',
						wp_kses_post( $author_bio ),
					);
				}

				if ( ! empty( $social_profiles ) && is_array( $social_profiles ) && 'yes' === $settings[ 'show_social_profiles' ] ) : ?>
                    <div class="author-social-profile">
						<?php foreach ( $social_profiles as $key => $profile ) :
							if ( ! empty( $profile ) ) :?>
                                <a href="<?php echo esc_url( $profile ); ?>" target="_blank" rel="noopener">
                                    <i class="<?php echo esc_attr( $rstb_social_icons[ $key ] ); ?>"></i>
                                </a>
							<?php endif;
						endforeach; ?>
                    </div>
				<?php endif; ?>
            </div>
        </div>
		<?php
	}
}