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

namespace PrisjaktFeed\App\Backend\Core\Feeds\Feed;

use PrisjaktFeed\App\DataStorage\Feed\GeneralSettingsData;
use PrisjaktFeed\App\DataStorage\Feed\GlobalFeedSettings;
use PrisjaktFeed\Common\Abstracts\Base;

/**
 * Class Steps
 */
class Steps extends Base {

	/**
	 * Feed steps
	 */
	public const STEPS = [
		0 => 'general_settings',
		1 => 'field_mapping',
		2 => 'filters',
		3 => 'category_mapping',
	];

	/**
	 * @param $index
	 *
	 * @return string
	 */
	public static function get_step_by_index( $index ): string {
		return self::STEPS[ $index ] ?? '';
	}

	/**
	 * @return string[]
	 */
	public static function get_steps(): array {
		return self::STEPS;
	}

	/**
	 * @param $feed_id
	 *
	 * @return mixed|string
	 */
	public function get_feed_step( $feed_id ) {
		$global_settings_instance = new GlobalFeedSettings();

		return $global_settings_instance->get_post_meta(
			$feed_id
		)[ $global_settings_instance->get_feed_step_setting_name() ] ?? self::get_step_by_index( 0 );

	}

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
	}
}
