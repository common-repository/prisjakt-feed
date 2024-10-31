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

use PrisjaktFeed\App\Backend\Core\Settings\Menu;
use PrisjaktFeed\Common\Abstracts\Base;

/**
 * Class Plugin
 *
 * @package PrisjaktFeed\App\Backend
 * @since 1.0.0
 */
class Plugin extends Base {



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

		add_filter(
			'plugin_action_links_' . plugin_basename( PRISJAKT_FEED_PLUGIN_FILE ),
			[
				$this,
				'plugin_action_links',
			]
		);

	}


	/**
	 * @param $links
	 *
	 * @return array
	 */
	public function plugin_action_links( $links ): array {

		$menu_slug = prisjakt_feed()->get_menu_slug();
		$url       = $menu_slug . '&page=' . Menu::MENU_SLUG;

		$action_links = [
			'settings' => '<a href="' . admin_url( $url ) . '" aria-label="' . esc_attr__( 'View settings', 'prisjakt-feed' ) . '">' . esc_html__( 'Settings', 'prisjakt-feed' ) . '</a>',
		];

		return array_merge( $action_links, $links );
	}
}
