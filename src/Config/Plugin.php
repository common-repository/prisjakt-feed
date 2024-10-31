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

use PrisjaktFeed\Common\Traits\Singleton;

/**
 * Plugin data which are used through the plugin, most of them are defined
 * by the root file meta data. The data is being inserted in each class
 * that extends the Base abstract class
 *
 * @see Base
 * @package PrisjaktFeed\Config
 * @since 1.0.0
 */
final class Plugin {
	/**
	 * Singleton trait
	 */
	use Singleton;


	public const PLUGIN_PREFIX = 'prisjakt_feed_';

	/**
	 * Get the plugin meta data from the root file and include own data
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function data(): array {
		$plugin_data = apply_filters(
			'prisjakt_feed_plugin_data',
			[
				'settings'               => get_option( 'prisjakt-feed-settings' ),
				'plugin_path'            => untrailingslashit(
					plugin_dir_path( PRISJAKT_FEED_PLUGIN_FILE )  // phpcs:disable ImportDetection.Imports.RequireImports.Symbol -- this constant is global
				),
				'plugin_template_folder' => 'templates',
				'ext_template_folder'    => 'prisjakt-feed-templates',
				/**
				 * Add extra data here
				 */
			]
		);

		return array_merge(
			apply_filters(
				'prisjakt_feed_plugin_meta_data',
				get_file_data(
					PRISJAKT_FEED_PLUGIN_FILE,
					// phpcs:disable ImportDetection.Imports.RequireImports.Symbol -- this constant is global
					[
						'name'         => 'Plugin Name',
						'uri'          => 'Plugin URI',
						'description'  => 'Description',
						'version'      => 'Version',
						'author'       => 'Author',
						'author-uri'   => 'Author URI',
						'text-domain'  => 'Text Domain',
						'domain-path'  => 'Domain Path',
						'required-php' => 'Requires PHP',
						'required-wp'  => 'Requires WP',
						'namespace'    => 'Namespace',
					],
					'plugin'
				)
			),
			$plugin_data
		);
	}

	/**
	 * Get the plugin global settings prefix
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function plugin_prefix(): string {
		return self::PLUGIN_PREFIX;
	}

	/**
	 * Get the plugin external template path
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function plugin_path(): string {
		return $this->data()['plugin_path'];
	}

	/**
	 * Get the plugin internal template path
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function template_path(): string {
		return $this->data()['plugin_path'] . '/' . $this->data()['plugin_template_folder'];
	}

	/**
	 * Get the plugin internal template folder name
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function template_folder(): string {
		return $this->data()['plugin_template_folder'];
	}

	/**
	 * Get the plugin external template path
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function ext_template_path(): string {
		return $this->data()['plugin_path'] . '/' . $this->data()['ext_template_folder'];
	}

	/**
	 * Get the plugin external template path
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function ext_template_folder(): string {
		return $this->data()['ext_template_folder'];
	}

	/**
	 * Get the plugin settings
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function settings(): string {
		return $this->data()['settings'];
	}

	/**
	 * Get the plugin version number
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function version(): string {
		return $this->data()['version'];
	}

	/**
	 * Get the required minimum PHP version
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function required_php(): string {
		return $this->data()['required-php'];
	}

	/**
	 * Get the required minimum WP version
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function required_wp(): string {
		return $this->data()['required-wp'];
	}

	/**
	 * Get the plugin name
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function name(): string {
		return $this->data()['name'];
	}

	/**
	 * Get the plugin url
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function uri(): string {
		return $this->data()['uri'];
	}

	/**
	 * Get the plugin description
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function description(): string {
		return $this->data()['description'];
	}

	/**
	 * Get the plugin author
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function author(): string {
		return $this->data()['author'];
	}

	/**
	 * Get the plugin author uri
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function author_uri(): string {
		return $this->data()['author-uri'];
	}

	/**
	 * Get the plugin text domain
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function text_domain(): string {
		return $this->data()['text-domain'];
	}

	/**
	 * Get the plugin domain path
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function domain_path(): string {
		return $this->data()['domain-path'];
	}

	/**
	 * Get the plugin namespace
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function namespace(): string {
		return $this->data()['namespace'];
	}
}
