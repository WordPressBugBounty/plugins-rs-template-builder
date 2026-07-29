<?php
/**
 * Footer Template
 */

use RsTemplateBuilder\Classes\Frontend;

defined( 'ABSPATH' ) || exit;
?>

        <?php
        Frontend::instance()->render_footer();
        wp_footer();
        ?>
    </body>
</html>