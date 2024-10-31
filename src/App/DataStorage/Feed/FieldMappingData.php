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

use PrisjaktFeed\App\Backend\Core\Feeds\Feed\Steps;
use PrisjaktFeed\App\DataStorage\DataStorage;
use PrisjaktFeed\Config\Plugin;

/**
 * Class ExtraFields
 *
 * @since 1.0.0
 */
class FieldMappingData extends DataStorage {

	/**
	 * @var string[]
	 */
	public $product_attributes;

	/**
	 * @var string[]
	 */
	public $prisjakt_attributes;

	public function __construct() {
		parent::__construct();

		$this->product_attributes  = $this->attributes_instance->get_field_mapping_product_attributes();
		$this->prisjakt_attributes = $this->attributes_instance->attributes_to_options();
	}

	/**
	 * @return string
	 */
	public function get_meta_name(): string {
		return Plugin::PLUGIN_PREFIX . Steps::get_step_by_index( 1 );
	}

	/**
	 * @return array[]
	 */
	public function get_notices(): array {
		return [
			[
				'class'   => [ 'prisjakt-feed-notice', 'prisjakt-feed-notice-info' ],
				'message' => sprintf(
				/* translators: %s */
					__(
						"For the selected channel the attributes shown below are mandatory,
					 please map them to your product attributes. We've already pre-filled a lot of mappings so all
					  you have to do is check those and map the ones that are left blank or add new ones by hitting
					   the Add field mapping button. %s",
						'prisjakt-feed'
					),
					sprintf(
						'<br><br><a href="%s">%s</a>',
						'#',
						__( 'Learn how to use static values', 'prisjakt-feed' )
					)
				),
			],
		];
	}

	/**
	 * @return array[]
	 */
	public function get_columns(): array {
		return [
			[
				'label' => __( 'Prisjakt shopping attributes', 'prisjakt-feed' ),
			],
			[
				'label' => __( 'Prefix', 'prisjakt-feed' ),
			],
			[
				'label' => __( 'Value', 'prisjakt-feed' ),
			],
			[
				'label' => __( 'Suffix', 'prisjakt-feed' ),
			],
			[
				'label' => __( 'Delete', 'prisjakt-feed' ),
			],
			[
				'label' => __( 'Field type', 'prisjakt-feed' ),
			],
		];
	}

	/**
	 * @return array
	 */
	public function get_custom_field(): array {

		$field = $this->get_field();

		/**
		 * Update type for first element
		 */
		$field[0] = array_merge(
			$field[0],
			[
				'type' => 'input_text',
				'data' => [
					'required'         => true,
					'class'            => [ 'field_mapping_text_validate' ],
					'max_input_length' => 20,
				],
			]
		);

		/**
		 * Update value for last element
		 */
		$field[5] = array_merge(
			$field[5],
			[
				'data' => [
					'value' => 'custom_field',
				],
			]
		);

		return $field;
	}


	/**
	 * @param bool $show_action
	 *
	 * @return array
	 */
	public function get_field( bool $show_action = true ): array {
		return [
			[
				'id'       => $this->get_mapping_shopping_attributes_field_name(),
				'type'     => 'select',
				'required' => true,
				'data'     => [
					'required' => true,
					'options'  => $this->prisjakt_attributes,
				],
			],
			[
				'id'   => 'mapping_prefix',
				'type' => 'input_text',
			],
			[
				'id'       => 'mapping_attribute_value',
				'type'     => 'select_optgroup',
				'required' => true,
				'data'     => [
					'required' => true,
					'options'  => $this->product_attributes,
				],
			],
			[
				'id'   => 'mapping_suffix',
				'type' => 'input_text',
			],
			[
				'id'   => 'mapping_action',
				'type' => 'label',
				'data' => [
					'text' => $show_action ?
						'<span data-action="delete_row" class="prisjakt-feed-icon dashicons dashicons-no-alt"></span>' :
						'',
				],
			],
			[
				'id'   => $this->get_mapping_field_type_name(),
				'type' => 'input_hidden',
				'data' => [
					'value' => 'field',
				],
			],
		];
	}


	/**
	 * @return array[]
	 */
	public function get_fields(): array {

		return [
			$this->get_field(),
			$this->get_custom_field(),
		];
	}

	/**
	 * @return string
	 */
	public function get_mapping_shopping_attributes_field_name(): string {
		return 'mapping_shopping_attributes';
	}

	/**
	 * @return string
	 */
	public function get_mapping_field_type_name(): string {
		return 'mapping_field_type';
	}
}
