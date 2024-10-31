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

use PrisjaktFeed\Common\Abstracts\Base;

/**
 * Class Settings
 *
 * @package PrisjaktFeed\App\Backend
 * @since 1.0.0
 */
class Settings extends Base {



	/**
	 * Feed post statuses
	 */
	public const TABS = [
		[
			'id'    => 'settings',
			'label' => 'Plugin settings',
		],
		[
			'id'    => 'extra_fields',
			'label' => 'Extra fields',
		],
		[
			'id'    => 'systems_check',
			'label' => 'Plugin systems check',
		],
	];

	/**
	 * @return array|false|mixed|string
	 */
	public static function get_active_tab() {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$active_tab = isset( $_REQUEST['tab'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['tab'] ) ) : '';

		if ( ! $active_tab ) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$active_tab = isset( $_GET['page'] ) && ( sanitize_text_field( wp_unslash( $_GET['page'] ) ) === Menu::MENU_SLUG ) ? self::get_tab_id_by_index( 0 ) : '';
		}

		if ( in_array( $active_tab, array_column( self::TABS, 'id' ), true ) ) {
			return $active_tab;
		}

		return false;
	}

	/**
	 * @param int $index
	 *
	 * @return string
	 */
	public static function get_tab_id_by_index( int $index ): string {
		return self::TABS[ $index ]['id'];
	}

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
	}
}
