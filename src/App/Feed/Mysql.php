<?php

namespace PrisjaktFeed\App\Feed;

use Ageno\Prisjakt\Component\Adapter\ResourceAdapter;
use Ageno\Prisjakt\Component\Adapter\ResourceAdapterInterface;
use Ageno\Prisjakt\Model\Feed;
use Ageno\Prisjakt\Model\FeedItem;
use PrisjaktFeed\App\DataStorage\Attributes\CustomAttributes;
use PrisjaktFeed\Config\Plugin;

require_once dirname( __FILE__, 4 ) . '/src/includes/db.php';

class Mysql implements ResourceAdapterInterface {



	public const FEED_TABLE              = 'prisjakt_feed';
	public const FEED_ITEM_TABLE         = 'prisjakt_feed_item';
	public const STATUS_META_FIELD       = Plugin::PLUGIN_PREFIX . 'status';
	public const ERROR_META_FIELD        = Plugin::PLUGIN_PREFIX . 'error';
	public const PROGRESS_META_FIELD     = Plugin::PLUGIN_PREFIX . 'progress';
	public const COUNT_META_FIELD        = Plugin::PLUGIN_PREFIX . 'count';
	public const URL_META_FIELD          = Plugin::PLUGIN_PREFIX . 'url';
	public const SCHEDULED_AT_META_FIELD = Plugin::PLUGIN_PREFIX . 'scheduled_at';
	public const GENERATED_AT_META_FIELD = Plugin::PLUGIN_PREFIX . 'generated_at';

	public const FEED_ITEM_FLAT_FIELDS = [
		'id',
		'post_parent',
		'post_status',
		'_link',
		'guid',
		'post_title',
		'item_group_id',
		'_item_group_id',
		'post_content',
		'post_excerpt',
	];

	public const FEED_ITEM_TERM_FIELDS = [ 'product_visibility', 'product_cat', 'product_type', 'product_tag' ];

	public const SKIP_FIELDS = [ 'gallery', 'thumbnail' ];

	public const DEBUG_MODE = 1;

	protected $wpdb;

	protected $wpPost;

	protected $mediaBaseUri;

	protected $categoriesMap;

	public function saveFeedStatus( Feed $feed ) {
		$this->saveFeed( $feed );

		insert_on_duplicate(
			$this->getTableName( self::FEED_TABLE ),
			[
				'id'     => $feed->getFeedId(),
				'status' => $feed->getStatus(),
			],
			[ 'status' ]
		);
	}

	public function saveError( Feed $feed ) {
		$this->saveFeed( $feed );
	}

	public function saveFeed( Feed $feed ) {
		global $wpdb;

		insert_on_duplicate(
			$wpdb->prefix . self::FEED_TABLE,
			[
				'post_id'        => $feed->getId(),
				'file_name'      => $feed->getFileName(),
				'completed_rows' => $feed->getCompletedRows(),
				'status'         => $feed->getStatus(),
			],
			[ 'post_id', 'file_name', 'rows', 'completed_rows', 'status' ]
		);

		if ( ! $feed->getId() && ( $id = $wpdb->insert_id ) ) {
			$feed->setFeedId( $id );
		}

		if ( $fileName = $feed->getFileName() ) {
			$uploadPaths = wp_get_upload_dir();
			$url         = $uploadPaths['baseurl'] . DIRECTORY_SEPARATOR . 'prisjakt' . DIRECTORY_SEPARATOR . $fileName;
			$this->updateUrl( $feed, $url );
		}

		$this->updateStatus( $feed, $feed->getStatus() );
		$this->updateError( $feed, $feed->getError() );
		$this->updateScheduledAt( $feed, $feed->getScheduledAt() );
		$this->updateGeneratedAt( $feed, $feed->getGeneratedAt() );
	}

	public function updateUrl( Feed $feed, string $url ) {
		$feed->setUrl( $url );
		if ( ! $url ) {
			delete_post_meta( $feed->getId(), self::URL_META_FIELD );
		} else {
			update_post_meta( $feed->getId(), self::URL_META_FIELD, $url );
		}
	}

	public function updateStatus( Feed $feed, string $status ) {
		$feed->setStatus( $status );
		if ( ! $status ) {
			delete_post_meta( $feed->getId(), self::STATUS_META_FIELD );
		} else {
			update_post_meta( $feed->getId(), self::STATUS_META_FIELD, $status );
		}
	}

	public function updateError( Feed $feed, ?string $error ) {
		$feed->setError( $error );
		if ( ! $error ) {
			delete_post_meta( $feed->getId(), self::ERROR_META_FIELD );
		} else {
			update_post_meta( $feed->getId(), self::ERROR_META_FIELD, $error );
		}
	}

	public function updateGeneratedAt( Feed $feed, ?string $date ) {
		$feed->setGeneratedAt( $date );
		if ( ! $date ) {
			delete_post_meta( $feed->getId(), self::GENERATED_AT_META_FIELD );
		} else {
			update_post_meta( $feed->getId(), self::GENERATED_AT_META_FIELD, $date );
		}
	}

	public function updateScheduledAt( Feed $feed, ?string $date ) {
		$feed->setScheduledAt( $date );
		if ( ! $date ) {
			delete_post_meta( $feed->getId(), self::SCHEDULED_AT_META_FIELD );
		} else {
			update_post_meta( $feed->getId(), self::SCHEDULED_AT_META_FIELD, $date );
		}
	}

	private function getTableName( $tableName ) {
		global $wpdb;

		return $wpdb->prefix . $tableName;
	}

	public function saveFeedItem( FeedItem $feedItem ) {
		global $wpdb;

		insert_on_duplicate(
			$this->getTableName( self::FEED_ITEM_TABLE ),
			[
				'post_id' => $feedItem->getId(),
				'feed_id' => $feedItem->getFeedId(),
				'xml'     => $feedItem->getXml(),
				'status'  => $feedItem->getStatus(),
			],
			[ 'xml', 'status' ]
		);

		if ( $id = $wpdb->insert_id ) {
			$feedItem->setFeedItemId( $id );
		}
	}

