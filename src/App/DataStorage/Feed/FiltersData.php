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
use PrisjaktFeed\App\DataStorage\Attributes\Attributes;
use PrisjaktFeed\App\DataStorage\DataStorage;
use PrisjaktFeed\Config\Plugin;

/**
 * Class ExtraFields
 *
 * @since 1.0.0
 */
class FiltersData extends DataStorage {



	/**
	 * @var string[]
	 */
	private $product_attributes;

	public function __construct() {
		parent::__construct();

		$this->product_attributes = $this->attributes_instance->get_filters_product_attributes();
	}

	/**
	 * @return string
	 */
	public function get_meta_name(): string {
		return Plugin::PLUGIN_PREFIX . Steps::get_step_by_index( 2 );
	}

	/**
	 * @return array[]
	 */
	public function get_notices(): array {
		return [
			[
				'class'   => [ 'prisjakt-feed-notice', 'prisjakt-feed-notice-info' ],
				'message' => sprintf(
				/* translators: %s: filter description */
					__(
						'Create filter so exactly the right products end up in your product feed. These filters are only 
					eligable for the current product feed you are configuring and will not be used for other feeds. %1$s
					 or %2$s',
						'prisjakt-feed'
					),
					sprintf(
						'<br><br><a href="%s">%s</a>',
						'#',
						__( 'Detailed information and filter examples', 'prisjakt-feed' )
					),
					sprintf(
						'<a href="%s">%s</a>',
						'#',
						__( 'Create a product feed for just 1 category', 'prisjakt-feed' )
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
				'label' => __( 'If', 'prisjakt-feed' ),
			],
			[
				'label' => __( 'Condition', 'prisjakt-feed' ),
			],
			[
				'label' => __( 'Value', 'prisjakt-feed' ),
			],
			[
				'label' => __( 'Delete', 'prisjakt-feed' ),
			],
		];
	}

	/**
	 * @return array
	 */
	public function get_filter_row(): array {
		return [
			[
				'id'       => 'filter',
				'type'     => 'select_optgroup',
				'required' => true,
				'data'     => [
					'class'    => [ 'prisjakt-filter-select' ],
					'required' => true,
					'options'  => $this->product_attributes,
				],
			],
			[
				'id'       => 'filter_condition',
				'required' => true,
				'type'     => 'select',
				'data'     => [
					'required' => true,
					'options'  => $this->get_conditions(),
				],
			],
			[
				'id'   => 'filter_value',
				'type' => 'input_text',
				'data' => [
					'class' => [ 'prisjakt-filter-value' ],
				],
			],
			[
				'id'   => 'filter_action',
				'type' => 'label',
				'data' => [
					'text' => '<span data-action="delete_row" class="prisjakt-feed-icon dashicons dashicons-no-alt"></span>',
				],
			],
		];
	}


	/**
	 * @return array[]
	 */
	public function get_fields(): array {
		return [
			$this->get_filter_row(),
		];
	}


	/**
	 * @return array
	 */
	public function get_conditions(): array {
		return [
			''        => '',
			'like'    => __( 'contains', 'prisjakt-feed' ),
			'notlike' => __( 'does not contain', 'prisjakt-feed' ),
			'eq'      => __( 'is equal to', 'prisjakt-feed' ),
			'neq'     => __( 'is not equal to', 'prisjakt-feed' ),
			'gt'      => __( 'is greater than', 'prisjakt-feed' ),
			'gteq'    => __( 'is greater or equal to', 'prisjakt-feed' ),
			'lt'      => __( 'is less than', 'prisjakt-feed' ),
			'lteq'    => __( 'is less or equal to', 'prisjakt-feed' ),
			'null'    => __( 'is empty', 'prisjakt-feed' ),
			'notnull' => __( 'is not empty', 'prisjakt-feed' ),
		];
	}

	/**
	 * @return array
	 */
	public function get_condition_actions(): array {
		return [
			'exclude'      => __( 'Exclude', 'prisjakt-feed' ),
			'include_only' => __( 'Include only', 'prisjakt-feed' ),
		];
	}
}
