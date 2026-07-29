<?php

namespace RsTemplateBuilder\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Utils;
use Elementor\Widget_Base;

class Post_Comments extends Widget_Base {

	public function get_name(): string {
		return 'rstb-post-comments';
	}

	public function get_title(): string {
		return esc_html__( 'Post Comments', 'rs-template-builder' );
	}

	public function get_icon(): string {
		return 'eicon-comments rstb-branding-icon';
	}

	public function get_categories(): array {
		return [ 'rstb_elements' ];
	}

	public function get_keywords(): array {
		return [ 'rs template builder', 'rstheme', 'header', 'footer', 'comments', 'post', 'rs-template-builder' ];
	}

	protected function is_dynamic_content(): bool {
		return true;
	}

	public function has_widget_inner_wrapper(): bool {
		return ! Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls(): void {
		$this->start_controls_section(
			'section_contents',
			[
				'label' => esc_html__( 'Post Comments', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'comments_notice',
			[
				'type'       => Controls_Manager::ALERT,
				'alert_type' => 'info',
				'content'    => esc_html__( 'Display current post comment template', 'rs-template-builder' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_comments_style',
			[
				'label' => esc_html__( 'Comments Form', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'form_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .comment-respond' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'form_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .comment-respond',
			]
		);

		$this->add_responsive_control(
			'form_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .comment-respond' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'form_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .comment-respond' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'form_title_heading',
			[
				'label'     => esc_html__( 'Form Title', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'form_title_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .comment-respond .comment-reply-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'form_title_typo',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .comment-respond .comment-reply-title',
			]
		);

		$this->add_control(
			'form_desc_heading',
			[
				'label'     => esc_html__( 'Form Desc', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'form_desc_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .comment-respond .comment-notes, {{WRAPPER}} .comment-respond label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'form_desc_typo',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .comment-respond .comment-notes, {{WRAPPER}} .comment-respond label',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_input_submit',
			[
				'label' => esc_html__( 'Input & Submit', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'input_heading',
			[
				'label' => esc_html__( 'Inputs', 'rs-template-builder' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'input_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .comment-respond input:not([type=submit]), {{WRAPPER}} .comment-respond textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'input_typo',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .comment-respond input:not([type=submit]), {{WRAPPER}} .comment-respond textarea',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'input_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .comment-respond input:not([type=submit]), {{WRAPPER}} .comment-respond textarea',
				'exclude'  => [ 'color' ]
			]
		);

		$this->add_responsive_control(
			'input_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .comment-respond input:not([type=submit]), {{WRAPPER}} .comment-respond textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'input_style_tabs' );

		$this->start_controls_tab(
			'input_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'input_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .comment-respond input:not([type=submit]), {{WRAPPER}} .comment-respond textarea' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'input_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .comment-respond input:not([type=submit]), {{WRAPPER}} .comment-respond textarea' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'input_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .comment-respond input:not([type=submit]), {{WRAPPER}} .comment-respond textarea' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'input_focus_tab',
			[
				'label' => esc_html__( 'Focus', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'input_color_focus',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .comment-respond input:not([type=submit]):focus, {{WRAPPER}} .comment-respond textarea:focus' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'input_bg_color_hover',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .comment-respond input:not([type=submit]):focus, {{WRAPPER}} .comment-respond textarea:focus' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'input_border_color_focus',
			[
				'label'     => esc_html__( 'Border Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .comment-respond input:not([type=submit]):focus, {{WRAPPER}} .comment-respond textarea:focus' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'submit_button_heading',
			[
				'label'     => esc_html__( 'Submit Button', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'submit_padding',
			[
				'label'      => esc_html__( 'Padding', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .comment-respond input[type=submit]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'submit_typo',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .comment-respond input[type=submit]',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'submit_border',
				'label'    => esc_html__( 'Border', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .comment-respond input[type=submit]',
			]
		);

		$this->add_responsive_control(
			'submit_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'rs-template-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .comment-respond input[type=submit]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'submit_button_style' );

		$this->start_controls_tab(
			'submit_normal_style',
			[
				'label' => esc_html__( 'Normal', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'submit_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .comment-respond input[type=submit]' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submit_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .comment-respond input[type=submit]' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'submit_hover_style',
			[
				'label' => esc_html__( 'Hover', 'rs-template-builder' ),
			]
		);

		$this->add_control(
			'submit_color_hover',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .comment-respond input[type=submit]:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submit_bg_color_hover',
			[
				'label'     => esc_html__( 'Background Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .comment-respond input[type=submit]:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_comment_list',
			[
				'label' => esc_html__( 'Comment List', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'comment_list_title',
			[
				'label' => esc_html__( 'List Title', 'rs-template-builder' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'list_title_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .comments-area .comments-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'list_title_typo',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .comments-area .comments-title',
			]
		);

		$this->add_control(
			'list_name_heading',
			[
				'label'     => esc_html__( 'Name', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'list_name_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .comment-body .comment-content .name' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'list_name_typo',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .comment-body .comment-content .name',
			]
		);

		$this->add_control(
			'list_body_heading',
			[
				'label'     => esc_html__( 'Body', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'list_body_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .comment-body .comment-content .comment-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} .comment-content .name .date'                 => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'list_body_typo',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .comment-body .comment-content .comment-text, {{WRAPPER}} .comment-content .name .date',
			]
		);

		$this->add_control(
			'list_reply_heading',
			[
				'label'     => esc_html__( 'Reply', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'list_reply_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .comment-body .comment-content .comment-reply-link' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'list_reply_typo',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .comment-body .comment-content .comment-reply-link',
			]
		);

		$this->end_controls_section();
	}

	protected function render(): void {
		$current_post_type = get_post_type();

		if ( Plugin::$instance->editor->is_edit_mode() || $current_post_type === 'rstb_template' ) {
			?>
            <div class="comments-area">
                <h3 class="comments-title"> Comments (02) </h3>
                <ul class="comment-list">
                    <li class="comment">
                        <div id="comment-9" class="comment-body">
                            <div class="comment-avatar">
                                <img src="<?php echo esc_url( Utils::get_placeholder_image_src() ) ?>"
                                     class="avatar avatar-100 photo" style="width: 100px; height: 100px;">
                            </div>
                            <div class="comment-content">
                                <h6 class="name">
                                    Jane Doe <span class="date">March 11, 2013</span>
                                </h6>
                                <div class="comment-text">
                                    This is placeholder content for design preview only. Actual content will appear on the live post.
                                </div>
                                <a class="comment-reply-link" href="#">Reply<i class="ri-reply-line"></i></a>
                            </div>
                        </div>
                    </li>
                    <li class="comment">
                        <div id="comment-9" class="comment-body">
                            <div class="comment-avatar">
                                <img src="<?php echo esc_url( Utils::get_placeholder_image_src() ) ?>"
                                     class="avatar avatar-100 photo" style="width: 100px; height: 100px;">
                            </div>
                            <div class="comment-content">
                                <h6 class="name">
                                    ThemeDemos <span class="date">March 25, 2014</span>
                                </h6>
                                <div class="comment-text">
                                    This is placeholder content for design preview only. Actual content will appear on the live post.
                                </div>
                                <a class="comment-reply-link" href="#">Reply<i class="ri-reply-line"></i></a>
                            </div>
                        </div>
                    </li>
                </ul>
                <div id="respond" class="comment-respond">
                    <h4 id="reply-title" class="comment-reply-title"><span>Leave a Comment </span></h4>
                    <form action="#" method="post" id="commentform" class="comment-form">
                        <p class="comment-notes">
                            <span id="email-notes">Your email address will not be published.</span>
                            <span class="required-field-message">Required fields are marked <span class="required">*</span></span>
                        </p>
                        <p class="comment-form-comment"><textarea id="comment" name="comment" aria-required="true" placeholder="Write Comment" required=""></textarea></p>
                        <p class="comment-form-author"><input id="author" name="author" type="text" placeholder="Full Name *" required=""></p>
                        <p class="comment-form-email"><input id="email" name="email" type="email" placeholder="Email *" required=""></p>
                        <p class="comment-form-url"><input id="url" name="url" type="url" placeholder="Website"></p>
                        <p class="comment-form-cookies-consent">
                            <input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes">
                            <label for="wp-comment-cookies-consent">Save my name, email, and website in this browser for the next time I comment.</label>
                        </p>
                        <p class="form-submit">
                            <input name="submit" type="submit" id="submit" class="submit-btn" value="Post Comment">
                        </p>
                    </form>
                </div>
            </div>
			<?php
		} else {
			comments_template();
		}
	}
}