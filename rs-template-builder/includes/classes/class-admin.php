<?php

namespace RsTemplateBuilder\Classes;

use RsTemplateBuilder\Helpers\Utils;

defined( 'ABSPATH' ) || exit;

class Admin {

	public function __construct() {
		add_action( 'init', [ $this, 'register_post_type' ] );
		add_filter( 'template_include', [ $this, 'load_editor_template' ], 20 );

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
		add_filter( 'views_edit-rstb_template', [ $this, 'render_admin_template' ] );

		add_filter( 'manage_rstb_template_posts_columns', [ $this, 'add_custom_column' ] );
		add_action( 'manage_rstb_template_posts_custom_column', [ $this, 'display_custom_columns' ], 10, 2 );

		add_filter( 'nav_menu_items_rstb_template', [ $this, 'filter_mega_menu_in_nav' ] );
		add_filter( 'nav_menu_items_rstb_template_recent', [ $this, 'filter_mega_menu_in_nav' ] );
		add_filter( 'wp_setup_nav_menu_item', [ $this, 'mega_menu_item_label' ] );

		add_filter( 'wp_nav_menu_item_custom_fields', [ $this, 'mega_menu_meta_fields' ], 10, 2 );
		add_action( 'wp_update_nav_menu_item', [ $this, 'save_mega_menu_meta' ], 10, 3 );

		add_action( 'show_user_profile', [ $this, 'user_social_fields' ] );
		add_action( 'edit_user_profile', [ $this, 'user_social_fields' ] );
		add_action( 'personal_options_update', [ $this, 'save_user_social_fields' ] );
		add_action( 'edit_user_profile_update', [ $this, 'save_user_social_fields' ] );
	}

	public function register_post_type(): void {
		register_post_type( 'rstb_template', [
			'labels'              => [
				'name'               => __( 'RSTB Templates', 'rs-template-builder' ),
				'singular_name'      => __( 'Template', 'rs-template-builder' ),
				'all_items'          => __( 'All Templates', 'rs-template-builder' ),
				'add_new_item'       => __( 'Add New Template', 'rs-template-builder' ),
				'edit_item'          => __( 'Edit Template', 'rs-template-builder' ),
				'new_item'           => __( 'New Template', 'rs-template-builder' ),
				'view_item'          => __( 'View Template', 'rs-template-builder' ),
				'search_items'       => __( 'Search Templates', 'rs-template-builder' ),
				'not_found'          => __( 'No Templates found', 'rs-template-builder' ),
				'not_found_in_trash' => __( 'No Templates found in Trash', 'rs-template-builder' ),
			],
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => true,
			'exclude_from_search' => true,
			'rewrite'             => false,
			'show_in_rest'        => false,
			'has_archive'         => false,
			'hierarchical'        => false,
			'taxonomies'          => [ 'rstb_template_type' ],
			'capability_type'     => 'post',
			'supports'            => [ 'title', 'author', 'elementor', 'editor', 'revisions' ],
			'menu_icon'           => RSTB_ASSETS . 'img/rstb-branding-icon.png',
			'menu_position'       => 25,
		] );

		register_taxonomy(
			'rstb_template_type',
			'rstb_template',
			[
				'label'             => __( 'Template Type', 'rs-template-builder' ),
				'public'            => false,
				'show_ui'           => false,
				'hierarchical'      => false,
				'show_admin_column' => false,
				'query_var'         => true,
				'show_in_rest'      => false,
				'rewrite'           => false,
			]
		);
	}

	public function load_editor_template( $template ) {
		global $post;

		if ( $post && $post->post_type === 'rstb_template' ) {
			$template_type = Utils::get_template_type( $post->ID );

			switch ( $template_type ) {
				case 'popup':
					$template = RSTB_PATH . 'templates/editor/popup.php';
					break;
				case 'offcanvas':
					$template = RSTB_PATH . 'templates/editor/offcanvas.php';
					break;
				default:
					$template = RSTB_PATH . 'templates/editor/canvas.php';
			}
		}

		return $template;
	}

