<?php

namespace RsTemplateBuilder\Classes;

use RsTemplateBuilder\Helpers\Utils;

defined( 'ABSPATH' ) || exit;

class Template_Rule {

	private static ?self $instance = null;

	private static ?string $current_page_type = null;

	private static array $current_page_data = [];

	private function __construct() {
	}

	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Get templates matching the current page's conditions.
	 */
	public function get_template_by_conditions(): array {
		$template_cpt = 'rstb_template';

		// Return cached result
		if ( isset( self::$current_page_data[ $template_cpt ] ) ) {
			return self::$current_page_data[ $template_cpt ];
		}

		$current_page_type = $this->get_current_page_type();
		$current_post_id   = self::$current_page_data[ 'ID' ] ?? 0;
		$current_post_type = self::$current_page_data[ 'post_type' ] ?? '';

		$templates = $this->query_included_templates( $template_cpt, $current_page_type, $current_post_id, $current_post_type );

		// Group templates by type
		foreach ( $templates as $template ) {
			self::$current_page_data[ $template_cpt ][ Utils::get_template_type( $template->ID ) ][] = $template->ID;
		}

		// Apply exclusion rules
		$this->remove_exclusion_rule_posts( $template_cpt, $current_post_id );

		return self::$current_page_data[ $template_cpt ] ?? [];
	}

	/**
	 * Query templates that match include rules.
	 */
	private function query_included_templates( $template_cpt, $page_type, $current_id, $current_post_type ): array {
		global $wpdb;

		$include_key = '_rstb_include';

		// Build meta LIKE conditions
		$conditions = $this->get_meta_conditions( $page_type, $current_id, $current_post_type );

		if ( empty( $conditions ) ) {
			return [];
		}

		$where = implode( ' OR ', $conditions );

		$query = "SELECT p.ID, pm.meta_value 
			FROM {$wpdb->postmeta} pm
			INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
			WHERE pm.meta_key = %s
			AND p.post_type = %s
			AND p.post_status = 'publish'
			AND ( $where )
			ORDER BY p.post_date DESC";

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter -- $where is pre-escaped in get_meta_conditions.
		return $wpdb->get_results( $wpdb->prepare( $query, $include_key, $template_cpt ) );
	}

	/**
	 * Build meta LIKE conditions for include rules.
	 */
	private function get_meta_conditions( $page_type, $current_id, $current_post_type ): array {
		global $wpdb;
		$q_obj = get_queried_object();

		$conds = [ "pm.meta_value LIKE '%\"basic-global\"%'" ];

		switch ( $page_type ) {
			case 'is_404':
				$conds[] = "pm.meta_value LIKE '%\"special-404\"%'";
				break;
			case 'is_search':
				$conds[] = "pm.meta_value LIKE '%\"special-search\"%'";
				break;
			case 'is_archive':
			case 'is_tax':
			case 'is_date':
			case 'is_author':
				$conds[] = "pm.meta_value LIKE '%\"basic-archives\"%'";
				$conds[] = $wpdb->prepare( "pm.meta_value LIKE %s", '%' . $wpdb->esc_like( '"' . $current_post_type . '|all|archive"' ) . '%' );

				if ( $page_type === 'is_tax' && ( is_category() || is_tag() || is_tax() ) ) {
					if ( is_object( $q_obj ) ) {
						$conds[] = $wpdb->prepare( "pm.meta_value LIKE %s", '%' . $wpdb->esc_like( '"' . $current_post_type . '|all|taxarchive|' . $q_obj->taxonomy . '"' ) . '%' );
						$conds[] = $wpdb->prepare( "pm.meta_value LIKE %s", '%' . $wpdb->esc_like( '"tax-' . absint( $q_obj->term_id ) . '"' ) . '%' );
					}
				} elseif ( $page_type === 'is_date' ) {
					$conds[] = "pm.meta_value LIKE '%\"special-date\"%'";
				} elseif ( $page_type === 'is_author' ) {
					$conds[] = "pm.meta_value LIKE '%\"special-author\"%'";
				}
				break;
			case 'is_home':
				$conds[] = "pm.meta_value LIKE '%\"special-blog\"%'";
				break;
			case 'is_front_page':
				$conds[] = "pm.meta_value LIKE '%\"special-front\"%'";
				$conds[] = $wpdb->prepare( "pm.meta_value LIKE %s", '%' . $wpdb->esc_like( '"' . $current_post_type . '|all"' ) . '%' );
				$conds[] = $wpdb->prepare( "pm.meta_value LIKE %s", '%' . $wpdb->esc_like( '"post-' . absint( $current_id ) . '"' ) . '%' );
				break;
			case 'is_singular':
				$conds[] = "pm.meta_value LIKE '%\"basic-singulars\"%'";
				$conds[] = $wpdb->prepare( "pm.meta_value LIKE %s", '%' . $wpdb->esc_like( '"' . $current_post_type . '|all"' ) . '%' );
				$conds[] = $wpdb->prepare( "pm.meta_value LIKE %s", '%' . $wpdb->esc_like( '"post-' . absint( $current_id ) . '"' ) . '%' );

				$post_terms = wp_get_object_terms( $current_id, get_object_taxonomies( $current_post_type ), [ 'fields' => 'ids' ] );
				if ( ! empty( $post_terms ) && ! is_wp_error( $post_terms ) ) {
					foreach ( $post_terms as $term_id ) {
						$conds[] = $wpdb->prepare( "pm.meta_value LIKE %s", '%' . $wpdb->esc_like( '"tax-' . absint( $term_id ) . '-singulars"' ) . '%' );
					}
				}
				break;
			case 'is_woo_shop_page':
				$conds[] = "pm.meta_value LIKE '%\"special-woo-shop\"%'";
				break;
		}

		return $conds;
	}

