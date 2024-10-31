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

use PrisjaktFeed\Common\Traits\Singleton;

/**
 * Plugin setup hooks (activation, deactivation, uninstall)
 *
 * @package PrisjaktFeed\Config
 * @since 1.0.0
 */
final class Setup {


	/**
	 * Singleton trait
	 */
	use Singleton;

	/**
	 * Run only once after plugin is activated
	 *
	 * @docs https://developer.wordpress.org/reference/functions/register_activation_hook/
	 */
	public static function activation(): void {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		new PluginActivation();

		/**
		 * Use this to add a database table after the plugin is activated for example
		 */

		flush_rewrite_rules();

	}

	/**
	 * Run only once after plugin is deactivated
	 *
	 * @docs https://developer.wordpress.org/reference/functions/register_deactivation_hook/
	 */
	public static function deactivation(): void {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		new PluginDeactivation();

		/**
		 * Use this to register a function which will be executed when the plugin is deactivated
		 */

		flush_rewrite_rules();

	}

	/**
	 * Run only once after plugin is uninstalled
	 *
	 * @docs https://developer.wordpress.org/reference/functions/register_uninstall_hook/
	 */
	public static function uninstall(): void {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		/**
		 * Use this to remove plugin data and residues after the plugin is uninstalled for example
		 */

	}
}
