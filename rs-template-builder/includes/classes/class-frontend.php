<?php

namespace RsTemplateBuilder\Classes;

use RsTemplateBuilder\Helpers\Utils;

defined( 'ABSPATH' ) || exit;

class Frontend {

	private static ?self $instance = null;

	private array $templates = [];

	private array $css_files_ids = [];

	private string $active_theme;

	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		add_action( 'wp', [ $this, 'init_templates' ], 8 );
		add_filter( 'wp_robots', [ $this, 'prevent_custom_url_indexing' ] );
	}

	public function init_templates(): void {
		// Fetch Templates
		$this->get_template_by_conditions();

		// Display Templates
		$this->detect_active_theme();
		$this->display_header();
		$this->display_footer();
		$this->display_page_title();
		$this->display_archive_single();
		$this->display_404_page();
		$this->display_popup();

		// Enqueue Assets
		$this->enqueue_frontend_styles();
	}

	private function get_template_by_conditions(): void {
		if ( empty( $this->templates ) ) {
			$all_templates = Template_Rule::instance()->get_template_by_conditions();

			foreach ( $all_templates as $location => $ids ) {
				if ( is_array( $ids ) && ! empty( $ids ) ) {
					if ( $location === 'popup' ) {
						$this->templates[ $location ] = array_reverse( $ids );
						$this->css_files_ids          = array_merge( $this->css_files_ids, $ids );
					} else {
						$this->templates[ $location ] = reset( $ids );
						$this->css_files_ids[]        = reset( $ids );
					}
				}
			}
		}
	}

	private function detect_active_theme(): void {
		$theme = wp_get_theme();

		if ( current_theme_supports( 'rstb-theme-support' ) ) {
			$this->active_theme = 'has-rstb-support';
		} else {
			$this->active_theme = $theme->get_stylesheet();
		}
	}

	public function display_header(): void {
		if ( ! $this->get_active_template( 'header' ) ) {
			return;
		}

		switch ( $this->active_theme ) {
			case 'astra':
				add_action( 'template_redirect', function () {
					remove_action( 'astra_header', 'astra_header_markup' );

					if ( class_exists( 'Astra_Builder_Helper' ) && \Astra_Builder_Helper::$is_header_footer_builder_active ) {
						remove_action( 'astra_header', [ \Astra_Builder_Header::get_instance(), 'header_builder_markup' ] );
					}
				} );
				add_action( 'astra_header', [ $this, 'render_header' ] );
				break;
			case 'blocksy':
				add_filter( 'blocksy:builder:header:enabled', '__return_false' );
				add_action( 'blocksy:header:before', [ $this, 'render_header' ] );
				break;
			case 'generatepress':
				add_action( 'template_redirect', function () {
					remove_action( 'generate_header', 'generate_construct_header' );
					remove_action( 'generate_after_header', 'generate_add_navigation_after_header', 5 );
				} );
				add_action( 'generate_header', [ $this, 'render_header' ] );
				break;
			case 'kadence':
				add_action( 'template_redirect', function () {
					remove_action( 'kadence_header', 'Kadence\header_markup' );
				} );
				add_action( 'kadence_header', [ $this, 'render_header' ] );
				break;
			case 'neve':
				add_filter( 'neve_filter_toggle_content_parts', function ( $enabled, $part ) {
					if ( $part === 'header' ) {
						return false;
					}

					return $enabled;
				}, 10, 2 );
				add_action( 'neve_before_header_wrapper_hook', [ $this, 'render_header' ] );
				break;
			case 'oceanwp':
				add_action( 'template_redirect', function () {
					remove_action( 'ocean_top_bar', 'oceanwp_top_bar_template' );
					remove_action( 'ocean_header', 'oceanwp_header_template' );
				} );
				add_action( 'ocean_header', [ $this, 'render_header' ] );
				break;
			case 'has-rstb-support' :
				add_filter( 'rstb_show_theme_header', '__return_false' );
				add_action( 'rstb_init_header', [ $this, 'render_header' ] );
				break;
			default:
				add_action( 'get_header', [ $this, 'get_replaced_header' ] );
				break;
		}
	}

	public function display_footer(): void {
		if ( ! $this->get_active_template( 'footer' ) ) {
			return;
		}

		switch ( $this->active_theme ) {
			case 'astra':
				add_action( 'template_redirect', function () {
					remove_action( 'astra_footer', 'astra_footer_markup' );

					if ( class_exists( 'Astra_Builder_Helper' ) && \Astra_Builder_Helper::$is_header_footer_builder_active ) {
						remove_action( 'astra_footer', [ \Astra_Builder_Footer::get_instance(), 'footer_markup' ] );
					}
				} );
				add_action( 'astra_footer', [ $this, 'render_footer' ] );
				break;
			case 'blocksy':
				add_filter( 'blocksy:builder:footer:enabled', '__return_false' );
				add_action( 'blocksy:footer:before', [ $this, 'render_footer' ] );
				break;
			case 'generatepress':
				add_action( 'template_redirect', function () {
					remove_action( 'generate_footer', 'generate_construct_footer' );
					remove_action( 'generate_footer', 'generate_construct_footer_widgets', 5 );
				} );
				add_action( 'generate_footer', [ $this, 'render_footer' ] );
				break;
			case 'kadence':
				add_action( 'template_redirect', function () {
					remove_action( 'kadence_footer', 'Kadence\footer_markup' );
				} );
				add_action( 'kadence_footer', [ $this, 'render_footer' ] );
				break;
			case 'neve':
				add_filter( 'neve_filter_toggle_content_parts', function ( $enabled, $part ) {
					if ( $part === 'footer' ) {
						return false;
					}

					return $enabled;
				}, 10, 2 );
				add_action( 'neve_after_primary', [ $this, 'render_footer' ] );
				break;
			case 'oceanwp':
				add_action( 'template_redirect', function () {
					remove_action( 'ocean_footer', 'oceanwp_footer_template' );
				} );
				add_action( 'ocean_footer', [ $this, 'render_footer' ] );
				break;
			case 'has-rstb-support' :
				add_filter( 'rstb_show_theme_footer', '__return_false' );
				add_action( 'rstb_init_footer', [ $this, 'render_footer' ] );
				break;
			default:
				add_action( 'get_footer', [ $this, 'get_replaced_footer' ] );
				break;
		}
	}

	public function display_page_title() {
		if ( ! $this->get_active_template( 'page_title' ) ) {
			return;
		}

		switch ( $this->active_theme ) {
			case 'astra':
				add_action( 'astra_header_after', [ $this, 'render_page_title' ] );
				break;
			case 'blocksy':
				add_action( 'blocksy:header:after', [ $this, 'render_page_title' ] );
				break;
			case 'generatepress':
				add_action( 'generate_after_header', [ $this, 'render_page_title' ] );
				break;
			case 'kadence':
				add_action( 'kadence_after_header', [ $this, 'render_page_title' ] );
				break;
			case 'neve':
				add_action( 'neve_after_header_wrapper_hook', [ $this, 'render_page_title' ] );
				break;
			case 'oceanwp':
				add_action( 'template_redirect', function () {
					remove_action( 'ocean_page_header', 'oceanwp_page_header_template' );
				} );
				add_action( 'ocean_page_header', [ $this, 'render_page_title' ] );
				break;
			case 'has-rstb-support' :
				add_filter( 'rstb_show_theme_page_title', '__return_false' );
				add_action( 'rstb_init_page_title', [ $this, 'render_page_title' ] );
				break;
			default:
				add_action( 'rstb_after_header', [ $this, 'render_page_title' ] );
				break;
		}
	}

	public function display_archive_single(): void {
		if ( $this->get_active_template( 'archive' ) ) {
			add_filter( 'template_include', function () {
				return RSTB_PATH . 'templates/parts/archive.php';
			}, 999 );
		}

		if ( $this->get_active_template( 'single_post' ) ) {
			add_filter( 'template_include', function () {
				return RSTB_PATH . 'templates/parts/single.php';
			}, 999 );
		}
	}

	public function display_404_page(): void {
		if ( $this->get_active_template( '404' ) ) {
			add_filter( 'template_include', function () {
				return RSTB_PATH . 'templates/parts/404.php';
			}, 999 );
		}
	}

	public function display_popup(): void {
		add_action( 'wp_footer', function () {
			if ( is_single() && 'rstb_template' === get_post_type() ) {
				return;
			}

			$popups           = $this->get_active_template( 'popup' );
			if ( ! empty( $popups ) ) :
				foreach ( $popups as $popup ) :
					$id = apply_filters( 'wpml_object_id', $popup, 'rstb_template', true );
					$settings = Utils::get_popup_data( $id );
					?>
                    <div
                            id="rstb-popup-<?php echo esc_attr( $id ); ?>"
                            class="<?php echo esc_attr( $settings[ 'wrapper_class' ] ); ?>"
                            style="<?php echo esc_attr( $settings[ 'wrapper_style' ] ); ?>"
                            data-settings="<?php echo esc_attr( wp_json_encode( $settings[ 'data_settings' ] ) ); ?>"
                    >
						<?php if ( $settings[ 'show_overly' ] ) {
							echo '<div class="popup-overly"></div>';
						} ?>
                        <div class="popup-container" style="<?php echo esc_attr( $settings[ 'container_style' ] ) ?>">
							<?php if ( $settings[ 'close_btn' ] ) : ?>
                                <button class="popup-close">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path d="M10.5859 12L2.79297 4.20706L4.20718 2.79285L12.0001 10.5857L19.793 2.79285L21.2072 4.20706L13.4143 12L21.2072 19.7928L19.793 21.2071L12.0001 13.4142L4.20718 21.2071L2.79297 19.7928L10.5859 12Z"></path>
                                    </svg>
                                </button>
							<?php endif;
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Displaying with Elementor content rendering
							echo Utils::get_elementor_content( $id );
							?>
                        </div>
                    </div>
				<?php
				endforeach;
			endif;
		}, 99 );
	}

	public function render_header(): void {
		$header_id = $this->get_active_template( 'header' );
		$header_id = apply_filters( 'wpml_object_id', $header_id, 'rstb_template', true );

		do_action( 'rstb_before_header' );
		echo '<header class="rstb-header">';
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Displaying with Elementor content rendering
		echo Utils::get_elementor_content( $header_id );
		echo '</header>';
		do_action( 'rstb_after_header' );
	}

	public function render_footer(): void {
		$footer_id = $this->get_active_template( 'footer' );
		$footer_id = apply_filters( 'wpml_object_id', $footer_id, 'rstb_template', true );

		do_action( 'rstb_before_footer' );
		echo '<footer class="rstb-footer">';
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Displaying with Elementor content rendering
		echo Utils::get_elementor_content( $footer_id );
		echo '</footer>';
		do_action( 'rstb_after_footer' );
	}

	public function render_page_title(): void {
		$page_title_id = $this->get_active_template( 'page_title' );
		$page_title_id = apply_filters( 'wpml_object_id', $page_title_id, 'rstb_template', true );

		do_action( 'rstb_before_page_title' );
		echo '<div class="rstb-page-title">';
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Displaying with Elementor content rendering
		echo Utils::get_elementor_content( $page_title_id );
		echo '</div>';
		do_action( 'rstb_after_page_title' );
	}

	public function get_replaced_header( $name ): void {
		if ( 'twentynineteen' === $this->active_theme ) {
			add_action( 'rstb_before_header', function () {
				echo '<div id="page" class="site">';
			} );

			add_action( 'rstb_after_header', function () {
				echo '<div id="content" class="site-content">';
			} );
		}

		require RSTB_PATH . 'templates/parts/header.php';

		$templates = [];
		$name      = (string) $name;
		if ( '' !== $name ) {
			$templates[] = "header-{$name}.php";
		}

		$templates[] = 'header.php';

		// Avoid running wp_head hooks again
		remove_all_actions( 'wp_head' );

		ob_start();
		// It causes a `require_once` so, in the get_header itself it will not be required again.
		locate_template( $templates, true );
		ob_get_clean();
	}

	public function get_replaced_footer( $name ): void {
		if ( 'twentynineteen' === $this->active_theme ) {
			add_action( 'rstb_before_footer', function () {
				echo '</div>';
			} );

			add_action( 'rstb_after_footer', function () {
				echo '</div>';
			} );
		}

		require RSTB_PATH . 'templates/parts/footer.php';

		$templates = [];
		$name      = (string) $name;
		if ( '' !== $name ) {
			$templates[] = "footer-{$name}.php";
		}

		$templates[] = 'footer.php';

		ob_start();
		// It causes a `require_once` so, in the get_header itself it will not be required again.
		locate_template( $templates, true );
		ob_get_clean();
	}

	public function enqueue_frontend_styles(): void {
		add_action( 'wp_enqueue_scripts', function () {
			// Load Global Styles
			\Elementor\Plugin::$instance->frontend->enqueue_styles();

			wp_enqueue_style(
				'rstb-frontend',
				RSTB_ASSETS . 'css/rstb-frontend.min.css',
				[],
				RSTB_VERSION
			);

			wp_enqueue_script(
				'rstb-frontend',
				RSTB_ASSETS . 'js/rstb-frontend.min.js',
				[ 'jquery' ],
				RSTB_VERSION,
				true
			);

			foreach ( $this->css_files_ids as $id ) {
				if ( ! empty( $id ) && \Elementor\Plugin::instance()->documents->get( $id )->is_built_with_elementor() ) {
					$css_file = new \Elementor\Core\Files\CSS\Post( $id );
					$css_file->enqueue();
				}
			}
		} );
	}

	public function get_active_template( string $type ) {
		return $this->templates[ $type ] ?? false;
	}

	public function prevent_custom_url_indexing( array $robots ): array {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET[ 'rstb_template' ] ) ) {
			$robots[ 'noindex' ] = true;
			$robots[ 'follow' ]  = true;
		}

		return $robots;
	}
}
