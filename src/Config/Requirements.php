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

namespace PrisjaktFeed\Config;

use PrisjaktFeed\Common\Abstracts\Base;
use PrisjaktFeed\Common\Utils\Errors;

/**
 * Check if any requirements are needed to run this plugin. We use the
 * "Requirements" package from "MicroPackage" to check if any PHP Extensions,
 * plugins, themes or PHP/WP version are required.
 *
 * @docs https://github.com/micropackage/requirements
 *
 * @package PrisjaktFeed\Config
 * @since 1.0.0
 */
final class Requirements extends Base {



	/**
	 * Specifications for the requirements
	 *
	 * @return array : used to specify the requirements
	 * @since 1.0.0
	 */
	public function specifications(): array {
		return apply_filters(
			'prisjakt_feed_plugin_requirements',
			[
				'php'            => $this->plugin->required_php(),
				'php_extensions' => [],
				'wp'             => $this->plugin->required_wp(),
				'plugins'        => [
					[
						'file' => 'woocommerce/woocommerce.php',
						'name' => 'WooCommerce',
					],

				],
			]
		);
	}

	/**
	 * Plugin requirements checker
	 *
	 * @since 1.0.0
	 */
	public function check() {
		if ( class_exists( '\Micropackage\Requirements\Requirements' ) ) {
			$this->requirements = new \Micropackage\Requirements\Requirements(
				$this->plugin->name(),
				$this->specifications()
			);
			if ( ! $this->requirements->satisfied() ) {
				$this->requirements->print_notice();
				Errors::plugin_die();
			}
		} else {
			$this->version_compare();
		}
	}

	/**
	 * Compares PHP & WP versions and kills plugin if it's not compatible
	 *
	 * @since 1.0.0
	 */
	public function version_compare() {
		foreach (
			[
				[
					'current' => PHP_VERSION,
					'compare' => $this->plugin->required_php(),
					'title'   => __( 'Invalid PHP version', 'prisjakt-feed' ),
					'message' => sprintf( /* translators: %1$1s: required php version, %2$2s: current php version */
						__(
							'You must be using PHP %1$1s or greater. You are currently using PHP %2$2s.',
							'prisjakt-feed'
						),
						$this->plugin->required_php(),
						PHP_VERSION
					),
				],
				[
					'current' => get_bloginfo( 'version' ),
					'compare' => $this->plugin->required_wp(),
					'title'   => __( 'Invalid WordPress version', 'prisjakt-feed' ),
					'message' => sprintf( /* translators: %1$1s: required wordpress version, %2$2s: current wordpress version */
						__(
							'You must be using WordPress %1$1s or greater. You are currently using WordPress %2$2s.',
							'prisjakt-feed'
						),
						$this->plugin->required_wp(),
						get_bloginfo( 'version' )
					),
				],
			] as $compat_check
		) {
			if ( version_compare(
				$compat_check['compare'],
				$compat_check['current'],
				'>='
			) ) {
				Errors::plugin_die(
					$compat_check['message'],
					$compat_check['title'],
					plugin_basename( __FILE__ )
				);
			}
		}
	}

	/**
	 * @return bool
	 */
	public function is_enabled_wp_cron(): bool {
		return ! ( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON );
	}

	/**
	 * @return string
	 */
	public function get_php_version(): string {
		return PHP_VERSION;
	}

	/**
	 * @return int
	 */
	public function get_php_max_input_vars(): int {
		return (int) ini_get( 'max_input_vars' );
	}

	/**
	 * @return string
	 */
	public function get_upload_dir(): string {
		$uploadPaths = wp_get_upload_dir();

		return $uploadPaths['basedir'] . DIRECTORY_SEPARATOR . 'prisjakt' . DIRECTORY_SEPARATOR;
	}

	/**
	 * @return bool
	 */
	public function is_upload_dir_writable(): bool {
		$upload_dir = $this->get_upload_dir();

        // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged,WordPress.WP.AlternativeFunctions.file_system_read_fopen
		return (bool) @fopen( $upload_dir . 'test-feed.xml', 'ab' );
	}
}
