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

namespace PrisjaktFeed\App\Pages\Feed;

use PrisjaktFeed\App\Backend\Core\Feeds\Feed\Steps;
use PrisjaktFeed\App\DataStorage\Feed\GeneralSettingsData;
use PrisjaktFeed\Config\Plugin;

/**
 * Class Settings
 *
 * @package PrisjaktFeed\App\Backend
 * @since 1.0.0
 */
class GeneralSettings extends Feed {


	/**
	 * @var string
	 */
	public $form_template = 'presentation-table';

	/**
	 * @var GeneralSettingsData
	 */
	public $data_provider;

	/**
	 * Init
	 */
	public function init(): void {
		parent::init();

		$this->id            = STEPS::get_step_by_index( 0 );
		$this->title         = __( 'General feed settings', 'prisjakt-feed' );
		$this->data_provider = new GeneralSettingsData();
	}


	/**
	 *
	 */
	public function set_form_actions_data(): void {

		$this->set_form_actions(
			[
				[
					'type'  => 'button',
					'label' => __( 'Save & Continue', 'prisjakt-feed' ),
					'class' => [ 'button-primary' ],
					'data'  => [
						'step'        => STEPS::get_step_by_index( 1 ),
						'action'      => 'save_continue',
						'ajax-action' => $this->get_action_name(),
					],
				],
			]
		);
	}

	/**
	 * @param $feed_id
	 * @param $form_options
	 */
	public function update_post( $feed_id, $form_options ): void {
		parent::update_post( $feed_id, $form_options );

		$feed_name_setting_name = $this->general_settings_instance->get_feed_name_setting_name();
		$meta_name              = $this->data_provider->get_meta_name();

		$feed_title = isset( $form_options[ $meta_name ][ $feed_name_setting_name ] ) ?
			$form_options[ $meta_name ][ $feed_name_setting_name ][0] :
			__( 'New feed', 'prisjakt-feed' );

		$feed = [
			'ID'          => $feed_id,
			'post_title'  => $feed_title,
			'post_status' => 'publish',
		];

		wp_update_post( $feed );
	}


	/**
	 * Set rows data
	 */
	public function set_rows_data(): void {

		global $post;

		$rows   = [];
		$prefix = Plugin::PLUGIN_PREFIX;

		$meta_name = $this->data_provider->get_meta_name();
		$meta_data = $this->data_provider->get_post_meta( $post->ID );

		foreach ( $this->data_provider->get_fields() as $field ) :

			$field_id  = $field['id'];
			$field_key = $prefix . $field_id;

			$field['data']['id']    = $field_key;
			$field['data']['name']  = $meta_name . "[$field_id][]";
			$field['data']['value'] = $meta_data[0][ $field_id ] ?? '';

			$rows[] = [
				[
					'type' => 'label',
					'data' => [
						'text' => $field['text'],
					],
				],
				$field,
			];

		endforeach;

		$this->set_rows( $rows );
	}

	public function set_hidden_rows_data(): void {

	}
}
