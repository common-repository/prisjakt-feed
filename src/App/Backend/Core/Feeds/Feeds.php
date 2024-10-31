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

use PrisjaktFeed\App\DataStorage\Feed\GlobalFeedSettings;
use PrisjaktFeed\Common\Abstracts\Base;
use WP_Error;

/**
 * Class Settings
 */
class Feeds extends Base {



	/**
	 * Feeds post type data
	 */
	public const POST_TYPE = [
		'id'       => 'pf_feed_post_type',
		'archive'  => 'pf_feed_post_types',
		'title'    => 'Feeds',
		'singular' => 'Feed',
		'plural'   => 'Feeds',
		'icon'     => 'dashicons-format-chat',
	];


	/**
	 * @var string
	 */
	public $feed_list_ajax_action = 'action-feed-list-update';

	/**
	 * @var string
	 */
	public $feed_list_ajax_nonce = 'feed_list';


	/**
	 * Initialize the class.
	 *
	 * @return void
	 * @since  1.0.0
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

		add_action( "wp_ajax_nopriv_{$this->feed_list_ajax_action}", [ $this, 'feed_list_update_callback' ] );
		add_action( "wp_ajax_{$this->feed_list_ajax_action}", [ $this, 'feed_list_update_callback' ] );
		add_action( 'admin_print_scripts-edit.php', [ $this, 'enqueue_scripts' ], 11 );
		add_filter( 'wp_check_post_lock_window', [ $this, 'check_post_lock_window_interval' ], 10 );
	}


	/**
	 * Load script for feeds list page
	 */
	public function enqueue_scripts(): void {

		$current_screen           = get_current_screen();
		$current_screen_base      = $current_screen->base ?? '';
		$current_screen_post_type = $current_screen->post_type ?? '';

		if ( 'edit' === $current_screen_base && $current_screen_post_type === $this::POST_TYPE['id'] ) {

			foreach (
				[
					[
						'deps'      => [],
						'handle'    => 'prisjakt-feed-list-js',
						'in_footer' => true,
						'source'    => plugins_url( '/assets/public/js/feedList.js', PRISJAKT_FEED_PLUGIN_FILE ),
                        // phpcs:disable ImportDetection.Imports.RequireImports.Symbol -- this constant is global
						'version'   => $this->plugin->version(),
					],
				] as $js
			) {

				if ( wp_script_is( $js['handle'] ) ) {
					return;
				}

				wp_enqueue_script( $js['handle'], $js['source'], $js['deps'], $js['version'], $js['in_footer'] );

				$script_data = [
					'ajax_url'    => admin_url( 'admin-ajax.php' ),
					'ajax_action' => $this->feed_list_ajax_action,
					'ajax_nonce'  => wp_create_nonce( $this->feed_list_ajax_nonce ),
				];

				wp_localize_script(
					$js['handle'],
					'prisjakt_ajax_object',
					$script_data
				);
			}
		}
	}

	/**
	 * Ajax callback
	 */
	public function feed_list_update_callback(): void {

		check_ajax_referer( $this->feed_list_ajax_nonce, 'security' );

		if ( ! current_user_can( 'edit_pages' ) ) {
			wp_die( -1 );
		}

		$post_id = isset( $_REQUEST['post_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['post_id'] ) ) : '';

		if ( empty( $post_id ) ) {
			$error = new WP_Error( '001', 'Missing post ID' );
			wp_send_json_error( $error );
			die();
		}

		$is_active = isset( $_REQUEST['is_active'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['is_active'] ) ) : '';

		/**
		 * Update feed status
		 */
		if ( ! empty( $is_active ) ) {
			$this->update_is_active( $post_id, $is_active );
		}

		wp_send_json_success( 'success' );
		die();
	}


	/**
	 * @param $post_id
	 * @param $is_active
	 */
	public function update_is_active( $post_id, $is_active ): void {
		$global_feed_settings = new GlobalFeedSettings();

		$is_active = 'true' === $is_active;
		$global_feed_settings->update_is_active( $post_id, $is_active );
	}


	/**
	 * @param $interval
	 *
	 * @return int
	 */
	public function check_post_lock_window_interval( $interval ): int {
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$post_id = isset( $_POST['post_ID'] ) ? (int) sanitize_text_field( wp_unslash( $_POST['post_ID'] ) ) : false;

		if ( $post_id ) {
			$post = get_post( $post_id );

			if ( $post->post_type === $this::POST_TYPE['id'] ) {
				return 10;
			}
		}

		return $interval;
	}
}
