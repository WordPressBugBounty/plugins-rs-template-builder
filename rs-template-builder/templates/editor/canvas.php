<?php

defined( 'ABSPATH' ) || exit;

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
    <?php
    $settings        = get_post_meta( get_the_ID(), '_rstb_settings', true );
    $mm_width        = $settings[ 'mm_width' ] ?? 'full';
    $custom_mm_width = $settings[ 'custom_mm_width' ] ?? 600;
    $width           = '100%';
    if ( 'custom' === $mm_width ) {
	    $width = $custom_mm_width . 'px';
    } elseif ( 'container' === $mm_width ) {
	    $width = 'var(--container-max-width)';
    }
    ?>
</head>
<body <?php body_class(); ?>>
    <div class="rs-template-canvas" style="max-width: <?php echo esc_attr( $width ); ?>; margin: 0 auto;">
        <?php
        if ( class_exists( '\Elementor\Plugin' ) ) {
            \Elementor\Plugin::$instance->modules_manager->get_modules( 'page-templates' )->print_content();
        } else {
            if ( have_posts() ) :
                while ( have_posts() ) : the_post();
                    the_content();
                endwhile;
            endif;
        }

        wp_footer();
        ?>
    </div>
</body>
</html>