	public function loadFeedData( int $id ): array {
		global $wpdb;

		$data = (array) $wpdb->get_row(
			$wpdb->prepare(
				'SELECT * FROM `%1s` WHERE `post_id` = %s;',
				[
					$this->getTableName( self::FEED_TABLE ),
					$wpdb->_escape( $id ),
				]
			)
		);

		$wpFeedData   = get_post( $id, ARRAY_A );
		$this->wpPost = $wpFeedData;

		$feedSettings = $this->loadFeedSettings( $id );
		if ( $feedSettings ) {
			$wpFeedData = array_merge( $wpFeedData, $feedSettings );
		}

		$feedFilters = get_post_meta( $id, 'prisjakt_feed_filters', true );

		if ( $feedFilters ) {
			$wpFeedData = array_merge( $wpFeedData, [ 'filters' => maybe_unserialize( $feedFilters ) ] );
		}

		$feedMapping = get_post_meta( $id, 'prisjakt_feed_field_mapping', true );

		if ( $feedMapping ) {
			$wpFeedData = array_merge( $wpFeedData, [ 'fields' => (array) maybe_unserialize( $feedMapping ) ] );
		}

		$feedCategoryMapping = get_post_meta( $id, 'prisjakt_feed_category_mapping', true );
		if ( $feedCategoryMapping ) {
			$feedCategoryMappingArray = (array) maybe_unserialize( $feedCategoryMapping );
			$feedCategoryMappingArray = array_filter( $feedCategoryMappingArray[0], 'strlen' );
			$wpFeedData               = array_merge( $wpFeedData, [ 'category_mapping' => $feedCategoryMappingArray ] );
		}

		$data['feed_id']      = isset( $data['id'] ) ? $data['id'] : null;
		$data['id']           = $id;
		$data['post_id']      = $id;
		$data['name']         = $wpFeedData['post_title'];
		$data['status']       = get_post_meta( $id, 'prisjakt_feed_status', true );
		$data['generated_at'] = get_post_meta( $id, 'prisjakt_feed_generated_at', true );
		$data['scheduled_at'] = get_post_meta( $id, 'prisjakt_feed_scheduled_at', true );

		return array_merge( $data, $wpFeedData );
	}

	public function getFeedItemsXml( Feed $feed ): array {
		global $wpdb;

		$feed_id = $feed->getFeedId();

		return $wpdb->get_col(
			$wpdb->prepare(
				'SELECT `pfi`.`xml` FROM `%1s` `pfi` WHERE `pfi`.`feed_id` = %s AND `pfi`.`status` = %s;',
				[
					$this->getTableName( self::FEED_ITEM_TABLE ),
					$feed_id,
					FeedItem::FEED_ITEM_STATUS_FINISHED,
				]
			)
		);
	}

	public function getFeedItems( Feed $feed, $limit = 1000, $offset = 0 ) {
		global $wpdb;

		if ( self::DEBUG_MODE ) {
            // phpcs:disable WordPress.PHP.DevelopmentFunctions
			var_dump( $limit );
			var_dump( $offset );
            // phpcs:enable
		}

		// Query parts already fully covered by prepare method.
        // phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
		$query = $this->prepareFeedItemsQuery( $feed, $limit, $offset );

		$rows = $wpdb->get_results( $query, ARRAY_A );
        // phpcs:enable

		if ( empty( $rows ) && $this->countItems( $feed ) === 0 ) {
			throw new \Exception( 'This feed has no items. Please remove some filters.' );
		}

		if ( empty( $rows ) ) {
			return;
		}

		$feedItemFields = [];

		foreach ( $feed->getFields() as $field ) {
			if ( isset( $field['mapping_shopping_attributes'] ) ) {
				if ( in_array( $field['mapping_shopping_attributes'], self::SKIP_FIELDS, true ) ) {
					continue;
				}
				$feedItemFields[] = [
					'field'  => $field['mapping_shopping_attributes'],
					'source' => $field['mapping_attribute_value'],
					'prefix' => $field['mapping_prefix'],
					'suffix' => $field['mapping_suffix'],
				];
			}
		}

		foreach ( FeedItem::REQUIRED_FIELDS as $requiredField ) {
			if ( ! in_array( $requiredField, array_column( $feedItemFields, 'field' ), true ) ) {
				$feedItemFields[] = [
					'field'  => $requiredField,
					'source' => $requiredField,
					'prefix' => '',
					'suffix' => '',
				];
			}
		}

		$feedItemIds = [];
		$feedItems   = [];

		if ( self::DEBUG_MODE ) {
			echo "\n";
		}

		foreach ( $rows as $data ) {
			$feedItemIds[] = $data['ID'];
			$feedItem      = new FeedItem( $this, $this->prepareFeedItemData( $data ) );

			$feedItem->setFields( $feedItemFields );
			$feedItem->setStatus( FeedItem::FEED_ITEM_STATUS_PROCESSING );
			$feedItem->save();

			$feedItems[] = $feedItem;
		}

		if ( self::DEBUG_MODE ) {
			echo "\n";
		}

		$categories      = $this->getCategories( $feedItemIds );
		$googleMapping   = $this->getGoogleCategoriesMapping( $feed );
		$categoriesNames = $this->getCategoriesNames();

		foreach ( $feedItems as $feedItem ) {
			if ( isset( $categories[ $feedItem->getId() ] ) ) {
				if ( isset( $categories[ $feedItem->getId() ] ) && isset( $googleMapping[ $categories[ $feedItem->getId() ] ] ) ) {
					$feedItem->setData( 'google_product_category', $googleMapping[ $categories[ $feedItem->getId() ] ] );
				} else {
					$feedItem->unsetData( 'google_product_category' );
					$feedItem->setData( 'product_type', $categoriesNames[ $categories[ $feedItem->getId() ] ] );
				}
			}

			$feedItem->generate();

			$feedItem->setStatus( FeedItem::FEED_ITEM_STATUS_FINISHED );

			$feedItem->save();
		}
	}

