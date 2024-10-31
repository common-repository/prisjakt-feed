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

namespace PrisjaktFeed\App\DataStorage\Settings;

use PrisjaktFeed\App\DataStorage\DataStorage;
use PrisjaktFeed\App\Backend\Core\Settings\Settings;
use PrisjaktFeed\Config\Plugin;

/**
 * Class SettingsData
 *
 * @since 1.0.0
 */
class SettingsData extends DataStorage {


	/**
	 * Data storage for options
	 *
	 * @return string
	 */
	public function get_option_name(): string {
		return Plugin::PLUGIN_PREFIX . Settings::get_tab_id_by_index( 0 );
	}

	public function get_columns(): array {
		return [
			[
				'label' => __( 'Plugin settings', 'prisjakt-feed' ),
			],
			[
				'label' => __( 'Off / On', 'prisjakt-feed' ),
			],
		];
	}

	public function get_options(): array {
		return [
			[
				'id'    => 'enable_logging',
				'label' => __( 'Enable logging (Enable only on advice of our support-team)', 'prisjakt-feed' ),
			],
		];
	}
}
