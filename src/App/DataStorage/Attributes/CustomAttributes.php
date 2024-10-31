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

namespace PrisjaktFeed\App\DataStorage\Attributes;

use PrisjaktFeed\App\DataStorage\DataStorage;
use PrisjaktFeed\App\General\Queries;
use PrisjaktFeed\Config\Plugin;

/**
 * Class Attributes
 *
 * @since 1.0.0
 */
class CustomAttributes {



	/**
	 * @var Queries
	 */
	protected $queries;

	public function __construct() {
		$this->queries = new Queries();
	}

	/**
	 *  Get all custom attributes for products
	 */
	public function get_custom_attributes( $check_taxonomy = false ) {
		global $wpdb;
		$list = [];

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching
		$data = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT meta.meta_id, meta.meta_key as name, meta.meta_value as type FROM %1s AS meta, %1s AS posts WHERE meta.post_id = posts.id AND posts.post_type LIKE %s AND meta.meta_key = %s;',
				[
					$wpdb->prefix . 'postmeta',
					$wpdb->prefix . 'posts',
					'%product%',
					'_product_attributes',
				]
			)
		);

		if ( count( $data ) ) {
			foreach ( $data as $value ) {
				$product_attr = json_decode( $value->type, true );
				if ( ! empty( $product_attr ) && is_array( $product_attr ) ) {
					foreach ( $product_attr as $product_attr_key => $arr_value ) {
						if ( ! empty( $arr_value['name'] ) ) {
							$value_display = str_replace( '_', ' ', $arr_value['name'] );

							if ( $check_taxonomy ) {
								$list[ $product_attr_key ] = [
									'label'       => ucfirst( $value_display ),
									'is_taxonomy' => $arr_value['is_taxonomy'],
								];
							} else {
								$list[ $product_attr_key ] = ucfirst( $value_display );
							}
						}
					}
				}
			}

			return $list;
		}
		return [];
	}
}
