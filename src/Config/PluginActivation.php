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
 * Plugin setup hook (activation)
 *
 * @package PrisjaktFeed\Config
 * @since 1.0.0
 */
final class PluginActivation {



	/**
	 * @var DataStorage
	 */
	public $settings = [];

	/**
	 *
	 */
	public function __construct() {

		$this->settings[] = new SettingsData();
		$this->settings[] = new ExtraFieldsData();

		$this->setup_options();
		$this->setup_posts_meta_type_index();
		$this->setup_schema();
		$this->setup_files();
		$this->setup_cron();
	}

	/**
	 * Setup required options fields and init empty options if user first time activate plugin
	 */
	protected function setup_options(): void {

		foreach ( $this->settings as $setting ) :

			$option_name = $setting->get_option_name();

			/**
			 * Set default empty options
			 */
			if ( ! get_option( $option_name ) ) {
				update_option( $option_name, [] );
			}

			$required_options  = $setting->get_required_enabled_options();
			$options_to_enable = [];

			/**
			 *  Setup required fields
			 */
			foreach ( $required_options as $required_option ) :
				$options_to_enable[ $required_option ] = true;
			endforeach;

			if ( $options_to_enable ) {
				update_option( $option_name, array_merge( get_option( $option_name ), $options_to_enable ) );
			}

		endforeach;
	}

	protected function setup_posts_meta_type_index(): void {
		global $wpdb;

		try {
			$wpdb->query( $wpdb->prepare( 'CREATE INDEX `%1s` ON %1s (`post_type`);', [ 'post_type', $wpdb->posts ] ) );
		} catch ( \Throwable $err ) {
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			wp_die( $err );
		}
	}

	protected function setup_schema(): void {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		global $wpdb;

		$feed_sql_create = 'CREATE TABLE `' . $wpdb->prefix . "prisjakt_feed` (
                      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                      `post_id` bigint(20) unsigned NOT NULL,
                      `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                      `rows` int(11) unsigned NOT NULL DEFAULT 0,
                      `completed_rows` int(11) unsigned NOT NULL DEFAULT 0,
                      `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
                      `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                      `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                      PRIMARY KEY (`id`),
                      UNIQUE KEY `post_id` (`post_id`) USING BTREE,
                      CONSTRAINT  `" . $wpdb->prefix . 'prisjakt_feed_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `' . $wpdb->prefix . 'posts` (`ID`) ON DELETE CASCADE
                    ) ENGINE=InnoDB ' . $wpdb->get_charset_collate() . ';';

		maybe_create_table( $wpdb->prefix . 'prisjakt_feed', $feed_sql_create );

		$feed_item_sql_create = 'CREATE TABLE `' . $wpdb->prefix . "prisjakt_feed_item` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `feed_id` int(11) unsigned NOT NULL,
                  `xml` blob NOT NULL,
                  `post_id` bigint(20) unsigned NOT NULL,
                  `status` varchar(255) NOT NULL DEFAULT 'new',
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `FEED_ID_POST_ID` (`feed_id`,`post_id`) USING BTREE,
                  KEY `post_id` (`post_id`),
                  CONSTRAINT `" . $wpdb->prefix . 'prisjakt_feed_item_ibfk_1` FOREIGN KEY (`feed_id`) REFERENCES `' . $wpdb->prefix . 'prisjakt_feed` (`id`) ON DELETE CASCADE,
                  CONSTRAINT `' . $wpdb->prefix . 'prisjakt_feed_item_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `' . $wpdb->prefix . 'posts` (`ID`) ON DELETE CASCADE
                ) ENGINE=InnoDB ' . $wpdb->get_charset_collate() . ';';

		maybe_create_table( $wpdb->prefix . 'prisjakt_feed_item', $feed_item_sql_create );

	}

	protected function setup_files(): void {
		$upload_dir = wp_get_upload_dir();
		$files      = [
			[
				'base'    => $upload_dir['basedir'] . '/prisjakt',
				'file'    => 'test-feed.xml',
				'content' => '',
			],
		];

		foreach ( $files as $file ) {
			if ( wp_mkdir_p( $file['base'] ) && ! file_exists( trailingslashit( $file['base'] ) . $file['file'] ) ) {
                // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.file_system_read_fopen
				$file_handle = @fopen( trailingslashit( $file['base'] ) . $file['file'], 'wb' );
				if ( $file_handle ) {
                    // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fwrite
					fwrite( $file_handle, $file['content'] );
                    // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fclose
					fclose( $file_handle );
				}
			}
		}
	}

	protected function setup_cron() {
		wp_schedule_event( time() + ( MINUTE_IN_SECONDS ), 'every_minute', 'prisjakt_feed_cron_job' );
	}
}


