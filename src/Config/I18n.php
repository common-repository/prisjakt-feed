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

namespace PrisjaktFeed\Config;

use PrisjaktFeed\Common\Abstracts\Base;

/**
 * Internationalization and localization definitions
 *
 * @package PrisjaktFeed\Config
 * @since 1.0.0
 */
final class I18n extends Base {
	/**
	 * Load the plugin text domain for translation
	 *
	 * @docs https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/#loading-text-domain
	 *
	 * @since 1.0.0
	 */
	public function load() {
		load_plugin_textdomain(
			$this->plugin->text_domain(),
			false,
			dirname( plugin_basename( PRISJAKT_FEED_PLUGIN_FILE ) ) . '/languages' // phpcs:disable ImportDetection.Imports.RequireImports.Symbol -- this constant is global
		);
	}
}
