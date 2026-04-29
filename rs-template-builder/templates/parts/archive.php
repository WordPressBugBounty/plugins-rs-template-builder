<?php
/**
 * Archive Template
 */

use RsTemplateBuilder\Classes\Frontend;
use RsTemplateBuilder\Helpers\Utils;

defined( 'ABSPATH' ) || exit;

get_header();

do_action( 'rstb_before_archive' );

$archive_id = Frontend::instance()->get_active_template( 'archive' );
$archive_id = apply_filters( 'wpml_object_id', $archive_id, 'rstb_template', true );
?>
    <div class="rstb-archive-page">
		<?php
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Displaying with Elementor content rendering
        echo Utils::get_elementor_content( $archive_id );
        ?>
    </div>
<?php
do_action( 'rstb_after_archive' );

get_footer();