	public function enqueue_admin_assets(): void {
		$screen = get_current_screen();

		wp_enqueue_style(
			'rstb-branding',
			RSTB_ASSETS . 'css/rstb-branding.min.css',
			[],
			RSTB_VERSION,
		);

		if ( isset( $screen->post_type ) && $screen->post_type === 'rstb_template' && $screen->base === 'edit' ) {
			$asset_file = include( RSTB_PATH . 'assets/admin/index.asset.php' );

			wp_enqueue_style(
				'rstb-admin',
				RSTB_ASSETS . 'admin/index.css',
				[],
				$asset_file[ 'version' ],
			);

			wp_enqueue_script(
				'rstb-admin',
				RSTB_ASSETS . 'admin/index.js',
				$asset_file[ 'dependencies' ],
				$asset_file[ 'version' ],
				true
			);

			wp_localize_script( 'rstb-admin', 'rstbData', Utils::get_admin_data() );
		}
	}

	public function render_admin_template( $views ): array {
		$current_type = '';
		$active_class = ' nav-item-active';

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! empty( $_REQUEST[ 'rstb_template_type' ] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$current_type = sanitize_text_field( wp_unslash( $_REQUEST[ 'rstb_template_type' ] ) );
			$active_class = '';
		}

		$url_args = [ 'post_type' => 'rstb_template' ];

		$baseurl = add_query_arg( $url_args, admin_url( 'edit.php' ) );
		$types   = Utils::get_supported_template_types();
		?>
        <div id="rstb-popup-wrap"></div>
        <div id="rstb-tabs" class="rstb-nav-wrap">
            <a class="nav-item<?php echo esc_attr( $active_class ); ?>" href="<?php echo esc_url( $baseurl ); ?>">
				<?php echo esc_html__( 'All', 'rs-template-builder' ); ?>
            </a>
			<?php foreach ( $types as $type ) :
				$active_class = '';

				if ( $current_type === $type ) {
					$active_class = ' nav-item-active';
				}

				$type_url   = add_query_arg( 'rstb_template_type', $type, $baseurl );
				$type_label = ucwords( str_replace( '_', ' ', $type ) );

				printf( '<a class="nav-item%1$s" href="%2$s">%3$s</a>',
					esc_attr( $active_class ),
					esc_url( $type_url ),
					esc_html( $type_label )
				);
			endforeach; ?>
        </div>
		<?php
		return $views;
	}

	public function add_custom_column( $columns ) {
		return [
			'cb'     => $columns[ 'cb' ] ?? '',
			'title'  => $columns[ 'title' ] ?? '',
			'author' => $columns[ 'author' ] ?? '',
			'type'   => __( 'Type', 'rs-template-builder' ),
			'info'   => __( 'Info', 'rs-template-builder' ),
			'date'   => $columns[ 'date' ] ?? '',
		];
	}

	public function display_custom_columns( $column, $post_id ): void {
		switch ( $column ) {
			case 'type':
				echo esc_html( ucwords( str_replace( '_', ' ', Utils::get_template_type( $post_id ) ) ) );
				break;
			case 'info':
				echo wp_kses_post( $this->get_template_info( $post_id ) );
				break;
		}
	}

