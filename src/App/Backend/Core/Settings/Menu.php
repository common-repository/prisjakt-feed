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

namespace PrisjaktFeed\App\Backend\Core\Settings;

use PrisjaktFeed\App\Pages\Settings\ExtraFieldsPage;
use PrisjaktFeed\App\Pages\Settings\SettingsPage;
use PrisjaktFeed\App\Pages\Settings\SystemsCheckPage;
use PrisjaktFeed\Common\Utils\Tabs\Tabs;


/**
 * Class Menu
 *
 * @package PrisjaktFeed\App\Backend
 * @since 1.0.0
 */
class Menu extends Settings {



	/**
	 *
	 */
	public const MENU_SLUG = 'prisjakt-feed-settings';


	protected $active_page_tab_instance;

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function init(): void {
		/**
		 * This backend class is only being instantiated in the backend as requested in the Bootstrap class
		 *
		 * @see Requester::is_admin_backend()
		 * @see Bootstrap::__construct
		 *
		 * Add plugin code here for admin settings specific functions
		 */

		add_action( 'admin_menu', [ $this, 'register_settings_page' ] );

		$active_tab = self::get_active_tab();

		if ( $active_tab ) {
			$this->active_page_tab_instance = call_user_func_array(
				[
					$this,
					"get_{$active_tab}_page_instance",
				],
				[]
			);
		}
	}

	/**
	 * Register settings page
	 */
	public function register_settings_page(): void {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		add_submenu_page(
			prisjakt_feed()->get_menu_slug(),
			__( 'Prisjakt Feed Settings', 'prisjakt-feed' ),
			__( 'Settings', 'prisjakt-feed' ),
			'activate_plugins',
			self::MENU_SLUG,
			[ $this, 'render_tabs_navigation' ],
			3
		);
	}

	/**
	 *
	 */
	public function render_content(): void {

		/**
		 * Load content
		 */

		$active_tab = self::get_active_tab();

		if ( $active_tab ) {
			$this->active_page_tab_instance->display();

		}
	}

	/**
	 *
	 */
	public function render_tabs_navigation(): void {

		/**
		 * Tabs nav
		 */
		prisjakt_feed()->templates()->get(
			'backend/settings/tabs',
			null,
			[
				'tabs' => ( new Tabs( $this::TABS ) )->get_tabs(),
			]
		);

		$this->render_content();
	}


	/**
	 *
	 */
	protected function get_settings_page_instance(): SettingsPage {
		return new SettingsPage();
	}

	/**
	 *
	 */
	protected function get_extra_fields_page_instance(): ExtraFieldsPage {
		return new ExtraFieldsPage();
	}

	/**
	 *
	 */
	protected function get_systems_check_page_instance(): SystemsCheckPage {
		return new SystemsCheckPage();
	}
}
