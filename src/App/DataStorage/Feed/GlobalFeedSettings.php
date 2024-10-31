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

namespace PrisjaktFeed\App\DataStorage\Feed;

use PrisjaktFeed\App\DataStorage\DataStorage;
use PrisjaktFeed\Config\Plugin;

/**
 * Class ExtraFields
 *
 * @since 1.0.0
 */
class GlobalFeedSettings extends DataStorage {

	/**
	 * @return string
	 */
	public function get_meta_name(): string {
		return Plugin::PLUGIN_PREFIX . 'global_feed_settings';
	}

	/**
	 * @return string
	 */
	public function get_feed_step_setting_name(): string {
		return 'feed_step';
	}

	/**
	 * @return string
	 */
	public function get_is_active_setting_name(): string {
		return 'is_active';
	}

	/**
	 * @return string
	 */
	public function get_feed_xml_url_setting_name(): string {
		return 'feed_xml_url';
	}

	/**
	 * @param $postID
	 * @param $value
	 */
	public function update_is_active( $postID, $value ): void {

		update_post_meta( $postID, Plugin::PLUGIN_PREFIX . $this->get_is_active_setting_name(), $value );
	}
}
