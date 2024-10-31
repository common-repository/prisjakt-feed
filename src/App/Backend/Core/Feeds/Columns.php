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

namespace PrisjaktFeed\App\Backend\Core\Feeds;

use PrisjaktFeed\App\DataStorage\Feed\GeneralSettingsData;
use PrisjaktFeed\App\DataStorage\Feed\GlobalFeedSettings;
use PrisjaktFeed\Config\Plugin;

/**
 * Class Columns
 */
class Columns extends Feeds {



	/**
	 * @var GeneralSettingsData
	 */
	protected $general_settings_instance;

	/**
	 * @var GlobalFeedSettings
	 */
	protected $global_settings_instance;

	/**
	 * Initialize the class.
	 *
	 * @return void
	 * @since  1.0.0
	 */
	public function init(): void {
		/**
		 * This backend class is only being instantiated in the backend as requested in the Bootstrap class
		 *
		 * @see Requester::is_admin_backend()
		 * @see Bootstrap::__construct
		 *
		 * Add plugin code here for admin settings specific functions
		 */

		add_filter( "manage_{$this::POST_TYPE['id']}_posts_columns", [ $this, 'add_feed_columns' ] );
		add_filter(
			"manage_{$this::POST_TYPE['id']}_posts_custom_column",
			[
				$this,
				'add_feed_columns_data',
			],
			10,
			2
		);

		$this->general_settings_instance = new GeneralSettingsData();
		$this->global_settings_instance  = new GlobalFeedSettings();

	}


	/**
	 * @param array $columns post type columns
	 *
	 * @return array
	 * @since  1.0.0
	 */
	public function add_feed_columns( array $columns ): array {
		unset( $columns['title'], $columns['date'] );

		$columns['is_active']             = __( 'Active', 'prisjakt-feed' );
		$columns['feed_name']             = __( 'Product feed name', 'prisjakt-feed' );
		$columns['feed_refresh_interval'] = __( 'Refresh interval', 'prisjakt-feed' );
		$columns['feed_process_status']   = __( 'Status', 'prisjakt-feed' );
		$columns['feed_process_count']    = __( 'Products', 'prisjakt-feed' );
		$columns['feed_process_progress'] = __( 'Progress', 'prisjakt-feed' );
		$columns['feed_generated_at']     = __( 'Generated At', 'prisjakt-feed' );
		$columns['feed_scheduled_at']     = __( 'Scheduled At', 'prisjakt-feed' );
		$columns['feed_update_date']      = __( 'Date', 'prisjakt-feed' );

		return $columns;
	}


	/**
	 * @param $column
	 * @param $post_id
	 *
	 * @since 1.0.0
	 */
	public function add_feed_columns_data( $column, $post_id ): void {
		$settings_data = $this->general_settings_instance->get_post_meta( $post_id )[0];

		$progress = ( (float) get_post_meta( $post_id, Plugin::PLUGIN_PREFIX . 'progress', true ) ) * 100 . '%';
		$count    = ( (int) get_post_meta( $post_id, Plugin::PLUGIN_PREFIX . 'count', true ) );

		switch ( $column ) {
			case 'is_active':
				$status   = checked( get_post_meta( $post_id, Plugin::PLUGIN_PREFIX . 'is_active', true ) ?? '', true, false );
				$disabled = get_post_status( $post_id ) === 'trash' ? 'disabled' : '';

				printf(
					'<input type="checkbox"  data-post-id="%s" class="prisjakt-switch" %s  />',
					esc_attr( $post_id ),
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					implode( ' ', [ $status, $disabled ] )
				);
				break;
			case 'feed_name':
				printf(
					' <a href ="%s" >%s</a > ',
					esc_url( get_edit_post_link( $post_id ) ),
					wp_kses_post( $settings_data[ $column ] )
				);
				break;
			case 'feed_refresh_interval':
				$cron_options = $this->general_settings_instance->get_cron_options();
				echo wp_kses_post( $cron_options[ $settings_data[ $column ] ] );
				break;
			case 'feed_process_status':
				$status = get_post_meta( $post_id, Plugin::PLUGIN_PREFIX . 'status', true );
				$error  = get_post_meta( $post_id, Plugin::PLUGIN_PREFIX . 'error', true );

				if ( ! $status ) {
					$status = 'new';
				}

				if ( 'failed' === $status ) {
					echo wp_kses_post( $status . ' [<strong>' . $error . '</strong>]' );
				} else {
					echo wp_kses_post( $status );
				}
				break;
			case 'feed_process_count':
				echo esc_html( $count );
				break;
			case 'feed_process_progress':
				echo esc_html( $progress );
				break;
			case 'feed_generated_at':
				echo esc_html( mysql2date( 'Y / m / d \a\t g:ia', get_post_meta( $post_id, Plugin::PLUGIN_PREFIX . 'generated_at', true ) ) );
				break;
			case 'feed_scheduled_at':
				echo esc_html( mysql2date( 'Y / m / d \a\t g:ia', get_post_meta( $post_id, Plugin::PLUGIN_PREFIX . 'scheduled_at', true ) ) );
				break;
			case 'feed_update_date':
				echo esc_html( the_modified_date( 'Y / m / d \a\t g:ia' ) );
				break;
			default:
				echo 'Example';
		}

	}
}
