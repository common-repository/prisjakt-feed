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

namespace PrisjaktFeed\App\Backend\Core\Feeds;

/**
 * Class Menu
 */
class Menu extends Feeds {
	/**
	 * Initialize the class.
	 *
	 * @return void
	 * @since  1.0.0
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

		add_action( 'admin_menu', [ $this, 'register_feed_page' ] );
	}

	/**
	 * Register feed page
	 *
	 * @since  1.0.0
	 */
	public function register_feed_page(): void {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$menu_slug = prisjakt_feed()->get_menu_slug();

		add_submenu_page(
			$menu_slug,
			__( 'All feeds', 'prisjakt-feed' ),
			__( 'All feeds', 'prisjakt-feed' ),
			'activate_plugins',
			sprintf( 'edit.php?post_type=%s', $this::POST_TYPE['id'] ),
			false,
			1
		);

		add_submenu_page(
			$menu_slug,
			__( 'Add new feed', 'prisjakt-feed' ),
			__( 'Add new feed', 'prisjakt-feed' ),
			'activate_plugins',
			sprintf( 'post-new.php?post_type=%s', $this::POST_TYPE['id'] ),
			false,
			2
		);
	}
}
