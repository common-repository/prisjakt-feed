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

namespace PrisjaktFeed\App\Pages\Settings;

use PrisjaktFeed\App\Backend\Core\Settings\Settings;
use PrisjaktFeed\App\DataStorage\Settings\ExtraFieldsData;
use PrisjaktFeed\Config\Plugin;

/**
 * Class Settings
 *
 * @package PrisjaktFeed\App\Backend
 * @since 1.0.0
 */
class ExtraFieldsPage extends SettingsPage {

	/**
	 * @var ExtraFieldsData
	 */
	public $data_provider;

	/**
	 * @var string
	 */
	public $id = Settings::TABS[1]['id'];

	/**
	 * Init
	 */
	public function init(): void {
		parent::init();

		$this->data_provider = new ExtraFieldsData();
	}

	/**
	 * Set columns data
	 */
	public function set_columns_data(): void {
		$this->set_columns(
			$this->data_provider->get_columns()
		);
	}

	/**
	 * Set rows data
	 */
	public function set_rows_data(): void {
		$rows = [];

		$prefix      = Plugin::PLUGIN_PREFIX;
		$option_name = $this->data_provider->get_option_name();
		$option_data = get_option( $option_name );

		foreach ( $this->data_provider->get_options() as $option ) :

			$option_id  = $option['id'];
			$option_key = $prefix . $option_id;

			$rows[] = [
				[
					'type' => 'label',
					'data' => [
						'text' => $option['id'],
					],
				],
				[
					'type' => 'input_checkbox',
					'data' => [
						'class'            => [ 'prisjakt-switch' ],
						'required_enabled' => $option['required_enabled'] ?? '',
						'id'               => $option_key,
						'name'             => $option_name . "[$option_id][]",
						'value'            => $option_data[ $option['id'] ] ?? '',
					],
				],
			];

		endforeach;

		$this->set_rows( $rows );
	}
}
