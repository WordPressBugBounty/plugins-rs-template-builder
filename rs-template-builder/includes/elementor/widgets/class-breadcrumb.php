<?php

namespace RsTemplateBuilder\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

class Breadcrumb extends Widget_Base {

	public function get_name(): string {
		return 'rstb-breadcrumb';
	}

	public function get_title(): string {
		return esc_html__( 'Breadcrumb NavXT', 'rs-template-builder' );
	}

	public function get_icon(): string {
		return 'eicon-navigation-horizontal rstb-branding-icon';
	}

	public function get_categories(): array {
		return [ 'rstb_elements' ];
	}

	public function get_keywords(): array {
		return [ 'rs template builder', 'rstheme', 'header', 'footer', 'Breadcrumb NavXT', 'rs-template-builder' ];
	}

	protected function is_dynamic_content(): bool {
		return true;
	}

	public function has_widget_inner_wrapper(): bool {
		return ! Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls(): void {
		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'Breadcrumb', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'separator_icon',
			[
				'label'       => esc_html__( 'Separator Icon', 'rs-template-builder' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => [
					'value'   => 'fas fa-angle-double-right',
					'library' => 'fa-solid',
				],
				'label_block' => false,
				'skin'        => 'inline',
				'description' => esc_html__( 'If no icon is selected, the widget will use the default icon set in Breadcrumb NavXT.', 'rs-template-builder' )
			]
		);

		$this->add_control(
			'show_home_icon',
			[
				'label'        => esc_html__( 'Show Home Icon?', 'rs-template-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-template-builder' ),
				'label_off'    => esc_html__( 'No', 'rs-template-builder' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'home_icon',
			[
				'label'       => esc_html__( 'Home Icon', 'rs-template-builder' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => [
					'value'   => 'fas fa-home',
					'library' => 'fa-solid',
				],
				'condition'   => [
					'show_home_icon' => 'yes',
				],
				'label_block' => false,
				'skin'        => 'inline'
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_breadcrumb_style',
			[
				'label' => esc_html__( 'Wrapper', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'breadcrumb_alignment',
			[
				'label'       => esc_html__( 'Alignment', 'rs-template-builder' ),
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
				'selectors'   => [
					'{{WRAPPER}} .rstb-breadcrumb' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_gap',
			[
				'label'      => esc_html__( 'Gaps', 'rs-template-builder' ),
				'type'       => Controls_Manager::GAPS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rstb-breadcrumb' => 'row-gap: {{ROW}}{{UNIT}}; column-gap: {{COLUMN}}{{UNIT}};',
				],
				'condition'  => [
					'layout' => 'grid',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_color_typo',
			[
				'label' => esc_html__( 'Color & Typography', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-breadcrumb, {{WRAPPER}} .rstb-breadcrumb a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'text_hover_color',
			[
				'label'     => esc_html__( 'Link Hover', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-breadcrumb a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'text_current_color',
			[
				'label'     => esc_html__( 'Current Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rstb-breadcrumb .current-item' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'text_typo',
				'label'    => esc_html__( 'Typography', 'rs-template-builder' ),
				'selector' => '{{WRAPPER}} .rstb-breadcrumb',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => esc_html__( 'Icons', 'rs-template-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'separator_heading',
			[
				'label' => esc_html__( 'Separator', 'rs-template-builder' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'separator_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .item-separator' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'separator_size',
			[
				'label'      => esc_html__( 'Font Size', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .item-separator' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'separator_vertical_p',
			[
				'label'      => esc_html__( 'Vertical Position', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .item-separator' => 'top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'home_heading',
			[
				'label'     => esc_html__( 'Home Icon', 'rs-template-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_home_icon' => 'yes'
				]
			]
		);

		$this->add_control(
			'home_icon_color',
			[
				'label'     => esc_html__( 'Color', 'rs-template-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .home-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'home_icon_size',
			[
				'label'      => esc_html__( 'Font Size', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .home-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'home_icon_vertical_p',
			[
				'label'      => esc_html__( 'Vertical Position', 'rs-template-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .home-icon' => 'top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	public function render(): void {
		$settings = $this->get_settings_for_display();
		?>
        <div class="rstb-breadcrumb">
			<?php
			if ( 'yes' === $settings[ 'show_home_icon' ] && ! empty( $settings[ 'home_icon' ][ 'value' ] ) ) {
				echo '<span class="home-icon">' . Icons_Manager::try_get_icon_html( $settings[ 'home_icon' ] ) . '</span>';
			}

			$separator_callback = null;

			if ( ! empty( $settings[ 'separator_icon' ][ 'value' ] ) ) {
				$separator_callback = function ( $separator, $position, $last_position, $depth ) use ( $settings ) {
					if ( $position >= $last_position ) {
						return '';
					}
					return '<span class="item-separator">' . Icons_Manager::try_get_icon_html( $settings[ 'separator_icon' ] ) . '</span>';
				};
				add_filter( 'bcn_display_separator', $separator_callback, 10, 4 );
			}

			bcn_display();
			if ( $separator_callback ) {
				remove_filter( 'bcn_display_separator', $separator_callback, 10 );
			}
			?>
        </div>
		<?php
	}
}