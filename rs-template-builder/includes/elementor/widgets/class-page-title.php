<?php

namespace RsTemplateBuilder\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Utils;
use Elementor\Widget_Base;

class Page_Title extends Widget_Base {

	public function get_name(): string {
		return 'rstb-page-title';
	}

	public function get_title(): string {
		return esc_html__( 'Page Title', 'rs-template-builder' );
	}

	public function get_icon(): string {
		return 'eicon-archive-title rstb-branding-icon';
	}

	public function get_categories(): array {
		return [ 'rstb_elements' ];
	}

	public function get_keywords(): array {
		return [ 'rs template builder', 'rstheme', 'header', 'footer', 'page', 'title', 'rs-template-builder' ];
	}

	protected function is_dynamic_content(): bool {
		return true;
	}

	public function has_widget_inner_wrapper(): bool {
		return ! Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls(): void {
		$this->start_controls_section(
			'section_page_title_content',
			[
				'label' => esc_html__( 'General', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title_type',
			[
				'label'       => esc_html__( 'Page Title', 'rs-template-builder' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'options'     => [
					'default' => esc_html__( 'Default', 'rs-template-builder' ),
					'custom'  => esc_html__( 'Custom', 'rs-template-builder' ),
				],
				'default'     => 'default',
			]
		);

		$this->add_control(
			'custom_title',
			[
				'label'       => esc_html__( 'Custom Title', 'rs-template-builder' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'Custom Page Title', 'rs-template-builder' ),
				'placeholder' => esc_html__( 'Enter custom page title', 'rs-template-builder' ),
				'condition'   => [
					'title_type' => 'custom'
				]
			]
		);

		$this->add_control(
			'show_title_prefix',
			[
				'label'       => esc_html__( 'Show Archive Title Prefix', 'fancy-post-grid-pro' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'fancy-post-grid-pro' ),
				'label_off'    => esc_html__( 'No', 'fancy-post-grid-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);


		$this->add_control(
			'title_tag',
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
				'default' => 'h1',
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label'     => esc_html__( 'Text Alignment', 'rs-template-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'separator' => 'before',
				'options'   => [
					'start'  => [
						'title' => esc_html__( 'Left', 'rs-template-builder' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'rs-template-builder' ),
						'icon'  => 'eicon-text-align-center',
					],
					'end'    => [
						'title' => esc_html__( 'End', 'rs-template-builder' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .rstb-page-title' => 'text-align: {{Value}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_page_title_style',
			[
				'label' => esc_html__( 'Page Title', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-page-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .rstb-page-title',
			]
		);

		$this->end_controls_section();
	}

	protected function render(): void {
		$settings = $this->get_settings_for_display();

		if ( 'custom' === $settings[ 'title_type' ] ) :
			$this->add_render_attribute( 'custom_title', 'class', 'rstb-page-title' );
			$this->add_inline_editing_attributes( 'custom_title', 'none' );

			printf( '<%1$s %2$s>%3$s</%1$s>',
				Utils::validate_html_tag( $settings[ 'title_tag' ] ),
				$this->get_render_attribute_string( 'custom_title' ),
				wp_kses_post( $settings[ 'custom_title' ] ),
			);
		else :
			printf( '<%1$s class="rstb-page-title">%2$s</%1$s>',
				Utils::validate_html_tag( $settings[ 'title_tag' ] ),
				wp_kses_post( $this->get_current_page_title($settings["show_title_prefix"]), ),
			);
		endif;
	}

     private function get_current_page_title($show_title_prefix): string {
		if ( is_front_page() ) {
			$front_page_id = get_option( 'page_on_front' );
			if ( $front_page_id ) {
				return get_the_title( $front_page_id );
			} else {
				return get_bloginfo( 'name' );
			}
		} elseif ( is_home() ) {
			$page_for_posts = get_option( 'page_for_posts' );
			if ( $page_for_posts ) {
				return get_the_title( $page_for_posts );
			}

			return __( 'Latest Blogs', 'rs-template-builder' );
		} elseif ( is_singular() ) {
			return get_the_title();
		} elseif ( is_search() ) {
			/* translators: %s: Search query. */
			return sprintf( __( 'Search results for "%s"', 'rs-template-builder' ), get_search_query() );
		} elseif ( is_archive() ) {
			if($show_title_prefix == "yes") {
				return get_the_archive_title();
			 }else {
				if ( is_category() || is_tag() || is_tax() ) {
					 return single_term_title( '', false );
				   } elseif ( is_author() ) {
					return get_the_author();
				   } elseif ( is_year() ) {
					return get_the_date( 'Y' );
				   } elseif ( is_month() ) {
					return get_the_date( 'F Y' );
				   } elseif ( is_day() ) {
					return get_the_date();
				   } elseif ( is_post_type_archive() ) {
					return post_type_archive_title( '', false );
				   }
				   
				   return get_the_archive_title();
			}
		} elseif ( is_404() ) {
			return __( 'Page Not Found', 'rs-template-builder' );
		}

		return '';
	}
}
