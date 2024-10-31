<?php
/**
 * Prisjakt Feed
 *
 * @package   prisjakt-feed
 * @author    Prisjakt <support@prisjakt.nu>
 * @copyright 2022 Prisjakt Feed
 * @license   GPL-2.0-or-later
 * @link      https://prisjakt.nu/
 *
 * @wordpress-plugin
 * Plugin Name:     Prisjakt Feed
 * Plugin URI:      https://schema.prisjakt.nu/integrations/wocommerce
 * Description:     The plugin allows you to generate Prisjakt product feeds
 * Version:         0.3.0.1
 * Author:          Prisjakt
 * License:         GPL v2 or later
 * Author URI:      https://prisjakt.nu/
 * Text Domain:     prisjakt-feed
 * Domain Path:     /languages
 * Requires PHP:    7.1
 * Requires WP:     5.5.0
 * Namespace:       PrisjaktFeed
 */

declare(strict_types=1);

/**
 * Define the default root file of the plugin
 *
 * @since 1.0.0
 */


use PrisjaktFeed\Bootstrap;
use PrisjaktFeed\Config\Setup;
use PrisjaktFeed\Common\Functions;


const PRISJAKT_FEED_PLUGIN_FILE = __FILE__;

/**
 * Load PSR4 autoloader
 *
 * @since 1.0.0
 */
$prisjakt_feed_autoloader = require plugin_dir_path( PRISJAKT_FEED_PLUGIN_FILE ) . 'vendor/autoload.php';

/**
 * Setup hooks (activation, deactivation, uninstall)
 *
 * @since 1.0.0
 */
register_activation_hook( __FILE__, [ Setup::class, 'activation' ] );
register_deactivation_hook( __FILE__, [ Setup::class, 'deactivation' ] );
register_uninstall_hook( __FILE__, [ Setup::class, 'uninstall' ] );


/**
 * Bootstrap the plugin
 *
 * @since 1.0.0
 */
if ( ! class_exists( Bootstrap::class ) ) {
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	wp_die( __( 'Prisjakt Feed is unable to find the Bootstrap class.', 'prisjakt-feed' ) );
}
add_action(
	'plugins_loaded',
	static function () use ( $prisjakt_feed_autoloader ) {
		/**
		 * @see \PrisjaktFeed\Bootstrap
		 */
		try {
			new Bootstrap( $prisjakt_feed_autoloader );
		} catch ( Exception $e ) {
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			wp_die( __( 'Prisjakt Feed is unable to run the Bootstrap class.', 'prisjakt-feed' ) );
		}
	}
);

/**
 * Create a main function for external uses
 *
 * @return Functions
 * @since 1.0.0
 */
function prisjakt_feed(): Functions {
	return new Functions();
}

