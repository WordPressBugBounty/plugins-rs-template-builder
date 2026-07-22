<?php

use RsTemplateBuilder\Helpers\Utils;

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <?php $settings = Utils::get_popup_data( get_the_ID(), true ); ?>
    <div
        id="rstb-popup-<?php echo esc_attr( get_the_ID() ); ?>"
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
            the_content();
            ?>
        </div>
    </div>
    <?php wp_footer(); ?>
</body>
</html>