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
use PrisjaktFeed\App\DataStorage\Feed\FiltersData;
use PrisjaktFeed\Config\Plugin;

/**
 * Class Settings
 *
 * @package PrisjaktFeed\App\Backend
 * @since 1.0.0
 */
class Filters extends Feed {

	/**
	 * @var FiltersData
	 */
	public $data_provider;

	/**
	 * Init
	 */
	public function init(): void {
		parent::init();

		$this->id            = STEPS::get_step_by_index( 2 );
		$this->title         = __( 'Feed Filters', 'prisjakt-feed' );
		$this->data_provider = new FiltersData( $this );
	}

	/**
	 *
	 */
	public function set_notices_data(): void {
		$this->set_notices( $this->data_provider->get_notices() );
	}

	/**
	 * Form actions
	 */
	public function set_form_actions_data(): void {
		$action_name = $this->get_action_name();

		$this->set_form_actions(
			[
				[
					'label' => __( 'Back', 'prisjakt-feed' ),
					'data'  => [
						'step'        => STEPS::get_step_by_index( 1 ),
						'action'      => 'back',
						'ajax-action' => $action_name,
					],
				],
				[
					'label' => __( 'Save & Continue', 'prisjakt-feed' ),
					'class' => [ 'button-primary' ],
					'data'  => [
						'step'        => STEPS::get_step_by_index( 3 ),
						'action'      => 'continue',
						'ajax-action' => $action_name,
					],
				],
			]
		);
	}

	/**
	 *
	 */
	public function set_table_actions_data(): void {
		$action_name = $this->get_action_name();

		$this->set_table_actions(
			[
				[
					'label' => __( 'Add Filter', 'prisjakt-feed' ),
					'data'  => [
						'row-action'  => '0',
						'action'      => 'add_filter',
						'ajax-action' => $action_name,
					],
				],
			]
		);
	}


	/**
	 * @param $new_options
	 *
	 * @return array
	 */
	public function filter_new_options( $new_options ): array {

		/**
		 * Remove last post data values for default hidden fields
		 */

		array_pop( $new_options );

		if ( empty( $new_options ) ) {
			return [];
		}

		return array_values( $new_options );
	}


	/**
	 * Set rows data
	 */
	public function set_rows_data(): void {
		global $post;

		$prefix    = Plugin::PLUGIN_PREFIX;
		$meta_data = $this->data_provider->get_post_meta( $post->ID );
		$meta_name = $this->data_provider->get_meta_name();

		$count = count( $meta_data );
		$rows  = [];

		for ( $i = 0; $i < $count; ) :
			$filter_row = $this->data_provider->get_filter_row();

			foreach ( $filter_row as $field_index => $field ) :
				$field_id  = $field['id'];
				$field_key = $prefix . $field_id;

				$filter_row[ $field_index ]['data']['id']    = $field_key;
				$filter_row[ $field_index ]['data']['name']  = $meta_name . "[$field_id][]";
				$filter_row[ $field_index ]['data']['value'] = $filter_row[ $field_index ]['data']['value'] ??
															   $meta_data[ $i ][ $field_id ] ?? '';

			endforeach;

			$rows[] = $filter_row;

			$i ++;
		endfor;

		$this->set_rows( $rows );

	}
}