	protected function prepareFeedItemsQuery( Feed $feed, $limit = 1000, $offset = 0 ): string {
		global $wpdb;

		$feed_id = $feed->getFeedId();
		$columns = '';
		$joins   = '';

		$queryJoins = $this->prepareQueryJoins( $feed );

		if ( ! empty( $queryJoins['column'] ) ) {
			$columns = ', ' . implode( ',', $queryJoins['column'] );
		}

		if ( ! empty( $queryJoins['join'] ) ) {
			$joins = implode( ' ', $queryJoins['join'] );
		}

		// Query parts already fully covered by prepare method.
        // phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
		$query = $wpdb->prepare(
			'SELECT `pfi`.`id` as `feed_item_id`, `pfi`.`feed_id`, `pfi`.`status` %1s FROM `%1s` `pfi` LEFT JOIN `%1s` as `p` ON `p`.`ID` = `pfi`.`post_id` ' . $joins . ' WHERE `pfi`.`feed_id` = %1s AND `pfi`.`status` IN (%s, %s) GROUP BY `p`.`ID`',
			[
				$columns,
				$this->getTableName( self::FEED_ITEM_TABLE ),
				$this->getTableName( 'posts' ),
				$feed_id,
				FeedItem::FEED_ITEM_STATUS_NEW,
				FeedItem::FEED_ITEM_STATUS_PROCESSING,
			]
		);
        // phpcs:enable

		if ( $limit ) {
			$query .= $wpdb->prepare( ' LIMIT %d', $limit );
		}

		if ( $offset ) {
			$query .= $wpdb->prepare( ' OFFSET %d', $offset );
		}

		if ( self::DEBUG_MODE ) {
            // phpcs:disable WordPress.PHP.DevelopmentFunctions
            // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
			echo "#######################\n";
			echo "prepareFeedItemsQuery\n";
			echo $query;
            // phpcs:enable
		}

		$query .= $wpdb->prepare( ';' );

		return $query;
	}

	private function prepareQueryJoins( Feed $feed ) {
		$customAttributesJoins = $this->getCustomAttributesJoins( $feed );
		$fields                = $this->getFieldsJoins( $feed );
		$defaultJoins          = $this->getDefaultJoins( $feed );

		return array_merge_recursive( $defaultJoins, $fields, $customAttributesJoins );
	}

	private function getCustomAttributesJoins( Feed $feed ) {
		global $wpdb;

		$include_product_variations = $feed->getData( 'include_product_variations' ) === 'on' ? true : false;

		$customAttributes = ( new CustomAttributes() )->get_custom_attributes( true );

		$fields = $feed->getFields();

		$join         = [];
		$columns      = [];
		$fieldsToJoin = [];

		foreach ( $fields as $key => $field ) {
			if ( in_array( $field['mapping_attribute_value'], array_keys( $customAttributes ), true ) ) {
				$fields[ $key ]['is_custom_attribute'] = 1;
				if ( $customAttributes[ $field['mapping_attribute_value'] ]['is_taxonomy'] ) {
					$fields[ $key ]['is_custom_attribute_taxonomy'] = 1;
					$fieldsToJoin[]                                 = $field['mapping_attribute_value'];
				}
			}
		}

		$feed->setFields( $fields );

		foreach ( $fieldsToJoin as $attribute_code ) {
			$alias  = uniqid() . "_{$attribute_code}";
			$join[] = $wpdb->prepare(
				'LEFT JOIN (SELECT `wptr_`.`object_id`, `wt_`.`name` FROM `wp_terms` `wt_`
                JOIN `wp_term_taxonomy` `wtt_` ON `wtt_`.`term_id` = `wt_`.`term_id`
                JOIN `wp_term_relationships` `wptr_` ON `wptr_`.`term_taxonomy_id` = `wtt_`.`term_taxonomy_id`
                WHERE `wtt_`.`taxonomy` = %s) `%s` ON `%s`.`object_id` = `p`.`ID`',
				[
					$attribute_code,
					'term_' . $alias,
					'term_' . $alias,

				]
			);

			$columns[] = $wpdb->prepare(
				'`%s`.`name` as `%s`',
				[
					'term_' . $alias,
					$attribute_code,
				]
			);
		}

