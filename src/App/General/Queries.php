<?php
/**
 * Prisjakt Feed
 *
 * @package   prisjakt-feed
 * @author    Prisjakt <support@prisjakt.nu>
 * @copyright 2022 Prisjakt Feed
 * @license   MIT
 * @link      https://prisjakt.nu
 */

declare(strict_types=1);

namespace PrisjaktFeed\App\General;

use PrisjaktFeed\Common\Abstracts\Base;
use PrisjaktFeed\Config\Plugin;

/**
 * Class Queries
 *
 * @package PrisjaktFeed\App\General
 * @since 1.0.0
 */
class Queries extends Base {



	/**
	 * @var string
	 */
	public $custom_attributes_transient_key = Plugin::PLUGIN_PREFIX . 'product_attributes';

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		/**
		 * This general class is always being instantiated as requested in the Bootstrap class
		 *
		 * @see Bootstrap::__construct
		 *
		 * Add plugin code here
		 */

		add_action( 'woocommerce_update_product', [ $this, 'on_product_save' ], 10 );
	}

	/**
	 * Remove attributes cache query on product update
	 */
	public function on_product_save(): void {
		delete_transient( $this->custom_attributes_transient_key );
	}


	/**
	 * @param $category
	 * @param int      $level
	 *
	 * @return array
	 */
	public function wc_hierarchical_term_tree( $category, int $level = 0 ): array {
		$categories = [];

		$args = [
			'parent'        => $category,
			'hide_empty'    => false,
			'no_found_rows' => true,
		];

		$next = get_terms( 'product_cat', $args );

		if ( $next ) {

			foreach ( $next as $sub_category ) {

				$term_id      = $sub_category->term_id;
				$woo_category = $sub_category->name;

				if ( 0 === $sub_category->parent ) {
					$level = 0;
				}

				$value                  = '<div class="level-' . $level . '">' . str_repeat( '-', $level ) . ' ' . $woo_category . '</div>';
				$categories[ $term_id ] = $value;

				0 !== $categories = $sub_category->term_id ? $categories + $this->wc_hierarchical_term_tree( $sub_category->term_id, ++$level ) : null;
			}
		}

		return $categories;
	}


	/**
	 * @return array
	 */
	public function get_woocommerce_categories(): array {

		return $this->wc_hierarchical_term_tree( 0 );
	}
}
