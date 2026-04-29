<?php

namespace RsTemplateBuilder;

use RsTemplateBuilder\Classes\Admin;
use RsTemplateBuilder\Classes\Frontend;
use RsTemplateBuilder\Classes\Rest_Api;
use RsTemplateBuilder\Elementor\Elementor_Addons;

defined( 'ABSPATH' ) || exit;

/**
 * The Main Plugin Class
 */
final class Plugin {

	private static ?self $instance = null;

	public static function instance(): self {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		$this->init_plugin();
	}

	public function init_plugin(): void {
		// Load Files
		include_once RSTB_INCLUDES . 'classes/class-admin.php';
		include_once RSTB_INCLUDES . 'classes/class-frontend.php';
		include_once RSTB_INCLUDES . 'classes/class-rest-api.php';
		include_once RSTB_INCLUDES . 'classes/class-template-rule.php';
		include_once RSTB_INCLUDES . 'helpers/class-utils.php';
		include_once RSTB_INCLUDES . 'helpers/class-nav-walker.php';
		include_once RSTB_INCLUDES . 'elementor/class-elementor-addons.php';

		// Init Classes
		new Admin();
		new Rest_Api();
		Frontend::instance();
		Elementor_Addons::instance();
	}
}
