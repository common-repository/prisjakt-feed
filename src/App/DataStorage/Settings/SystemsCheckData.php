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

declare( strict_types=1 );

namespace PrisjaktFeed\App\DataStorage\Settings;

use PrisjaktFeed\App\DataStorage\DataStorage;


/**
 * Class SystemsCheckData
 *
 * @since 1.0.0
 */
class SystemsCheckData extends DataStorage {


	public function get_columns(): array {
		return [
			[
				'label' => __( 'System check', 'prisjakt-feed' ),
			],
			[
				'label' => __( 'Status', 'prisjakt-feed' ),
			],
		];
	}

	/**
	 * @return array[]
	 */
	public function get_statuses(): array {

		return [
			'wp_cron'                         => __( 'WP-Cron', 'prisjakt-feed' ),
			'php_version'                     => __( 'PHP-version sufficient', 'prisjakt-feed' ),
			'max_input_vars'                  => __( 'PHP max input vars', 'prisjakt-feed' ),
			'product_feed_directory_writable' => __( 'Product feed directory writable', 'prisjakt-feed' ),
		];
	}
}
