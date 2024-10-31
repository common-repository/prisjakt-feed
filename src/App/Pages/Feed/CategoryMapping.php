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
use PrisjaktFeed\App\DataStorage\Feed\CategoryMappingData;
use PrisjaktFeed\App\DataStorage\Feed\GlobalFeedSettings;
use PrisjaktFeed\App\Feed\Mysql;
use PrisjaktFeed\App\Feed\SettingsProvider;
use PrisjaktFeed\App\General\Queries;
use PrisjaktFeed\Config\Plugin;
use WP_CLI;

/**
 * Class Settings
 *
 * @package PrisjaktFeed\App\Backend
 * @since 1.0.0
 */
class CategoryMapping extends Feed {



	/**
	 * @var CategoryMappingData
	 */
	public $data_provider;

	/**
	 * Init
	 */
	public function init(): void {
		parent::init();

		$this->id            = STEPS::get_step_by_index( 3 );
		$this->title         = __( 'Category mapping', 'prisjakt-feed' );
		$this->data_provider = new CategoryMappingData();
	}

	/**
	 *
	 */
	public function set_notices_data(): void {
		$this->set_notices( $this->data_provider->get_notices() );
	}

	/**
	 *
	 */
	public function set_form_actions_data(): void {
		$action_name = $this->get_action_name();

		$this->set_form_actions(
			[
				[
					'label' => __( 'Back', 'prisjakt-feed' ),
					'data'  => [
						'step'        => STEPS::get_step_by_index( 2 ),
						'action'      => 'back',
						'ajax-action' => $action_name,
					],
				],
				[
					'id'    => 'publish',
					'name'  => 'save',
					'type'  => 'submit',
					'label' => __( 'Generate Product Feed', 'prisjakt-feed' ),
					'class' => [ 'button-primary' ],
					'data'  => [
						'action'      => 'generate',
						'ajax-action' => $action_name,
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

		$is_active_setting_name = $this->global_feed_settings->get_is_active_setting_name();

		/**
		 * Update feed status meta
		 */

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$is_active = isset( $_REQUEST[ $is_active_setting_name ] ) ? sanitize_text_field( wp_unslash( $_REQUEST[ $is_active_setting_name ] ) ) : '';

		if ( ! empty( $is_active ) ) {
			$this->enable_feed( $feed_id, $is_active );
		}

		$settings = new SettingsProvider();
		$settings->setBatchSize( 5000 );
		$mysqlProvider = new Mysql();

		$feed = new \Ageno\Prisjakt\Model\Feed( $settings, $mysqlProvider );
		$feed->load( $feed_id );
		$feed->cleanFeedItems();
		$feed->setStatus( 'pending' );
		$feed->save();
	}

	/**
	 *
	 * Enable feed for cron or manual generate
	 *
	 * @param $feed_id
	 * @param $is_active
	 */
	public function enable_feed( $feed_id, $is_active ): void {
		$global_feed_settings = new GlobalFeedSettings();
		$global_feed_settings->update_is_active( $feed_id, filter_var( $is_active, FILTER_VALIDATE_BOOLEAN ) );
	}


	/**
	 * Set rows data
	 */
	public function set_rows_data(): void {

		global $post;

		$prefix     = Plugin::PLUGIN_PREFIX;
		$meta_data  = $this->data_provider->get_post_meta( $post->ID );
		$meta_name  = $this->data_provider->get_meta_name();
		$categories = ( new Queries() )->get_woocommerce_categories();

		$rows = [];

		foreach ( $categories as $category_slug => $category_name ) :

			$rows[] = [
				[
					'type' => 'label',
					'data' => [
						'text' => $category_name,
					],
				],
				[
					'type' => 'input_text',
					'data' => [
						'class' => [ 'category_input_autocomplete' ],
						'id'    => $prefix . $category_slug,
						'name'  => $meta_name . "[$category_slug][]",
						'value' => $meta_data[0][ $category_slug ] ?? '',
					],
				],
			];

		endforeach;

		$this->set_rows( $rows );

	}

	/**
	 *
	 */
	public function set_hidden_rows_data(): void {

	}
}