		return [
			'join'   => $join,
			'column' => $columns,
		];
	}

	private function getFieldsJoins( Feed $feed ) {
		global $wpdb;

		$include_product_variations = $feed->getData( 'include_product_variations' ) === 'on' ? true : false;

		$fields = $feed->getFields();

		$join    = [];
		$columns = [];

		foreach ( $fields as $field ) {
			if ( '_sale_price_effective_date' === $field['mapping_attribute_value'] ) {
				$fields[] = array_merge(
					$field,
					[
						'mapping_shopping_attributes' => '_sale_price_start_date',
						'mapping_attribute_value'     => '_sale_price_dates_from',
					]
				);
				$fields[] = array_merge(
					$field,
					[
						'mapping_shopping_attributes' => '_sale_price_end_date',
						'mapping_attribute_value'     => '_sale_price_dates_to',
					]
				);
			}
			if ( '_sale_price' === $field['mapping_attribute_value'] ) {
				$fields[] = array_merge(
					$field,
					[
						'mapping_shopping_attributes' => '_regular_price',
						'mapping_attribute_value'     => '_regular_price',
					]
				);
			}
		}

		$includedColumns = [];
		foreach ( $fields as $field ) {
			if ( isset( $field['is_custom_attribute'] ) && $field['is_custom_attribute'] ) {
				continue;
			}

			if ( in_array( $field['mapping_attribute_value'], self::FEED_ITEM_FLAT_FIELDS, true ) ) {
				continue;
			}

			$column = $field['mapping_attribute_value'];

			if ( in_array( $column, $includedColumns, true ) ) {
				// Skip if already joined.
				continue;
			}

			$includedColumns[] = $column;

			if ( in_array( $column, self::FEED_ITEM_TERM_FIELDS, true ) ) {
				$join[]    = $wpdb->prepare(
					'LEFT JOIN `wp_term_relationships` `%1s` ON `%1s`.`object_id` = `p`.`ID`',
					[
						'wptr_' . $column,
						'wptr_' . $column,
					]
				);
				$join[]    = $wpdb->prepare(
					'LEFT JOIN `wp_term_taxonomy` `%1s` ON `%1s`.`term_taxonomy_id` = `%1s`.`term_taxonomy_id` AND `%1s`.`taxonomy` = %s',
					[
						'wptt_' . $column,
						'wptt_' . $column,
						'wptr_' . $column,
						'wptt_' . $column,
						$column,
					]
				);
				$join[]    = $wpdb->prepare(
					'LEFT JOIN `wp_terms` `%1s` ON `%1s`.`term_id` = `%1s`.`term_id`',
					[
						'wpt_' . $column,
						'wpt_' . $column,
						'wptt_' . $column,
					]
				);
				$columns[] = $wpdb->prepare(
					'`%1s`.`name` as `%1s`',
					[
						'wpt_' . $column,
						$column,
					]
				);
			} else {
				$field_name = $field['mapping_shopping_attributes'];
				$alias      = uniqid( '', true ) . "_{$field_name}";

				$join[] = $wpdb->prepare(
					'LEFT JOIN `%1s` as `%1s` ON `p`.`ID` = `%1s`.`post_id` AND `%1s`.`meta_key` = %s',
					[
						$this->getTableName( 'postmeta' ),
						$alias,
						$alias,
						$alias,
						$column,
					]
				);

				if ( $include_product_variations ) {
					$join[]    = $wpdb->prepare(
						'LEFT JOIN `%1s` as `%1s` ON `p`.`post_parent` = `%1s`.`post_id` AND `%1s`.`meta_key` = %s',
						[
							$this->getTableName( 'postmeta' ),
							$alias . '_parent',
							$alias . '_parent',
							$alias . '_parent',
							$column,
						]
					);
					$columns[] = $wpdb->prepare(
						'COALESCE(NULLIF(`%1s`.`meta_value`,""), `%1s`.`meta_value`) as `%1s`',
						[
							$alias,
							$alias . '_parent',
							$column,
						]
					);
				} else {
					$columns[] = $wpdb->prepare(
						'`%1s`.`meta_value` as `%1s`',
						[
							$alias,
							$column,
						]
					);
				}
			}
		}

		return [
			'join'   => $join,
			'column' => $columns,
		];
	}

	/**
	 * @param $filter1
	 *
	 * @return string
	 */
	protected function prepareFieldName( $filter1 ): string {
		if ( strpos( $filter1, Plugin::PLUGIN_PREFIX ) === false && substr( $filter1, 0, 1 ) !== '_' ) {
			$field = '_' . $filter1;
		} else {
			$field = $filter1;
		}

		return $field;
	}

	private function getGoogleCategoriesMapping( Feed $feed ) {
		$googleMapping = $feed->getCategoryMapping();

		return $googleMapping;
	}

	private function getCategoriesNames() {
		global $wpdb;

		$categories = [];

		$query = '
        SELECT `wt`.`term_id`, `wt`.`name`
        FROM `wp_terms` `wt`
        JOIN `wp_term_taxonomy` `wtt` ON `wtt`.`term_id` = `wt`.`term_id`
        WHERE `wtt`.`taxonomy` = "product_cat"';

		// Query parts already fully covered by prepare method.
        // phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
		foreach ( (array) $wpdb->get_results( $wpdb->prepare( $query ), ARRAY_A ) as $row ) {
			if ( ! isset( $categories[ $row['term_id'] ] ) ) {
				$categories[ $row['term_id'] ] = $row['name'];
			}
		}

        // phpcs:enable

		return $categories;
	}

	private function getCategories( array $feedItemIds ) {
		global $wpdb;

		$query = "
            SELECT `r`.`object_id`, MAX(`tt`.`term_taxonomy_id`) as `category_id`
            FROM `wp_term_taxonomy` AS `tt`, `wp_term_relationships` as `r`
            WHERE
            `r`.`term_taxonomy_id`=`tt`.`term_taxonomy_id`
            AND `tt`.`taxonomy` = 'product_cat'
            AND `r`.`object_id` IN (" . implode( ',', $feedItemIds ) . ')
            GROUP BY `r`.`object_id`;';

		$productCategories = [];

		// Query parts already fully covered by prepare method.
        // phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
		foreach ( (array) $wpdb->get_results( $wpdb->prepare( $query ), ARRAY_A ) as $row ) {
			$productCategories[ $row['object_id'] ] = $row['category_id'];
		}

        // phpcs:enable

		return $productCategories;
	}

	private function getDefaultJoins( Feed $feed ) {
		global $wpdb;

		$include_product_variations = $feed->getData( 'include_product_variations' ) === 'on' ? true : false;

		$fields = [
			[
				'mapping_attribute_value'     => '_stock_status',
				'mapping_shopping_attributes' => 'availability',
			],
			[
				'mapping_attribute_value'     => '_thumbnail_id',
				'mapping_shopping_attributes' => 'thumbnail',
			],
			[
				'mapping_attribute_value'     => '_product_image_gallery',
				'mapping_shopping_attributes' => 'gallery',
			],
		];

		$join    = [];
		$columns = [];

		$columns[] = $wpdb->prepare( 'IF(`p`.`post_parent` > 0, `p`.`post_parent`, NULL) as `item_group_id`' );
		$columns[] = $wpdb->prepare( '`p`.`ID`' );
		$columns[] = $wpdb->prepare( '`p`.`post_status`' );
		$columns[] = $wpdb->prepare( '`p`.`guid`' );
		$columns[] = $wpdb->prepare( '`p`.`post_type`' );

		if ( $include_product_variations ) {
			$join[]    = $wpdb->prepare(
				'LEFT JOIN `%1s` as `p_parent` ON `p`.`post_parent` = `p_parent`.`ID`',
				[
					$this->getTableName( 'posts' ),
				]
			);
			$columns[] = $wpdb->prepare( 'COALESCE(NULLIF(`p`.`post_content`,""), `p_parent`.`post_content`) as `post_content`' );
			$columns[] = $wpdb->prepare( 'COALESCE(NULLIF(`p`.`post_title`,""), `p_parent`.`post_title`) as `post_title`' );
			$columns[] = $wpdb->prepare( 'COALESCE(NULLIF(`p`.`post_name`,""), `p_parent`.`post_name`) as `post_name`' );
			$columns[] = $wpdb->prepare( 'COALESCE(NULLIF(`p`.`post_excerpt`,""), `p_parent`.`post_excerpt`) as `post_excerpt`' );
		} else {
			$columns[] = $wpdb->prepare( '`p`.`post_content`' );
			$columns[] = $wpdb->prepare( '`p`.`post_title`' );
			$columns[] = $wpdb->prepare( '`p`.`post_name`' );
			$columns[] = $wpdb->prepare( '`p`.`post_excerpt`' );
		}

		$join[]    = $wpdb->prepare(
			'LEFT JOIN `%1s` as `wpp_product_attributes` ON `p`.`ID` = `wpp_product_attributes`.`post_id` AND `wpp_product_attributes`.`meta_key` = "_product_attributes"',
			[
				$this->getTableName( 'postmeta' ),
			]
		);
		$columns[] = $wpdb->prepare( '`wpp_product_attributes`.`meta_value` as `_product_attributes`' );

		foreach ( $fields as $field ) {
			if ( in_array( $field['mapping_attribute_value'], self::FEED_ITEM_FLAT_FIELDS, true ) ) {
				continue;
			}
			$column     = $field['mapping_attribute_value'];
			$field_name = $field['mapping_shopping_attributes'];
			$alias      = uniqid( '', true ) . "_{$field_name}";

			$join[] = $wpdb->prepare(
				'LEFT JOIN `%1s` as `%1s` ON `p`.`ID` = `%1s`.`post_id` AND `%1s`.`meta_key` = %s',
				[
					$this->getTableName( 'postmeta' ),
					$alias,
					$alias,
					$alias,
					$column,
				]
			);

			if ( $include_product_variations ) {
				$join[] = $wpdb->prepare(
					'LEFT JOIN `%1s` as `%1s` ON `p`.`post_parent` = `%1s`.`post_id` AND `%1s`.`meta_key` = %s',
					[
						$this->getTableName( 'postmeta' ),
						$alias . '_parent',
						$alias . '_parent',
						$alias . '_parent',
						$column,

					]
				);
				$columns[] = $wpdb->prepare(
					'COALESCE(NULLIF(`%1s`.`meta_value`,""), `%1s`.`meta_value`) as `%1s`',
					[
						$alias,
						$alias . '_parent',
						$field_name,
					]
				);
			} else {
				$columns[] = $wpdb->prepare(
					'`%1s`.`meta_value` as `%1s`',
					[
						$alias,
						$field_name,
					]
				);
			}
		}

		return [
			'join'   => $join,
			'column' => $columns,
		];
	}

	protected function stockStatusMapper( $value ) {
		// @todo prepare mapping logic interface

		$map = [
			'instock'     => 'in_stock',
			'outofstock'  => 'out_of_stock',
			'onbackorder' => 'backorder',
		];

		if ( isset( $map[ $value ] ) ) {
			return $map[ $value ];
		}

		return $value;
	}

	public function prepareFeedItemData( array $data ): array {
		$data['id'] = $data['ID'];

		if ( isset( $data['_product_attributes'] ) ) {
			$productAttributes = maybe_unserialize( $data['_product_attributes'] );

			foreach ( $productAttributes as $attributeKey => $values ) {
				if ( ! $values['is_taxonomy'] && ! isset( $data[ $attributeKey ] ) ) {
					$data[ $attributeKey ] = $values['value'];
				}
			}

			unset( $data['_product_attributes'] );
		}

		$data['_link'] = get_permalink( $data['ID'] );

		if ( isset( $data['_stock_status'] ) ) {
			$data['_stock_status'] = $this->stockStatusMapper( $data['_stock_status'] );
		}

		if ( self::DEBUG_MODE ) {
			echo '.';
		}

		$weight_unit    = get_option( 'woocommerce_weight_unit' );
		$dimension_unit = get_option( 'woocommerce_dimension_unit' );
		$currency       = get_woocommerce_currency();

		/*
		 * Use regular price for price field if sale price exists
		 */
		if ( isset( $data['_sale_price'] ) && isset( $data['_regular_price'] ) ) {
			$data['_price'] = $data['_regular_price'];
		}

		/*
		 * Modify value based on field
		 */
		if ( isset( $data['_price'] ) ) {
			$data['_price'] = number_format( (float) $data['_price'], 2, '.', '' ) . ' ' . $currency;
		}

		if ( isset( $data['_sale_price'] ) ) {
			$data['_sale_price'] = number_format( (float) $data['_sale_price'], 2, '.', '' ) . ' ' . $currency;
		}

		if ( array_key_exists( '_sale_price_effective_date', $data ) && isset( $data['_sale_price_dates_from'] ) && isset( $data['_sale_price_dates_to'] ) ) {
			$data['_sale_price_effective_date'] = date_i18n( 'c', $data['_sale_price_dates_from'] ) . '/' . date_i18n( 'c', $data['_sale_price_dates_to'] );
		}

		if ( isset( $data['_weight'] ) ) {
			$data['_weight'] = number_format( (float) $data['_weight'], 2, '.', '' ) . ' ' . $weight_unit;
		}

		if ( isset( $data['_height'] ) ) {
			$data['_height'] = number_format( (float) $data['_height'], 2, '.', '' ) . ' ' . $dimension_unit;
		}

		if ( isset( $data['_length'] ) ) {
			$data['_length'] = number_format( (float) $data['_length'], 2, '.', '' ) . ' ' . $dimension_unit;
		}

		if ( isset( $data['_width'] ) ) {
			$data['_width'] = number_format( (float) $data['_width'], 2, '.', '' ) . ' ' . $dimension_unit;
		}

		return $data;
	}

	private function getFilteredItemsQuery( Feed $feed, $onlyIds = false ): string {
		global $wpdb;

		$feed_id = $feed->getFeedId();

		$filters = $this->getFilters( $feed );

		$condition_query_part = $this->prepareFilterConditionQueryPart( $filters );

		if ( $onlyIds ) {
			// Query parts already fully covered by prepare method.
            // phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
			$idsQuery = $wpdb->prepare(
				'SELECT %1s FROM `%1s` as `wp` ' . $condition_query_part . ';',
				[
					implode( ', ', $filters['columns'] ),
					$this->getTableName( 'posts' ),
				]
			);

            // phpcs:enable

			return $idsQuery;
		}

		// Query parts already fully covered by prepare method.
        // phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
		return $wpdb->prepare(
			'INSERT INTO `%1s` (`feed_id`, `post_id`) SELECT %d as `feed_id`%1s FROM `%1s` as `wp` ' . $condition_query_part . ' ON DUPLICATE KEY UPDATE `post_id` = VALUES(`post_id`)',
			[
				$this->getTableName( self::FEED_ITEM_TABLE ),
				$feed_id,
				', ' . implode( ', ', $filters['columns'] ),
				$this->getTableName( 'posts' ),
			]
		);
        // phpcs:enable
	}

	private function getFilters( Feed $feed ) {
		$filters = (array) $feed->getFilters();

		$columns = [];
		$group   = [];

		$include_product_variations = $feed->getData( 'include_product_variations' ) === 'on' ? true : false;

		$join[] = 'LEFT JOIN `wp_term_relationships` `wptr` ON `wptr`.`object_id` = `wp`.`ID`';
		$join[] = 'LEFT JOIN `wp_term_taxonomy` `wptt` ON `wptt`.`term_taxonomy_id` = `wptr`.`term_taxonomy_id` AND `wptt`.`taxonomy` = "product_type"';
		$join[] = 'LEFT JOIN `wp_terms` `wpt` ON `wptt`.`term_id` = `wpt`.`term_id`';

		$where[] = '`wp`.`post_type` = "product"';

		if ( $include_product_variations ) {
			$columns[] = 'COALESCE(`wp_variable`.`ID`, `wp`.`ID`) as `ID`';
			$join[]    = 'LEFT JOIN `wp_posts` `wp_variable` ON `wp_variable`.`post_parent` = `wp`.`ID` AND `wp_variable`.`post_type` = "product_variation"';
			$where[]   = '((`wp`.`post_type` = "product" AND (`wpt`.`name` = "simple" OR `wpt`.`name` = "variable")))';
			$group[]   = '`wp_variable`.`ID`';
		} else {
			$columns[] = '`wp`.`ID`';
			$where[]   = '((`wp`.`post_type` = "product" AND (`wpt`.`name` = "simple" OR `wpt`.`name` = "variable")))';
		}

		foreach ( $filters as $filter ) {
			$field    = $this->prepareFieldName( $filter['filter'] );
			$value    = $filter['filter_value'];
			$operator = $filter['filter_condition'];

			if ( '_categories' === $field ) {
				$alias       = uniqid();
				$table_alias = "{$alias}_wpt_categories";
				$table_field = 'slug';
			} else {
				$alias       = uniqid() . "_{$field}";
				$table_alias = $alias;
				$table_field = 'meta_value';
			}

			if ( '_categories' === $field ) {
				$join[] = "LEFT JOIN `wp_term_relationships` `{$alias}_wptr_categories` ON `{$alias}_wptr_categories`.`object_id` = `wp`.`ID`";
				if ( 'eq' === $operator ) {
					$join[] = "JOIN `wp_term_taxonomy` `{$alias}_wptt_categories` ON `{$alias}_wptt_categories`.`term_taxonomy_id` = `{$alias}_wptr_categories`.`term_taxonomy_id` AND `{$alias}_wptt_categories`.`taxonomy` = \"product_cat\"";
				} else {
					$join[] = "LEFT JOIN `wp_term_taxonomy` `{$alias}_wptt_categories` ON `{$alias}_wptt_categories`.`term_taxonomy_id` = `{$alias}_wptr_categories`.`term_taxonomy_id` AND `{$alias}_wptt_categories`.`taxonomy` = \"product_cat\"";
				}
				$join[] = "LEFT JOIN `wp_terms` `{$alias}_wpt_categories` ON `{$alias}_wpt_categories`.`term_id` = `{$alias}_wptt_categories`.`term_id`";
			} else {
				$join[] = 'LEFT JOIN `' . $this->getTableName( 'postmeta' ) . "` as `{$alias}` ON `wp`.`ID` = `{$alias}`.`post_id` AND `{$alias}`.`meta_key` = \"{$field}\"";

				if ( $include_product_variations ) {
					$join[] = 'LEFT JOIN `' . $this->getTableName( 'postmeta' ) . "` as `{$alias}_parent` ON `wp`.`ID` = `{$alias}_parent`.`post_id` AND `{$alias}_parent`.`meta_key` = \"{$field}\"";
				}
			}

			$raw_value = $value;

			if ( is_numeric( $value ) ) {
				$value = (int) $value;
			} elseif ( is_array( $value ) ) {
				$value = '"' . implode( '","', $value ) . '"';
			} else {
				$value = "\"{$value}\"";
			}

			switch ( $operator ) {
				case 'like':
					if ( $include_product_variations && '_categories' !== $field ) {
						$where[] = "(COALESCE(`{$table_alias}`.`{$table_field}`, `{$table_alias}_parent`.`meta_value`) LIKE \"%$raw_value\" OR COALESCE(`{$table_alias}`.`{$table_field}`, `{$table_alias}_parent`.`meta_value`) LIKE \"%$raw_value%\" OR COALESCE(`{$table_alias}`.`{$table_field}`, `{$table_alias}_parent`.`meta_value`) LIKE \"$raw_value%\")";
					} else {
						$where[] = "(`{$table_alias}`.`{$table_field}` LIKE \"%$raw_value\" OR `{$table_alias}`.`{$table_field}` LIKE \"%$raw_value%\" OR `{$table_alias}`.`{$table_field}` LIKE \"$raw_value%\")";
					}
					break;
				case 'notlike':
					if ( $include_product_variations && '_categories' !== $field ) {
						$where[] = "(COALESCE(`{$table_alias}`.`{$table_field}`, `{$table_alias}_parent`.`meta_value`) NOT LIKE \"%$raw_value\" OR COALESCE(`{$table_alias}`.`{$table_field}`, `{$table_alias}_parent`.`meta_value`) NOT LIKE \"%$raw_value%\" OR COALESCE(`{$table_alias}`.`{$table_field}`, `{$table_alias}_parent`.`meta_value`) NOT LIKE \"$raw_value%\")";
					} else {
						$where[] = "(`{$table_alias}`.`{$table_field}` NOT LIKE \"%$raw_value\" OR `{$table_alias}`.`{$table_field}` NOT LIKE \"%$raw_value%\" OR `{$table_alias}`.`{$table_field}` NOT LIKE \"$raw_value%\")";
					}
					break;
				case 'neq':
					if ( $include_product_variations && '_categories' !== $field ) {
						$where[] = "(COALESCE(`{$table_alias}`.`{$table_field}`, `{$table_alias}_parent`.`meta_value`) != {$value}";
					} else {
						$where[] = "`{$table_alias}`.`{$table_field}` != {$value}";
					}
					break;
				case 'gt':
					if ( $include_product_variations && '_categories' !== $field ) {
						$where[] = "(COALESCE(`{$table_alias}`.`{$table_field}`, `{$table_alias}_parent`.`meta_value`) > {$value}";
					} else {
						$where[] = "`{$table_alias}`.`{$table_field}` > {$value}";
					}
					break;
				case 'gteq':
					if ( $include_product_variations && '_categories' !== $field ) {
						$where[] = "(COALESCE(`{$table_alias}`.`{$table_field}`, `{$table_alias}_parent`.`meta_value`) >= {$value}";
					} else {
						$where[] = "`{$table_alias}`.`{$table_field}` >= {$value}";
					}
					break;
				case 'lt':
					if ( $include_product_variations && '_categories' !== $field ) {
						$where[] = "(COALESCE(`{$table_alias}`.`{$table_field}`, `{$table_alias}_parent`.`meta_value`) < {$value}";
					} else {
						$where[] = "`{$table_alias}`.`{$table_field}` < {$value}";
					}
					break;
				case 'lteq':
					if ( $include_product_variations && '_categories' !== $field ) {
						$where[] = "(COALESCE(`{$table_alias}`.`{$table_field}`, `{$table_alias}_parent`.`meta_value`) <= {$value}";
					} else {
						$where[] = "`{$table_alias}`.`{$table_field}` <= {$value}";
					}
					break;
				case 'null':
					if ( $include_product_variations && '_categories' !== $field ) {
						$where[] = "(COALESCE(`{$table_alias}`.`{$table_field}`, `{$table_alias}_parent`.`meta_value`) IS NULL";
					} else {
						$where[] = "`{$table_alias}`.`{$table_field}` IS NULL";
					}
					break;
				case 'notnull':
					if ( $include_product_variations && '_categories' !== $field ) {
						$where[] = "(COALESCE(`{$table_alias}`.`{$table_field}`, `{$table_alias}_parent`.`meta_value`) IS NOT NULL";
					} else {
						$where[] = "`{$table_alias}`.`{$table_field}` IS NOT NULL";
					}
					break;
				case 'in':
					if ( $include_product_variations && '_categories' !== $field ) {
						$where[] = "(COALESCE(`{$table_alias}`.`{$table_field}`, `{$table_alias}_parent`.`meta_value`) IN ({$value})";
					} else {
						$where[] = "`{$table_alias}`.`{$table_field}` IN ({$value})";
					}
					break;
				case 'nin':
					if ( $include_product_variations && '_categories' !== $field ) {
						$where[] = "(COALESCE(`{$table_alias}`.`{$table_field}`, `{$table_alias}_parent`.`meta_value`) NOT IN ({$value})";
					} else {
						$where[] = "`{$table_alias}`.`{$table_field}` NOT IN ({$value})";
					}
					break;
				case 'eq':
				default:
					if ( $include_product_variations && '_categories' !== $field ) {
						$where[] = "(COALESCE(`{$table_alias}`.`{$table_field}`, `{$table_alias}_parent`.`meta_value`) = {$value}";
					} else {
						$where[] = "`{$table_alias}`.`{$table_field}` = {$value}";
					}
			}
		}

		return [
			'columns' => $columns,
			'join'    => $join,
			'where'   => $where,
			'group'   => $group,
		];
	}

	protected function prepareFilterConditionQueryPart( $filters ) {
		global $wpdb;

		// Query parts already fully covered by prepare method.
        // phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
		return implode( ' ', $filters['join'] ) . $wpdb->prepare(
			' WHERE `wp`.`post_status` = "publish" ' . ( ! empty( $filters['where'] ) ? ( ' AND ' . implode( ' AND ', $filters['where'] ) ) : '' ) . ' GROUP BY `wp`.`ID` ' . ( ! empty( $filters['group'] ) ? ',' . implode( ',', $filters['group'] ) : '' )
		);
        // phpcs:enable
	}

	public function prepareFeedItems( Feed $feed, $reset = false ): void {
		global $wpdb;

		if ( ! ( $feed->getFeedId() ) ) {
			$feed->save();
		}

		if ( $reset ) {
			$this->cleanFeedItems( $feed );
		}

		$query = $this->getFilteredItemsQuery( $feed );

		if ( self::DEBUG_MODE ) {
            // phpcs:disable WordPress.PHP.DevelopmentFunctions
            // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
			echo "#######################\n";
			echo "getFilteredItemsQuery\n";
			echo $query;
            // phpcs:enable
		}

		// Query parts already fully covered by prepare method.
        // phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
		$wpdb->query( $query );
        // phpcs:enable

		$this->countItems( $feed );
	}

	public function cleanFeedItems( $feed ) {
		global $wpdb;

		$feed_id = $feed->getFeedId();

		// TODO Check why $feed_id is empty.

		if ( isset( $feed_id ) ) {
			$wpdb->query(
				$wpdb->prepare(
					'DELETE FROM `%1s` WHERE `feed_id` = %d',
					[
						$this->getTableName( self::FEED_ITEM_TABLE ),
						$feed_id,
					]
				)
			);
		}
	}

	public function countItems( Feed $feed ): int {
		global $wpdb;

		$feed_id = $feed->getFeedId();
		$count   = $wpdb->get_var(
			$wpdb->prepare(
				'SELECT COUNT(*) FROM `%1s` WHERE `feed_id` = %d',
				[
					$this->getTableName( self::FEED_ITEM_TABLE ),
					$feed_id,

				]
			)
		);

		update_post_meta( $feed->getId(), self::COUNT_META_FIELD, $count );

		return (int) $count;
	}

	public function getFeedProgress( Feed $feed ): float {
		$progress = $this->checkFeedProgress( $feed );

		update_post_meta( $feed->getId(), self::PROGRESS_META_FIELD, $progress );

		return $progress;
	}

	protected function checkFeedProgress( Feed $feed ): float {
		global $wpdb;

		$feed_id = $feed->getFeedId();

		return (float) $wpdb->get_var(
			$wpdb->prepare(
				'SELECT IF(count(`pfi`.`id`) > 0, count(IF(`pfi`.`status` = %s,`pfi`.`id`,NULL)) / count(`pfi`.`id`), 0) AS `progress`
				FROM `wp_prisjakt_feed_item` `pfi`
				WHERE `pfi`.`feed_id` = %d;',
				[
					FeedItem::FEED_ITEM_STATUS_FINISHED,
					$feed_id,
				]
			)
		);
	}

	public function saveFile( $path, $content
	): string {
		$dir      = dirname( $path );
		$realPath = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $dir;

		if ( ! file_exists( $realPath ) ) {
			mkdir( $realPath );
		}

		// @todo - Implement sequential file save to temp file and replace final file with it
        // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents
		file_put_contents( $realPath . DIRECTORY_SEPARATOR . basename( $path ), $content . PHP_EOL );

		return $this->getFileUrl( $path );
	}

	public function getFileUrl( $path ) {
		$upload_dir = wp_upload_dir();

		return $upload_dir['baseurl'] . DIRECTORY_SEPARATOR . $path;
	}

	public function getGallery( FeedItem $feedItem ): array {
		$gallery = [];

		if ( ! $feedItem->getData( 'gallery' ) ) {
			return [];
		}

		$imageIds = explode( ',', $feedItem->getData( 'gallery' ) );

		if ( $imageIds ) {
			$gallery = $this->getImages( $imageIds );
		}

		return $gallery;
	}

	protected function getImages( array $imageIds ): array {
		global $wpdb;

		if ( ! $imageIds ) {
			return [];
		}
		$imageIds = array_filter( $imageIds );

		$rows = $wpdb->get_col(
			$wpdb->prepare(
				'SELECT `pm`.`meta_value` FROM `%1s` as `p` 
				JOIN `%1s` as `pm` ON `p`.`ID` = `pm`.`post_id` AND `pm`.`meta_key` = "_wp_attached_file"
				WHERE `ID` IN (%s);',
				[
					$this->getTableName( 'posts' ),
					$this->getTableName( 'postmeta' ),
					implode( ',', $imageIds ),
				]
			)
		);

		$url = $this->getUploadBaseUri();

		return array_map(
			function ( $path ) use ( $url ) {
				return $url . DIRECTORY_SEPARATOR . $path;
			},
			$rows
		);
	}

	protected function getUploadBaseUri() {
		if ( ! $this->mediaBaseUri ) {
			$uploadDir = wp_get_upload_dir();

			$this->mediaBaseUri = $uploadDir['baseurl'];
		}

		return $this->mediaBaseUri;
	}

	public function getThumbnail( FeedItem $feedItem ): string {
		$thumbnail = '';

		if ( ! $feedItem->getData( 'thumbnail' ) ) {
			return '';
		}

		$imageIds = [ $feedItem->getData( 'thumbnail' ) ];

		if ( $imageIds ) {
			$gallery   = $this->getImages( $imageIds );
			$thumbnail = $gallery[0];
		}

		return $thumbnail;
	}

	/**
	 * @param int $id
	 *
	 * @return mixed
	 */
	public function loadFeedSettings( int $id ) {
		$data         = [];
		$feedSettings = get_post_meta( $id, 'prisjakt_feed_general_settings', true );

		if ( is_array( $feedSettings ) && isset( $feedSettings[0] ) ) {
			$feedSettings = $feedSettings[0];
		}

		if ( $feedSettings ) {
			$data = array_merge( $data, maybe_unserialize( $feedSettings ) );
		}

		$feedGlobalSettings = get_post_meta( $id, 'prisjakt_feed_global_feed_settings', true );
		if ( $feedGlobalSettings ) {
			$data = array_merge( $data, maybe_unserialize( $feedGlobalSettings ) );
		}

		return $data;
	}
}
