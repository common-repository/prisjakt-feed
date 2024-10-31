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

namespace PrisjaktFeed\App\Frontend;

use PrisjaktFeed\Common\Abstracts\Base;

/**
 * Class Enqueue
 *
 * @package PrisjaktFeed\App\Frontend
 * @since 1.0.0
 */
class Enqueue extends Base {



	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		/**
		 * This frontend class is only being instantiated in the frontend as requested in the Bootstrap class
		 *
		 * @see Requester::is_frontend()
		 * @see Bootstrap::__construct
		 *
		 * Add plugin code here
		 */
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Enqueue scripts function
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		foreach (
			[
				[
					'deps'    => [],
					'handle'  => 'plugin-name-frontend-css',
					'media'   => 'all',
					'source'  => plugins_url( '/assets/public/css/frontend.css', PRISJAKT_FEED_PLUGIN_FILE ),
                    // phpcs:disable ImportDetection.Imports.RequireImports.Symbol -- this constant is global
					'version' => $this->plugin->version(),
				],
			] as $css
		) {
			wp_enqueue_style( $css['handle'], $css['source'], $css['deps'], $css['version'], $css['media'] );
		}
		foreach (
			[
				[
					'deps'      => [],
					'handle'    => 'plugin-test-frontend-js',
					'in_footer' => true,
					'source'    => plugins_url( '/assets/public/js/frontend.js', PRISJAKT_FEED_PLUGIN_FILE ),
                    // phpcs:disable ImportDetection.Imports.RequireImports.Symbol -- this constant is global
					'version'   => $this->plugin->version(),
				],
			] as $js
		) {
			wp_enqueue_script( $js['handle'], $js['source'], $js['deps'], $js['version'], $js['in_footer'] );
		}

		global $wp_query;

		wp_localize_script(
			'plugin-test-frontend-js',
			'plugin_frontend_script',
			[
				'plugin_frontend_url'  => admin_url( 'admin-ajax.php' ),
				'plugin_wp_query_vars' => $wp_query->query_vars,
			]
		);
	}
}
