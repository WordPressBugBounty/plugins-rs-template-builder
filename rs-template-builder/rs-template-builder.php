<?php
/**
 * Plugin Name: RS Template Builder
 * Description: Build custom Header, Footer, Mega Menu, Popup, Off-Canvas, Archive, Single Post, Page Title, and 404 templates with Elementor. Includes 14+ widgets and display conditions for full theme building without coding.
 * Plugin URI: https://rstheme.com/rs-template-builder
 * Author: RSTheme
 * Author URI: https://rstheme.com/
 * Version: 1.4.1
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: rs-template-builder
 * Domain Path: /languages
 * Requires at least: 5.9
 * Requires PHP: 7.4
 * Requires Plugins: elementor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Define constants
 */
define( 'RSTB_VERSION', '1.4.1' );
define( 'RSTB_FILE', __FILE__ );
define( 'RSTB_PATH', plugin_dir_path( RSTB_FILE ) );
define( 'RSTB_URL', plugin_dir_url( RSTB_FILE ) );
define( 'RSTB_ASSETS', trailingslashit( RSTB_URL . 'assets' ) );
define( 'RSTB_INCLUDES', trailingslashit( RSTB_PATH . 'includes' ) );
define( 'RSTB_ELEMENTOR', trailingslashit( RSTB_INCLUDES . 'elementor' ) );
define( 'RSTB_MINIMUM_ELEMENTOR_VERSION', '3.3' );
define( 'RSTB_MINIMUM_PHP_VERSION', '7.4' );

/**
 * Rs Template Builder Kick-start
 */
function rstb_plugin_kick_start(): void {

	// Check for required PHP version
	if ( version_compare( PHP_VERSION, RSTB_MINIMUM_PHP_VERSION, '<' ) ) {
		add_action( 'admin_notices', 'rstb_minimum_php_version_notice' );

		return;
	}

	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'rstb_elementor_missing_notice' );

		return;
	}

	// Check for required Elementor version
	if ( ! version_compare( ELEMENTOR_VERSION, RSTB_MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
		add_action( 'admin_notices', 'rstb_elementor_version_notice' );

		return;
	}

	require RSTB_INCLUDES . 'class-plugin.php';
	\RsTemplateBuilder\Plugin::instance();
}

add_action( 'plugins_loaded', 'rstb_plugin_kick_start' );

/**
 * Minimum PHP Version Notice
 */
function rstb_minimum_php_version_notice(): void {
	$notice = sprintf(
	/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
		esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'rs-template-builder' ),
		'<strong>' . esc_html__( 'RS Template Builder', 'rs-template-builder' ) . '</strong>',
		'<strong>' . esc_html__( 'PHP', 'rs-template-builder' ) . '</strong>',
		RSTB_MINIMUM_PHP_VERSION
	);

	printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $notice ) );
}

/**
 * Elementor Plugin Missing Notice
 */
function rstb_elementor_missing_notice(): void {
	$notice = sprintf(
	/* translators: 1: Plugin name 2: Elementor */
		esc_html__( '"%1$s" requires "%2$s" to be installed and activated to function properly', 'rs-template-builder' ),
		'<strong>' . esc_html__( 'RS Template Builder', 'rs-template-builder' ) . '</strong>',
		'<strong>' . esc_html__( 'Elementor', 'rs-template-builder' ) . '</strong>'
	);

	printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $notice ) );
}

/**
 * Minimum Elementor Version Notice
 */
function rstb_elementor_version_notice(): void {
	$notice = sprintf(
	/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
		esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'rs-template-builder' ),
		'<strong>' . esc_html__( 'RS Template Builder', 'rs-template-builder' ) . '</strong>',
		'<strong>' . esc_html__( 'Elementor', 'rs-template-builder' ) . '</strong>',
		RSTB_MINIMUM_ELEMENTOR_VERSION
	);

	printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $notice ) );
}

/**
 * RS Template Builder Activation Hook
 */
function rstb_activation_hook(): void {
	$installed = get_option( 'rstb_installed' );

	if ( ! $installed ) {
		update_option( 'rstb_installed', time() );
	}

	update_option( 'rstb_version', RSTB_VERSION );
}

register_activation_hook( RSTB_FILE, 'rstb_activation_hook' );
