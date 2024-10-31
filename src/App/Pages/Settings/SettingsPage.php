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
use PrisjaktFeed\App\DataStorage\Settings\SettingsData;
use PrisjaktFeed\Common\Utils\Form\Form;
use PrisjaktFeed\Config\Plugin;

/**
 * Class Settings
 *
 * @package PrisjaktFeed\App\Backend
 * @since 1.0.0
 */
class SettingsPage extends Form {

	/**
	 * @var SettingsData
	 */
	public $data_provider;

	/**
	 * @var string
	 */
	public $id = Settings::TABS[0]['id'];


	public function init(): void {
		add_filter(
			'prisjakt_feed_form_localize_script_data',
			[
				$this,
				'set_form_localize_script_extra_data',
			]
		);

		$this->data_provider = new SettingsData();
	}

	/**
	 * Add missing tab request data to ajax callback
	 */
	public function set_form_localize_script_extra_data( $data ): array {
		$data['extra_data']['tab'] = $this->id;

		return $data;
	}

	/**
	 * Set columns data
	 */
	public function set_columns_data(): void {
		$this->set_columns( $this->data_provider->get_columns() );
	}


	/**
	 * Set rows data
	 */
	public function set_rows_data(): void {
		$rows = [];

		$prefix = Plugin::PLUGIN_PREFIX;

		$option_name = $this->data_provider->get_option_name();
		$option_data = get_option( $option_name );

		foreach ( $this->data_provider->get_options() as $option ) :

			$option_id  = $option['id'];
			$option_key = $prefix . $option['id'];

			$rows[] = [
				[
					'type' => 'label',
					'data' => [
						'text' => $option['label'],
					],
				],
				[
					'type' => 'input_checkbox',
					'data' => [
						'class' => [ 'prisjakt-switch' ],
						'id'    => $option_key,
						'name'  => $option_name . "[$option_id][]",
						'value' => $option_data[ $option['id'] ] ?? '',
					],
				],
			];

		endforeach;

		$this->set_rows( $rows );
	}
}
