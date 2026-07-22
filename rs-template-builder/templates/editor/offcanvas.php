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
	$settings     = get_post_meta( get_the_ID(), '_rstb_settings', true );
	$canvas_width = $settings[ 'offcanvas_width' ] ?? '450px';
	$width        = '450px';
	if ( ! empty( $canvas_width ) ) {
		$width = $canvas_width;
	}
	?>
</head>
<body <?php body_class(); ?>>
    <div class="rstb-offcanvas-wrap">
        <div class="rstb-offcanvas-panel show-offcanvas">
            <div class="offcanvas-overly"></div>
            <div class="offcanvas-container" style="width: <?php echo esc_attr( $width ); ?>">
	            <?php
	            if ( have_posts() ) :
		            while ( have_posts() ) : the_post();
			            the_content();
		            endwhile;
	            endif;
	            ?>
            </div>
        </div>
    </div>
    <?php wp_footer(); ?>
</body>
</html>