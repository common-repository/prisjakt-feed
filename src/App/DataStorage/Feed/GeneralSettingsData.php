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

namespace PrisjaktFeed\App\DataStorage\Feed;

use PrisjaktFeed\App\Backend\Core\Feeds\Feed\Steps;
use PrisjaktFeed\App\DataStorage\DataStorage;
use PrisjaktFeed\Config\Plugin;

/**
 * Class ExtraFields
 *
 * @since 1.0.0
 */
class GeneralSettingsData extends DataStorage {


	/**
	 * @return string
	 */
	public function get_meta_name(): string {
		return Plugin::PLUGIN_PREFIX . Steps::get_step_by_index( 0 );
	}

	/**
	 * @return array
	 */
	public function get_columns(): array {
		return [];
	}

	/**
	 * @return array[]
	 */
	public function get_fields(): array {
		return [
			[
				'id'   => $this->get_feed_name_setting_name(),
				'text' => __( 'Name', 'prisjakt-feed' ),
				'type' => 'input_text',
				'data' => [
					'required' => true,
				],
			],
			[
				'id'   => 'feed_refresh_interval',
				'text' => __( 'Refresh interval', 'prisjakt-feed' ),
				'type' => 'input_radio',
				'data' => [
					'radio_buttons' => $this->get_cron_options(),
				],
			],
		];
	}

	/**
	 * @return array
	 */
	public function get_required_fields_ids(): array {
		return [];
	}


	/**
	 * @return string
	 */
	public function get_feed_name_setting_name(): string {
		return 'feed_name';
	}

	/**
	 * @return array
	 */
	public function get_cron_options(): array {
		return [
			'daily'      => __( '24h', 'prisjakt-feed' ),
			'twicedaily' => __( '12h', 'prisjakt-feed' ),
			'hourly'     => __( '1h', 'prisjakt-feed' ),
		];
	}
}
