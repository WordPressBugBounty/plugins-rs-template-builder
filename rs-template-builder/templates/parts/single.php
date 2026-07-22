<?php
/**
 * Archive Template
 */

use RsTemplateBuilder\Classes\Frontend;
use RsTemplateBuilder\Helpers\Utils;

defined( 'ABSPATH' ) || exit;

get_header();

do_action( 'rstb_before_single' );

$single_id = Frontend::instance()->get_active_template( 'single_post' );
$single_id = apply_filters( 'wpml_object_id', $single_id, 'rstb_template', true );
?>
    <div class="rstb-single-page">
		<?php
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Displaying with Elementor content rendering
        echo Utils::get_elementor_content( $single_id );
        ?>
    </div>
<?php
do_action( 'rstb_after_single' );

get_footer();