<?php

namespace RsTemplateBuilder\Helpers;

use Walker_Nav_Menu;

defined( 'ABSPATH' ) || exit;

class Nav_Walker extends Walker_Nav_Menu {

	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ): void {
		$menu_item = $data_object;

		$t      = isset( $args->item_spacing ) && 'discard' === $args->item_spacing ? '' : "\t";
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes   = empty( $menu_item->classes ) ? [] : (array) $menu_item->classes;
		$classes[] = 'menu-item-' . $menu_item->ID;

		$classes[] = $this->get_additional_classes( $menu_item );

		/** This filter is documented in wp-includes/class-walker-nav-menu.php */
		$args = apply_filters( 'nav_menu_item_args', $args, $menu_item, $depth );

		/** This filter is documented in wp-includes/class-walker-nav-menu.php */
		$class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $menu_item, $args, $depth ) );

		/** This filter is documented in wp-includes/class-walker-nav-menu.php */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $menu_item->ID, $menu_item, $args, $depth );

		$li_atts = [
			'id'    => ! empty( $id ) ? $id : '',
			'class' => ! empty( $class_names ) ? $class_names : ''
		];

		/** This filter is documented in wp-includes/class-walker-nav-menu.php */
		$li_atts       = apply_filters( 'nav_menu_item_attributes', $li_atts, $menu_item, $args, $depth );
		$li_attributes = $this->build_atts( $li_atts );

		$output .= $indent . '<li' . $li_attributes . '>';

		$atts       = $this->build_link_attributes( $menu_item, $args, $depth );
		$attributes = $this->build_atts( $atts );

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $menu_item->title, $menu_item->ID );

		/** This filter is documented in wp-includes/class-walker-nav-menu.php */
		$title = apply_filters( 'nav_menu_item_title', $title, $menu_item, $args, $depth );

		$item_output = $args->before;
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $args->link_before;
		$item_output .= '<span class="menu-item-text">' . $title . '</span>';
		$item_output .= $args->link_after;
		$item_output .= $this->get_submenu_indicator_html( $menu_item, $classes, $args );
		$item_output .= '</a>';
		$item_output .= $this->get_builder_mm_content( $menu_item );
		$item_output .= $args->after;

		/** This filter is documented in wp-includes/class-walker-nav-menu.php */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $menu_item, $depth, $args );
	}

	protected function get_additional_classes( $menu_item ): string {
		$additional_classes = '';

		if ( $menu_item->object === 'rstb_template' ) {
			$additional_classes .= 'menu-item-has-mega-menu';
		}

		return $additional_classes;
	}

	protected function build_link_attributes( $menu_item, $args, $depth ) {
		$atts = [
			'title'  => ! empty( $menu_item->attr_title ) ? $menu_item->attr_title : '',
			'target' => ! empty( $menu_item->target ) ? $menu_item->target : '',
			'rel'    => ! empty( $menu_item->xfn ) ? $menu_item->xfn : '',
			'href'   => ! empty( $menu_item->url ) ? $menu_item->url : '',
			'class'  => 'menu-item-link',
		];

		// Set Builder mega-menu URL
		if ( $menu_item->object === 'rstb_template' ) {
			$mm_url         = get_post_meta( $menu_item->ID, '_rstb_mm_url', true );
			$atts[ 'href' ] = $mm_url ?: '#';
		}

		/** This filter is documented in wp-includes/class-walker-nav-menu.php */
		return apply_filters( 'nav_menu_link_attributes', $atts, $menu_item, $args, $depth );
	}

	protected function get_submenu_indicator_html( $menu_item, $classes, $args = null ): string {
		if ( in_array( 'menu-item-has-children', $classes ) || $menu_item->object === 'rstb_template' ) {
			$icon = $args->submenu_icon ?? '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M11.9999 13.1714L16.9497 8.22168L18.3639 9.63589L11.9999 15.9999L5.63599 9.63589L7.0502 8.22168L11.9999 13.1714Z"></path></svg>';

			$icon .= $args->submenu_icon_two ?? '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M11.9999 10.8284L7.0502 15.7782L5.63599 14.364L11.9999 8L18.3639 14.364L16.9497 15.7782L11.9999 10.8284Z"></path></svg>';

			return '<span class="sub-menu-icon">' . $icon . '</span>';
		}

		return '';
	}

	protected static $rendering_mm_ids = [];

	protected function get_builder_mm_content( $menu_item ): string {
		if ( $menu_item->object !== 'rstb_template' ) {
			return '';
		}

		$settings      = get_post_meta( $menu_item->object_id, '_rstb_settings', true );
		$template_type = Utils::get_template_type( $menu_item->object_id );

		if ( 'mega_menu' !== $template_type ) {
			return '';
		}

		// Prevent infinite recursion when a mega menu embeds a nav menu that loops back to itself.
		if ( in_array( $menu_item->object_id, self::$rendering_mm_ids, true ) ) {
			return '';
		}

		self::$rendering_mm_ids[] = $menu_item->object_id;

		$menu_width   = $settings[ 'mm_width' ] ?? 'full';
		$custom_width = $settings[ 'custom_mm_width' ] ?? 600;

		$content = '<div class="mega-menu mega-menu-width-' . esc_attr( $menu_width ) . '"';
		if ( 'custom' === $menu_width && ! empty( $custom_width ) ) {
			$content .= ' style="width: ' . esc_attr( $custom_width ) . 'px; margin-left: -' . esc_attr( $custom_width / 2 ) . 'px"';
		}
		$content .= '>';
		$content .= Utils::get_elementor_content( $menu_item->object_id );
		$content .= '</div>';

		array_pop( self::$rendering_mm_ids );

		return $content;
	}
}
