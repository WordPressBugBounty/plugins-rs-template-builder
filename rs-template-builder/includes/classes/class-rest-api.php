<?php

namespace RsTemplateBuilder\Classes;

use RsTemplateBuilder\Helpers\Utils;
use WP_Error;
use WP_Query;
use WP_REST_Request;
use WP_REST_Response;

defined( 'ABSPATH' ) || exit;

class Rest_Api {

	private string $namespace = 'rstb/v1';

	private string $rest_base = 'templates';

	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	public function register_routes(): void {
		register_rest_route( $this->namespace, '/' . $this->rest_base, [
			'methods'             => 'POST',
			'callback'            => [ $this, 'save_template' ],
			'permission_callback' => [ $this, 'check_permissions' ],
			'args'                => $this->get_endpoint_args(),
			'show_in_index'       => false,
		] );

		register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)', [
			'methods'             => 'GET',
			'callback'            => [ $this, 'get_template_info' ],
			'permission_callback' => [ $this, 'check_permissions' ],
			'show_in_index'       => false,
		] );

		register_rest_route( $this->namespace, '/specific-search', [
			'methods'             => 'GET',
			'callback'            => [ $this, 'get_specific_search' ],
			'permission_callback' => [ $this, 'check_permissions' ],
			'args'                => [
				'q'    => [
					'required'          => true,
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
				],
				'type' => [
					'required'          => true,
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
				]
			],
			'show_in_index'       => false,
		] );
	}

	public function save_template( $request ) {
		$data = $request->get_json_params();

		$validated = $this->validate_template_data( $data );
		if ( is_wp_error( $validated ) ) {
			return $validated;
		}

		$title    = $validated[ 'title' ];
		$status   = $validated[ 'status' ];
		$type     = $validated[ 'type' ];
		$edit_id  = $validated[ 'edit_id' ];
		$include  = $validated[ 'include' ];
		$exclude  = $validated[ 'exclude' ];
		$settings = $validated[ 'settings' ];

		$allowed_types = Utils::get_supported_template_types();

		if ( ! in_array( $type, $allowed_types, true ) ) {
			return new WP_REST_Response(
				[ 'success' => false, 'message' => __( 'Invalid template type', 'rs-template-builder' ) ],
				400
			);
		}

		$term = get_term_by( 'slug', $type, 'rstb_template_type' );

		if ( ! $term ) {
			$term = wp_insert_term(
				$type,
				'rstb_template_type',
				[ 'slug' => $type ]
			);

			if ( is_wp_error( $term ) ) {
				return new WP_REST_Response(
					[ 'success' => false, 'message' => $term->get_error_message() ],
					500
				);
			}

			$termId = $term[ 'term_id' ];
		} else {
			$termId = $term->term_id;
		}

		$post_args = [
			'ID'          => $edit_id ?: 0,
			'post_title'  => $title,
			'post_status' => $status,
			'post_type'   => 'rstb_template',
		];

		$post_id = wp_insert_post( $post_args );
		if ( is_wp_error( $post_id ) ) {
			return new WP_REST_Response( [ 'success' => false, 'message' => $post_id->get_error_message() ], 500 );
		}

		wp_set_post_terms( $post_id, [ $termId ], 'rstb_template_type' );

		$this->save_post_meta( $post_id, '_rstb_include', $include );
		$this->save_post_meta( $post_id, '_rstb_exclude', $exclude );
		$this->save_post_meta( $post_id, '_rstb_settings', $settings );

		return new WP_REST_Response( [ 'success' => true, 'id' => $post_id ], 200 );
	}

	public function get_template_info( $request ) {
		$post_id = intval( $request[ 'id' ] ?? 0 );

		if ( ! $post_id || 'rstb_template' !== get_post_type( $post_id ) ) {
			return new WP_REST_Response( [ 'success' => false, 'message' => __( 'Invalid template ID', 'rs-template-builder' ) ], 404 );
		}

		$post = get_post( $post_id );
		if ( ! $post ) {
			return new WP_REST_Response( [ 'success' => false, 'message' => __( 'Template not found', 'rs-template-builder' ) ], 404 );
		}

		$terms = wp_get_post_terms( $post_id, 'rstb_template_type' );
		$type  = ! empty( $terms ) && ! is_wp_error( $terms ) ? $terms[ 0 ]->slug : '';

		$response = [
			'title'    => $post->post_title,
			'status'   => $post->post_status,
			'type'     => $type,
			'include'  => get_post_meta( $post_id, '_rstb_include', true ) ?: [],
			'exclude'  => get_post_meta( $post_id, '_rstb_exclude', true ) ?: [],
			'settings' => get_post_meta( $post_id, '_rstb_settings', true ) ?: [],
			'edit_id'  => $post_id,
		];

		return new WP_REST_Response( [ 'success' => true, 'data' => $response ], 200 );
	}

	public function get_specific_search( $request ) {
		$query = sanitize_text_field( $request->get_param( 'q' ) ?? '' );
		$type  = sanitize_text_field( $request->get_param( 'type' ) ?? '' );

		if ( empty( $query ) ) {
			return new WP_REST_Response( [ 'success' => false, 'message' => __( 'Search query required', 'rs-template-builder' ) ], 400 );
		}

		$supported_post_types = Utils::get_supported_post_types();
		$post_types           = array_keys( $supported_post_types );
		$grouped              = [];

		if ( 'archive' !== $type ) {
			// Search Posts
			add_filter( 'posts_search', [ $this, 'title_only_search_filter' ], 10, 2 );

			$query_obj = new WP_Query( [
				'post_type'      => $post_types,
				'post_status'    => 'publish',
				's'              => $query,
				'posts_per_page' => - 1,
				'orderby'        => 'title',
				'order'          => 'ASC',
			] );

			$posts = $query_obj->posts;

			remove_filter( 'posts_search', [ $this, 'title_only_search_filter' ], 10 );

			foreach ( $posts as $post ) {
				$post_type = $post->post_type;
				if ( ! isset( $grouped[ $post_type ] ) ) {
					$pt_obj                = $supported_post_types[ $post_type ] ?? null;
					$grouped[ $post_type ] = [
						'label'   => $pt_obj && isset( $pt_obj->label ) ? $pt_obj->label : ucfirst( $post_type ),
						'options' => [],
					];
				}

				$title = get_the_title( $post );

				if ( $post->post_parent ) {
					$parent_title = get_the_title( $post->post_parent );
					if ( $parent_title ) {
						$title .= ' (' . $parent_title . ')';
					}
				}

				$grouped[ $post_type ][ 'options' ][] = [
					'value' => 'post-' . $post->ID,
					'label' => $title,
				];
			}
		}

		// Search Taxonomies
		$taxonomies = [];
		foreach ( $post_types as $post_type ) {
			$pt_taxonomies = get_object_taxonomies( $post_type, 'objects' );
			foreach ( $pt_taxonomies as $taxonomy ) {
				if ( $taxonomy->public ) {
					if ( ! isset( $taxonomies[ $taxonomy->name ] ) ) {
						$taxonomies[ $taxonomy->name ] = [
							'object'     => $taxonomy,
							'post_types' => [],
						];
					}
					$taxonomies[ $taxonomy->name ][ 'post_types' ][] = $post_type;
				}
			}
		}

		foreach ( $taxonomies as $taxonomy_name => $taxonomy_data ) {
			$terms = get_terms( [
				'taxonomy'   => $taxonomy_name,
				'search'     => $query,
				'hide_empty' => false,
				'orderby'    => 'name',
				'order'      => 'ASC',
			] );

			if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
				$tax_key      = 'tax-' . $taxonomy_name;
				$taxonomy_obj = $taxonomy_data[ 'object' ];

				if ( ! isset( $grouped[ $tax_key ] ) ) {
					$grouped[ $tax_key ] = [
						'label'   => $taxonomy_obj->label,
						'options' => [],
					];
				}

				foreach ( $terms as $term ) {
					if ( 'single_post' !== $type ) {
						$grouped[ $tax_key ][ 'options' ][] = [
							'value' => 'tax-' . $term->term_id,
							'label' => $term->name . ' - ' . __( 'Archive Page', 'rs-template-builder' ),
						];
					}
					if ( 'archive' !== $type ) {
						$grouped[ $tax_key ][ 'options' ][] = [
							'value' => 'tax-' . $term->term_id . '-singulars',
							'label' => $term->name . ' - ' . __( 'All Singulars', 'rs-template-builder' ),
						];
					}
				}
			}
		}

		return new WP_REST_Response( [ 'success' => true, 'results' => array_values( $grouped ) ], 200 );
	}

	public function validate_template_data( $data ) {
		$clean = [];

		// Title
		if ( empty( $data[ 'title' ] ) || ! is_string( $data[ 'title' ] ) ) {
			return new WP_Error( 'invalid_title', __( 'Title is required', 'rs-template-builder' ), [ 'status' => 400 ] );
		}
		$clean[ 'title' ] = sanitize_text_field( $data[ 'title' ] );

		// Status
		$status = $data[ 'status' ] ?? 'publish';
		if ( ! in_array( $status, [ 'publish', 'draft' ], true ) ) {
			$status = 'publish';
		}
		$clean[ 'status' ] = $status;

		// Type
		if ( empty( $data[ 'type' ] ) || ! is_string( $data[ 'type' ] ) ) {
			return new WP_Error( 'invalid_type', __( 'Template type is required', 'rs-template-builder' ), [ 'status' => 400 ] );
		}
		$clean[ 'type' ] = sanitize_text_field( $data[ 'type' ] );

		// Edit ID
		$clean[ 'edit_id' ] = isset( $data[ 'edit_id' ] ) ? intval( $data[ 'edit_id' ] ) : 0;

		// Include/Exclude
		foreach ( [ 'include', 'exclude' ] as $key ) {
			if ( isset( $data[ $key ] ) && is_array( $data[ $key ] ) ) {
				$clean[ $key ] = array_map( function ( $item ) {
					$specific = [];
					if ( isset( $item[ 'specific_items' ] ) && is_array( $item[ 'specific_items' ] ) ) {
						$specific = array_map( function ( $sp ) {
							return [
								'label' => sanitize_text_field( $sp[ 'label' ] ?? '' ),
								'value' => sanitize_text_field( $sp[ 'value' ] ?? '' ),
							];
						}, $item[ 'specific_items' ] );
					}

					return [
						'criteria'       => sanitize_text_field( $item[ 'criteria' ] ?? '' ),
						'specific_items' => $specific,
					];
				}, $data[ $key ] );
			} else {
				$clean[ $key ] = [];
			}
		}

		// Settings
		$clean[ 'settings' ] = [];
		if ( isset( $data[ 'settings' ] ) && is_array( $data[ 'settings' ] ) ) {
			$clean[ 'settings' ] = $this->sanitize_settings( $data[ 'settings' ] );
			if ( empty( $clean[ 'settings' ] ) ) {
				return new WP_Error( 'invalid_settings', __( 'Invalid settings structure', 'rs-template-builder' ), [ 'status' => 400 ] );
			}
		}

		return $clean;
	}

	protected function sanitize_settings( $settings ) {
		$allowed = [
			'header_replace_enabled' => 'bool',
			'header_selector'        => 'string',
			'footer_replace_enabled' => 'bool',
			'footer_selector'        => 'string',
			'mm_width'               => [ 'full', 'container', 'custom' ],
			'custom_mm_width'    => 'int',
			'offcanvas_width'    => 'string',
			'pp_width'           => [ 'full', 'auto', 'custom' ],
			'pp_custom_width'    => 'int',
			'pp_height'          => [ 'auto', 'full', 'custom' ],
			'pp_custom_height'   => 'int',
			'pp_position'        => 'string',
			'pp_overly'          => 'bool',
			'pp_close_btn'       => 'bool',
			'pp_page_load'       => 'bool',
			'pp_page_delay'      => 'int',
			'pp_page_scroll'     => 'bool',
			'pp_scroll_amount'   => 'int',
			'pp_scroll_el'       => 'bool',
			'pp_scroll_el_sel'   => 'string',
			'pp_click_el'        => 'bool',
			'pp_click_el_sel'    => 'string',
			'pp_show_frequency'  => [ 'every-time', 'per-session', 'x-hours' ],
			'pp_repeat_int'      => 'int',
			'pp_limit_display'   => 'int',
			'pp_reset_on_update' => 'bool',
		];

		$clean = [];
		foreach ( $settings as $key => $value ) {
			if ( ! array_key_exists( $key, $allowed ) ) {
				continue;
			}

			$type = $allowed[ $key ];
			if ( is_array( $type ) && in_array( $value, $type, true ) ) {
				$clean[ $key ] = $value;
			} elseif ( $type === 'int' && is_numeric( $value ) ) {
				$clean[ $key ] = intval( $value );
			} elseif ( $type === 'bool' ) {
				$clean[ $key ] = filter_var( $value, FILTER_VALIDATE_BOOLEAN );
			} elseif ( $type === 'string' && is_string( $value ) ) {
				$clean[ $key ] = sanitize_text_field( $value );
			}
		}

		return $clean;
	}

	protected function save_post_meta( $post_id, $key, $value ): void {
		if ( ! empty( $value ) ) {
			update_post_meta( $post_id, $key, $value );
		} else {
			delete_post_meta( $post_id, $key );
		}
	}

	public function check_permissions( WP_REST_Request $request ): bool {
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		$nonce = $request->get_header( 'X-WP-Nonce' );
		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
			return false;
		}

		return true;
	}

	public function get_endpoint_args(): array {
		return [
			'title'    => [ 'required' => true, 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ],
			'status'   => [ 'required' => false, 'type' => 'string', 'enum' => [ 'publish', 'draft' ], 'default' => 'publish' ],
			'type'     => [ 'required' => true, 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ],
			'edit_id'  => [ 'required' => false, 'type' => 'integer', 'sanitize_callback' => 'absint' ],
			'include'  => [ 'required' => false, 'type' => 'array' ],
			'exclude'  => [ 'required' => false, 'type' => 'array' ],
			'settings' => [ 'required' => false, 'type' => 'object' ],
		];
	}

	public function title_only_search_filter( $search, $wp_query ) {
		global $wpdb;

		if ( empty( $search ) ) {
			return $search;
		}

		$terms = $wp_query->get( 'search_terms' );
		if ( empty( $terms ) ) {
			return $search;
		}

		$clauses = [];
		foreach ( $terms as $term ) {
			$like      = '%' . $wpdb->esc_like( $term ) . '%';
			$clauses[] = $wpdb->prepare( "$wpdb->posts.post_title LIKE %s", $like );
		}

		return ' AND (' . implode( ' AND ', $clauses ) . ')';
	}
}