	/**
	 * Remove excluded templates.
	 */
	private function remove_exclusion_rule_posts( $post_type, $current_post_id ): void {
		foreach ( self::$current_page_data[ $post_type ] ?? [] as $template_type => $ids ) {
			foreach ( $ids as $index => $id ) {
				$rules     = get_post_meta( $id, '_rstb_exclude', true );
				$isExclude = $this->parse_layout_display_condition( $current_post_id, $rules );

				if ( $isExclude ) {
					unset( self::$current_page_data[ $post_type ][ $template_type ][ $index ] );
				}
			}
		}
	}

	/**
	 * Check if a given rule excludes the current post.
	 */
	public function parse_layout_display_condition( $post_id, $rules ): bool {
		if ( ! is_array( $rules ) || empty( $rules ) ) {
			return false;
		}

		foreach ( $rules as $rule ) {
			if ( empty( $rule[ 'criteria' ] ) ) {
				continue;
			}

			if ( $this->match_rule( $rule, $post_id ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Match a single rule.
	 */
	private function match_rule( $rule, $post_id ): bool {
		$criteria = is_array( $rule ) ? ( $rule[ 'criteria' ] ?? '' ) : $rule;

		switch ( $criteria ) {
			case 'basic-global':
				return true;
			case 'basic-singulars':
				return is_singular();
			case 'basic-archives':
				return is_archive();
			case 'special-404':
				return is_404();
			case 'special-search':
				return is_search();
			case 'special-blog':
				return is_home();
			case 'special-front':
				return is_front_page();
			case 'special-date':
				return is_date();
			case 'special-author':
				return is_author();
			case 'special-woo-shop':
				return function_exists( 'is_shop' ) && is_shop();

			case 'specific':
				$ids = array_column( $rule[ 'specific_items' ] ?? [], 'value' );

				return ! empty( $ids ) && in_array( 'post-' . $post_id, $ids, true );

			default:
				// Handle "post_type|all|archive" type rules
				if ( str_contains( $criteria, '|' ) ) {
					$parts = explode( '|', $criteria );

					return $this->match_complex_rule( $parts, $post_id );
				}

				return false;
		}
	}

	/**
	 * Match complex rules like "post|all|taxarchive|taxonomy".
	 */
	private function match_complex_rule( $parts, $post_id ): bool {
		$post_type = $parts[ 0 ] ?? '';
		$type      = $parts[ 2 ] ?? '';
		$taxonomy  = $parts[ 3 ] ?? '';

		if ( $type === '' ) {
			return get_post_type( $post_id ) === $post_type;
		}

		if ( is_archive() && get_post_type() === $post_type ) {
			if ( $type === 'archive' ) {
				return true;
			}
			if ( $type === 'taxarchive' ) {
				$obj = get_queried_object();

				return $obj && $obj->taxonomy === $taxonomy;
			}
		}

		return false;
	}

	/**
	 * Detect current page type (cached).
	 */
	public function get_current_page_type(): ?string {
		if ( self::$current_page_type !== null ) {
			return self::$current_page_type;
		}

		$page_type  = '';
		$current_id = 0;

		if ( is_404() ) {
			$page_type = 'is_404';
		} elseif ( is_search() ) {
			$page_type = 'is_search';
		} elseif ( is_archive() ) {
			$page_type = 'is_archive';
			if ( is_category() || is_tag() || is_tax() ) {
				$page_type = 'is_tax';
			} elseif ( is_date() ) {
				$page_type = 'is_date';
			} elseif ( is_author() ) {
				$page_type = 'is_author';
			} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
				$page_type = 'is_woo_shop_page';
			}
		} elseif ( is_home() ) {
			$page_type = 'is_home';
		} elseif ( is_front_page() ) {
			$page_type  = 'is_front_page';
			$current_id = get_the_ID();
		} elseif ( is_singular() ) {
			$page_type  = 'is_singular';
			$current_id = get_the_ID();
		} else {
			$current_id = get_the_ID();
		}

		self::$current_page_data[ 'ID' ]        = $current_id;
		self::$current_page_data[ 'post_type' ] = get_post_type( $current_id );
		self::$current_page_type                = $page_type;

		return $page_type;
	}
}
