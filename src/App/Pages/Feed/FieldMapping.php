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
use PrisjaktFeed\App\DataStorage\Feed\FieldMappingData;
use PrisjaktFeed\App\DataStorage\Settings\ExtraFieldsData;
use PrisjaktFeed\Config\Plugin;

/**
 * Class Settings
 *
 * @package PrisjaktFeed\App\Backend
 * @since 1.0.0
 */
class FieldMapping extends Feed {



	/**
	 * @var FieldMappingData
	 */
	public $data_provider;

	/**
	 * Init
	 */
	public function init(): void {
		parent::init();

		$this->id            = STEPS::get_step_by_index( 1 );
		$this->title         = __( 'Field mapping', 'prisjakt-feed' );
		$this->data_provider = new FieldMappingData( $this );
	}

	/**
	 * @param $columns
	 *
	 * @return int|void
	 */
	public function get_columns_count( $columns ) {
		return count( $columns ) - 2;
	}

	/**
	 *
	 */
	public function set_notices_data(): void {
		$this->set_notices( $this->data_provider->get_notices() );
	}

	/**
	 * @param $new_options
	 *
	 * @return array
	 */
	public function filter_new_options( $new_options ): array {

		/**
		 * Remove 2 last post data values for default hidden fields
		 */

		array_pop( $new_options );
		array_pop( $new_options );

		if ( empty( $new_options ) ) {
			return [];
		}

		return array_values( $new_options );
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
						'step'        => STEPS::get_step_by_index( 0 ),
						'action'      => 'back',
						'ajax-action' => $action_name,
					],
				],
				[
					'label' => __( 'Save & Continue', 'prisjakt-feed' ),
					'class' => [ 'button-primary' ],
					'data'  => [
						'step'        => STEPS::get_step_by_index( 2 ),
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
					'label' => __( 'Add Field Mapping', 'prisjakt-feed' ),
					'data'  => [
						'row-action'  => '0',
						'action'      => 'add_field_mapping',
						'ajax-action' => $action_name,
					],
				],
				[
					'label' => __( 'Add Custom Field', 'prisjakt-feed' ),
					'data'  => [
						'row-action'  => '1',
						'action'      => 'add_custom_field',
						'ajax-action' => $action_name,
					],
				],
			]
		);
	}


	/**
	 * @param $meta_data
	 *
	 * @return array
	 */
	public function get_missing_required_fields( $meta_data ): array {
		$required_fields = $this->data_provider->attributes_instance->get_attributes_by_format();

		if ( empty( $meta_data ) ) {
			return $required_fields;
		}

		$mapping_shopping_attributes_field_name = $this->data_provider->get_mapping_shopping_attributes_field_name();
		$meta_data_fields_names                 = [];
		$required_fields_names                  = array_map(
			static function ( $item ) {
				return $item['name'];
			},
			$required_fields
		);

		foreach ( $meta_data as $row ) :
			$meta_data_fields_names[] = $row[ $mapping_shopping_attributes_field_name ];
		endforeach;

		$missing_fields_names = array_diff( $required_fields_names, $meta_data_fields_names );

		return array_values(
			array_filter(
				$required_fields,
				static function ( $item ) use ( $missing_fields_names ) {
					return in_array( $item['name'], $missing_fields_names, true );
				}
			)
		);
	}

	/**
	 * @param $i
	 * @param $row
	 * @param $meta_data
	 * @param $meta_name
	 * @param $prefix
	 * @param array     $value
	 *
	 * @return array
	 */
	public function get_row( $i, $row, $meta_data, $meta_name, $prefix, array $value = [] ): array {

		foreach ( $row as $field_index => $field ) :
			$field_id  = $field['id'];
			$field_key = $prefix . $field_id;

			$row[ $field_index ]['data']['id']    = $field_key;
			$row[ $field_index ]['data']['name']  = $meta_name . "[$field_id][]";
			$row[ $field_index ]['data']['value'] = $value[ $field_index ] ?? $row[ $field_index ]['data']['value'] ??
				$meta_data[ $i ][ $field_id ] ?? '';

		endforeach;

		return $row;
	}

	public function get_optional_fields(): array {
		global $post;

		$rows      = [];
		$meta_data = $this->data_provider->get_post_meta( $post->ID );

		if ( ! empty( $meta_data ) ) {
			return [];
		}

		$prefix          = Plugin::PLUGIN_PREFIX;
		$optional_fields = $this->data_provider->attributes_instance->get_attributes_by_format( 'optional' );
		$extra_fields    = ( new ExtraFieldsData() )->get_option_value();
		$filter_row      = $this->data_provider->get_field();
		$meta_data       = $this->data_provider->get_post_meta( $post->ID );
		$meta_name       = $this->data_provider->get_meta_name();

		foreach ( $optional_fields as $optional_field ) {
			if ( isset( $extra_fields[ $optional_field['name'] ] ) ) {

				$rows[] = $this->get_row(
					-1,
					$filter_row,
					$meta_data,
					$meta_name,
					$prefix,
					[
						$optional_field['name'],
						'',
						$optional_field['suggest'],
						'',
					]
				);
			}
		}

		return $rows;
	}


	/**
	 * @return array
	 */
	public function get_required_fields(): array {
		global $post;

		$rows                          = [];
		$prefix                        = Plugin::PLUGIN_PREFIX;
		$meta_data                     = $this->data_provider->get_post_meta( $post->ID );
		$meta_name                     = $this->data_provider->get_meta_name();
		$missing_required_fields       = $this->get_missing_required_fields( $meta_data );
		$missing_required_fields_count = count( $missing_required_fields );

		for ( $i = 0; $i < $missing_required_fields_count; ) :
			$filter_row = $this->data_provider->get_field( false );

			$name    = $missing_required_fields[ $i ]['name'];
			$suggest = $missing_required_fields[ $i ]['suggest'];
			$rows[]  = $this->get_row(
				$i,
				$filter_row,
				$meta_data,
				$meta_name,
				$prefix,
				[
					$name,
					'',
					$suggest,
					'',
				]
			);

			$i++;
		endfor;

		return $rows;
	}


	/**
	 * Set rows data
	 */
	public function set_rows_data(): void {
		global $post;

		$prefix    = Plugin::PLUGIN_PREFIX;
		$meta_data = $this->data_provider->get_post_meta( $post->ID );
		$meta_name = $this->data_provider->get_meta_name();
		$count     = count( $meta_data );

		$finded_required_fields = [];
		$required_fields        = array_map(
			static function ( $field ) {
				return $field['name'];
			},
			$this->data_provider->attributes_instance->get_attributes_by_format()
		);
		$rows                   = $this->get_required_fields();

		$optional_fields = $this->get_optional_fields();

		if ( $optional_fields ) {
			$rows = array_merge( $rows, $optional_fields );
		}

		for ( $i = 0; $i < $count; ) :

			$show_action             = true;
			$mapping_field_type_name = $meta_data[ $i ][ $this->data_provider->get_mapping_shopping_attributes_field_name() ];

			if (
				in_array( $mapping_field_type_name, $required_fields, true ) &&
				( ! in_array( $mapping_field_type_name, $finded_required_fields, true ) )
			) {
				$show_action              = false;
				$finded_required_fields[] = $mapping_field_type_name;
			}

			$filter_row = $this->data_provider->get_field( $show_action );

			/**
			 * Load custom field for other field type
			 */

			if ( 'custom_field' === $meta_data[ $i ][ $this->data_provider->get_mapping_field_type_name() ] ) {
				$filter_row = $this->data_provider->get_custom_field();
			}

			$rows[] = $this->get_row( $i, $filter_row, $meta_data, $meta_name, $prefix );

			$i++;
		endfor;

		$this->set_rows( $rows );

	}
}
