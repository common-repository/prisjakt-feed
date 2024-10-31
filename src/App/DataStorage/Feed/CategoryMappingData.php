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
use PrisjaktFeed\App\General\Queries;
use PrisjaktFeed\Config\Plugin;

/**
 * Class ExtraFields
 *
 * @since 1.0.0
 */
class CategoryMappingData extends DataStorage {

	/**
	 * @return string
	 */
	public function get_meta_name(): string {
		return Plugin::PLUGIN_PREFIX . Steps::get_step_by_index( 3 );
	}

	/**
	 * @return array[]
	 */
	public function get_notices(): array {
		return [
			[
				'class'   => [ 'prisjakt-feed-notice', 'prisjakt-feed-notice-info' ],
				'message' => __(
					'Assign your categories to the Prisjakt category, if a category is not assigned, its default value will be sent.',
					'prisjakt-feed'
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
				'label' => __( 'Your Category', 'prisjakt-feed' ),
			],
			[
				'label' => __( 'Google shopping category', 'prisjakt-feed' ),
			],
		];
	}


	/**
	 * @return array
	 */
	public function category_row(): array {
		return [];
	}

	/**
	 * @return array
	 */
	public function get_fields(): array {
		return $this->category_row();
	}

	/**
	 * @return array
	 */
	public function get_required_fields_ids(): array {
		return [];
	}
}
