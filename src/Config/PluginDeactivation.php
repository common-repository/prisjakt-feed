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

use PrisjaktFeed\App\DataStorage\DataStorage;
use PrisjaktFeed\App\DataStorage\Settings\ExtraFieldsData;
use PrisjaktFeed\App\DataStorage\Settings\SettingsData;

/**
 * Plugin setup hook (deactivation)
 *
 * @package PrisjaktFeed\Config
 * @since 1.0.0
 */
final class PluginDeactivation {

	public function __construct() {
		$this->remove_posts_meta_type_index();
		$this->uninstall_cron();
	}

	public function remove_posts_meta_type_index(): void {
		global $wpdb;

		try {
			$wpdb->query( $wpdb->prepare( 'DROP INDEX `%1s` ON %1s', 'post_type', $wpdb->posts ) );
		} catch ( \Throwable $err ) {
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			wp_die( $err );
		}
	}

	protected function uninstall_cron() {
		wp_clear_scheduled_hook( 'prisjakt_feed_cron_job' );
	}
}


