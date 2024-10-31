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

namespace PrisjaktFeed\App\General;

use PrisjaktFeed\App\Feed\Mysql;
use PrisjaktFeed\App\Feed\SettingsProvider;
use PrisjaktFeed\App\Backend\Core\Feeds\Feeds;
use Ageno\Prisjakt\Model\Feed;
use PrisjaktFeed\Common\Abstracts\Base;

/**
 * Class Cron
 *
 * @package PrisjaktFeed\App\General
 * @since 1.0.0
 */
class Cron extends Base {


	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		/**
		 * This general class is always being instantiated as requested in the Bootstrap class
		 *
		 * @see Bootstrap::__construct
		 *
		 * Add plugin code here
		 */
		add_action( 'prisjakt_feed_cron_job', [ $this, 'prisjakt_feed_generate' ], 10 );
	}


	public function schedule_prisjakt_cron_job() {
		if ( ! wp_next_scheduled( 'prisjakt_feed_cron_job' ) ) {
			wp_schedule_event( time(), 'every_minute', 'prisjakt_feed_cron_job' );
		}
	}


	public function prisjakt_feed_generate() {
		global $wpdb;

		$now = current_time( 'Y-m-d H:i:s' );

		$feedIds = get_posts(
			[
				'post_type'      => Feeds::POST_TYPE['id'],
				'fields'         => 'ids',
				'posts_per_page' => -1,
                // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_key'       => 'prisjakt_feed_status',
                // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				'meta_query'     => [
					'relation' => 'AND',
					[
						'relation' => 'OR',
						[
							'relation' => 'OR',
							[
								'key'     => 'prisjakt_feed_status',
								'value'   => 'new',
								'compare' => '=',
							],
							[
								'relation' => 'AND',
								[
									'key'     => 'prisjakt_feed_status',
									'value'   => 'pending',
									'compare' => '=',
								],
							],
						],
						[
							'key'     => 'prisjakt_feed_status',
							'value'   => null,
							'compare' => 'NOT EXISTS',
						],
					],
					[
						'key'     => 'prisjakt_feed_is_active',
						'value'   => 1,
						'compare' => '=',
					],
				],
			]
		);

		$settings = new SettingsProvider();
		$settings->setBatchSize( 5000 );
		$mysqlProvider = new Mysql();

		foreach ( $feedIds as $feedId ) {
			$feed = new Feed( $settings, $mysqlProvider );
			$feed->load( $feedId );
			$feed->generate();
			$feed->save();
		}

		$feedIdsToSchedule = get_posts(
			[
				'post_type'      => Feeds::POST_TYPE['id'],
				'fields'         => 'ids',
				'posts_per_page' => -1,
                // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_key'       => 'prisjakt_feed_status',
                // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				'meta_query'     => [
					'relation' => 'AND',
					[
						'key'     => 'prisjakt_feed_status',
						'value'   => 'finished',
						'compare' => 'eq',
					],
					[
						'relation' => 'OR',
						[
							'key'     => 'prisjakt_feed_scheduled_at',
							'value'   => null,
							'compare' => 'NOT EXISTS',
						],
						[
							'key'     => 'prisjakt_feed_scheduled_at',
							'value'   => $now,
							'compare' => '<=',
						],
					],
				],
			]
		);

		foreach ( $feedIdsToSchedule as $feedId ) {
			$feed = new Feed( $settings, $mysqlProvider );
			$feed->load( $feedId );

			$refreshInterval = $feed->getFeedRefreshInterval();
			$generatedAt     = $feed->getGeneratedAt();

			$scheduledAt = $feed->getScheduledAt();
			if ( ! $scheduledAt ) {
				switch ( $refreshInterval ) {
					case 'twicedaily':
						$interval = '+12 HOURS';
						break;
					case 'hourly':
						$interval = '+1 HOURS';
						break;
					case 'daily':
					default:
						$interval = '+1 DAY';
						break;
				}

				$time = new \DateTime( $generatedAt );
				$time->modify( $interval );

				$scheduledAt = $time->format( 'Y-m-d H:i:s' );
				$feed->setScheduledAt( $scheduledAt );
				$feed->save();
			} elseif ( $scheduledAt && $feed->getStatus() !== 'pending' ) {
				$time = current_time( 'Y-m-d H:i:s' );
				if ( $time > $scheduledAt ) {
					$feed->setStatus( 'pending' );
					$feed->save();
				}
			}
		}

		$this->schedule_prisjakt_cron_job();
	}
}
