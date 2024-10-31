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

namespace PrisjaktFeed\App\Pages\Feed;

use PrisjaktFeed\App\Backend\Core\Feeds\Feed\Steps;
use PrisjaktFeed\App\Backend\Core\Feeds\Feeds;
use PrisjaktFeed\App\DataStorage\Attributes\Attributes;
use PrisjaktFeed\App\DataStorage\Feed\CategoryMappingAutocomplete;
use PrisjaktFeed\App\DataStorage\Feed\FiltersValues;
use PrisjaktFeed\App\DataStorage\Feed\GeneralSettingsData;
use PrisjaktFeed\App\DataStorage\Feed\GlobalFeedSettings;
use PrisjaktFeed\App\DataStorage\Form\Messages;
use PrisjaktFeed\Common\Utils\Fields\Fields;
use PrisjaktFeed\Common\Utils\Form\Form;
use PrisjaktFeed\Config\Plugin;

/**
 * Class Settings
 *
 * @package PrisjaktFeed\App\Backend
 * @since 1.0.0
 */
class Feed extends Form {



	/**
	 * @var string
	 */
	protected $feed_ajax_nonce = Plugin::PLUGIN_PREFIX . 'steps';

	/**
	 * @var Fields
	 */
	protected $fields;
	/**
	 * @var Steps
	 */
	protected $steps;

	/**
	 * @var GeneralSettingsData
	 */
	protected $general_settings_instance;

	/**
	 * @var GlobalFeedSettings
	 */
	protected $global_feed_settings;


	/**
	 * Load once script
	 */
	public function init(): void {

		add_filter( 'prisjakt_feed_form_localize_script_load', [ $this, 'disable_load_form_script' ] );
		add_filter( 'prisjakt_feed_load_form_wrapper', [ $this, 'disable_load_form_wrapper' ] );

		$this->fields                    = new Fields();
		$this->steps                     = new Steps();
		$this->general_settings_instance = new GeneralSettingsData();
		$this->global_feed_settings      = new GlobalFeedSettings();
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
	 * Enqueue scripts
	 */
	public function enqueue_scripts(): void {
		$post_type = Feeds::POST_TYPE['id'];

		if ( get_post_type() !== $post_type ) {
			return;
		}

		foreach (
			[
				[
					'deps'      => [ 'jquery' ],
					'handle'    => 'feed-feed-form-js',
					'in_footer' => true,
					'source'    => plugins_url( '/assets/public/js/feed.js', PRISJAKT_FEED_PLUGIN_FILE ),
					'version'   => prisjakt_feed()->get_data()['version'],
				],
			] as $js
		) {

			if ( wp_script_is( $js['handle'] ) ) {
				return;
			}

			$feed_id = get_the_ID();

			$script_data = [
				'form_id'             => 'post',
				'ajax_url'            => admin_url( 'admin-ajax.php' ),
				'post_id'             => $feed_id,
				'ajax_nonce'          => wp_create_nonce( $this->feed_ajax_nonce ),
				'prefix'              => Plugin::PLUGIN_PREFIX,
				'steps'               => $this->steps::STEPS,
				'step'                => $this->steps->get_feed_step( $feed_id ),
				'category_mapping'    => ( new CategoryMappingAutocomplete() )->get_category_mapping_autocomplete(),
				'feed_filters'        => [
					'_categories' => ( new FiltersValues() )->get_categories(),
				],
				'validation_messages' => ( new Messages() )->get_messages(),
			];

			wp_enqueue_script( $js['handle'], $js['source'], $js['deps'], $js['version'], $js['in_footer'] );
			wp_localize_script(
				$js['handle'],
				'prisjakt_ajax_object',
				$script_data
			);
		}
	}


	/**
	 * @param $feed_id
	 * @param $form_options
	 */
	public function update_post( $feed_id, $form_options ): void {
		parent::update_post( $feed_id, $form_options );

		$feed_step_setting_name = $this->global_feed_settings->get_feed_step_setting_name();

		/**
		 * Update feed step meta
		 */

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$feed_step = isset( $_REQUEST[ $feed_step_setting_name ] ) ? sanitize_text_field( wp_unslash( $_REQUEST[ $feed_step_setting_name ] ) ) : '';

		if ( ! empty( $feed_step ) ) {
			$this->update_feed_step( $feed_id, $feed_step, $feed_step_setting_name );
		}
	}


	/**
	 * @param $feed_id
	 * @param $feed_step
	 * @param $feed_step_setting_name
	 */
	public function update_feed_step( $feed_id, $feed_step, $feed_step_setting_name ): void {
		$feed_meta = $this->global_feed_settings->get_post_meta( $feed_id );

		if ( ! is_array( $feed_meta ) ) {
			$this->global_feed_settings->set_post_meta( $feed_id, [] );

			$feed_meta = [];
		}

		$feed_meta[ $feed_step_setting_name ] = $feed_step;

		$this->global_feed_settings->set_post_meta( $feed_id, $feed_meta );
	}


	/**
	 * Ajax callback
	 */
	public function update_form_callback(): void {
		check_ajax_referer( $this->feed_ajax_nonce, 'security' );

		if ( ! current_user_can( 'edit_pages' ) ) {
			wp_die( -1 );
		}

		$this->update_form_data_process();

		wp_send_json_success( 'success' );
		die();
	}

	/**
	 *
	 */
	public function set_hidden_rows_data(): void {
		$prefix    = Plugin::PLUGIN_PREFIX;
		$meta_name = $this->data_provider->get_meta_name();
		$rows      = $this->data_provider->get_fields();

		foreach ( $rows as $row_index => $row ) :

			foreach ( $row as $field_index => $field ) :
				$field_id  = $field['id'];
				$field_key = $prefix . $field_id;

				$rows[ $row_index ][ $field_index ]['data']['id']   = $field_key;
				$rows[ $row_index ][ $field_index ]['data']['name'] = $meta_name . "[$field_id][]";

				unset( $rows[ $row_index ][ $field_index ]['id'] );

			endforeach;
		endforeach;

		$this->set_hidden_rows(
			$rows
		);
	}

	/**
	 * @return bool
	 */
	public function disable_load_form_script(): bool {
		return false;
	}


	/**
	 * @return bool
	 */
	public function disable_load_form_wrapper(): bool {
		return false;
	}
}
