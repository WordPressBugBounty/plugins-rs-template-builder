<?php

namespace RsTemplateBuilder\Elementor;

defined( 'ABSPATH' ) || exit;

final class Elementor_Addons {

	protected static ?self $instance = null;

	public static function instance(): self {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		add_action( 'elementor/elements/categories_registered', [ $this, 'init_categories' ], 12 );
		add_action( 'elementor/widgets/register', [ $this, 'init_widgets' ] );

		add_action( 'elementor/editor/after_enqueue_styles', function () {
			wp_enqueue_style( 'rstb-branding', RSTB_ASSETS . '/css/rstb-branding.min.css', [], RSTB_VERSION );
		} );

		add_filter( 'woocommerce_add_to_cart_fragments', function ( $fragments ) {
			if ( ! is_a( WC()->cart, 'WC_Cart' ) ) {
				return $fragments;
			}

			$fragments[ '.rstb-cart-count' ]    = '<span class="rstb-cart-count">' . esc_html( WC()->cart->get_cart_contents_count() ) . '</span>';
			$fragments[ '.rstb-cart-subtotal' ] = '<span class="rstb-cart-subtotal">' . wp_kses_post( WC()->cart->get_cart_subtotal() ) . '</span>';

			ob_start();
			woocommerce_mini_cart();
			$fragments[ '.rstb-cart-content' ] = '<div class="rstb-cart-content">' . ob_get_clean() . '</div>';

			return $fragments;
		} );

		$this->include_extensions();

		// Allowed SVG to Breadcrumb NavXT
		add_filter( 'bcn_allowed_html', function ( $allowed_html ) {
			$allowed_html[ 'svg' ]      = [
				'class'        => true,
				'aria-hidden'  => true,
				'role'         => true,
				'xmlns'        => true,
				'xmlns:xlink'  => true,
				'width'        => true,
				'height'       => true,
				'viewbox'      => true,
				'fill'         => true,
				'stroke'       => true,
				'stroke-width' => true,
			];
			$allowed_html[ 'path' ]     = [ 'd' => true, 'fill' => true, 'stroke' => true ];
			$allowed_html[ 'circle' ]   = [ 'cx' => true, 'cy' => true, 'r' => true, 'fill' => true, 'stroke' => true ];
			$allowed_html[ 'ellipse' ]  = [ 'cx' => true, 'cy' => true, 'rx' => true, 'ry' => true, 'fill' => true, 'stroke' => true ];
			$allowed_html[ 'rect' ]     = [ 'x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true, 'ry' => true, 'fill' => true, 'stroke' => true ];
			$allowed_html[ 'line' ]     = [ 'x1' => true, 'y1' => true, 'x2' => true, 'y2' => true, 'stroke' => true ];
			$allowed_html[ 'polyline' ] = [ 'points' => true, 'fill' => true, 'stroke' => true ];
			$allowed_html[ 'polygon' ]  = [ 'points' => true, 'fill' => true, 'stroke' => true ];
			$allowed_html[ 'g' ]        = [ 'fill' => true, 'stroke' => true ];
			$allowed_html[ 'defs' ]     = [];
			$allowed_html[ 'use' ]      = [ 'href' => true, 'xlink:href' => true ];

			return $allowed_html;
		} );
	}

	public function init_categories( $elements_manager ): void {
		$elements_manager->add_category(
			'rstb_elements',
			[
				'title' => esc_html__( 'RS Template Builder', 'rs-template-builder' ),
				'icon'  => 'fa fa-smile-o',
			]
		);
	}

	public function init_widgets( $widgets_manager ): void {
		include_once RSTB_ELEMENTOR . 'widgets/class-site-logo.php';
		include_once RSTB_ELEMENTOR . 'widgets/class-mini-search.php';
		include_once RSTB_ELEMENTOR . 'widgets/class-nav-menu.php';
		include_once RSTB_ELEMENTOR . 'widgets/class-offcanvas.php';
		include_once RSTB_ELEMENTOR . 'widgets/class-page-title.php';
		include_once RSTB_ELEMENTOR . 'widgets/class-archive-posts.php';
		include_once RSTB_ELEMENTOR . 'widgets/class-post-meta.php';
		include_once RSTB_ELEMENTOR . 'widgets/class-post-excerpt.php';
		include_once RSTB_ELEMENTOR . 'widgets/class-post-content.php';
		include_once RSTB_ELEMENTOR . 'widgets/class-post-featured-img.php';
		include_once RSTB_ELEMENTOR . 'widgets/class-post-comments.php';
		include_once RSTB_ELEMENTOR . 'widgets/class-post-author.php';
		include_once RSTB_ELEMENTOR . 'widgets/class-post-navigation.php';
		include_once RSTB_ELEMENTOR . 'widgets/class-copyright.php';

		$widgets_manager->register( new Widgets\Site_Logo() );
		$widgets_manager->register( new Widgets\Mini_Search() );
		$widgets_manager->register( new Widgets\Nav_Menu() );
		$widgets_manager->register( new Widgets\Offcanvas() );
		$widgets_manager->register( new Widgets\Page_Title() );
		$widgets_manager->register( new Widgets\Archive_Posts() );
		$widgets_manager->register( new Widgets\Post_Meta() );
		$widgets_manager->register( new Widgets\Post_Excerpt() );
		$widgets_manager->register( new Widgets\Post_Content() );
		$widgets_manager->register( new Widgets\Post_Featured_Img() );
		$widgets_manager->register( new Widgets\Post_Comments() );
		$widgets_manager->register( new Widgets\Post_Author() );
		$widgets_manager->register( new Widgets\Post_Navigation() );
		$widgets_manager->register( new Widgets\Copyright() );

		if ( class_exists( 'bcn_breadcrumb_trail' ) ) {
			include_once RSTB_ELEMENTOR . 'widgets/class-breadcrumb.php';
			$widgets_manager->register( new Widgets\Breadcrumb() );
		}

		if ( class_exists( 'woocommerce' ) ) {
			include_once RSTB_ELEMENTOR . 'widgets/class-mini-cart.php';
			$widgets_manager->register( new Widgets\Mini_Cart() );
		}
	}

	public function include_extensions(): void {
		include_once RSTB_ELEMENTOR . 'extensions/class-header-options.php';
		include_once RSTB_ELEMENTOR . 'extensions/class-popup-options.php';
	}
}