	public function get_template_info( $post_id ): string {
		$include  = get_post_meta( $post_id, '_rstb_include', true );
		$exclude  = get_post_meta( $post_id, '_rstb_exclude', true );
		$settings = get_post_meta( $post_id, '_rstb_settings', true );
		$type     = Utils::get_template_type( $post_id );

		$info = '';

		if ( 'mega_menu' !== $type && 'offcanvas' !== $type ) {
			if ( ! empty( $include ) ) {
				$info .= '<b>' . __( 'Display On', 'rs-template-builder' ) . ': </b>';
				$info .= $this->display_condition( $include );
				$info .= '</br>';
			}
			if ( ! empty( $exclude ) ) {
				$info .= '<b>' . __( 'Hide On', 'rs-template-builder' ) . ': </b>';
				$info .= $this->display_condition( $exclude );
				$info .= '</br>';
			}
		}
		if ( 'mega_menu' === $type ) {
			$mm_width        = $settings[ 'mm_width' ] ?? 'full';
			$custom_mm_width = $settings[ 'custom_mm_width' ] ?? 600;

			$info .= '<b>' . __( 'Width:', 'rs-template-builder' ) . '</b> ' . ucfirst( $mm_width );
			if ( $mm_width == 'custom' ) {
				$info .= ' (' . esc_html( $custom_mm_width ) . 'px)';
			}
		}
		if ( 'offcanvas' === $type ) {
			$offcanvas_width = $settings[ 'offcanvas_width' ] ?? '450px';

			$info .= '<b>' . __( 'Width:', 'rs-template-builder' ) . '</b> ' . esc_html( $offcanvas_width );
		}
		if ( 'popup' === $type ) {
			$popup_width        = $settings[ 'pp_width' ] ?? 'full';
			$custom_popup_width = $settings[ 'pp_custom_width' ] ?? 720;

			$info .= '<b>' . __( 'Width:', 'rs-template-builder' ) . '</b> ' . ucfirst( $popup_width );
			if ( $popup_width == 'custom' ) {
				$info .= ' (' . esc_html( $custom_popup_width ) . 'px)';
			}
		}

		return $info;
	}

	private function display_condition( $condition ): string {
		static $criteria_options = null;
		$display = [];

		if ( is_null( $criteria_options ) ) {
			$criteria_options = Utils::get_criteria_options();
		}

		if ( ! is_array( $condition ) ) {
			return '';
		}

		foreach ( $condition as $rule ) {
			if ( empty( $rule[ 'criteria' ] ) ) {
				continue;
			}

			if ( 'specific' === $rule[ 'criteria' ] ) {
				$specific_items = $rule[ 'specific_items' ] ?? [];

				if ( is_array( $specific_items ) && ! empty( $specific_items ) ) {
					$labels    = array_column( $specific_items, 'label' );
					$display[] = esc_html__( 'Specific', 'rs-template-builder' ) . ' [' . implode( ' | ', $labels ) . ']';
				} else {
					$display[] = esc_html__( 'Specific', 'rs-template-builder' );
				}
			} else {
				foreach ( $criteria_options as $criteria ) {
					foreach ( $criteria[ 'options' ] as $option ) {
						if ( $option[ 'value' ] == $rule[ 'criteria' ] ) {
							$display[] = $option[ 'label' ];
						}
					}
				}
			}
		}

		return implode( ', ', $display );
	}

	public function filter_mega_menu_in_nav( $items ): array {
		$new_items = [];

		foreach ( $items as $item ) {
			$type = Utils::get_template_type( $item->ID );

			if ( 'mega_menu' === $type ) {
				$new_items[] = $item;
			}
		}

		return $new_items;
	}

	public function mega_menu_item_label( $menu_item ) {
		if ( $menu_item->object === 'rstb_template' ) {
			$menu_item->type_label = __( 'RSTB Mega Menu', 'rs-template-builder' );
		}

		return $menu_item;
	}

	public function mega_menu_meta_fields( $item_id, $item ): void {
		if ( $item->object === 'rstb_template' ) {
			$post_type_object = get_post_type_object( 'rstb_template' );
			$url              = get_post_meta( $item_id, '_rstb_mm_url', true );

			if ( ! $post_type_object ) {
				return;
			}

			if ( ! current_user_can( 'edit_post', $item->object_id ) ) {
				return;
			}

			if ( $post_type_object->_edit_link ) {
				$link = admin_url( sprintf( $post_type_object->_edit_link . '&action=elementor', $item->object_id ) );
			} else {
				$link = '';
			}

			wp_nonce_field( 'rstb_mm_meta_action', 'rstb_mm_meta_action_name' );

			echo '<p class="field-url description description-wide">
				<label for="edit-menu-item-url-' . esc_attr( $item_id ) . '">
					' . esc_html__( 'URL', 'rs-template-builder' ) . '<br>
					<input type="text" id="edit-menu-item-url-' . esc_attr( $item_id ) . '" class="widefat code edit-menu-item-url" name="menu-item-url[' . esc_attr( $item_id ) . ']" value="' . esc_url( $url ) . '" placeholder="#">
				</label>
			</p>';

			echo '<a style="display: inline-block; margin: 10px 0;" href="' . esc_url( $link ) . '">' . esc_html__( 'Edit with Elementor', 'rs-template-builder' ) . '</a><br/>';
		}
	}

