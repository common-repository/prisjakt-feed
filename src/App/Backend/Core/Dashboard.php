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

namespace PrisjaktFeed\App\Backend\Core;

use PrisjaktFeed\Common\Abstracts\Base;

/**
 * Class Settings
 *
 * @package PrisjaktFeed\App\Backend
 * @since 1.0.0
 */
class Dashboard extends Base {


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
		 * Add plugin code here for admin settings specific functions
		 */

		add_action( 'admin_menu', [ $this, 'register_menu_page' ] );
	}


	/**
	 * Register main menu page
	 */
	public function register_menu_page() {

		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		add_menu_page(
			__( 'Prisjakt', 'prisjakt-feed' ),
			__( 'Prisjakt', 'prisjakt-feed' ),
			'activate_plugins',
			prisjakt_feed()->get_menu_slug(),
			null,
			plugins_url( '/assets/public/images/menu-icon.svg', PRISJAKT_FEED_PLUGIN_FILE ),
			81
		);
	}
}
