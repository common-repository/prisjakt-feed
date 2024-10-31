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

use PrisjaktFeed\App\Backend\Core\Feeds\Feed\Feed;
use PrisjaktFeed\App\DataStorage\Feed\GlobalFeedSettings;
use Ageno\Prisjakt\Model\Feed as FeedModel;
use PrisjaktFeed\Config\Plugin;

/**
 * Class PostType
 */
class Actions extends Feeds {



	/**
	 * @var Feed
	 */
	protected $feed;

	/**
	 * @var array
	 */
	protected $actions;


	/**
	 * Action names
	 */
	public const ACTION_NAMES = [
		'download_feed',
		'refresh_feed',
	];


	/**
	 * @return array
	 */
	public static function get_actions(): array {
		return [
			self::ACTION_NAMES[0] => __( 'Download feed', 'prisjakt-feed' ),
			self::ACTION_NAMES[1] => __( 'Refresh feed', 'prisjakt-feed' ),
		];
	}

	/**
	 *
	 */
	public function init() {
		/**
		 * This backend class is only being instantiated in the backend as requested in the Bootstrap class
		 *
		 * @see Requester::is_admin_backend()
		 * @see Bootstrap::__construct
		 *
		 * Add plugin code here for admin settings specific functions
		 */

		$this->feed    = new Feed();
		$this->actions = self::get_actions();

		add_filter( 'post_row_actions', [ $this, 'feed_action_row' ], 10, 2 );

		/**
		 * Trash action, disable feed status
		 */
		add_action( 'transition_post_status', [ $this, 'update_feed_post_status' ], 10, 3 );
		add_action( 'admin_notices', [ $this, 'feed_refresh_success_notice' ] );

		foreach ( $this->actions as $action_name => $action_label ) :
			add_action( "admin_action_$action_name", [ $this, "{$action_name}_action" ] );
		endforeach;

		add_filter( "bulk_actions-edit-{$this::POST_TYPE['id']}", [ $this, 'filter_bulk_actions' ], 10, 1 );

		/**
		 * Delete XML actions and filters
		 */
		add_action( 'before_delete_post', [ $this, 'delete_feed_data_handler' ], 10, 1 );
		add_action(
			'load-edit.php',
			function () {
				add_action(
					'before_delete_post',
					function ( $post_id ) {

						if (
							filter_input( INPUT_GET, 'post_type' ) === $this::POST_TYPE['id'] &&
							'trash' === get_post_status( $post_id )
							&& (
								filter_input( INPUT_GET, 'delete_all' ) ||
								filter_input( INPUT_GET, 'action' ) === 'delete'
							)
						) {
							do_action( 'bulk_delete_trash_action', $post_id );
						}
					}
				);
			}
		);

		add_action( 'bulk_delete_trash_action', [ $this, 'bulk_delete_trash_handle' ], 10, 1 );

	}

	/**
	 * This method support bulk and empty trash actions (Delete permanently)
	 */
	public function bulk_delete_trash_handle( $post_id ): void {

		/**
		 * TODO Add in this condition remove file XML
		 */
	}

	public function filter_bulk_actions( $bulk_array ) {

		unset( $bulk_array['edit'] );

		return $bulk_array;
	}

	/**
	 * @param $post_id
	 */
	public function delete_feed_data_handler( $post_id ): void {

        // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf
		if ( $this->feed->is_feed_post_edit() ) {

			/**
			 * TODO Add in this condition remove file XML
			 */
		}

	}