	public function save_mega_menu_meta( $menu_id, $menu_item_db_id, $menu_item_data ): void {
		if ( ! isset( $_POST[ 'rstb_mm_meta_action_name' ] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST[ 'rstb_mm_meta_action_name' ] ) ), 'rstb_mm_meta_action' ) ) {
			return;
		}

		if ( isset( $_POST[ 'menu-item-url' ][ $menu_item_db_id ] ) ) {
			$url = sanitize_text_field( wp_unslash( $_POST[ 'menu-item-url' ][ $menu_item_db_id ] ) );
			update_post_meta( $menu_item_db_id, '_rstb_mm_url', $url );
		}
	}

	public function user_social_fields( $user ) {
		$profiles = get_user_meta( $user->ID, '_rstb_social_profiles', true );
		$profiles = is_array( $profiles ) ? $profiles : [];

		$fields = [
			'facebook'  => __( 'Facebook URL', 'rs-template-builder' ),
			'twitter'   => __( 'Twitter/X URL', 'rs-template-builder' ),
			'instagram' => __( 'Instagram URL', 'rs-template-builder' ),
			'linkedin'  => __( 'LinkedIn URL', 'rs-template-builder' ),
			'youtube'   => __( 'YouTube URL', 'rs-template-builder' ),
			'tiktok'    => __( 'TikTok URL', 'rs-template-builder' ),
			'pinterest' => __( 'Pinterest URL', 'rs-template-builder' ),
			'github'    => __( 'GitHub URL', 'rs-template-builder' ),
			'website'   => __( 'Website URL', 'rs-template-builder' ),
			'whatsapp'  => __( 'WhatsApp URL', 'rs-template-builder' ),
			'telegram'  => __( 'Telegram URL', 'rs-template-builder' ),
		];
		?>

        <h2 style="margin: 2em 0 0.5em"><?php esc_html_e( 'Social Profiles', 'rs-template-builder' ); ?></h2>

        <table class="form-table">
			<?php foreach ( $fields as $key => $label ) :
				$value = $profiles[ $key ] ?? '';
				$input_name = '_rstb_social_' . $key;
				?>
                <tr>
                    <th><label for="<?php echo esc_attr( $input_name ); ?>"><?php echo esc_html( $label ); ?></label></th>
                    <td>
                        <input
                                type="text"
                                id="<?php echo esc_attr( $input_name ); ?>"
                                name="<?php echo esc_attr( $input_name ); ?>"
                                class="regular-text"
                                value="<?php echo esc_attr( $value ); ?>"
                        >
                    </td>
                </tr>
			<?php endforeach; ?>
        </table>
		<?php
	}

	public function save_user_social_fields( $user_id ) {
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return;
		}

		if ( ! isset( $_POST[ '_wpnonce' ] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST[ '_wpnonce' ] ) ), 'update-user_' . $user_id ) ) {
			return;
		}

		$fields = [ 'facebook', 'twitter', 'instagram', 'linkedin', 'youtube', 'tiktok', 'pinterest', 'github', 'website', 'whatsapp', 'telegram' ];

		$profiles = [];

		foreach ( $fields as $key ) {
			$post_key = '_rstb_social_' . $key;

			if ( isset( $_POST[ $post_key ] ) ) {
				$profiles[ $key ] = esc_url_raw( wp_unslash( $_POST[ $post_key ] ) );
			}
		}

		update_user_meta( $user_id, '_rstb_social_profiles', $profiles );
	}
}
