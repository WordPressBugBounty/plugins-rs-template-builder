<?php
/**
 * 404 Template
 */

use RsTemplateBuilder\Classes\Frontend;
use RsTemplateBuilder\Helpers\Utils;

defined( 'ABSPATH' ) || exit;

get_header();

do_action( 'rstb_before_404' );

$template_404 = Frontend::instance()->get_active_template( '404' );
$template_404 = apply_filters( 'wpml_object_id', $template_404, 'rstb_template', true );
?>
    <div class="rstb-404-page">
		<?php
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Displaying with Elementor content rendering
        echo Utils::get_elementor_content( $template_404 );
        ?>
    </div>
<?php
do_action( 'rstb_after_404' );

get_footer();