	/**
	 * @param $actions
	 * @param $post
	 *
	 * @return mixed
	 */
	public function feed_action_row( $actions, $post ) {

		if ( $this->feed->is_feed_post_edit() && get_post_status( $post->ID ) !== 'trash' ) {
			unset( $actions['inline hide-if-no-js'], $actions['view'] );

			$post_id = $post->ID;

			/**
			 *  Render row actions
			 */
			foreach ( $this->actions as $action_name => $action_label ) :
				if ( 'download_feed' === $action_name ) {
					$url = get_post_meta( $post->ID, 'prisjakt_feed_url', true );

					$enable_button = false;

					if ( $url ) {
						$parts         = wp_parse_url( $url );
						$enable_button = file_exists( dirname( WP_CONTENT_DIR ) . $parts['path'] );
					}

					$link_classes = $enable_button ? '' : 'disabled';

					$actions[ $action_name ] = sprintf(
						'<a href="%s" class="prisjakt-link ' . $link_classes . '" target="_blank" download="' . basename( $url ) . '">%s</a>',
						$url,
						$action_label
					);

				} elseif ( 'refresh_feed' === $action_name ) {
					if ( ! in_array(
						get_post_meta( $post->ID, 'prisjakt_feed_status', true ),
						[
							FeedModel::FEED_STATUS_NEW,
							FeedModel::FEED_STATUS_PROCESSING,
							FeedModel::FEED_STATUS_PENDING,
						],
						true
					) ) {
						$menu_slug = prisjakt_feed()->get_menu_slug();
						$url       = admin_url( "$menu_slug&post=" . $post_id );

						$action_link = wp_nonce_url(
							add_query_arg( [ 'action' => $action_name ], $url ),
							"{$action_name}_$post_id"
						);

						$actions[ $action_name ] = sprintf(
							'<a class="prisjakt-link" href="%s">%s</a>',
							esc_url( $action_link ),
							$action_label
						);
					}
				} else {
					$menu_slug = prisjakt_feed()->get_menu_slug();
					$url       = admin_url( "$menu_slug&post=" . $post_id );

					$action_link = wp_nonce_url(
						add_query_arg( [ 'action' => $action_name ], $url ),
						"{$action_name}_$post_id"
					);

					$actions[ $action_name ] = sprintf(
						'<a href="%s">%s</a>',
						esc_url( $action_link ),
						$action_label
					);
				}

			endforeach;
		}

		return $actions;
	}

	/**
	 * @param $action_name
	 *
	 * @return bool|false
	 */
	public function check_action_referer( $action_name ): bool {

		if ( empty( $_REQUEST['post'] ) ) {
			wp_die( wp_kses_post( __( 'Cant do this action!', 'prisjakt-feed' ) ) );
		}

		$feed_id = isset( $_REQUEST['post'] ) ? absint( $_REQUEST['post'] ) : '';

		return (bool) check_admin_referer( "{$action_name}_$feed_id" );
	}


	/**
	 * Refresh feed xml
	 */
	public function refresh_feed_action(): void {

		if ( $this->check_action_referer( self::ACTION_NAMES[1] ) ) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$post_id = isset( $_REQUEST['post'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['post'] ) ) : false;
			update_post_meta( $post_id, Plugin::PLUGIN_PREFIX . 'status', 'pending' );
			update_post_meta( $post_id, Plugin::PLUGIN_PREFIX . 'progress', 0 );
			update_post_meta( $post_id, Plugin::PLUGIN_PREFIX . 'count', 0 );

			wp_safe_redirect(
				add_query_arg(
					[
						'post_type' => 'pf_feed_post_type',
						'refresh'   => 'feed_generation_refresh',
					],
					admin_url( 'edit.php' )
				)
			);
		}

		exit();
	}


	/**
	 *
	 */
	public function feed_refresh_success_notice(): void {
		$screen = get_current_screen();

		if ( 'edit' !== $screen->base ) {
			return;
		}

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['refresh'] ) && 'feed_generation_refresh' === sanitize_text_field( wp_unslash( $_GET['refresh'] ) ) ) {
			echo wp_kses_post( sprintf( '<div class="notice notice-success is-dismissible"><p>%s</p></div>', __( 'Updated feed status to pending. Generation process is now scheduled.', 'prisjakt-feed' ) ) );
		}

	}

	/**
	 * Disable feed
	 */
	public function update_feed_post_status( $new_status, $old_status, $post ): void {

		/**
		 * Force draft status to publish
		 * Action it's very important for bulk actions on feeds list
		 * and feed save, continue, generate actions
		 */

		if ( 'draft' === $new_status && get_post_type( $post ) === self::POST_TYPE['id'] ) {
			wp_update_post(
				[
					'ID'          => $post->ID,
					'post_status' => 'publish',
				]
			);
		}

		/**
		 * Disable status feed
		 */

		if ( 'trash' === $new_status && get_post_type( $post ) === self::POST_TYPE['id']
		) {
			$global_feed_settings = new GlobalFeedSettings();
			$global_feed_settings->update_is_active( $post->ID, false );
		}
	}
}
