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

namespace PrisjaktFeed\App\Backend;

use PrisjaktFeed\App\Backend\Core\Feeds\Feed\Feed;
use PrisjaktFeed\Common\Abstracts\Base;

/**
 * Class Enqueue
 *
 * @package PrisjaktFeed\App\Backend
 * @since 1.0.0
 */
class Enqueue extends Base {



	/**
	 * @var Feed
	 */
	public $feed;

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		/**
		 * This backend class is only being instantiated in the backend as requested in the Bootstrap class
		 *
		 * @see Requester::is_admin_backend()
		 * @see Bootstrap::__construct
		 *
		 * Add plugin code here
		 */

		$this->feed = new Feed();
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Enqueue scripts
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		foreach (
			[
				[
					'deps'    => [],
					'handle'  => 'prisjakt-feed-backend-css',
					'media'   => 'all',
					'source'  => plugins_url( '/assets/public/css/backend.css', PRISJAKT_FEED_PLUGIN_FILE ),
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
					'handle'    => 'prisjakt-feed-backend-js',
					'in_footer' => true,
					'source'    => plugins_url( '/assets/public/js/backend.js', PRISJAKT_FEED_PLUGIN_FILE ),
                    // phpcs:disable ImportDetection.Imports.RequireImports.Symbol -- this constant is global
					'version'   => $this->plugin->version(),
				],
			] as $js
		) {
			wp_enqueue_script( $js['handle'], $js['source'], $js['deps'], $js['version'], $js['in_footer'] );
		}
	}

}
